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

require_once '/usr/share/php/tests/ramp/model/business/RecordComponentTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/URNQueryEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/DataWriteException.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/DataExistingEntryException.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToMany.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/Key.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockInput.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToMany.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModelManager.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\DataExistingEntryException;
use ramp\model\business\Key;

use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockField;
use tests\ramp\mocks\model\MockRecordComponent;
use tests\ramp\mocks\model\MockBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\Key.
 */
class KeyTest extends \tests\ramp\model\business\RecordComponentTest
{
  #region Setup
  protected function preSetup() : void {
    MockBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockBusinessModelManager';
    $this->name = Str::set('primaryKey');
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
  }
  protected function getTestObject() : RAMPObject {
    return $this->record->primaryKey;
  }
  protected function postSetup() : void {
    $this->expectedChildCountNew = 3;
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\Key::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\RecordComponent}
   * - assert is instance of {@see \ramp\model\buiness\Key}   
   * @see ramp.model.business.Key ramp\model\business\Key
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Key', $this->testObject);
  }

  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->expectedChildCountExisting = 3;
    $this->postData = PostData::build(array(
      'mock-record:new:keyA' => 1,
      'mock-record:new:keyB' => 'BadValue',
      'mock-record:new:keyC' => 1
    ));
    $this->childErrorIndexes = array(1);
  }
  protected function complexModelIterationTypeCheck()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    $this->assertSame('mock-field field', (string)$this->testObject[0]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
    $this->assertSame('mock-field field', (string)$this->testObject[1]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
    $this->assertSame('mock-field field', (string)$this->testObject[2]->type);
  } 
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\buiness\Key::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\buiness\Key::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\buiness\Key::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\buiness\Key::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\buiness\Key::__get() and \ramp\model\buiness\Key::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @see \ramp\model\buiness\Key::__set()
   * @see \ramp\model\buiness\Key::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\buiness\Key::__toString().
   * - assert {@see \ramp\model\buiness\Key::__toString()} returns string 'class name'
   * @see \ramp\model\buiness\Key::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Minimumal key\Key initial state.
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
   * @see \ramp\model\business\Key::type
   * @see \ramp\model\business\Key::getIterator()
   * @see \ramp\model\business\Key::offsetExists()
   * @see \ramp\model\business\Key::count()
   * @see \ramp\model\business\Key::hasErrors()
   * @see \ramp\model\business\Key::getErrors()
   */
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Key::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see ramp.model.business.key\Key#method_set_id ramp\model\business\Key::id
   */
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\Key::type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @see \ramp\model\business\Key::type
   */
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\Key::children.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @see \ramp\model\business\Key::children
   */
  public function testGetChildrenBadPropertyCallException() : void
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\Key::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\Key::offsetGet()
   */
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Index editing of children through \ramp\model\business\Key::offsetSet and
   * for \ramp\model\business\Key::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @see \ramp\model\business\Key::offsetSet()
   * @see \ramp\model\business\Key::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset(new MockField(Str::set('KeyA'), $this->record));
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable key\Key.
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
   * @see \ramp\model\business\Key::children
   * @see \ramp\model\business\Key::type
   * @see \ramp\model\business\Key::getIterator()
   * @see \ramp\model\business\Key::offsetGet()
   * @see \ramp\model\business\Key::offsetExists()
   * @see \ramp\model\business\Key::count
   */
  public function testComplexModelIteration() : void
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert validate method returns void (null) when called.
   * - assert hasErrors reports as expected.
   * @see \ramp\model\business\Key::children
   * @see \ramp\model\business\Key::validate()
   * @see \ramp\model\business\Key::hasErrors()
   */
  public function testTouchValidityAndErrorMethods() : void
  {
    parent::testTouchValidityAndErrorMethods();
  }

  /**
   * Error reporting within complex models using \ramp\model\business\Key::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\Key::getErrors()
   */
  public function testErrorReportingPropagation() : void
  {
    parent::testErrorReportingPropagation();
  }

  /**
   * Set 'record' NOT accessable ramp\model\business\Key::record.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @see \ramp\model\business\Key::record
   */
  public function testSetParentRecordPropertyNotSetException() : void
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\Key::propertyName.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @see \ramp\model\business\Key::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException() : void
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  #endregion
  
  /**
   * Hold a collection of reference back to parent (Record), name value and id.
   * - assert parent same as passed to constructor.
   * - assert name same as passed to constructor.
   * - assert id returns as expected.
   * - assert value returns as expected based on state of record
   * - assert key value unchangable (having valid value).
   * @see \ramp\model\business\Key::record
   * @see \ramp\model\business\Key::parentProppertyName
   */
  public function testStateChangesRecordComponent(string $name = NULL) : void
  {
    $this->assertSame('mock-record:new:' . Str::hyphenate($this->name), (string)$this->testObject->id);
    $this->assertEquals($this->name, $this->testObject->name);
    $this->assertSame($this->record, $this->testObject->parent);
    $this->assertNull($this->testObject->value);
    $this->assertEquals(
      (string)Str::COLON()->prepend($this->record->id)->append(Str::hyphenate($this->name)),
      (string)$this->testObject->id
    );
    $this->dataObject->keyC = 1;
    $this->assertNull($this->testObject->value);
    $this->dataObject->keyB = 1;
    $this->assertNull($this->testObject->value);
    $this->dataObject->keyA = 1;
    $this->assertEquals('1|1|1', $this->testObject->value);
    $this->testObject->validate(PostData::build(array('mock-record:1|1|1:keyB' => 2)));
    $this->assertSame(0, $this->record->keyB->validateCount); // No attempted change of record
    $this->assertNotEquals('1|2|1', $this->testObject->value);
    $this->assertEquals('1|1|1', $this->testObject->value);
  }

  /**
   * Offset addition minimum type checking test
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope
   *   and expected associated record and unique to 'Key' propertyName.
   * @see \ramp\model\business\Key::offsetSet()
   */
  public function testOffsetSetTypeCheckException(string $MinAllowedType = NULL, RAMPObject $objectOutOfScope = NULL, string $errorMessage = NULL)
  {
    parent::testOffsetSetTypeCheckException(
      'ramp\model\business\field\Field',
      new MockRecordComponent($this->name, $this->record),
      'Adding properties to Key through offsetSet STRONGLY DISCOURAGED, refer to manual!'
    );
    parent::testOffsetSetTypeCheckException(
      'ramp\model\business\field\Field',
      new MockRecordComponent(Str::set('NotApropertyName'), $this->record),
      'Adding properties to Key through offsetSet STRONGLY DISCOURAGED, refer to manual!'
    );
    parent::testOffsetSetTypeCheckException(
      'ramp\model\business\field\Field',
      new MockRecordComponent($this->name, new Record()),
      'Adding properties to Key through offsetSet STRONGLY DISCOURAGED, refer to manual!'
    );
  }

  /**
   * Test state changes for indexs, values, and value following before, during and after validation.
   * - assert compound key indexes and values based on parent record state
   * - assert validation leads to relevant state changes. 
   * - assert unchangable following successfull setting.  
   */
  public function testStateChangesKey() : void
  {
    $indexs = $this->testObject->indexes;
    $this->assertEquals('keyA', (string)$indexs[0]);
    $this->assertEquals('keyB', (string)$indexs[1]);
    $this->assertEquals('keyC', (string)$indexs[2]);
    $this->assertSame(0, $this->record->keyC->validateCount);
    $this->testObject->validate(PostData::build(array('mock-record:new:keyC' => 3)));
    $this->assertSame(1, $this->record->keyC->validateCount); // Has been touched since previous
    $this->assertTrue($this->record->isModified);
    $this->assertFalse($this->record->isValid);
    $this->assertNull($this->testObject->values);
    $this->assertNull($this->testObject->value);
    $this->assertSame(1, $this->record->keyB->validateCount);
    $this->testObject->validate(PostData::build(array('mock-record:new:keyB' => 3)));
    $this->assertSame(2, $this->record->keyB->validateCount); // Has been touched since previous
    $this->assertTrue($this->record->isModified);
    $this->assertFalse($this->record->isValid);
    $this->assertNull($this->testObject->values);
    $this->assertNull($this->testObject->value);
    $this->assertSame(2, $this->record->keyA->validateCount);
    $this->testObject->validate(PostData::build(array('mock-record:new:keyA' => 3)));
    $this->assertSame(3, $this->record->keyA->validateCount); // Has been touched since previous
    $this->assertTrue($this->record->isModified);
    $this->assertTrue($this->record->isValid);
    $this->record->updated();
    $this->assertFalse($this->record->isNew);
    $values = $this->testObject->values;
    $this->assertInstanceOf('ramp\core\StrCollection', $values);
    $this->assertEquals('3', $values[0]);
    $this->assertEquals('3', $values[1]);
    $this->assertEquals('3', $values[2]);
    $this->assertEquals('3|3|3', $this->testObject->value);
    $this->assertSame(3, $this->record->keyA->validateCount);
    $this->testObject->validate(PostData::build(array('mock-record:3|3|3:keyA' => 2)));
    $this->assertSame(3, $this->record->keyA->validateCount); // SAME: No attempted change of record
    $this->assertNotEquals('2|3|3', $this->testObject->value);
    $this->assertEquals('3|3|3', $this->testObject->value); // Unchanged
    $this->assertFalse($this->testObject->isEditable);
    $this->testObject->isEditable = TRUE; // CANNOT be changed to TRUE.
    $this->assertFalse($this->testObject->isEditable);
  }

  /**
   * Test DataExistingEntryException when new key entry matches an existing entry. 
   * - assert throws \ramp\model\business\DataExistingEntryException
   *   with message 'An entry already exists with this key!'.
   */
  public function testExistingEntryException() : void
  {
    $this->expectException(DataExistingEntryException::class);
    $this->expectExceptionMessage('An entry already exists with this key!');
    $this->testObject->validate(PostData::build(array(
      'mock-record:new:keyA' => 2,
      'mock-record:new:keyB' => 2,
      'mock-record:new:keyC' => 2
    )));
  }
}
