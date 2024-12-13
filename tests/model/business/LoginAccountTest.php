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

require_once '/usr/share/php/tests/ramp/model/business/RecordTest.php';

require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Alphabetic.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowercaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexEmailAddress.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/SpecialistValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/ServerSideEmail.class.php';
require_once '/usr/share/php/ramp/model/business/AuthenticatableUnit.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccount.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccountType.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/AnAuthenticatableUnit.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\BadPropertyCallException;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\LoginAccount;
use ramp\model\business\LoginAccountType;
use ramp\model\business\AuthenticatableUnit;
use ramp\model\business\SimpleBusinessModelDefinition;

use tests\ramp\mocks\model\MockField;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\AnAuthenticatableUnit;
use tests\ramp\mocks\model\MockSqlBusinessModelManager;

/**
 * Collection of tests for ramp\model\business\AuthenticatableUnit.
 */
class LoginAccountTest extends \tests\ramp\model\business\RecordTest
{
  protected $auDataObject;
  protected $authenticatableUnit;

  #region Setup
  #[\Override]
  protected function preSetup() : void {
    MockSqlBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_AUTHENTICATABLE_UNIT = 'AnAuthenticatableUnit';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockSqlBusinessModelManager';
    \ramp\SETTING::$SECURITY_PASSWORD_SALT = 'It has been a hard days night And I have been working like a dog';

    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->dataObject = new \StdClass();
  }
  protected function getTestObject() : RAMPObject { return new LoginAccount($this->dataObject); }
  protected function postSetup() : void {
    $this->auDataObject = $this->auDataObject = new \StdClass();
    $this->auTestObject = new AnAuthenticatableUnit($this->auDataObject);
    $this->expectedChildCountNew = 1;
  }
  #endregion

