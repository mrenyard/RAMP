<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\model\business;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Alphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowercaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/EmailAddress.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/AuthenticatableUnit.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccountType.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccount.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockSqlBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/AnAuthenticatableUnit.class.php';

use ramp\core\Str;
use ramp\model\business\LoginAccount;
use ramp\model\business\LoginAccountType;
use ramp\model\business\SimpleBusinessModelDefinition;

use tests\ramp\mocks\model\MockSqlBusinessModelManager;

/**
 * Collection of tests for ramp\model\business\LoginAccount.
 */
class LoginAccountTest extends \PHPUnit\Framework\TestCase
{
  private $dataObject;
  private $testObject;

  /**
   * Set-up.
   */
  public function setUp() : void
  {
    MockSqlBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_AUTHENTICATABLE_UNIT = 'AnAuthenticatableUnit';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockSqlBusinessModelManager';
    \ramp\SETTING::$SECURITY_PASSWORD_SALT = 'It\'s been a hard day\'s night And I\'ve been working like a dog';
    $this->dataObject = new \stdClass();
    $this->testObject = new LoginAccount($this->dataObject);
  }

  /**
   * Check initial state.
   * - assert is instance of {@see \ramp\model\business\Record}
   * - assert is instance of {@see \ramp\model\business\LoginAccount}
   * - assert isNew returns as expected (TRUE).
   * - assert isModified returns as expected (FALSE).
   * - assert isValid returns as expected (FALSE).
   * - assert primaryKey returns expected (NULL).
   * - assert auPK property retuns instanceof {@see \ramp\model\business\RecordComponent} with value of NULL.
   * - assert email property retuns instanceof {@see \ramp\model\business\RecordComponent} with value of NULL.
   * - assert {@see \ramp\model\business\loginAccountType} property retuns instanceof
   *   {@see \ramp\model\business\RecordComponent} with value {@see \ramp\model\business\LoginAccountType} Option.
   */
  public function testInitState()
  {
    $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\LoginAccount', $this->testObject);
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isModified);
    $this->assertFalse($this->testObject->isValid);
    $this->assertNull($this->testObject->primaryKey->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject->auPK);
    $this->assertNull($this->testObject->auPK->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject->email);
    $this->assertNull($this->testObject->email->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject->loginAccountType);
    $this->assertSame(0, $this->testObject->loginAccountType->value->key);
  }

  /**
   * Populate with associated data for provided AutenticatableUnit.
   * - assert *this* auPK holds primaryKey data for relevant {@see \ramp\model\business\AutenticatableUnit}.
   * - assert email eqaul to that of {@see \ramp\model\business\AutenticatableUnit}.
   * - assert LoginAccoutType::REGISTERED set as default.
   * - assert randomised password generated and encrypted with temparary access to the unencrypted version.
   * - assert data writen to datastore for future varification.
   * - assert through access provided for ALL other properties of {@see \ramp\model\business\AutenticatableUnit}.
   */
  public function testCreateForAutenticatableUnit()
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $manager = $MODEL_MANAGER::getInstance();
    $authenticatableUnit = $manager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('AnAuthenticatableUnit'), Str::set('existing'))
    );
    $this->assertFalse($authenticatableUnit->isNew);
    $this->assertTrue($authenticatableUnit->isValid);
    $this->testObject->createFor($authenticatableUnit);
    $this->assertEquals($this->testObject->auPK->value, $authenticatableUnit->uname->value);
    $this->assertEquals($this->testObject->email->value, $authenticatableUnit->email->value);
    $this->assertSame(LoginAccountType::REGISTERED, $this->testObject->loginAccountType->value->key);
    $password = $this->testObject->unencryptedPassword;
    $this->assertSame(
      crypt($password, \ramp\SETTING::$SECURITY_PASSWORD_SALT), $this->dataObject->encryptedPassword
    );
    $this->assertArrayHasKey('ramp\model\business\LoginAccount:existing', $manager->updateLog);
    $this->assertSame($this->testObject->givenName->value, $authenticatableUnit->givenName->value);
    $this->assertSame($this->testObject->familyName->value, $authenticatableUnit->familyName->value);
  }

  /**
   * Change or set new password and password validation.
   * - assert provided password set and encrypted with temparary access to the unencrypted version.
   * - assert correct password validation.
   * @see \ramp\model\business\LoginAccount::setPassword()
   * @see \ramp\model\business\LoginAccount::validatePassword()
   */
  public function testSetValidatePassword()
  {
    $this->testObject->setPassword('Pa55w0rd');
    $this->assertSame(
      crypt('Pa55w0rd', \ramp\SETTING::$SECURITY_PASSWORD_SALT), $this->dataObject->encryptedPassword
    );    
    $this->assertSame('Pa55w0rd', $this->testObject->unencryptedPassword);
    $this->assertFalse($this->testObject->validatePassword('N0tPa55w0rd'));
    $this->assertTrue($this->testObject->validatePassword('Pa55w0rd'));
  }

  /**
   * Check BadMethodCallException on improper access of getUnencryptedPassword().
   * - assert throws {@see \BadMethodCallException} when requested outside of same HTTP request. 
   *   - with message: **'Unencrypted password only available on same http request as set'**
   */
  public function testUnencryptedPasswordBadCall()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage('Unencrypted password only available on same http request as set');
    $this->testObject->getUnencryptedPassword();
  }
}
