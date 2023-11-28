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

require_once '/usr/share/php/tests/ramp/model/business/RelatableTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/Key.class.php';
require_once '/usr/share/php/ramp/model/business/DataWriteException.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/DataExistingEntryException.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToMany.class.php';
require_once '/usr/share/php/ramp/model/business/RelationLookup.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/Lookup.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/RecordA.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/RecordB.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordComponent.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockInput.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToMany.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModelManager.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\Filter;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\SimpleBusinessModelDefinition;

use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockRecordComponent;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockField;
use tests\ramp\mocks\model\MockBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\Record.
 */
class RecordTest extends \tests\ramp\model\business\RelatableTest
{
  private $propertyName;
  private $modelManager;

  #region Setup
  protected function preSetup() : void {
    MockBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockBusinessModelManager';
    $this->dataObject = new \StdClass();
  }
  protected function getTestObject() : RAMPObject { return new MockRecord($this->dataObject); }
  protected function postSetup() : void {
    $this->propertyName = $this->testObject->propertyName;
    $this->expectedChildCountNew = 3;
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
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
   * @see ramp.model.business.Relatable ramp\model\business\Relatable
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject);
  }

  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->assertTrue($this->testObject->isNew);
    $this->testObject->setPropertyValue(Str::set('keyA'), 3);
    $this->testObject->setPropertyValue(Str::set('keyB'), 3);
    $this->testObject->setPropertyValue(Str::set('keyC'), 3);
    $this->assertSame('3|3|3', (string)$this->testObject->primaryKey->value);
    $this->assertTrue($this->testObject->isModified);
    $this->testObject->updated();
    $this->assertTrue($this->testObject->isValid);
    $this->assertFalse($this->testObject->isNew);
    $this->assertSame('mock-record:3|3|3', (string)$this->testObject->id);
    $this->expectedChildCountExisting = 4;
    $this->postData = PostData::build(array(
      'mock-record:3|3|3:a-property' => 'BadValue'
    ));
    $this->childErrorIndexes = array(0);
    $this->assertSame(0, $this->testObject->aProperty->validateCount);
  }
  protected function complexModelIterationTypeCheck()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    $this->assertSame('mock-field field', (string)$this->testObject[0]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
    $this->assertSame('mock-field field', (string)$this->testObject[1]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
    $this->assertSame('mock-relation-to-many relation-to-many', (string)$this->testObject[2]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[3]->type);
    $this->assertSame('mock-relation-to-one relation-to-one', (string)$this->testObject[3]->type);
    $this->assertFalse(isset($this->testObject[4]));
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Record::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Record::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Record::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Record::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\Record::__get() and \ramp\model\Record::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @see \ramp\model\Record::__set()
   * @see \ramp\model\Record::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Record::__toString().
   * - assert {@see \ramp\model\Record::__toString()} returns string 'class name'
   * @see \ramp\model\Record::__toString()
   */
  public function testToString()
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
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Record::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\Record::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();

  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\Record::type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @see \ramp\model\business\Record::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\Record::children.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @see \ramp\model\business\Record::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();

  }

  /**
   * Index beyond bounds with \ramp\model\business\Record::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\Record::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
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
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset(new MockField(Str::set('propertyName'), $this->testObject));
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
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable Record.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @see \ramp\model\business\Record::children
   * @see \ramp\model\business\Record::validate()
   * @see \ramp\model\business\Record::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    parent::testTouchValidityAndErrorMethods();
  }

  /**
   * (OVERIDE) Error reporting within complex models using \ramp\model\business\Record::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\Record::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }
  #endregion

  /**
   * Offset addition minimum type checking test
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetTypeCheckException(string $MinAllowedType = NULL, RAMPObject $objectOutOfScope = NULL, string $errorMessage = NULL)
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
  public function testOffSetSetBadMethodCallException()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!');
    $this->testObject[0] = new MockBusinessModel();
  }

  /**
   * Record Component registration process, used for KEY, PROPERTY and RELATION management.
   * - assert FIRST call to register() returns FALSE (registers $components[type][i] = name) and expects a second call.
   * - assert SECOND call to register() returns TRUE, potentially allowing continuation to initiate().
   * - assert $registeredName corresponds to that provided at register().
   * - assert following initiate(), $registered return same object as provided on initiate().
   * - assert $registered returns same on all subsequent calls, provided preceded by register().
   * - assert if register() called with different 'name' between calls, $registered does NOT return same. 
   */
  public function testRecordComponentRegistrationProcess()
  {
    $testObject = new class() extends Record
    {
      public function __construct() {} // OVERRIDE 
      public function doRegister(string $name, int $type) : bool { return $this->register($name, $type); }
      public function doInitiate(RecordComponent $o) : void { $this->initiate($o); }
      protected static function checkRequired($dataObject) : bool { return TRUE; }
    };
    $this->assertFalse($testObject->doRegister('alpha', RecordComponentType::KEY)); // First call
    $this->assertTrue($testObject->doRegister('alpha', RecordComponentType::KEY)); // Second call
    $this->assertSame('alpha', (string)$testObject->registeredName);
    $expectedField = new MockField($testObject->registeredName, $testObject);
    $testObject->doInitiate($expectedField);
    $actualField = $testObject->registered;
    $this->assertSame($expectedField, $actualField);
    $this->assertFalse($testObject->doRegister('alpha', RecordComponentType::KEY)); // Third call
    $this->assertSame($expectedField, $testObject->registered); // Same on all subsequent calls, provided preceded be register().
    $this->assertFalse($testObject->doRegister('beta', RecordComponentType::KEY)); // First call
    $this->assertNotSame($expectedField, $testObject->registered);
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
   * @see \ramp\model\business\Record::isNew
   * @see \ramp\model\business\Record::isValid
   * @see \ramp\model\business\Record::isModified
   * @see \ramp\model\business\Record::id
   * @see \ramp\model\business\Record::primarykey
   */
  public function testRecordNewState()
  {
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isValid);
    $this->assertFalse($this->testObject->isModified);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);
    $keys = [$this->testObject->primaryKey[0], $this->testObject->primaryKey[1], $this->testObject->primaryKey[2]];
    $this->assertSame($keys[0], $this->testObject->keyA);
    $this->assertSame($keys[1], $this->testObject->keyB);
    $this->assertSame($keys[2], $this->testObject->keyC);
    $i = 0;
    foreach($this->testObject as $key) { $this->assertSame($keys[$i++], $key); }
    $this->assertSame($this->expectedChildCountNew, $i);
    $this->assertObjectHasProperty('aProperty', $this->dataObject);
    $this->assertObjectHasProperty('keyA', $this->dataObject);
    $this->assertObjectHasProperty('keyB', $this->dataObject);
    $this->assertObjectHasProperty('keyC', $this->dataObject);
  }

  /**
   * Validate process for primaryKey sub KEY inputs to achive valid record state.
   * - assert initial 'new' Record state:
   *   - assert isNew, isModified, isValid flags report expected (TRUE|FALSE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * - assert post simulated FIRST valid KEY input: 
   *   = assert dataObject updated with valid value for FIRST KEY 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * - assert post simulated SECOND valid KEY input: 
   *   = assert dataObject updated with valid values for FIRST and SECOND KEY 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * - assert post simulated FINAL valid KEY input:
   *   = assert dataObject updated with valid values for ALL KEYs 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|TRUE).
   *   - assert id matches expected result, in the format [class-name]:[keyA]|[keyB]|[keyC].
   * - assert post simulated updated() called from BusinessModelManager:
   *   - assert isNew, isModified, isValid flags report expected (TRUE|FALSE|TRUE).
   */
  public function testNewRecordPrimaryKeyInput()
  {
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isValid);
    $this->assertFalse($this->testObject->isModified);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);

    $this->assertNull($this->dataObject->keyA);
    $this->assertNull($this->dataObject->keyB);
    $this->assertNull($this->dataObject->keyC);
    $this->assertNull($this->testObject->primaryKey->value);

    $keyAValue = 'A1'; $keyBValue = 'B1'; $keyCValue = 'C1';

    // Simulate getPropertyValue() called from relevant RecordComponent.
    $this->testObject->validate(PostData::build(array('mock-record:new:key-b' => $keyBValue)));
    // $this->testObject->setPropertyValue('keyB', $keyBValue);
    $this->assertSame($keyBValue, $this->dataObject->keyB);
    $this->assertSame($keyBValue, $this->testObject->keyB->value);
    $this->assertSame($this->dataObject->keyA, $this->testObject->keyA->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertTrue($this->testObject->isModified);
    $this->assertFalse($this->testObject->isValid);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);

    // Simulate getPropertyValue() called from relevant RecordComponent.
    $this->testObject->validate(PostData::build(array('mock-record:new:key-a' => $keyAValue)));
    // $this->testObject->setPropertyValue('keyA', $keyAValue);
    $this->assertSame($keyBValue, $this->dataObject->keyB);
    $this->assertSame($keyAValue, $this->dataObject->keyA);
    $this->assertSame($keyAValue, $this->testObject->keyA->value);
    $this->assertSame($this->dataObject->keyA, $this->testObject->keyA->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertTrue($this->testObject->isModified);
    $this->assertFalse($this->testObject->isValid);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);

    // Simulate getPropertyValue() called from relevant RecordComponent.
    $this->testObject->validate(PostData::build(array('mock-record:new:key-c' => $keyCValue)));
    // $this->testObject->setPropertyValue('keyC', $keyCValue);
    $this->assertSame($keyBValue, $this->dataObject->keyB);
    $this->assertSame($keyAValue, $this->dataObject->keyA);
    $this->assertSame($keyCValue, $this->dataObject->keyC);
    $this->assertSame($keyCValue, $this->testObject->keyC->value);
    $this->assertSame($this->dataObject->keyC, $this->testObject->keyC->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertTrue($this->testObject->isModified);

    $this->assertSame($this->testObject->keyA, $this->testObject->primaryKey[0]);
    $this->assertSame($this->testObject->keyB, $this->testObject->primaryKey[1]);
    $this->assertSame($this->testObject->keyC, $this->testObject->primaryKey[2]);

    $this->assertSame($keyAValue, $this->testObject->primaryKey[0]->value);
    $this->assertSame($keyBValue, $this->testObject->primaryKey[1]->value);
    $this->assertSame($keyCValue, $this->testObject->primaryKey[2]->value);
    $this->assertSame('A1|B1|C1', (string)$this->testObject->primaryKey->value);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':a1|b1|c1', (string)$this->testObject->id);

    // Simulate updated() called from BusinessModelManager
    $this->testObject->updated();
    $this->assertFalse($this->testObject->isNew);
    $this->assertTrue($this->testObject->isValid);
    $this->assertFalse($this->testObject->isModified);
    $this->assertTrue($this->testObject->isValid);
  }
 
  /**
   * Set 'new' relation on Record (ONE) accessable with appropiate state changes.
   * - assert dataObject of parent Record does NOT contain relation name.
   * - assert dataObject of parent Record contains expected 'foreign keys'.
   * - assert pre ANY validation:
   *   - assert id of 'new' Record pre validation() as expected (mock-record:new).
   *   - assert value matches id of expected related 'new' Record pre validation() (mock-min-record:new)
   * - assert attempted simultaneous validation of BOTH parent Record and Related Record:
   *   - assert id of parent Record post validation() modified primaryKey as expected.
   *   - assert 'value' matches 'id' of expected related Record NOT yet isModified() as parent was 'new'.
   *   - assert post validate() parent record 'new' related record NOT isModified() as parent was 'new'.
   *   - assert interator on relation 'keys' are in expected unmodified state.
   *     - assert children primaryKey name match expected related record's.
   *     - assert children match expected related record's keys.
   *     - assert validate NOT called on related 'new' Record's key.
   *     - assert hasErrors called on each related Record's key.
   *     - assert validated related Record field's (key) values are NULL.
   *  - assert related Record validation following successful parent Record primaryKey set:
   *   - assert post validate() related record isModified() and hasErrors() as expected.
   *   - assert 'value' matches 'id' of expected related Record with newly set primaryKey (mock-min-record:value1|value2|value3).
   *   - assert interator on relation 'keys' are in expected modified state.
   *     - assert children primaryKey name match expected related record's.
   *     - assert children match expected related record's keys.
   *     - assert validate called on each related Record's key.
   *     - assert hasErrors called on each related Record's key.
   *     - assert validated related Record field (key) values are modified as directed.
   *   - assert interator on relation NOW returns proerpties NOT keys.
   *     - assert children property name match expected related record's.
   *     - assert children match expected related record's properties.
   *     - assert validate called on each related Record's property.
   *     - assert hasErrors called on each related Record's property.
   *     - assert validated related Record field (property) values are NULL.
   */
  public function testRecordNewWithRelationOfOne()
  {
    // Expected related existing record from data store to test against
    $toRecord = $this->modelManager->mockMinNew;
    $toRecord->reset();
    $this->assertTrue($toRecord->isNew);
    $this->assertFalse($toRecord->isModified);
    $this->assertFalse($toRecord->isValid);
    // Ensure dataObject of parent Record does NOT contain relation name.
    $this->assertObjectNotHasProperty('relationBeta', $this->dataObject); // to ONE
    // Check dataObject of parent Record contains expected 'foreign keys'.
    $this->assertObjectHasProperty('fk_relationBeta_MockMinRecord_key1', $this->dataObject);
    $this->assertObjectHasProperty('fk_relationBeta_MockMinRecord_key2', $this->dataObject);
    $this->assertObjectHasProperty('fk_relationBeta_MockMinRecord_key3', $this->dataObject);
    $this->assertNull($this->dataObject->fk_relationBeta_MockMinRecord_key1);
    $this->assertNull($this->dataObject->fk_relationBeta_MockMinRecord_key2);
    $this->assertNull($this->dataObject->fk_relationBeta_MockMinRecord_key3);
    // Get relation to test.
    $relation = $this->testObject->relationBeta; // to ONE
    $relation->isEditable = TRUE;
    // Pre ANY validation
    // Check id of 'new' Record pre validation() as expected.
    $this->assertSame('mock-record:new', (string)$this->testObject->id);
    // Check value matches id of expected related 'new' Record pre validation().
    $this->assertSame('mock-min-record:new', (string)$relation->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isModified);
    // Attempt simultaneous validation of BOTH parent Record and Related Record:
    $this->testObject->validate(PostData::build(array(
      'mock-record:new:key-a' => 3,
      'mock-record:new:key-b' => 3,
      'mock-record:new:key-c' => 3,
      'mock-record:new:relation-beta' => array('key1' => 'VALUE1', 'key2' => 'VALUE2', 'key3' => 'VALUE3')
    )));
    $this->assertTrue($this->testObject->isModified);
    $this->assertTrue($this->testObject->isValid);
    $this->testObject->updated();
    $this->assertFalse($this->testObject->isNew);
    // Check 'id' of parent Record post validation() modified primaryKey as expected.
    $this->assertSame('mock-record:3|3|3', (string)$this->testObject->id);
    // Check 'value' matches 'id' of expected related Record NOT yet isModified() as parent was 'new'.
    $this->assertSame('mock-min-record:new', (string)$relation->value);
    $this->assertSame((string)$toRecord->id, $relation->value);
    // Check post validate() parent record 'new' related record NOT isModified() as parent was 'new'.
    $this->assertTrue($toRecord->isNew);
    $this->assertFalse($toRecord->isModified);
    // Check post validate() parent record 'new' hasErrors as expected.
    $this->assertFalse($toRecord->hasErrors);
    // Check interator on relation 'keys' are in expected unmodified state. 
    $i = 0;
    $keyIterator = $relation->getIterator();
    $keyIterator->rewind();
    foreach ($toRecord->primaryKey as $toRecordKey) {
      $this->assertTrue($keyIterator->valid());
      $expectedRecordKey = $keyIterator->current();
      // Check children primaryKey name match expected related record's.
      $this->assertEquals('key' . ++$i, $expectedRecordKey->name);
      // Check children match expected related record's keys.
      $this->assertSame($expectedRecordKey, $toRecordKey);
      // Check validate NOT called on related 'new' Record's key.
      $this->assertSame(0, $toRecordKey->validateCount);
      // Check hasErrors called on each related Record's key.
      $this->assertSame(1, $toRecordKey->hasErrorsCount);
      // Check validated related Record field's values are NULL.
      $this->assertNull($expectedRecordKey->value);
      $keyIterator->next();
    }
    $this->assertSame($i, 3);
    // Related Record validation following successful parent Record primaryKey set:
    $this->testObject->validate(PostData::build(array( // placed out of order
      'mock-record:3|3|3:relation-beta' => array('key2' => 'VALUE2', 'key3' => 'VALUE3', 'key1' => 'VALUE1')
    )));
    // Check post validate() related record isModified as expected.
    // $this->assertTrue($toRecord->isModified);
    // Check post validate() related record hasErrors as expected.
    $this->assertFalse($toRecord->hasErrors);
    // Check 'value' matches 'id' of expected related Record with newly set primaryKey (mock-min-record:value1|value2|value3).
    $this->assertSame('mock-min-record:value1|value2|value3', $relation->value);
    $this->assertSame((string)$toRecord->id, $relation->value);
    $this->assertTrue($toRecord->isValid);
    $toRecord->updated();
    // Check interator on relation 'keys' are in expected modified state
    $i = 0;
    $keyIterator->rewind();
    foreach ($toRecord->primaryKey as $toRecordKey) {
      $expectedRecordKey = $keyIterator->current();
      // Check children primaryKey name match expected related record's.
      $this->assertEquals('key' . ++$i, (string)$expectedRecordKey->name);
      // Check children match expected related record's keys.
      $this->assertSame($expectedRecordKey, $toRecordKey);
      // Check validate called on each related Record's key.
      $this->assertSame(2, $toRecordKey->validateCount);
      // Check hasErrors called on each related Record's key.
      $this->assertSame(4, $toRecordKey->hasErrorsCount);
      // Check validated related Record field (key) values are modified as directed.
      $this->assertSame('VALUE' . $i, $toRecordKey->value);
      $keyIterator->next();
    }
    $this->assertSame($i, 3);
    // Check interator on relation NOW returns properties NOT keys.
    $i = 0;
    $propertyIterator = $relation->getIterator();
    $propertyIterator->rewind();
    foreach ($toRecord as $toRecordProperty) {
      $expectedRecordProperty = $propertyIterator->current();
      // Check children property name match expected related record's.
      $this->assertEquals('property' . ++$i, (string)$expectedRecordProperty->name);
      // Check children match expected related record's properties.
      $this->assertSame($expectedRecordProperty, $toRecordProperty);
      // Check validate called on each related Record's property.
      // TODO:mrenyard: This should be touched check post isEditable implimentaion.
      // $this->assertSame(1, $toRecordProperty->validateCount);
      // Check hasErrors called on each related Record's property.
      $this->assertSame(2, $toRecordProperty->hasErrorsCount);
      // Check validated related Record field (property) values are modified as directed.
      $this->assertNull($toRecordProperty->value);
      $propertyIterator->next();
    }
    $this->assertSame($i, 2); 
  }

  /**
   * Set 'existing' relation on Record (ONE) accessable with appropiate state changes.
   * - assert post validate() related record is as expected.
   * - assert 'value' matches 'id' of expected related Record mock-min-record:a|b|c).
   * - assert interator on relation returns expected properties.
   *   - assert children property name match expected related record's.
   *   - assert children match expected related record's properties.
   */
  public function testSetExistingRelationOfOne()
  {
    $fromRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockRecord'), Str::set('1|1|1'))
    );
    $this->assertSame($this->modelManager->objectNew, $fromRecord);
    $this->assertFalse($fromRecord->isNew);
    $fromData = $this->modelManager->dataObjectNew;
    $toRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockMinRecord'), Str::set('A|B|C'))
    );
    $this->assertSame($this->modelManager->objectTwo, $toRecord);
    $toData = $this->modelManager->dataObjectTwo;
    $fromRecord->reset();
    $toRecord->reset();
    // Get relation to test.
    $relation = $fromRecord->relationBeta; // to ONE
    $relation->isEditable = TRUE;
    $this->assertInstanceOf('\ramp\model\business\Relation', $relation);
    $fromRecord->validate(PostData::build(array(
      'mock-record:1|1|1:relation-beta' => array('key2' => 'B', 'key3' => 'C', 'key1' => 'A')
    )));
    $this->assertSame($toRecord, $relation->with);
    // Check interator on relation returns properties.
    $i = 0;
    $propertyIterator = $relation->getIterator();
    $propertyIterator->rewind();
    foreach ($toRecord as $toRecordProperty) {
      $expectedRecordProperty = $propertyIterator->current();
      // Check children property name match expected related record's.
      $this->assertEquals('property' . ++$i, (string)$expectedRecordProperty->name);
      // Check children match expected related record's properties.
      $this->assertSame($expectedRecordProperty, $toRecordProperty);
      $propertyIterator->next();
    }
    $this->assertSame($i, 2);
  }

  /**
   * Unset 'existing' relation on Record (ONE) accessable with appropiate state changes.
   * - assert dataObject of parent Record does NOT contain relation name.
   * - assert dataObject of parent Record contains expected 'foreign keys'.
   * - assert while relation NOT isEditable no change occurs.
   * - assert existing relation unset upon valid request.
   * - assert following successful 'unset' foreignKey values reset NULL.
   * - assert relation reset 'new' ready for any future changes.
   */ 
  public function testUnsetExistingRelationOfOne()
  {
    $fromRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockRecord'), Str::set('2|2|2'))
    );
    $this->assertSame($this->modelManager->objectOne, $fromRecord);
    $this->assertFalse($fromRecord->isNew);
    $fromData = $this->modelManager->dataObjectOne;
    // Ensure dataObject of parent Record does NOT contain relation name.
    $this->assertObjectNotHasProperty('relationBeta', $fromData); // to ONE
    // Check dataObject of parent Record contains expected 'foreign keys'.
    $this->assertObjectHasProperty('fk_relationBeta_MockMinRecord_key1', $fromData);
    $this->assertObjectHasProperty('fk_relationBeta_MockMinRecord_key2', $fromData);
    $this->assertObjectHasProperty('fk_relationBeta_MockMinRecord_key3', $fromData);
    $this->assertSame('A', $fromData->fk_relationBeta_MockMinRecord_key1);
    $this->assertSame('B', $fromData->fk_relationBeta_MockMinRecord_key2);
    $this->assertSame('C', $fromData->fk_relationBeta_MockMinRecord_key3);
    // Hold referance to expected current relation
    $currentToRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockMinRecord'), Str::set('A|B|C'))
    );
    $this->assertSame($this->modelManager->objectTwo, $currentToRecord);
    $currentToData = $this->modelManager->dataObjectTwo;
    $fromRecord->reset();
    $currentToRecord->reset();
    // Get relation to test.
    $relation = $fromRecord->relationBeta; // to ONE
    $this->assertSame($currentToRecord, $relation->with);
    // While relation NOT isEditable no change occurs.
    $this->assertFalse($relation->isEditable);
    $fromRecord->validate(PostData::build(array(
      'mock-record:2|2|2:relation-beta' => array('unset' => 'A|B|C')
    )));
    $this->assertSame($currentToRecord, $relation->with);
    $relation->isEditable = TRUE;
    $this->assertTrue($relation->isEditable);
    $fromRecord->validate(PostData::build(array(
      'mock-record:2|2|2:relation-beta' => array('unset' => 'A|B|C')
    )));
    // Check foreignKey values reset NULL
    $this->assertNull($fromData->fk_relationBeta_MockMinRecord_key1);
    $this->assertNull($fromData->fk_relationBeta_MockMinRecord_key2);
    $this->assertNull($fromData->fk_relationBeta_MockMinRecord_key3);
    // Check existing reltion unset
    $this->assertNotSame($currentToRecord, $relation->with);
    // Check relation reset 'new' ready for any future changes.
    $this->assertTrue($relation->with->isNew);
  }

  /**
   * Add 'existing' and 'new' relation on Record collection (MANY) accessable with appropiate state changes.
   * - assert Record with relation (MANY) holds expected collection of associated Records.
   * - assert default relation NOT isEditable (no extra 'new' Record appended to collection as default).
   * - assert following change to isEditable=TRUE property has appended 'new' record ready to recieve primaryKey values.
   * - assert when provided primaryKay values relate to existing Record, replaces 'new' with relevant Record in relation collection.
   *   - assert that access to added existing Record is access checked before actioning.
   *   - assert reset 'new' Record to end off relation collection for next add/edit.
   * - assert when provided primaryKey values are 'new' (unique) Record added to relation collection and data store.
   */
  public function testAddExistingNewRelationOfMany()
  {
    $fromRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockRecord'), Str::set('1|1|1'))
    );
    $this->assertSame($this->modelManager->objectNew, $fromRecord);
    $this->assertFalse($fromRecord->isNew);
    $fromData = $this->modelManager->dataObjectNew;
    // Ensure dataObject of parent Record does NOT contain relation name.
    $this->assertObjectNotHasProperty('relationAlpha', $fromData); // to ONE
    $expectedCollection = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockMinRecord')),
      Filter::build(Str::set('MockMinRecord'), array(
        'fk_relationDelta_MockRecord_keyA' => '1',
        'fk_relationDelta_MockRecord_keyB' => '1',
        'fk_relationDelta_MockRecord_keyC' => '1'
      ))
    );
    // Get relation to test.
    $relation = $fromRecord->relationAlpha; // to MANY
    // Check relation collection not editable as no prepended 'new'.
    $this->assertFalse($relation->isEditable); // NOT Editable
    $this->assertSame(3, $relation->count); // NO prepended 'new' Record for addition
    $i = 0;
    $iterator = $expectedCollection->getIterator();
    $iterator->rewind();
    foreach ($relation as $relatedRecord) {
      $expectedRecord = $iterator->current();
      $this->assertSame($expectedRecord, $relatedRecord);
      $i++; $j = 0;
      foreach ($relatedRecord as $subkeyOrProperty) {
        $j++;
        $this->assertSame('property' . $j, (string)$subkeyOrProperty->name);
      }
      $iterator->next();
    }
    $this->assertSame(3, $i);
    $this->assertSame(0, $relation->validateCount);
    $fromRecord->validate(PostData::build(array(
      'mock-min-record:new:key1' => 'A',
      'mock-min-record:new:key2' => 'B',
      'mock-min-record:new:key3' => 'C'
    )));
    // Check validate tounced but collection unaffected
    $this->assertSame(1, $relation->validateCount);
    $this->assertSame(3, $relation->count); // STILL ONLY 3 in Collection.
    //  isEditable  
    $relation->isEditable = TRUE;
    // Check existance of append 'new' editable relevant record on collection.
    $this->assertSame(4, $relation->count); // NOW (3 + 'new').
    $i = 0;
    $iterator = $expectedCollection->getIterator();
    $iterator->rewind();
    foreach ($relation as $relatedRecord) {
      $i++; $j = 0;
      $expectedRecord = $iterator->current();
      if ($i < 4) { $this->assertSame($expectedRecord, $relatedRecord); }
      foreach ($relatedRecord as $subkeyOrProperty) {
        $j++;
        if ($i === 4) {
          $this->assertTrue($relatedRecord->isNew);
          $this->assertNull($relatedRecord->primaryKey->value);
          $this->assertSame('key' . $j, (string)$subkeyOrProperty->name);
          continue;
        }
        $this->assertFalse($relatedRecord->isNew);
        $this->assertNotNull($relatedRecord->primaryKey->value);
        $this->assertSame('property' . $j, (string)$subkeyOrProperty->name);
      }
      $iterator->next();
    }
    $this->assertSame(4, $i);
    // Edit existing record as 4th member of this relations collection.
    $fromRecord->validate(PostData::build(array(
      'mock-min-record:new:key1' => 'A',
      'mock-min-record:new:key2' => 'B',
      'mock-min-record:new:key3' => 'C'
    )));
    $this->assertSame(5, $relation->count); // NOW (3 + added-existing + 'new').
    $i = 0;
    $iterator = $expectedCollection->getIterator();
    $iterator->rewind();
    foreach ($relation as $relatedRecord) {
      $i++; $j = 0;
      $expectedRecord = $iterator->current();
      if ($i < 4) { $this->assertSame($expectedRecord, $relatedRecord); }
      if ($i === 4) { $this->assertSame($this->modelManager->objectTwo, $relatedRecord); }
      foreach ($relatedRecord as $subkeyOrProperty) {
        $j++;
        if ($i === 5) {
          $this->assertTrue($relatedRecord->isNew);
          $this->assertNull($relatedRecord->primaryKey->value);
          $this->assertSame('key' . $j, (string)$subkeyOrProperty->name);
          continue;
        }
        $this->assertFalse($relatedRecord->isNew);
        $this->assertNotNull($relatedRecord->primaryKey->value);
        $this->assertSame('property' . $j, (string)$subkeyOrProperty->name);
      }
      $iterator->next();
    }
    $this->assertSame(5, $i);
    // Edit existing record as 4th member of this relations collection.
    $fromRecord->validate(PostData::build(array(
      'mock-min-record:new:key1' => 'A',
      'mock-min-record:new:key2' => 'B',
      'mock-min-record:new:key3' => 'G'
    )));
    // Get relation to test.
    $relation = $fromRecord->relationAlpha; // to MANY
    $this->assertSame(6, $relation->count); // NOW (3 + added-existing + added-new + 'new').
    $i = 0;
    $iterator = $expectedCollection->getIterator();
    $iterator->rewind();
    foreach ($relation as $relatedRecord) {
      $i++; $j = 0;
      $expectedRecord = $iterator->current();
      if ($i < 4) { $this->assertSame($expectedRecord, $relatedRecord); }
      if ($i === 4) { $this->assertSame($this->modelManager->objectTwo, $relatedRecord); }
      if ($i === 5) { $this->assertSame('A|B|G', $relatedRecord->primaryKey->value); }
      foreach ($relatedRecord as $subkeyOrProperty) {
        $j++;
        if ($i === 6) {
          $this->assertTrue($relatedRecord->isNew);
          $this->assertNull($relatedRecord->primaryKey->value);
          $this->assertSame('key' . $j, (string)$subkeyOrProperty->name);
          continue;
        }
        $this->assertFalse($relatedRecord->isNew);
        $this->assertNotNull($relatedRecord->primaryKey->value);
        $this->assertSame('property' . $j, (string)$subkeyOrProperty->name);
      }
      $iterator->next();
    }
    $this->assertSame(6, $i);
  }

  /**
   * Remove 'existing' relation on Record collection (MANY) accessable with appropiate state changes.
   * - assert Record with relation (MANY) holds expected collection of associated Records.
   * - assert following change to isEditable=TRUE property has appended 'new' record ready to recieve primaryKey values.
   * - assert error recorded on attempting an Illegal UNSET Action.
   * - assert on valid UNSET of a single Record in the elation collection:
   *   - no errors are reported.
   *   - the resulting relation collection modified as expected.
   */
  public function testRemoveExistingRelationOfMany()
  {
     $fromRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockRecord'), Str::set('1|1|1'))
    );
    $this->assertSame($this->modelManager->objectNew, $fromRecord);
    $this->assertFalse($fromRecord->isNew);
    $fromData = $this->modelManager->dataObjectNew;
    // Ensure dataObject of parent Record does NOT contain relation name.
    $this->assertObjectNotHasProperty('relationAlpha', $fromData); // to ONE
    $expectedCollection = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockMinRecord')),
      Filter::build(Str::set('MockMinRecord'), array(
        'fk_relationDelta_MockRecord_keyA' => '1',
        'fk_relationDelta_MockRecord_keyB' => '1',
        'fk_relationDelta_MockRecord_keyC' => '1'
      ))
    );
    // Get relation to test.
    $relation = $fromRecord->relationAlpha; // to MANY
    // Check relation collection not editable as no prepended 'new'.
    $this->assertFalse($relation->isEditable); // NOT Editable
    $this->assertSame(3, $relation->count); // NO prepended 'new' Record for addition
    $fromRecord->validate(PostData::build(array(
      'mock-record:1|1|1:relation-alpha' => array('unset' => 'A|B|E')
    )));
    $this->assertSame(1, $relation->validateCount);
    $this->assertSame(3, $relation->count); // 'new' Record for addition
    $relation->isEditable = TRUE;
    $this->assertTrue($relation->isEditable); // Changed
    $this->assertSame(4, $relation->count); // NO prepended 'new' Record for addition
    $this->assertTrue($relation[3]->isNew);
    // Attemp an Illegal UNSET Action. 
    $fromRecord->validate(PostData::build(array(
      'mock-record:1|1|1:relation-alpha' => array('unset' => 'A|B|C')
    )));
    // Confirm errors.
    $this->assertTrue($relation->hasErrors);
    $this->assertSame(
      'Illegal UNSET Action: mock-record:1|1|1:relation-alpha[A|B|C]',
      (string)$fromRecord->errors[0]
    );
    $this->assertSame(2, $relation->validateCount);
    // Attemp valid unset on A|B|E.
    $fromRecord->validate(PostData::build(array(
      'mock-record:1|1|1:relation-alpha' => array('unset' => 'A|B|E')
    )));
    // Confirm no errors and correct removal on relation collection.
    $this->assertFalse($relation->hasErrors);
    $this->assertSame(3, $relation->validateCount);
    $this->assertSame(3, $relation->count); // Existing - 1 (A|B|E)
    $this->assertSame($relation[0], $expectedCollection[0]);
    $this->assertSame($relation[1], $expectedCollection[2]);
    $this->assertTrue($relation[2]->isNew);
    $this->assertFalse(isset($relation[3]));
  }
}