<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\http;

use ramp\SETTING;
use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\condition\Filter;
use ramp\condition\FilterCondition;
use ramp\model\business\LoginAccountType;
use ramp\model\business\LoginAccount;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\DataFetchException;
use ramp\model\business\validation\FailedValidationException;
use ramp\view\View;
use ramp\view\RootView;
use ramp\view\document\Email;

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
 * } catch (http\Unauthorized401Exception $exception) {
 *   header('HTTP/1.1 401 Unauthorized');
 *   ...
 *   return;
 * }
 * ...
 * ```
 * 
 * @property-read \ramp\model\business\LoginAccount $loginAccount LoginAccount for authentication and authorization
 */
final class Session extends RAMPObject
{
  private static $instance;

  private $modelManager;
  private $loginAccount;
  private $accountEmailFilter;
  private $accountEmailCondition;

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
    @session_start();
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->loginAccount = (isset($_SESSION['loginAccount']))? $_SESSION['loginAccount'] :
      $this->modelManager->getBusinessModel(new SimpleBusinessModelDefinition(Str::set('LoginAccount'), Str::set('new')));
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
   * - SETTING::$RAMP_BUSINESS_MODEL_MANAGER MUST be set.
   *
   * COLLABORATORS
   * - $_SESSION
   * - {@see \ramp\SETTING}
   * - {@see \ramp\condition\Filter}
   * - {@see \ramp\condition\FilterCondition}
   * - {@see \ramp\model\business\iBusinessModelManager}
   * - {@see \ramp\model\business\SimpleBusinessModelDefinition}
   *
   * @return \ramp\http\Session Single instance of Session
   */
  public static function getInstance() : Session
  {
    if (!isset(SELF::$instance) || SETTING::$TEST_RESET_SESSION == TRUE) {
      SELF::$instance = new Session();
    }
    return SELF::$instance;
  }

  /**
   * Returns wether the current authenticated $_SESSION has at least the specified authorization level.
   * @param int $authorizationLevel Authorization level to be surpassed.
   * @return bool Current authenticated session login account authorized at authorization level
   * @throws \BadMethodCallException Session::instance() MUST be called prior to use
   */
  public static function authorizedAs(int $authorizationLevel) : bool
  {
    if(!isset(SELF::$instance)) {
      throw new \BadMethodCallException(
        'Session::instance() MUST be called before Session::authorizedAs().'
      );
    }
    return (
      (isset(SELF::$instance->loginAccount)) &&
      (SELF::$instance->loginAccount->isValid) &&
      (SELF::$instance->loginAccount->loginAccountType->value->key >= $authorizationLevel)
    );
  }

  /**
   * Checks the current $_SESSION has at least the specified authentication level
   * and if successful just returns, otherwise throws an Unauthorized401Exception.
   *
   * PRECONDITIONS
   * - SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE MUST be set
   * - SETTING::$RAMP_AUTHENTICATIBLE_UNIT MUST be set
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
   * - {@see \ramp\SETTING}
   * - {@see \ramp\condition\Filter}
   * - {@see \ramp\condition\FiltetCondition}
   * - {@see \ramp\model\business\iBusinessModelManager}
   * - {@see \ramp\model\business\SimpleBusinessModelDefinition}
   * - {@see \ramp\model\business\LoginAccountType}
   * - {@see \ramp\model\business\LoginAccount}
   * - {@see \ramp\http\Unauthorized401Exception}
   *
   * @param int $authorizationLevel Required authorization Level
   * @throws Unauthorized401Exception when authorisation fails with one of the following messages:
   * - Unauthenticated or insufficient authority (1)
   * - Attempting POST to resource REQUIRING authentication or insufficient authority (2)
   * - Invalid email format (3)
   * - Account (email) NOT in database (4)
   * - New Authenticatible Unit Form: e-mail mismatch (5)
   * - Invalid password or insufficient privileges (6)
   * - Trying to create new login where one already exists! (7)
   * - SHOULD NEVER REACH HERE! (0)
   * @throws BadMethodCallException Session::instance() MUST be called prior to use
   */
  public function authorizeAs(int $authorizationLevel) : void
  {
    if (SELF::authorizedAs($authorizationLevel))
    {
      // Already authenticated with sufficient authority
      unset($_POST['login-password']);
      unset($_POST['login-email']);
      return;
    }
    // Unauthorized401Exception allowed to bubble up
    $this->attemptAccess($authorizationLevel);
  }

  private function attemptAccess(int $authorizationLevel) : void
  {
    $this->loginAccount->forcePasswordField = TRUE;
    if (!isset($_POST) || count($_POST) < 1) // GET request
    {
      throw new Unauthorized401Exception('Unauthenticated or insufficient authority', 1);
    }
    if (!isset($_POST['login-email']))
    {
      $_SESSION['post_array'] = $_POST; // set aside Post array
      throw new Unauthorized401Exception(
        'Attempting POST to resource REQUIRING authentication or insufficient authority', 2
      );
    }
    $loginEmail = $_POST['login-email']; unset($_POST['login-email']);
    try {
      $this->accountEmailCondition->comparable = $loginEmail;
    } catch (\DomainException $invalidEmailFormat) {
      throw new Unauthorized401Exception('Invalid email format', 3);
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
          new SimpleBusinessModelDefinition(Str::set('LoginAccount')),
          $this->accountEmailFilter
        )[0];
      } catch (\DomainException | \OutOfBoundsException $loginAccountNotInDatabase) {
        throw new Unauthorized401Exception('Account (email) NOT in database', 4);
      }
      if (
        (!$this->loginAccount->validatePassword($loginPassword)) ||
        ((int)$this->loginAccount->loginAccountType->value->key < $authorizationLevel)
      ) {
        throw new Unauthorized401Exception('Invalid password or insufficient privileges', 6);
      }
      if (isset($_SESSION['post_array'])) // reset $_POST
      {
        $_POST = $_SESSION['post_array']; unset($_SESSION['post_array']);
      }
      $_SESSION['loginAccount'] = $this->loginAccount;
      return;
    }
    $strAU = Str::set(SETTING::$RAMP_AUTHENTICATABLE_UNIT);
    $auEmailPropertyID = (string)Str::hyphenate($strAU)->append(Str::set(':new:email'));
    if (isset($_POST[$auEmailPropertyID]))
    {
      if ($loginEmail !== $_POST[$auEmailPropertyID])
      {
        unset($_POST[$auEmailPropertyID]);
        // TODO:mrenyard: add e-mail mismatch to errorCollection
        throw new Unauthorized401Exception('New Authenticatable Unit Form: e-mail mismatch', 5);
      }
      try {
        // check login account NOT already exist
        $this->modelManager->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set('LoginAccount')),
          $this->accountEmailFilter
        )[0];
        unset($_POST[$auEmailPropertyID]);
        throw new Unauthorized401Exception('Trying to create new login where one already exists!', 7);
      }
      catch (DataFetchException | \OutOfBoundsException $confirmedEmailNew)
      {
        // new login details successfully confirmed
        if ($authorizationLevel == LoginAccountType::REGISTERED)
        {
          try {
            $authenticatableUnit = $this->modelManager->getBusinessModel(
              new SimpleBusinessModelDefinition($strAU),
              Filter::build($strAU, array('email' => $_POST[$auEmailPropertyID]))
            )[0];
          } catch (DataFetchException $authenticatableUnitNotInDatabase) {
            $authenticatableUnit = $this->modelManager->getBusinessModel(
              new SimpleBusinessModelDefinition($strAU, Str::set('new'))
            );  
            $authenticatableUnit->validate(PostData::build($_POST));
            $this->modelManager->update($authenticatableUnit);
          }          
          $this->loginAccount->createFor($authenticatableUnit);
          $view = new Email(
            RootView::getInstance(),
            Str::set(SETTING::$EMAIL_WELCOME_TEMPLATE),
            Str::set(SETTING::$EMAIL_WELCOME_TEMPLATE_TYPE)
          );
          $view->title = Str::set(SETTING::$EMAIL_WELCOME_SUBJECT_LINE);
          $view->setModel($this->loginAccount);
          $_SESSION['loginAccount'] = $this->loginAccount;
          if (isset($_SESSION['post_array'])) // reset $_POST
          {
            $_POST = $_SESSION['post_array']; unset($_SESSION['post_array']);
            foreach ($_POST as $name => $value) {
              if (strpos($name, (string)Str::hyphenate($strAU) . ':new') !== FALSE) {
                unset($_POST[$name]);
              }
            }
          }
          return;
        }
      }
    }
    throw new Unauthorized401Exception('SHOULD NEVER REACH HERE!', 0);
  }

  /**
   * Accessor to logingAccount
   * @return \ramp\model\business\LoginAccount loginAccount LoginAccount for authentication and authorization
   */
  protected function get_loginAccount() : LoginAccount
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
