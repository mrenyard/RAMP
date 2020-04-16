<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @version 0.0.9;
 */
namespace tests\svelte\http;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/Filter.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/FilterCondition.class.php';
require_once '/usr/share/php/svelte/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/svelte/http/Session.class.php';
require_once '/usr/share/php/svelte/http/Unauthorized401Exception.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';

require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/HeaderFunctions.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/view/View.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/view/PasswordResetView.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/LoginAccountTypeOption.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/LoginAccountType.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/MockField.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/LoginAccount.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/Person.class.php';

use svelte\SETTING;
use svelte\view\AuthenticationView;
use svelte\view\PasswordResetView;
use svelte\http\Unauthorized401Exception;
use svelte\http\Session;

use svelte\model\business\MockBusinessModelManager;
use svelte\model\business\LoginAccountTypeOption;
use svelte\model\business\LoginAccountType;
use svelte\model\business\LoginAccount;

/**
 * Collection of tests for svelte\http\Session.
 */
class SessionTest extends \PHPUnit\Framework\TestCase
{
  private static $ref;

  private $sessionLoginAccountEmail;
  private $unencryptedPassword;

  /**
   * Setup
   * - assert throws \BadMethodCallException MUST provide an Authentication Form View and a
   *  Reset Password View on first call.
   */
  public function setUp() //  - PHP varable that fake a basic HTTP Response.
  {
    SETTING::$TEST_ON = TRUE;
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='svelte\model\business';
    SETTING::$SVELTE_BUSINESS_MODEL_MANAGER = 'svelte\model\business\MockBusinessModelManager';
    SETTING::$SECURITY_PASSWORD_SALT = 'A hard days night!';
    SETTING::$SVELTE_AUTHENTICATIBLE_UNIT = 'Person';

    $this->sessionLoginAccountEmail = 'a.person@domain.com';
    $this->unencryptedPassword = 'P@ssw0rd!';
    if (!isset(self::$ref))
    {
      try {
        Session::authorizedAs(LoginAccountType::REGISTERED());
      } catch (\BadMethodCallException $expected) {
        $this->AssertSame(
          'Session::instance() MUST be called before Session::authorizedAs().',
          $expected->getMessage()
        );
        Session::getInstance();
        self::$ref = Session::getInstance(new PasswordResetView());
        return;
      }
      $this->fail('An expected \BadMethodCallException has NOT been raised');
    }
    SETTING::$TEST_RESET_SESSION = FALSE;
  }

