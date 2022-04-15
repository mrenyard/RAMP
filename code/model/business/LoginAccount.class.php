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
use ramp\core\BadPropertyCallException;
use ramp\condition\PostData;
use ramp\model\business\RecordCollection;
use ramp\model\business\BusinessModel;
use ramp\model\business\LoginAccountType;
use ramp\model\business\AuthenticatibleUnit;

/**
 * Referance and maintain a collection of \ramp\model\business\LoginAccounts.
 *
 * RESPONSIBILITIES
 * - Implement methods for property access
 * - Implement methods for validity checking & reporting
 * - Provide access to this Collection
 * - Provide methods to maintain a Collection of {@link Record}s
 *
 * COLLABORATORS
 * - Collection of {@link \ramp\model\business\LoginAccount}s
 */
class LoginAccountCollection extends RecordCollection
{
}

/**
 * LoginAccount for authentication and authorization.
 *
 * RESPONSIBILITIES
 * - Act as main component of {@link \ramp\http\Session} to manage login activities
 * - Holds current access level {@link \ramp\model\business\LoginAccountType}
 * - Coupling of login and authentication activities with chosen local
 *   {@link \ramp\model\business\AuthenticatableUnit}
 * - Auto generate secure password to associate with provided email address
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\AuthenticatableUnit}
 * - {@link \ramp\model\business\LoginAccountType}
 * - {@link \ramp\model\business\field\SelectOne}
 * - {@link \ramp\model\business\field\Input}
 *
 * @property-read field\Field $auPK Returns field containg value of Authenticatable Unit Primary Key.
 * @property-read field\Field $email Returns field containing value of login account associated email address.
 * @property-read field\Field $accountType Returns field containing value of account type (LoginAccountType).
 */
class LoginAccount extends Record
{
  private $primaryProperty;
  private $email;
  private $authenticatableUnit;
  private $unencryptedPassword;

  /**
   * Flag for assiciated View expressing wish to show password field.
   * @var bool $forcePasswordField Show password field flag
   */
  public $forcePasswordField;

  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public static function primaryKeyName() : Str { return Str::set('auPK'); }

  /**
   * Get field containing authenticatable unit's primary key
   * **DO NOT CALL DIRECTLY, USE this->auPK;**
   * @return \ramp\model\business\field\Field Returns field containing value of
   * authenticatable unit's primary key.
   */
  protected function get_auPK() : field\Field
  {
    if (!isset($this->primaryProperty))
    {
      $this->primaryProperty = new field\Input(
        Str::set('auPK'),
        $this,
        new validation\dbtype\VarChar(
          20,
          new validation\LowerCaseAlphanumeric(),
          Str::set('My error message HERE!')
        )
      );
      if ($this->isNew) { $this['auPK'] = $this->primaryProperty; }
    }
    return $this->primaryProperty;
  }

  /**
   * Get field containing email address
   * **DO NOT CALL DIRECTLY, USE this->email;**
   * @return \ramp\model\business\field\Field Returns field containing value of email address
   */
  protected function get_email() : field\Field
  {
    if (!isset($this->email))
    {
      $this->email = new field\Input(
        Str::set('email'),
        $this,
        new validation\dbtype\VarChar(
          150,
          new validation\RegexEmail(),
          Str::set('My error message HERE!')
        )
      );
      // if ($this->isNew) { $this['email'] = $this->email; }
    }
    return $this->email;
  }

  /**
   * Get field containing login account type
   * **DO NOT CALL DIRECTLY, USE this->accountType;**
   * @return \ramp\model\business\field\Field Returns field containing value (LoginAccountType)
   */
  protected function get_accountType() : field\Field
  {
    if (!isset($this['accountType']))
    {
      $this['accountType'] = new field\SelectOne(
        Str::set('accountType'),
        $this,
        new LoginAccountType()
      );
    }
    return $this['accountType'];
  }

  /**
   * Get the associated Authenticatable Unit as defined with \ramp\SETTING and $this->auPK.
   * @return AuthenticatableUnit Authenticatable Unit associated with *this*.
   */
  private function getAuthenticatableUnit() : AuthenticatableUnit
  {
    $auPK = $this->getPropertyValue('auPK');
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    if (isset($auPK) && (!isset($this->authenticatableUnit) || $this->authenticatableUnit->isNew))
    {
      $this->authenticatableUnit = $MODEL_MANAGER::getInstance()->getBusinessModel(
        new SimpleBusinessModelDefinition(Str::set(SETTING::$RAMP_AUTHENTICATABLE_UNIT), Str::set($auPK))
      );
    }
    elseif (!isset($this->authenticatableUnit))
    {
      $this->authenticatableUnit = $MODEL_MANAGER::getInstance()->getBusinessModel(
        new SimpleBusinessModelDefinition(Str::set(SETTING::$RAMP_AUTHENTICATABLE_UNIT), Str::set('new'))
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
    } catch (BadPropertyCallException $e) {
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
    return ($this->getPropertyValue('encryptedPassword') == crypt($password, SETTING::$SECURITY_PASSWORD_SALT));
  }

  /**
   * Populate properties of *this* and associated authenticatable unit with provided PostData,
   * generated or default values.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   * to be assessed for validity and imposed on associated authenticatable unit or *this*
   * @throws \BadMethodCallException When NOT new
   */
  public function populateAsNew(PostData $postdata)
  {
    if (!$this->isNew) {
      throw new \BadMethodCallException('Method NOT allowed on existing LoginAccount!');
    }
    $au = $this->getAuthenticatableUnit();
    $au->validate($postdata);
    $this->setPropertyValue('auPK', $au->getPropertyValue((string)$au->primaryKeyName()));
    $this->setPropertyValue('email', $au->getPropertyValue('email'));
    $this->setPropertyValue('accountType', 1);
    $this->setPassword($this->generateRandomPassword());
    /*if ($this->isValid && $au->isValid) {
      $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
      $modelManager = $MODEL_MANAGER::getInstance();
      $modelManager->update($au);
      $modelManager->update($this);
    }*/
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

  /**
   * Check requeried properties have value or not.
   * @param $dataObject DataObject to be checked for requiered property values
   * @return bool All requiered properties set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->email) &&
      isset($dataObject->encryptedPassword) &&
      isset($dataObject->accountType)
    );
  }
}
