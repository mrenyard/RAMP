<?php
/**
 * Svelte - Rapid web application development using best practice.
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
namespace svelte\model\business;

use svelte\SETTING;
use svelte\core\Str;
use svelte\condition\PostData;
use svelte\model\business\RecordCollection;
use svelte\model\business\BusinessModel;
use svelte\model\business\LoginAccountType;

/**
 * Mock Collection of LoginAccount for testing \svelte\http\Session
 * .
 */
class LoginAccountCollection extends RecordCollection
{
}

/**
 * Mock LoginAccount for testing \svelte\http\Session
 * .
 */
class LoginAccount extends Record
{
  private $authenticatableUnit;
  private $dataObject;

  /**
   * Flag for assiciated View expressing wish to show password field.
   */
  public $forcePasswordField;

  /**
   * Returns property name of concrete classes primary key.
   * @return \svelte\core\Str Name of property that is concrete classes primary key
   */
  protected static function primaryKeyName() : Str { return Str::set('auPK'); }


  protected function get_auPK() : field\Field
  {
    if (!isset($this->children['auPK']))
    {
      $this->children['auPK'] = new field\Input(
        Str::set('auPK'),
        $this,
        new validationRule\LowerCaseAlphanumeric()
      );
    }
    return $this->children['auPK'];
  }

  /**
   * Get email
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Email address associated with *this*.
   */
  protected function get_email()
  {
    return new MockField(); //Str::set('email'), $this);
  }

  /**
   * Get login account type
   * **DO NOT CALL DIRECTLY, USE this->accountType;**
   * @return \svelte\model\business\LoginAccountType
   */
  protected function get_accountType()
  {
    return (isset($this->dataObject->typeID)) ?
      LoginAccountType::get($this->dataObject->typeID) :
        LoginAccountType::get(0);
  }

  /**
   * Get the associated Authenticatable Unit as defined with \svelte\SETTING and $this->auPK.
   * @return Record Authenticatable Unit associated with *this*.
   */
  protected function get_authenticatableUnit() //: Record
  {
    if (!isset($this->authenticatableUnit))
    {
      $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
      $auPK = $this->dataObject->auPK;
      if ($auPK) {
        $this->authenticatableUnit = $MODEL_MANAGER::getInstance()->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set(SETTING::$SVELTE_AUTHENTICATIBLE_UNIT), Str::set($auPK))
        );
      } else {
        $this->authenticatableUnit = $MODEL_MANAGER::getInstance()->getBusinessModel(
          new SimpleBusinessModelDefinition(Str::set(SETTING::$SVELTE_AUTHENTICATIBLE_UNIT), Str::set('new'))
        );
      }
    }
    return $this->authenticatableUnit;
  }

  /**
   * {@inheritdoc}
   */
  public function __get($propertyName)
  {
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $e) {
      return $this->getAuthenticatableUnit()->$propertyName;
    }
  }

  public function validatePassword(string $password) : bool
  {
    return ($password == 'P@ssw0rd!');
  }

  /**
   *
   */
  public function populateAsNew(PostData $postdata)
  {
    /*if (!$this->isNew() || !$this->isValid()) { throw new BadMethodCallException(); }
    $this->setPassword($this->generateRandomPassword());
    $this->validate($postdata);
    $au = $this->getAuthenticatableUnit();
    $au->validate($postdata);
    $auPkName = (string)$au->primaryKeyName();
    $this->auPK = $au->$auPkName;
    if ($au->isValid() && $account->isValid()) {
      $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
      $modelManager = $MODEL_MANAGER::getInstance();
      $modelManager->update($au);
      $modelManager->update($this);
    }*/
  }

  /**
   * Generate a random string for a password.
   * @return \svelte\String $newPassword New rendom grnerated password.
   *
  private static function generateRandomPassword()
  {
    $numChars = 8;
    $keyset = 'abcdefghjkmpqrstuvwxyABCDEFGHJKLMNPQRSTVWXYZ1234567890!"#$%&()+,-./:;<=>?@[\]^_`{|}~';
    $randkey = '';
    for ($i = 0; $i < $numChars; $i++) {
      $randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
    }
    return $randkey;
  }*/

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($this->dataObject->auPK) &&
      isset($this->dataObject->email) &&
      isset($this->dataObject->encryptedPassword) &&
      isset($this->dataObject->typeID)
    );
  }
}
