<?php
/**
 * RAMP - Rapid web application development using best practice.
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
namespace ramp\model\business;

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\core\BadPropertyCallException;
use ramp\condition\PostData;
use ramp\condition\Filter;
use ramp\model\business\RecordCollection;
use ramp\model\business\BusinessModel;
use ramp\model\business\LoginAccountType;
use ramp\model\business\AuthenticatibleUnit;

/**
 * LoginAccount for authentication and authorization.
 *
 * RESPONSIBILITIES
 * - Act as main component of {@see \ramp\http\Session} to manage login activities
 * - Holds current access level {@see \ramp\model\business\LoginAccountType}
 * - Coupling of login and authentication activities with chosen local
 *   {@see \ramp\model\business\AuthenticatableUnit}
 * - Auto generate secure password to associate with provided email address
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\AuthenticatableUnit}
 * - {@see \ramp\model\business\LoginAccountType}
 * - {@see \ramp\model\business\field\SelectOne}
 * - {@see \ramp\model\business\field\Input}
 *
 * @property-read field\Field $auPK Returns field containg value of Authenticatable Unit Primary Key.
 * @property-read field\Field $email Returns field containing value of login account associated email address.
 * @property-read field\Field $accountType Returns field containing value of account type (LoginAccountType).
 */
final class LoginAccount extends Record
{
  private $authenticatableUnit;
  private $unencryptedPassword;

  /**
   * Flag for assiciated View expressing wish to show password field.
   * @var bool $forcePasswordField Show password field flag
   */
  public $forcePasswordField;

  /**
   * @ignore
   */
  protected function get_auPK() : ?RecordComponent
  {
    if ($this->register('auPK', RecordComponentType::KEY, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('The associated authenticatable Unit\'s primary key'),
        new validation\dbtype\VarChar(
          Str::set('string with a maximun character length of '),
          20, new validation\LowerCaseAlphanumeric(
            Str::set('lowercase and alphanumeric')
          )
        )
      ));
    }
    return $this->registered;
  }

  /**
   * @ignore
   */
  protected function get_email() : ?RecordComponent
  {
    if ($this->register('email', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('A uniquely identified electronic mailbox at which you receive written messages.'),
        new validation\dbtype\VarChar(
          Str::set('string with a maximun character length of '),
          150,  new validation\EmailAddress(
            Str::set('validly formatted email address')
          )
        )
      ));
    }
    return $this->registered; 
  }

  /**
   * @ignore
   */
  protected function get_loginAccountType() : ?RecordComponent
  {
    if ($this->register('loginAccountType', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new field\SelectOne($this->registeredName, $this, new LoginAccountType()));
    }
    return $this->registered; 
  }

  /**
   * Get the associated Authenticatable Unit as defined with \ramp\SETTING and $this->auPK.
   * @return AuthenticatableUnit Authenticatable Unit associated with *this*.
   */
  private function getAuthenticatableUnit(string $byAuEmail = NULL) : AuthenticatableUnit
  {
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $auPK = $this->getPropertyValue('auPK');
    if (!isset($this->authenticatableUnit)) {
      $key = Str::set((isset($auPK))? $auPK : 'new');
      $this->authenticatableUnit = $MODEL_MANAGER::getInstance()->getBusinessModel(
        new SimpleBusinessModelDefinition(Str::set(SETTING::$RAMP_AUTHENTICATABLE_UNIT), $key)
      );  
    }
    return $this->authenticatableUnit;
  }

  /**
   * Allows C# type access to properties, including those of associated AutenticatableUnit's
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   *
   * **Passes:** `$object->aProperty;` **to:** `$object->get_aProperty();`
   *
   * Implementation in concrete Object
   * ```php
   * private aProperty;
   *
   * protected function get_aProperty()
   * {
   *   return $this->aProperty;
   * }
   * ```
   *
   * Called externally (C# style)
   * ```php
   * $object->aProperty; // Get value 'aProperty'
   * ```
   *
   * @param string $propertyName Name of property (handled internally)
   * @return mixed|void The value of requested property
   * @throws \ramp\core\BadPropertyCallException Undefined or inaccessible property called
   */
  public function __get($propertyName)
  {
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $exception) {
      if ($propertyName == 'unencryptedPassword' && isset($this->unencryptedPassword)) {
        return $this->getUnencryptedPassword();
      }
      return  $this->getAuthenticatableUnit()->$propertyName;
    }
  }

  /**
   * Validate provided password against stored encrypted password.
   * @param string $password Password to be validated
   * @return bool Password validity
   */
  public function validatePassword(string $password) : bool
  {
    return ($this->getPropertyValue('encryptedPassword') === \crypt($password, SETTING::$SECURITY_PASSWORD_SALT));
  }

  /**
   * Populate properties of *this* and associated authenticatable unit with provided PostData,
   * generated or default values.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   * to be assessed for validity and imposed on associated authenticatable unit or *this*
   * @throws \BadMethodCallException When NOT new
   * @throws \InvalidArgumentException When provided argument NOT in valid state.
   */
  public function createFor(AuthenticatableUnit $authenticatableUnit)
  {
    if (!$this->isNew) { throw new \BadMethodCallException('Method NOT allowed on existing LoginAccount!'); }
    if (!$authenticatableUnit->isValid) { throw new \InvalidArgumentException('$authenticatableUnit MUST be in Valid state'); }
    $this->setPropertyValue('auPK', $authenticatableUnit->primaryKey->value);
    $this->setPropertyValue('email', $authenticatableUnit->getPropertyValue('email'));
    $this->setPropertyValue('loginAccountType', 1);
    $this->setPassword($this->generateRandomPassword());
    if (!$this->isValid) { throw new \Exception('Unable to add Login Account'); }
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $modelManager = $MODEL_MANAGER::getInstance();
    $modelManager->update($this);
  }

  /**
   * Returns recently set password (unencrypted) provided called within the same HTTP request.
   * @return string Returns recently set password (unencrypted).
   * @throws \ramp\core\BadPropertyCallException When called outside of same http request as set
   */
  public function getUnencryptedPassword() : string
  {
    if (!isset($this->unencryptedPassword))
    {
      throw new \BadMethodCallException(
        'Unencrypted password only available on same http request as set'
      );
    }
    return $this->unencryptedPassword;
  }

  /**
   * Set and encrypt password
   * @param string $unencryptedPassword Password to be encrypted and set
   */
  public function setPassword(string $unencryptedPassword)
  {
    $this->unencryptedPassword = $unencryptedPassword;
    $this->setPropertyValue(
      'encryptedPassword',
      crypt($this->unencryptedPassword, SETTING::$SECURITY_PASSWORD_SALT)
    );
  }

  /**
   * Generate a random string for a password.
   * @return string $newPassword New rendom grnerated password.
   */
  private static function generateRandomPassword() : string
  {
    $numChars = 12;
    $keyset = 'abcdefghjkmpqrstuvwxyzABCDEFGHJKLMNPQRSTVWXYZ0123456789!"#$%&()+,-./:;<=>?[]^_`{|}~';
    $randkey = '';
    for ($i = 0; $i < $numChars; $i++) {
      $randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
    }
    return $randkey;
  }
}