  /**
   * Default base constructor assertions \ramp\model\business\Relatable::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iOption}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\Relatable}
   * - assert is instance of {@see \ramp\model\business\Record}
   * - assert is instance of {@see \ramp\model\business\LoginAccount}
   * @see \ramp\model\business\Relatable
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\LoginAccount', $this->testObject);
  }

  #region Sub model templates model setup
  #[\Override]
  protected function populateSubModelTree() : void
  {
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isValid);
    $this->expectedChildCountExisting = 2;
  }
  #[\Override]
  protected function complexModelIterationTypeCheck() : void
  {
    $this->testObject->setPropertyValue('auPK', 'tmp-key');
    $i = 0;
    $iterator = $this->testObject->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject[$i]->type);
      $this->assertEquals(($i == 1) ? 'select-one select-from' : 'input field', $this->testObject[$i]->type);
      $this->assertEquals(($i == 0)? 'email' : 'loginAccountType', $this->testObject[$i]->name);
      $iterator->next();
      $i++;
    }
    $this->assertSame(2, $i);
    $this->assertFalse(isset($this->testObject[2]));
    $this->dataObject->auPK = NULL;
    $this->expectedChildCountExisting = 1;
  }
  #endregion

  #region Inherited Tests
    /**
   * Bad property (name) NOT accessable on \ramp\model\Record::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Record::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Record::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Record::__get()
   */
  #[\Override]
  public function testBadPropertyCallExceptionOn__get() : void
  {
    $this->expectException(BadPropertyCallException::class);
    $this->expectExceptionMessage('Unable to locate \'badProperty\' of \'tests\ramp\mocks\model\AnAuthenticatableUnit\'');
    $o = $this->testObject->badProperty;
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Record::__toString().
   * - assert {@see \ramp\model\Record::__toString()} returns string 'class name'
   * @see \ramp\model\Record::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Minimumal Record initial state.
   * - assert property 'type' is gettable:
   *   - assert returned value is of type {@see \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert getIterator() returns object instance of {@see \Traversable}
   * - assert foreach iterates zero times as no properties are present.
   * - assert OffsetExists False returned on isset() when indexed with invalid index (0).
   * - assert return expected int value related to the number of child Records held (0).
   * - assert hasErrors returns FALSE.
   * - assert returned errors are as expected:
   *   - assert errors instance of {@see \ramp\core\StrCollection}.
   *   - assert errors count is 0.
   * @see \ramp\model\business\Record::type
   * @see \ramp\model\business\Record::getIterator()
   * @see \ramp\model\business\Record::offsetExists()
   * @see \ramp\model\business\Record::count()
   * @see \ramp\model\business\Record::hasErrors()
   * @see \ramp\model\business\Record::getErrors()
   */
  #[\Override]
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Record::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\Record::id
   */
  #[\Override]
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();

  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\Record::type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @see \ramp\model\business\Record::type
   */
  #[\Override]
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\Record::children.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @see \ramp\model\business\Record::children
   */
  #[\Override]
  public function testGetChildrenBadPropertyCallException() : void
  {
    $this->expectException(BadPropertyCallException::class);
    $this->expectExceptionMessage('Unable to locate \'children\' of \'tests\ramp\mocks\model\AnAuthenticatableUnit\'');
    $o = $this->testObject->children;
  }

  /**
   * Index beyond bounds with \ramp\model\business\Record::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\Record::offsetGet()
   */
  #[\Override]
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Index editing of children through \ramp\model\business\Record::offsetSet and
   * for \ramp\model\business\Record::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @see \ramp\model\business\Record::offsetSet()
   * @see \ramp\model\business\Record::offsetUnset()
   */
  #[\Override]
  public function testOffsetSetOffsetUnset(?BusinessModel $o = NULL) : void
  {
    parent::testOffsetSetOffsetUnset();
  }

  /**
   * (OVERRIDE) Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable Record.
   * - assert property 'type' is gettable:
   *   - assert returned value is of type {@see \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert foreach loop, iterates through each expected object:
   *   - assert returns object that is an instance of {@see \Traversable}
   *   - assert foreach returned object matches expected.
   * - assert expected object returned at its expected index.
   * - assert offsetExists returns correctly:
   *   - assert True returned on isset() when within expected bounds.
   *   - assert False returned on isset() when outside expected bounds.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @see \ramp\model\business\Record::children
   * @see \ramp\model\business\Record::type
   * @see \ramp\model\business\Record::getIterator()
   * @see \ramp\model\business\Record::offsetGet()
   * @see \ramp\model\business\Record::offsetExists()
   * @see \ramp\model\business\Record::count
   */
  #[\Override]
  public function testComplexModelIteration() : void
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking on provided **AuthenticatableUnit**.
   * - assert set 'children' modifies interable Record.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @see \ramp\model\business\Record::children
   * @see \ramp\model\business\Record::validate()
   * @see \ramp\model\business\Record::hasErrors()
   */
  #[\Override]
  public function testTouchValidityAndErrorMethods($touchCountTest = FALSE) : void
  {
    $this->auTestObject->setPropertyValue('uname', 'asmith');
    $this->assertNull($this->auTestObject->validate(
      PostData::build(array('AnAuthenticatableUnit:asmith:family-name' => 840))
    ));
    $this->assertTrue($this->auTestObject->hasErrors);
    if ($touchCountTest) {
      $i = 0;
      foreach ($this->auTestObject as $child) {
        $touch = ($i <= $this->childErrorIndexes[0]) ? 1 : 0;
        $this->assertSame(1, $child->validateCount);
        $this->assertGreaterThanOrEqual($touch, $child->hasErrorsCount);
        $i++;
      }
      $this->assertEquals($this->expectedChildCountExisting, $i);
      $this->auDataObject->uname = NULL;
    }
  }

  /**
   * Error reporting within complex models using getErrors() on provided **AuthenticatableUnit**.
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\Record::getErrors()
   */
  #[\Override]
  public function testErrorReportingPropagation($message = '$value failed to match provided regex!') : void
  {
    $this->auTestObject->setPropertyValue('uname', 'asmith');
    $this->assertNull($this->auTestObject->validate(
      PostData::build(array('AnAuthenticatableUnit:asmith:family-name' => 840))
    ));
    $this->assertTrue($this->auTestObject->hasErrors);
    $errors = $this->auTestObject->errors;
    $this->assertSame(count(array(0)), $errors->count);
    $i = 0;
    do {
    $this->assertSame($message, (string)$errors[$i++]);
    } while  ($i < $errors->count);
    $this->auDataObject->uname = NULL;
  }

  /**
   * Offset addition minimum type checking test
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\Record::offsetSet()
   */
  #[\Override]
  public function testOffsetSetTypeCheckException(?string $minAllowedType = NULL, ?RAMPObject $objectOutOfScope = NULL, ?string $errorMessage = NULL) : void
  {
    parent::testOffsetSetTypeCheckException(
      'ramp\model\business\RecordComponent',
      new MockBusinessModel(),
      'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
    );
  }

  /**
   * Ensure children index editing restricted to BusinessModels of type 'RecordComponent's
   */
  #[\Override]
  public function testOffSetSetBadMethodCallException() : void
  {
    parent::testOffSetSetBadMethodCallException();
  }

  /**
   * Record Component registration process, used for KEY, PROPERTY and RELATION management
   * - assert throws \BadMethodCallException when called without being proceded by 2 calls to register().
   *   - with message: *'Method call MUST be proceded by register() as documented!'*
   * - assert throws \InvalidArgumentException when provided RecordComponent::$parent dose NOT match Record.
   *   - with message: *'Invalid RecordComponent argument (1), $parent does NOT match this Record.'*
   * - assert FIRST call to register() returns FALSE (registers $components[type][i] = name) and expects a second call.
   * - assert SECOND call to register() returns TRUE, potentially allowing continuation to initiate().
   * - assert $registeredName corresponds to that provided at register().
   * - assert following initiate(), $registered return same object as provided on initiate().
   * - assert $registered returns same on all subsequent calls, provided preceded by register().
   * - assert if register() called with different 'name' between calls, $registered does NOT return same. 
   */
  #[\Override]
  public function testRecordComponentRegistrationProcess() : void
  {
    parent::testRecordComponentRegistrationProcess();
  }

  /**
   * Record initial 'new' state.
   * - assert data state as expected:
   *   - assert isModifed returns FALSE.
   *   - assert isValid returns FALSE.
   *   - assert isNew returns TRUE.
   * - assert property 'id' is gettable:
   *   - assert returned value instance of {@see \ramp\core\Str}.
   *   - assert returned value matches expected result, in the format:
   *     - lowercase and hypenated colon seperated [class-name]:[key].
   * - assert property 'primarykey' is gettable:
   *   - assert returned value instance of {@see \ramp\core\Str}.
   *   - assert returned value matches expected result value of 'new' when new.
   * - assert contained dataObject properties match requirements.
   *   - assert 'keyA' property NULL. 
   * - assert each key 'Field' in 'primaryKey' set to isRequered.
   * @see \ramp\model\business\Record::isNew
   * @see \ramp\model\business\Record::isValid
   * @see \ramp\model\business\Record::isModified
   * @see \ramp\model\business\Record::id
   * @see \ramp\model\business\Record::primarykey
   */
  #[\Override]
  public function testRecordNewState() : void
  {
    parent::testRecordNewState();
  }

  /**
   * Testing primary key error reporting on provided **AuthenticatableUnit**.
   * - assert following BAD 'key' validation hasErrors returns TRUE.
   * - assert following BAD 'key' validation error returns StrCollection with relevant message. 
   * @see \ramp\model\business\Record::$hasErrors
   * @see \ramp\model\business\Record::$errors
   */
  #[\Override]
  public function testPrimaryKeyErrors() : void
  {
    $this->auTestObject->validate(PostData::build(array(
      'an-authenticatable-unit:new:uname' => 'i$>N&T',
    )));
    $this->assertTrue($this->auTestObject->hasErrors);
    $this->assertInstanceOf('\ramp\core\StrCollection', $this->auTestObject->errors);
    $this->assertEquals('$value failed to match provided regex!', $this->auTestObject->errors[0]);
  }

  /**
   * Validate process for primaryKey sub KEY inputs to achive valid **AuthenticatableUnit** record state.
   * - assert initial 'new' Record state:
   *   - assert isNew, isModified, isValid flags report expected (TRUE|FALSE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * - assert post simulated FIRST valid KEY input: 
   *   = assert dataObject updated with valid value for FIRST KEY 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|TRUE).
   *   - assert id matches expected result, in the format [class-name]:[key].
   * - assert post simulated updated() called from BusinessModelManager:
   *   - assert isNew, isModified, isValid flags report expected (FALSE|FALSE|TRUE).
   */
  #[\Override]
  public function testNewRecordPrimaryKeyInput() : void
  {
    $this->assertTrue($this->auTestObject->isNew);
    $this->assertFalse($this->auTestObject->isModified);
    $this->assertFalse($this->auTestObject->isValid);
    $this->assertInstanceOf('\ramp\core\Str', $this->auTestObject->id);
    $this->assertEquals($this->processType(
      get_class($this->auTestObject), TRUE) . ':new',
      $this->auTestObject->id
    );
    $this->assertNull($this->auDataObject->uname);
    $this->assertNull($this->auTestObject->primaryKey->value);
    $keyValue = 'asmith';
    // Simulate setPropertyValue() called from relevant RecordComponent.
    $this->auTestObject->setPropertyValue('uname', $keyValue);
    $this->assertSame($keyValue, $this->auDataObject->uname);
    $this->assertSame($keyValue, $this->auTestObject->uname->value);
    $this->assertTrue($this->auTestObject->isNew);
    $this->assertTrue($this->auTestObject->isModified);
    $this->assertTrue($this->auTestObject->isValid);
    $this->assertInstanceOf('\ramp\core\Str', $this->auTestObject->id);
    $this->assertEquals(
      $this->processType(get_class($this->auTestObject), TRUE) . ':' . $keyValue,
      $this->auTestObject->id
    );
    // Simulate updated() called from BusinessModelManager
    $this->auTestObject->updated();
    $this->assertFalse($this->auTestObject->isNew);
    $this->assertFalse($this->auTestObject->isModified);
    $this->assertTrue($this->auTestObject->isValid);
  }
 
  /**
   * THIS TEST AND THE FOLLOWING 5 ARE STUBS AS WE WILL NOT BE TESTING RELATION FOR AuthenticatableUnit.
   */
  #[\Override]
  public function testRecordNewWithRelationOfOne() : void { $this->assertTrue(TRUE); }

  /**
   * THIS TEST IS STUBED AS WE WILL NOT BE TESTING RELATION FOR AuthenticatableUnit.
   */
  #[\Override]
  public function testSetExistingRelationOfOne() : void { $this->assertTrue(TRUE); }

  /**
   * THIS TEST IS STUBED AS WE WILL NOT BE TESTING RELATION FOR AuthenticatableUnit.
   */
  #[\Override]
  public function testUnsetExistingRelationOfOne() : void { $this->assertTrue(TRUE); }

  /**
   * THIS TEST IS STUBED AS WE WILL NOT BE TESTING RELATION FOR AuthenticatableUnit.
   */
  #[\Override]
  public function testAddExistingNewRelationOfMany() : void { $this->assertTrue(TRUE); }

  /**
   * THIS TEST IS STUBED AS WE WILL NOT BE TESTING RELATION FOR AuthenticatableUnit.
   */
  #[\Override]
  public function testRemoveExistingRelationOfMany() : void { $this->assertTrue(TRUE); }
  #endregion

  #region New Specialist Tests
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
    $this->assertEquals(
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
  #endregion
}
