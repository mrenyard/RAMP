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
//use svelte\model\business\LoginAccountTypeOption;
use svelte\model\business\LoginAccountType;
use svelte\model\business\LoginAccount;
use svelte\model\business\validation\FailedValidationException;
use svelte\view\View;

/**
 * Methods relating to logging in, setting up PHP session variables,
 * and checking session security before page execution.
 * **An instance of *this* must be called before outputting anything to the browser, therefore
 * this should be the first object referenced at the top on any HTTP controller script**
 *
 * EXAMPLE USE:
 * ```php
 * $session = http\Session::getInstance();
 * try {
 *   $session->authorizeAs(model\business\LoginAccountType::SYSTEM_ADMINISTRATOR());
 * } catch (http\Unauthorized401Exception $e) {
 *   header('HTTP/1.1 401 Unauthorized');
 *   ...
 *   return;
 * }
 * ...
 * ```
 */
final class Session extends SvelteObject
{
  private static $instance;

  private $modelManager;
  private $loginAccount;
  private $accountEmailFilter;
  private $accountEmailCondition;
  private $userEmailFilter;
  private $userEmailCondition;

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
    @session_start();
    $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
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
   * **This method MUST be called at least once before outputting anything to the
   * browser, ideally the first thing at the top of any controller page.**
   *
   * PRECONDITIONS
   * - SETTING::$SVELTE_BUSINESS_MODEL_MANAGER MUST be set.
   *
   * COLLABORATORS
   * - $_SESSION
   * - {@link \svelte\SETTING}
   * - {@link \svelte\condition\Filter}
   * - {@link \svelte\condition\FilterCondition}
   * - {@link \svelte\model\business\iBusinessModelManager}
   * - {@link \svelte\model\business\SimpleBusinessModelDefinition}
   *
   * @return \svelte\http\Session Single instance of Session
   */
  public static function getInstance() : Session
  {
    if (!isset(self::$instance) || SETTING::$TEST_RESET_SESSION == TRUE) {
      self::$instance = new Session();
    }
    return self::$instance;
  }

  /**
   * Returns wether the current authenticated $_SESSION has at least the specified authorization level.
   * @param int $authorizationLevel Authorization level to be surpassed.
   * @return bool Current authenticated session login account authorized at authorization level
   * @throws \BadMethodCallException Session::instance() MUST be called prior to use
   */
  public static function authorizedAs(int $authorizationLevel) : bool
  {
    if(!isset(self::$instance)) {
      throw new \BadMethodCallException(
        'Session::instance() MUST be called before Session::authorizedAs().'
      );
    }
    return (
      
      (isset(self::$instance->loginAccount)) &&
      (self::$instance->loginAccount->isValid) &&
      (self::$instance->loginAccount->accountType->value->key >= $authorizationLevel)
    );
  }

  /**
   * Checks the current $_SESSION has at least the specified authentication level
   * and if successful just returns, otherwise throws an Unauthorized401Exception.
   *
   * PRECONDITIONS
   * - SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE MUST be set
   * - SETTING::$SVELTE_AUTHENTICATIBLE_UNIT MUST be set
   * - Session::getInstance() MUST have been called at least once.
   * - $_SESSION['loginAccount'] MAY already be set, following proir succesfully authentication
   * - $_POST data MAY be sent for submission
   * - and or $_POST data login details for authentication
   *
   * POSTCONDITIONS
   * - Login related $_POST data unset
   * - On failed authentication $_SESSION['post_array'] holds sent $_POST data (NOT login related)
   * - On successful authentication $_SESSION['loginAccount'] has referance to relevant LoginAccount
   *
   * COLLABORATORS
   * - $_POST
   * - $_SESSION
   * - {@link \svelte\SETTING}
   * - {@link \svelte\condition\Filter}
   * - {@link \svette\condition\FiltetCondition}
   * - {@link \svelte\model\business\iBusinessModelManager}
   * - {@link \svelte\model\business\SimpleBusinessModelDefinition}
   * - {@link \svelte\model\business\LoginAccountTypeOption}
   * - {@link \svelte\model\business\LoginAccountType}
   * - {@link \svelte\model\business\LoginAccount}
   * - {@link \svelte\http\Unauthorized401Exception}
   *
   * @param int $authorizationLevel Required authorization Level
   * @throws Unauthorized401Exception when authorisation fails with one of the following messages:
   * - Unauthenticated or insufficient authority
   * - Attempting POST to resource REQUIRING authentication or insufficient authority
   * - Invalid email format
   * - Account (email) NOT in database
   * - Invalid password or insufficient privileges
   * - New Authenticatible Unit Form: e-mail mismatch
   * - Trying to create new login where one already exists!
   * - SHOULD NEVER REACH HERE!
   * @throws BadMethodCallException Session::instance() MUST be called prior to use
   */
  public function authorizeAs(int $authorizationLevel)
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

