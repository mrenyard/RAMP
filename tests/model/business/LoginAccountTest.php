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
 * @version 0.0.9;
 */
namespace tests\ramp\model\business;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/AuthenticatableUnit.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccountType.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccount.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Alphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowerCaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexEmail.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';

require_once '/usr/share/php/tests/ramp/http/mocks/SessionTest/model/business/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/http/mocks/SessionTest/model/business/AnAuthenticatableUnit.class.php';
require_once '/usr/share/php/tests/ramp/http/mocks/SessionTest/model/business/AnAuthenticatableUnitCollection.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\condition\Filter;
use ramp\model\business\LoginAccountCollection;
use ramp\model\business\LoginAccount;
use ramp\model\business\LoginAccountType;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\field\Input;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\LowerCaseAlphanumeric;
use ramp\model\business\validation\RegexEmail;

use tests\ramp\http\mocks\SessionTest\model\business\MockBusinessModelManager;

/**
 * Collection of tests for ramp\model\business\LoginAccount.
 */
class LoginAccountTest extends \PHPUnit\Framework\TestCase
{
  private $dataObject;
  private $testObject;
  private $testObjectCollection;

  /**
   * Set-up.
   */
  public function setUp() : void
  {
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='ramp\model\business';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\http\mocks\SessionTest\model\business\MockBusinessModelManager';
    SETTING::$SECURITY_PASSWORD_SALT = 'A hard days night!';
    SETTING::$RAMP_AUTHENTICATABLE_UNIT = 'AnAuthenticatableUnit';
    $this->dataObject = new \stdClass();
    $this->testObject = new LoginAccount($this->dataObject);
    $this->testObjectCollection = new LoginAccountCollection();
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccountCollection::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\model\business\RecordCollection}
   * - assert is instance of {@link \ramp\model\business\LoginAccountCollection}
   * @link ramp.model.business.LoginAccountCollection ramp\model\business\LoginAccountCollection
   */
  public function testGetCollectionInstance()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObjectCollection);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObjectCollection);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $this->testObjectCollection);
    $this->assertInstanceOf('\ramp\model\business\LoginAccountCollection', $this->testObjectCollection);
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\model\business\Record}
   * - assert is instance of {@link \ramp\model\business\LoginAccount}
   * @link ramp.model.business.LoginAccount ramp\model\business\LoginAccount
   */
  public function testGetInstance()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\LoginAccount', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::auPK.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'auPK'
   * - assert property 'auPK' is gettable.
   * - assert returned value instance of {@link \ramp\model\business\field\Field}.
   * - assert returned value property of Field matches expected result.
   * @link ramp.model.business.LoginAccount#method_get_auPK ramp\model\business\LoginAccount::auPK
   */
  public function testGet_auPK()
  {
    try {
      $this->testObject->auPK = 'bad';
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        get_class($this->testObject) . '->auPK is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject->auPK);
      $this->assertNull($this->testObject->auPK->value);
      $this->dataObject->auPK = 'user';
      $this->assertEquals('user', $this->testObject->auPK->value);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::email.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'email'
   * - assert property 'email' is gettable.
   * - assert returned value instance of {@link \ramp\model\business\field\Field}.
   * - assert returned value property of Field matches expected result.
   * @link ramp.model.business.LoginAccount#method_get_email ramp\model\business\LoginAccount::email
   */
  public function testGet_email()
  {
    try {
      $this->testObject->email = 'bad@doman.com';
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        get_class($this->testObject) . '->email is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject->email);
      $this->assertNull($this->testObject->email->value);
      $this->dataObject->email = 'a.person@email.com';
      $this->assertEquals('a.person@email.com', $this->testObject->email->value);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::accountType.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'accountType'
   * - assert property 'accountType' is gettable.
   * - assert returned value instance of {@link \ramp\model\business\field\Field}.
   * - assert returned value property of Field matches expected result (LoginAccoutTypeOption)
   * @link ramp.model.business.LoginAccount#method_get_accountType ramp\model\business\LoginAccount::accountType
   */
  public function testGet_accountType()
  {
    try {
      $this->testObject->accountType = 1;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        get_class($this->testObject) . '->accountType is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject->loginAccountTypeID);
      $this->assertEquals(0, $this->testObject->loginAccountTypeID->value->key);
      $this->dataObject->loginAccountTypeID = LoginAccountType::ADMINISTRATOR();
      $this->assertEquals(LoginAccountType::ADMINISTRATOR(), $this->testObject->loginAccountTypeID->value->key);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::validatePassword.
   * - assert invalid password returns FALSE
   * - assert valid password returns TRUE
   * @link ramp.model.business.LoginAccount#method_validatePassword ramp\model\business\LoginAccount::validatePassword
   */
  public function testValidatePassword()
  {
    $this->dataObject->encryptedPassword = crypt('Pa55w0rd', SETTING::$SECURITY_PASSWORD_SALT);
    $this->assertFalse($this->testObject->validatePassword('N0tPa55w0rd'));
    $this->assertTrue($this->testObject->validatePassword('Pa55w0rd'));
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::setPassword() and
   * \ramp\model\business\LoginAccount::getUnencryptedPassword().
   * - assert throws {@link \BadMethodCallException} when trying to get prior to setting
   *  - with massage *'Unencrypted password only available on same http request as set'*
   * - assert void returned on setting with setPassword() method
   * - assert getUnencryptedPassword() returns expected value post setPassword()
   * @link ramp.model.business.LoginAccount#method_setPassword ramp\model\business\LoginAccount::setPassword()
   * @link ramp.model.business.LoginAccount#method_getUnencryptedPassword ramp\model\business\LoginAccount::getUnencryptedPassword()
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
   * Collection of assertions for \ramp\model\business\LoginAccount::createFor() new AuthenticatableUnit.
   * - assert void returned on calling
   * - assert contained dataObject populated as expected
   * - assert property accountType is set to level one
   * - assert password auto generated, accessible through getUnencryptedPassword(), in expected format.
   * - assert encryptedPassword set and matches provided crypt with SALT
   * - assert properties of AuthenticatableUnit populated with PostData
   * - assert both loginAccount and associated AuthenticatableUnit are updated through relevant BusinessModelManager
   * - assert throws {@link \BadMethodCallException} when called on existing (NOT isNew) LoginAccount
   *  - with massage *'Method NOT allowed on existing LoginAccount!'*
   * @link ramp.model.business.LoginAccount#method_createFor ramp\model\business\LoginAccount::createFor()
   */
  public function testCreateForNewAuthenticatableUnit()
  {
    $modelManager = SETTING::$RAMP_BUSINESS_MODEL_MANAGER::getInstance();
    $authenticatableUnitType = Str::set(SETTING::$RAMP_AUTHENTICATABLE_UNIT);
    $authenticatableUnit = $modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition($authenticatableUnitType, Str::set('new'))
    );  
    $_POST = array(
      'an-authenticatable-unit:new:uname' => 'aperson',
      'an-authenticatable-unit:new:email' => 'a.person@domain.com',
      'an-authenticatable-unit:new:family-name' => 'Person',
      'an-authenticatable-unit:new:given-name' => 'Ann',
    );
    $authenticatableUnit->validate(PostData::build($_POST));
    $modelManager->update($authenticatableUnit);
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['ramp\model\business\AnAuthenticatableUnit:aperson']));
    $this->assertNull($this->testObject->createFor($authenticatableUnit));

    $this->assertArrayNotHasKey('auPK', $this->testObject);
    $this->assertEquals('aperson', $this->dataObject->auPK);
    $this->assertEquals('a.person@domain.com', $this->dataObject->email);
    $this->assertEquals(1, $this->dataObject->loginAccountTypeID);
    $this->assertRegExp(
      "/^[a-zA-Z0-9!\"#$%&()+,-.\/:;<=>?@[\]^_{|`{|}~]{12}$/",
      $this->testObject->getUnencryptedPassword()
    );
    $this->assertSame(
      crypt((string)$this->testObject->getUnencryptedPassword(), \ramp\SETTING::$SECURITY_PASSWORD_SALT),
      $this->dataObject->encryptedPassword
    );
    $this->assertEquals('aperson', $this->testObject->uname->value);
    $this->assertEquals('a.person@domain.com', $this->testObject->email->value);
    $this->assertEquals('Person', $this->testObject->familyName->value);
    $this->assertEquals('Ann', $this->testObject->givenName->value);
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['ramp\model\business\LoginAccount:aperson']));
    $this->assertFalse($this->testObject->isNew);
    try {
      $this->testObject->createFor($authenticatableUnit);
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals('Method NOT allowed on existing LoginAccount!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\LoginAccount::createFor() existing AuthenticatableUnit.
   * - assert void returned on calling
   * - assert contained dataObject populated as expected
   * - assert property accountType is set to level one
   * - assert password auto generated, accessible through getUnencryptedPassword(), in expected format.
   * - assert encryptedPassword set and matches provided crypt with SALT
   * - assert both loginAccount and associated AuthenticatableUnit are updated through relevant BusinessModelManager
   * - assert throws {@link \BadMethodCallException} when called on existing (NOT isNew) LoginAccount
   *  - with massage *'Method NOT allowed on existing LoginAccount!'*
   * @link ramp.model.business.LoginAccount#method_createFor ramp\model\business\LoginAccount::createFor()
   */
  public function testCreateForExistingAuthenticatableUnit()
  {
    $modelManager = SETTING::$RAMP_BUSINESS_MODEL_MANAGER::getInstance();
    $authenticatableUnitType = Str::set(SETTING::$RAMP_AUTHENTICATABLE_UNIT);
    $authenticatableUnit = $modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition($authenticatableUnitType),
      Filter::build($authenticatableUnitType, array('email' => 'existing.person@domain.com'))
    )[0];
    $this->assertNull($this->testObject->createFor($authenticatableUnit));
    $this->assertTrue(isset(MockBusinessModelManager::$updateLog['ramp\model\business\LoginAccount:existing']));
    $this->assertArrayNotHasKey('auPK', $this->testObject);
    $this->assertEquals('existing', $this->dataObject->auPK);
    $this->assertEquals('existing.person@domain.com', $this->dataObject->email);
    $this->assertEquals(1, $this->dataObject->loginAccountTypeID);
    $this->assertRegExp(
      "/^[a-zA-Z0-9!\"#$%&()+,-.\/:;<=>?@[\]^_{|`{|}~]{12}$/",
      $this->testObject->getUnencryptedPassword()
    );
    $this->assertSame(
      crypt((string)$this->testObject->getUnencryptedPassword(), \ramp\SETTING::$SECURITY_PASSWORD_SALT),
      $this->dataObject->encryptedPassword
    );
    $this->assertEquals('existing.person@domain.com', $this->testObject->email->value);
    $this->assertEquals('Person', $this->testObject->familyName->value);
    $this->assertEquals('Exist', $this->testObject->givenName->value);
    $this->assertFalse($this->testObject->isNew);
    try {
      $this->testObject->createFor($authenticatableUnit);
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals('Method NOT allowed on existing LoginAccount!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }
}
