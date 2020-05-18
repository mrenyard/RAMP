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
namespace tests\svelte\model\business;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';
require_once '/usr/share/php/svelte/model/business/AuthenticatableUnit.class.php';
require_once '/usr/share/php/svelte/model/business/LoginAccountType.class.php';
require_once '/usr/share/php/svelte/model/business/LoginAccount.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Input.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/LowerCaseAlphanumeric.class.php';
require_once '/usr/share/php/svelte/model/business/validation/RegexEmail.class.php';

require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/SessionTest/model/business/AnAuthenticatableUnit.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
use svelte\model\business\LoginAccountCollection;
use svelte\model\business\LoginAccount;
use svelte\model\business\LoginAccountType;
use svelte\model\business\field\Input;
use svelte\model\business\validation\LowerCaseAlphanumeric;
use svelte\model\business\validation\RegexEmail;

use svelte\model\business\MockBusinessModelManager;

/**
 * Collection of tests for svelte\model\business\LoginAccount.
 */
class LoginAccountTest extends \PHPUnit\Framework\TestCase
{
  private $dataObject;
  private $testObject;
  private $testObjectCollection;

  /**
   * Set-up.
   */
  public function setUp()
  {
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='svelte\model\business';
    SETTING::$SVELTE_BUSINESS_MODEL_MANAGER = 'svelte\model\business\MockBusinessModelManager';
    SETTING::$SECURITY_PASSWORD_SALT = 'A hard days night!';
    SETTING::$SVELTE_AUTHENTICATABLE_UNIT = 'AnAuthenticatableUnit';
    $this->dataObject = new \stdClass();
    $this->testObject = new LoginAccount($this->dataObject);
    $this->testObjectCollection = new LoginAccountCollection();
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccountCollection::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \svelte\model\business\RecordCollection}
   * - assert is instance of {@link \svelte\model\business\LoginAccountCollection}
   * @link svelte.model.business.LoginAccountCollection svelte\model\business\LoginAccountCollection
   */
  public function testGetCollectionInstance()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObjectCollection);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObjectCollection);
    $this->assertInstanceOf('\svelte\model\business\RecordCollection', $this->testObjectCollection);
    $this->assertInstanceOf('\svelte\model\business\LoginAccountCollection', $this->testObjectCollection);
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \svelte\model\business\Record}
   * - assert is instance of {@link \svelte\model\business\LoginAccount}
   * @link svelte.model.business.LoginAccount svelte\model\business\LoginAccount
   */
  public function testGetInstance()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\Record', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\LoginAccount', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::auPK.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'auPK'
   * - assert property 'auPK' is gettable.
   * - assert returned value instance of {@link \svelte\model\business\field\Field}.
   * - assert returned value property of Field matches expected result.
   * @link svelte.model.business.LoginAccount#method_get_auPK svelte\model\business\LoginAccount::auPK
   */
  public function testGet_auPK()
  {
    try {
      $this->testObject->auPK = new Input(
        Str::set('auPK'),
        $this->testObject,
        new LowerCaseAlphanumeric()
      );
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        get_class($this->testObject) . '->auPK is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\model\business\field\Field', $this->testObject->auPK);
      $this->assertNull($this->testObject->auPK->value);
      $this->dataObject->auPK = 'user';
      $this->assertEquals('user', $this->testObject->auPK->value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::email.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'email'
   * - assert property 'email' is gettable.
   * - assert returned value instance of {@link \svelte\model\business\field\Field}.
   * - assert returned value property of Field matches expected result.
   * @link svelte.model.business.LoginAccount#method_get_email svelte\model\business\LoginAccount::email
   */
  public function testGet_email()
  {
    try {
      $this->testObject->email = new Input(
        Str::set('email'),
        $this->testObject,
        new RegexEmail()
      );
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        get_class($this->testObject) . '->email is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\model\business\field\Field', $this->testObject->email);
      $this->assertNull($this->testObject->email->value);
      $this->dataObject->email = 'a.person@email.com';
      $this->assertEquals('a.person@email.com', $this->testObject->email->value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::accountType.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'accountType'
   * - assert property 'accountType' is gettable.
   * - assert returned value instance of {@link \svelte\model\business\field\Field}.
   * - assert returned value property of Field matches expected result (LoginAccoutTypeOption)
   * @link svelte.model.business.LoginAccount#method_get_accountType svelte\model\business\LoginAccount::accountType
   */
  public function testGet_accountType()
  {
    try {
      $this->testObject->accountType = new Input(
        Str::set('accountType'),
        $this->testObject,
        new RegexEmail()
      );
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        get_class($this->testObject) . '->accountType is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\model\business\field\Field', $this->testObject->accountType);
      $this->assertEquals(LoginAccountType::get(0), $this->testObject->accountType->value);
      $this->assertEquals(0, $this->testObject->accountType->value->key);
      $this->dataObject->typeID = 4;
      $this->assertEquals(LoginAccountType::get(4), $this->testObject->accountType->value);
      $this->assertEquals(4, $this->testObject->accountType->value->key);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::validatePassword.
   * - assert invalid password returns FALSE
   * - assert valid password returns TRUE
   * @link svelte.model.business.LoginAccount#method_validatePassword svelte\model\business\LoginAccount::validatePassword
   */
  public function testValidatePassword()
  {
    $this->dataObject->encryptedPassword = crypt('Pa55w0rd', SETTING::$SECURITY_PASSWORD_SALT);
    $this->assertFalse($this->testObject->validatePassword('N0tPa55w0rd'));
    $this->assertTrue($this->testObject->validatePassword('Pa55w0rd'));
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::setPassword() and
   * \svelte\model\business\LoginAccount::getUnencryptedPassword().
   * - assert throws {@link \BadMethodCallException} when trying to get prior to setting
   *  - with massage *'Unencrypted password only available on same http request as set'*
   * - assert void returned on setting with setPassword() method
   * - assert getUnencryptedPassword() returns expected value post setPassword()
   * @link svelte.model.business.LoginAccount#method_setPassword svelte\model\business\LoginAccount::setPassword()
   * @link svelte.model.business.LoginAccount#method_getUnencryptedPassword svelte\model\business\LoginAccount::getUnencryptedPassword()
   */
  public function testSetPasswordGetUnencryptedPassword()
  {
    $this->dataObject->encryptedPassword = crypt('Pa55w0rd', SETTING::$SECURITY_PASSWORD_SALT);
    try {
      $this->testObject->getUnencryptedPassword();
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals(
        'Unencrypted password only available on same http request as set',
        $expected->getMessage()
      );
      $this->assertNull($this->testObject->setPassword('N3wPa55w0rd'));
      $this->assertEquals('N3wPa55w0rd', $this->testObject->getUnencryptedPassword());
    }
  }

  /**
   * Collection of assertions for \svelte\model\business\LoginAccount::populateAsNew().
   * - assert void returned on calling
   * - assert contained dataObject populated as expected
   * - assert property accountType is set to level one
   * - assert password auto generated, accessible through getUnencryptedPassword(), in expected format.
   * - assert encryptedPassword set and matches provided crypt with SALT
   * - assert properties of AuthenticatableUnit populated with PostData
   * - assert both loginAccount and associated AuthenticatableUnit are updated through relevant BusinessModelManager
   * - assert throws {@link \BadMethodCallException} when called on existing (NOT isNew) LoginAccount
   *  - with massage *'Method NOT allowed on existing LoginAccount!'*
   * @link svelte.model.business.LoginAccount#method_populateAsNew svelte\model\business\LoginAccount::populateAsNew()
   */
  public function testPopulateAsNew()
  {
    $_POST = array(
      'an-authenticatable-unit:new:uname' => 'user',
      'an-authenticatable-unit:new:email' => 'correct@email.com',
      'an-authenticatable-unit:new:family-name' => 'surname',
      'an-authenticatable-unit:new:given-name' => 'name'
    );
    $this->assertNull($this->testObject->populateAsNew(PostData::build($_POST)));
    $this->assertEquals('user', $this->dataObject->auPK);
    $this->assertEquals('correct@email.com', $this->dataObject->email);
    $this->assertEquals(1, $this->dataObject->typeID);
    $this->assertRegExp(
      "/^[a-zA-Z0-9!\"#$%&()+,-.\/:;<=>?@[\]^_{|`{|}~]{8}$/",
      $this->testObject->getUnencryptedPassword()
    );
    $this->assertSame(
      crypt((string)$this->testObject->getUnencryptedPassword(), \svelte\SETTING::$SECURITY_PASSWORD_SALT),
      $this->dataObject->encryptedPassword
    );
    $this->assertEquals('user', $this->testObject->uname->value);
    $this->assertEquals('surname', $this->testObject->familyName->value);
    $this->assertEquals('name', $this->testObject->givenName->value);
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['svelte\model\business\AnAuthenticatableUnit:user']));
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['svelte\model\business\LoginAccount:user']));
    $this->testObject->updated();
    $this->assertFalse($this->testObject->isNew);
    try {
      $this->testObject->populateAsNew(PostData::build($_POST));
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals('Method NOT allowed on existing LoginAccount!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }
}