  private function attemptAccess(int $authorizationLevel)
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
    try {
      $this->accountEmailCondition->comparable = $loginEmail;
    } catch (\DomainException $invalidEmailFormat) {
      throw new Unauthorized401Exception('Invalid email format');
    }
    if (isset($_POST['login-password']))
    {
      $loginPassword = $_POST['login-password']; unset($_POST['login-password']);
    }
    $_SESSION['post_array'] = $_POST; // set aside Post array
    if (isset($loginPassword)) // attempt email - password authentication
    {
      try {
        $this->loginAccount = $this->modelManager->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set('LoginAccount')), $this->accountEmailFilter
        )[0];
      } catch (\OutOfBoundsException $emailNotInDatabase) {
        throw new Unauthorized401Exception('Account (email) NOT in database');
      }
      if (
        (!$this->loginAccount->validatePassword($loginPassword)) ||
        ((int)$this->loginAccount->accountType->value->key <= $authorizationLevel)
      ) {
        throw new Unauthorized401Exception('Invalid password or insufficient privileges');
      }
      if (isset($_SESSION['post_array'])) // reset $_POST
      {
        $_POST = $_SESSION['post_array']; unset($_SESSION['post_array']);
      }
      $_SESSION['loginAccount'] = $this->loginAccount;
      return;
    }
    $auEmailPropertyID = (string)Str::hyphenate(
      Str::set(SETTING::$SVELTE_AUTHENTICATABLE_UNIT), TRUE)->append(Str::set(':new:email')
    );
    if (isset($_POST[$auEmailPropertyID]))
    {
      if ($loginEmail !== $_POST[$auEmailPropertyID])
      {
        unset($_POST[$auEmailPropertyID]);
        // TODO:mrenyard: add e-mail mismatch to errorCollection
        throw new Unauthorized401Exception('New Authenticatable Unit Form: e-mail mismatch');
      }
      try {
        // check login account NOT already exist
        $this->modelManager->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set('LoginAccount')), $this->accountEmailFilter
        )[0];
        unset($_POST[$auEmailPropertyID]);
        throw new Unauthorized401Exception(
          'Trying to create new login where one already exists!'
        );
      }
      catch (\OutOfBoundsException $confirmedEmailIsNew) // new login details successfully confirmed
      {
        if ($authorizationLevel == 1)
        {
          $this->loginAccount->populateAsNew(PostData::build($_POST));
          $_SESSION['loginAccount'] = $this->loginAccount;
          if (isset($_SESSION['post_array'])) // reset $_POST
          {
            $_POST = $_SESSION['post_array']; unset($_SESSION['post_array']);
          }
          return;
        }
      }
    }
    throw new Unauthorized401Exception('SHOULD NEVER REACH HERE!');
  }

  /**
   * Accessor to logingAccount
   * @return \svelte\model\business\LoginAccount loginAccount LoginAccount for authentication and authorization
   */
  protected function get_loginAccount()
  {
    return $this->loginAccount;
  }

  /**
   * Prevent cloning.
   * @throws \BadMethodCallException Cloning not allowed
   */
  public function __clone()
  {
    throw new \BadMethodCallException('Clone is not allowed');
  }
}