  /**
   * Collection of assertions for \svelte\http\Session::getInstance().
   * - assert is instance of {@link svelte\core\SvelteObject}
   * - assert is instance of {@link svelte\http\Session}
   * - assert is same instance on every call (Singleton)
   * - assert cannot be cloned
   * @link svelte.http.Session svelte\http\Session
   */
  public function testGetInstance()
  {
    $testObject = Session::getInstance();
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\svelte\http\Session', $testObject);
    $this->assertSame(Session::getInstance(), $testObject);
    try {
      $fail = clone $testObject;
    } catch (\BadMethodCallException $expected) {
      $this->AssertSame('Clone is not allowed', $expected->getMessage());
      unset($fail);
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizedAs()
   *  with already Authenticated $_SESSION.
   * - assert returns TRUE when $_SESSION['LoginAccount'] has sufficient privileges
   * - assert returns FALSE when $_SESSION['LoginAccount'] has insufficient privileges
   * @link svelte.http.Session#method_authorizedAs svelte\http\Session::AuthorizedAs()
   */
  public function testAuthorizedAsAlreadyAuthenticated()
  {
    $_POST = array();
    $dataObject = new \stdClass();
    $dataObject->id = 'login-account:aperson';
    $dataObject->email = $this->sessionLoginAccountEmail;
    $dataObject->encryptedPassword = crypt(
      $this->unencryptedPassword, \svelte\SETTING::$SECURITY_PASSWORD_SALT
    );
    $dataObject->typeID = LoginAccountType::ADMINISTRATOR()->id;
    $dataObject->auPK = 'aperson';
    $sessionLoginAccount = new LoginAccount($dataObject);
    $_SESSION['loginAccount'] = $sessionLoginAccount;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());

    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::REGISTERED()));
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::USER()));
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::AFFILIATE()));
    $this->assertTrue(Session::AuthorizedAs(LoginAccountType::ADMINISTRATOR()));
    $this->assertFalse(Session::AuthorizedAs(LoginAccountType::ADMINISTRATOR_MANAGER()));
    $this->assertFalse(Session::AuthorizedAs(LoginAccountType::SYSTEM_ADMINISTRATOR()));

    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with already Authenticated $_SESSION.
   * - assert returns without interuption when $_SESSION['LoginAccount'] has sufficient privileges
   * - assert throws Unauthorized401Exception when $_SESSION['LoginAccount'] has insufficient privileges
   *   - with message: <em>'Unauthenticated or insufficient authority'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsAlreadyAuthenticated()
  {
    $postArray = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $dataObject = new \stdClass();
    $dataObject->id = 'login-account:aperson';
    $dataObject->email = $this->sessionLoginAccountEmail;
    $dataObject->encryptedPassword = crypt(
      $this->unencryptedPassword, \svelte\SETTING::$SECURITY_PASSWORD_SALT
    );
    $dataObject->typeID = LoginAccountType::ADMINISTRATOR()->id;
    $dataObject->auPK = 'aperson';
    $sessionLoginAccount = new LoginAccount($dataObject);
    $_SESSION['loginAccount'] = $sessionLoginAccount;
    $_POST = $postArray;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());

    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::USER()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::AFFILIATE()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::ADMINISTRATOR()));
    $this->assertSame($postArray, $_POST);
    $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
    try {
      $this->assertFalse($testObject->AuthorizeAs(LoginAccountType::ADMINISTRATOR_MANAGER()));
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame(
        'Attempting POST to resource REQUIRING authentication or insufficient authority',
        $expected->getMessage()
      );
      $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
      $_POST = array();
      try {
        $this->assertFalse($testObject->AuthorizeAs(LoginAccountType::SYSTEM_ADMINISTRATOR()));
      } catch (Unauthorized401Exception $expected) {
        $this->assertSame('Unauthenticated or insufficient authority', $expected->getMessage());
        $this->assertSame($_SESSION['loginAccount'], $sessionLoginAccount);
        return;
      }
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with no $_SESSION logging-in or a set of valid credentials.
   * - .
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'Unauthenticated or insufficient authority'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNoCredentials()
  {
    $_SESSION = array();
    $_POST = array();

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Unauthenticated or insufficient authority', $expected->getMessage());
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with no $_SESSION logging-in or a set of valid credentials but with $_POST data.
   * - .
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'Attempting POST to resource REQUIRING authentication or insufficient authority'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNoCredentialsWithPost()
  {
    $postArray = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $postArray;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame(
        'Attempting POST to resource REQUIRING authentication or insufficient authority',
        $expected->getMessage()
      );
      $this->assertTrue(isset($_SESSION['post_array']));
      $this->assertSame($_SESSION['post_array'], $postArray);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while ONLY providing login-email without login-password or [authenticatableUnit]:new:email.
   * - .
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'SHOULD NEVER REACH HERE!'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithEmailNoPassword()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = $this->sessionLoginAccountEmail;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('SHOULD NEVER REACH HERE!', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with a set of valid Credentials.
   * - .
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'Account (email) NOT in database'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsEmailNotInDatabase()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = 'unregistered.email@domain.com';
    $_POST['login-password'] = $this->unencryptedPassword;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Account (email) NOT in database', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['login-password']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with a set of valid Credentials.
   * - .
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'Invalid password or insufficient privileges'</em>
   */
  public function testAuthorizeAsWithInvalidPassword()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = $this->sessionLoginAccountEmail;
    $_POST['login-password'] = 'b@dP@ssw0rd';

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $testObject->authorizeAs(LoginAccountType::REGISTERED());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Invalid password or insufficient privileges', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['login-password']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with a set of valid Credentials.
   * - .
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'Invalid password or insufficient privileges'</em>
   */
  public function testAuthorizeAsWithInsufficientPrivileges()
  {
    $additionalPostdata = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = $this->sessionLoginAccountEmail;
    $_POST['login-password'] = $this->unencryptedPassword;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $testObject->authorizeAs(LoginAccountType::ADMINISTRATOR_MANAGER());
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Invalid password or insufficient privileges', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['login-password']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  while logging-in with a set of valid Credentials.
   * - .
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsWithValidCredentials()
  {
    $postArray = array(
      'record-name:key:property-a' => 'valueA',
      'record-name:key:property-b' => 'valueB',
      'record-name:key:property-c' => 'valueC'
    );
    $_SESSION['post_array'] = $postArray;
    $_POST['login-email'] = $this->sessionLoginAccountEmail;
    $_POST['login-password'] = $this->unencryptedPassword;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());

    $testObject->authorizeAs(LoginAccountType::REGISTERED());
    $this->assertFalse(isset($_POST['login-email']));
    $this->assertFalse(isset($_POST['login-password']));
    $this->assertSame($postArray, $_POST);
    $this->assertTrue(isset($_SESSION['loginAccount']));
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with new authenticatible unit details to setup as new login account BUT emails mismatch.
   * - .
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'new authenticatible unit form e-mail mismatch'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNewLoginAccountEmailMismatch()
  {
    $additionalPostdata = array (
      'person:new:email' => 'correct@email.com',
      'person:new:family-name' => 'Surname',
      'person:new:given-name' => 'Name'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = 'misspell@email.com';

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $this->assertTrue($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('new authenticatible unit form e-mail mismatch', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['person:new:email']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      // TODO:mrenyard: test errorCollection
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with new authenticatible unit details to setup as new login account BUT alreay exists
   * - .
   * - assert throws \svelte\http\Unauthorized401Exception
   *   - with message: <em>'Trying to create new login where one already exists!'</em>
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNewLoginAccountEmailAlreadyExists()
  {
    $additionalPostdata = array (
      'person:new:email' => $this->sessionLoginAccountEmail,
      'person:new:family-name' => 'Surname',
      'person:new:given-name' => 'Name'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = $this->sessionLoginAccountEmail;

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    try {
      $this->assertTrue($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    } catch (Unauthorized401Exception $expected) {
      $this->assertSame('Trying to create new login where one already exists!', $expected->getMessage());
      $this->assertFalse(isset($_POST['login-email']));
      $this->assertFalse(isset($_POST['person:new:email']));
      $this->assertSame($_SESSION['post_array'], $additionalPostdata);
      $this->assertFalse(isset($_SESSION['loginAccount']));
      // TODO:mrenyard: test errorCollection
      return;
    }
    $this->fail('An expected Unauthorized401Exception has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\http\Session::authorizeAs()
   *  with new authenticatible unit details to setup as new login account.
   * - .
   * @link svelte.http.Session#method_authorizeAs svelte\http\Session::AuthorizeAs()
   */
  public function testAuthorizeAsNewLoginAccount()
  {
    $additionalPostdata = array (
      'person:new:email' => 'correct@email.com',
      'person:new:family-name' => 'Surname',
      'person:new:given-name' => 'Name'
    );
    $_SESSION = array();
    $_POST = $additionalPostdata;
    $_POST['login-email'] = 'correct@email.com';

    SETTING::$TEST_RESET_SESSION = TRUE;
    $testObject = Session::getInstance(new PasswordResetView());
    $this->assertNull($testObject->AuthorizeAs(LoginAccountType::REGISTERED()));
    $this->assertFalse(isset($_POST['login-email']));
    $this->assertTrue(isset($_POST['person:new:email']));
    $this->assertSame($_SESSION['post_array'], $additionalPostdata);
    $this->assertTrue(isset($_SESSION['loginAccount']));
    // TODO:mrenyard: Remove need to send PasswordResetView on getInstance().
    // TODO:mrenyard: Tidy up documentation Test and Actual.
  }
}
