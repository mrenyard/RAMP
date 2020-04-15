<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\http;

use svelte\SETTING;
use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\condition\PostData;
use svelte\condition\Filter;
use svelte\condition\FilterCondition;
use svelte\model\business\SimpleBusinessModelDefinition;

use svelte\model\business\LoginAccountTypeOption;
use svelte\model\business\LoginAccountType;
use svelte\model\business\LoginAccount;

use svelte\view\View;

/**
 * Methods relating to logging in, setting up PHP session variables,
 * and checking account security before page execution.
 * <b>An instance of <i>this</i> must be called before outputting anything to the browser, therefore
 * this should be the first object referenced at the top on any HTTP controller script</b>
 */
final class Session extends SvelteObject {

  private static $instance;

  private $modelManager;
  private $resetPasswordView;

  private $loginAccount;

  private $accountEmailFilter;
  private $accountEmailCondition;

  private $userEmailFilter;
  private $userEmailCondition;

  /**
   * Constuct the instance.
   * @param \svelte\view\View $authenticationForm View used for authentication
   * @param \svelte\view\View $resetPasswordView View used for password reset including email.
   */
  private function __construct(View $resetPasswordView)
  {
    @session_start();
    $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->resetPasswordView = $resetPasswordView;

    $this->loginAccount = (isset($_SESSION['loginAccount']))? $_SESSION['loginAccount'] :
      $this->modelManager->getBusinessModel(
        new SimpleBusinessModelDefinition(Str::set('LoginAccount'), Str::set('new'))
      );

    $this->accountEmailFilter = new Filter();
    $this->accountEmailCondition = new FilterCondition(Str::set('LoginAccount'), Str::set('email'));
    $this->accountEmailFilter->add($this->accountEmailCondition);
  }

  /**
   * Get instance - same instance on every request (singleton) within same http request.
   * <b>This method must be called at least once before outputting anything to the browser.</b>
   * @param \svelte\view\View $resetPasswordView View used for password reset including email.
   * @throws \BadMethodCallException MUST provide an Authentication Form View and a
   *  Reset Password View on first call.
   * @return \svelte\http\Session Single instance of Session
   */
  public static function getInstance(View $resetPasswordView = null) : Session
  {
    if (!isset(self::$instance) || SETTING::$TEST_RESET_SESSION == TRUE) {
      if ($resetPasswordView == null) {
        throw new \BadMethodCallException(
          'MUST provide an Authentication Form View and a Reset Password View on first call.'
        );
      }
      self::$instance = new Session($resetPasswordView);
    }
    return self::$instance;
  }

  /**
   * Checks current user has at least the specified user authentication level.
   * @param LoginAccountTypeOption $authorizationLevel Authorization level to be surpassed.
   * @throws \BadMethodCallException Session::instance() MUST be called prior to use
   * @return \svelte\core\Boolean Current session login account authorized at authorization level
   */
  public static function authorizedAs(LoginAccountTypeOption $authorizationLevel) : bool
  {
    if(!isset(self::$instance)) {
      throw new \BadMethodCallException(
        'Session::instance() MUST be called before Session::authorizedAs().'
      );
    }
    return (
      (isset(self::$instance->loginAccount)) &&
      (self::$instance->loginAccount->isValid()) &&
      (self::$instance->loginAccount->accountType->id >= $authorizationLevel->id)
    );
  }

  /**
   * Checks current user has at least the specified user authentication level,
   * ff successful just returns, otherwise thows an Unauthorized401Exception.
   * @param AccountType $authorizationLevel
   * @throws svelte\http\Unauthorized401Exception when authentication fails
   */
  public function authorizeAs(LoginAccountTypeOption $authorizationLevel)
  {
    if (self::authorizedAs($authorizationLevel))
    {
      // Already authenticated with sufficient authority
      unset($_POST['login-password']);
      unset($_POST['login-email']);
      return;
    }
    // Unauthorized401Exception allowed to bubble up
    $this->attemptAccess($authorizationLevel);
  }

  /**
   * Uses any avalible credentials to assertain allowed access.
   * ... .
   * ... otherwise a 401 error page is generated and script exits
   * @throws svelte\http\Unauthorized401Exception when authentication fails
   */
  private function attemptAccess(LoginAccountTypeOption $authorizationLevel)
  {
    $this->loginAccount->forcePasswordField = TRUE;
    if (!isset($_POST) || count($_POST) < 1) // GET request
    {
      throw new Unauthorized401Exception('Unauthenticated or insufficient authority');
    }
    if (!isset($_POST['login-email']))
    {
      $_SESSION['post_array'] = $_POST; // set aside Post array
      throw new Unauthorized401Exception(
        'Attempting POST to resource REQUIRING authentication or insufficient authority'
      );
    }
    $loginEmail = $_POST['login-email']; unset($_POST['login-email']);
    $this->accountEmailCondition->comparable = $loginEmail;
    if (isset($_POST['login-password']))
    {
      $loginPassword = $_POST['login-password']; unset($_POST['login-password']);
    }
    $_SESSION['post_array'] = $_POST; // set aside Post array
    //$postdata = PostData::build($_POST);
    //$this->user->validate($postdata);
    if (isset($loginPassword)) // attempt email - password authentication
    {
      try {
        $loginAccount = $this->modelManager->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set('LoginAccount')), $this->accountEmailFilter
        )[0];
      } catch (\OutOfBoundsException $e) {
        throw new Unauthorized401Exception('Account (email) NOT in database');
      }
      if (
        (!$loginAccount->validatePassword($loginPassword)) ||
        ((int)$loginAccount->accountType->id <= (int)$authorizationLevel->id)
      ) {
        throw new Unauthorized401Exception('Invalid password or insufficient privileges');
      }
      if (isset($_SESSION['post_array'])) // reset $_POST
      {
        $_POST = $_SESSION['post_array']; unset($_SESSION['post_array']);
      }
      return;
    }
    $auEmailPropertyID = (string)Str::hyphenate(
      Str::set(SETTING::$SVELTE_AUTHENTICATIBLE_UNIT), TRUE)->append(Str::set(':new:email')
    );
    if (isset($_POST[$auEmailPropertyID]))
    {
      if ($loginEmail !== $_POST[$auEmailPropertyID])
      {
        unset($_POST[$auEmailPropertyID]);
        // TODO:mrenyard: add e-mail mismatch to errorCollection
        throw new Unauthorized401Exception('new authenticatible unit form e-mail mismatch');
      }
      try {
        // check authenticatible unit record NOT already exists
        $this->modelManager->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set('LoginAccount')), $this->accountEmailFilter
        )[0];
        unset($_POST[$auEmailPropertyID]);
        //TODO:mrenyard: add already registered to errorCollection
        throw new Unauthorized401Exception(
          'Trying to create new login where one already exists!'
        );
      }
      catch (\OutOfBoundsException $emailNOTexist) // new login details successfully confirmed
      {
        if ($authorizationLevel->id == 1)
        {
          $this->loginAccount->populateAsNew(PostData::build($_POST));
          if ($this->loginAccount->authenticatableUnit->isValid()) { return; }
        }
      }
    }
    throw new Unauthorized401Exception('SHOULD NEVER REACH HERE!');
  }

  /**
   * Changes the password of the supplied user and if the user has
   * sufficient authorisation. If no user is specified then the current user is assumed
   * If successful then it just returns, otherwise a 401 error page is generated and then
   * the script exits
   * @param string $password
   * @param \svelte\Record $accountRecord
   *
  public static function updatePassword(Str $password, model\UserAccount $accountRecord = \NULL)
  {
    $currentUserAccountRecord = self::get(); // get session data
    if (is_null($accountRecord)) {
      $accountRecord = $currentUserAccountRecord;
    } else {
      $currentUserAccountType = $currentUserAccountRecord->typeID;
      if ($currentUserAccountType < self::$staffAccess) {
        // user does not have suffcient privilages to change the
        // password of a different user
        throw new \Exception();
        //self::generate401();
      }
    }
    $modelManager = model\Manager::getInstance();

    $inputData = new \svelte\ManualInputData();
    $value = crypt($password, SETTING::$SVELTE_PASSWORD_SALT);
    $data = new PropertyInputData(
      $accountRecord->type,
      $accountRecord->id,
      Str::set('password'),
      $value
    );
    $inputData->add($data);

    $accountRecord->validate($inputData);
  }*/

  /**
   * Generate the reset key for a user password reset
   * @param \svelte\model\UserAccount $accountRecord
   *
  public static function generateResetPasswordKey(model\UserAccount $accountRecord)
  {
    $modelManager = model\Manager::getInstance();

    //$data['resetPasswordKey'] = md5(uniqid('php'));
    //$data['resetPasswordTimeStamp'] = date('Y-m-d H:i:s', mktime());

    $inputData = new \svelte\ManualInputData();

    $value = md5(uniqid('php'));
    $data = new PropertyInputData(
      $accountRecord->type,
      $accountRecord->id,
      Str::set('resetPasswordKey'),
      $value
    );
    $inputData->add($data);

    $value = date('Y-m-d H:i:s', mktime());
    $data = new PropertyInputData(
      $accountRecord->type,
      $accountRecord->id,
      Str::set('resetPasswordTimeStamp'),
      $value
    );
    $inputData->add($data);

    $accountRecord->validate($inputData);

    if (defined('DEV_MODE') && DEV_MODE) {
      \FB::group('generateResetPasswordKey');
      \FB::log($data);
      \FB::groupEnd();
    }
  }*/

  /**
   * Generate a Password Reset page if authentication fails.
   * @param int $errorCode
   *
  private static function generateLostpasswordPage($accountEmail)
  {
    $modelManager = model\Manager::getInstance();
    try {
      $accountRecord = $modelManager->getRecord(Str::set('UserAccount'), Str::set($accountEmail), Str::set('email'));
    } catch (\Exception $e) {
      if (defined('DEV_MODE') && DEV_MODE) {
        \FB::group('generateLostpasswordPage');
        \FB::log($e);
        \FB::groupEnd();
      }
      self::generate401();
    }
    if (isset($accountRecord)) {
      // generate a reset key and store it in the user record
      self::generateresetPasswordKey($accountRecord);
      $emailTo = $accountRecord->email;
      if (defined('DEV_MODE') && DEV_MODE) {
        \FB::group('generateLostpasswordPage');
        \FB::log($accountRecord);
        \FB::groupEnd();
      }
      // Create an instance of the Email document so we can generate and send an email
      $email = new view\email\Email();
      $emailRender = new view\email\passwordResendEmailRender(Str::set($emailTo));
      $emailRender->setEmail($email);
      $emailPage = new view\OutlineView($emailRender);

      $emailDocumentRender = new view\Templated(Str::set('byw/emailLostpassword'));
      $emailView = new view\RecordView($emailDocumentRender);
      $emailPage->add($emailView);
      $emailView->setModel($accountRecord);
      $emailPage->render();   // render and send email
    } else {
      if (defined('DEV_MODE') && DEV_MODE) {
        \FB::group('generateLostpasswordPage');
        \FB::log('Email not found');
        \FB::groupEnd();
      }
      self::generate401();
    }
    $modelManager = \NULL;

    header("HTTP/1.1 303 See Other");
    header("Location: /password-reset/");
    exit(0);
  }*/

  /**
   * Prevent cloning.
   * @throws \BadMethodCallException Cloning not allowed
   */
  public function __clone()
  {
    throw new \BadMethodCallException('Clone is not allowed');
  }
}
