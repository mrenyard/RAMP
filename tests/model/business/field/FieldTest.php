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
namespace tests\ramp\model\business\field;

require_once '/usr/share/php/tests/ramp/model/business/RecordComponentTest.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\field\Option;

use tests\ramp\mocks\model\MockOption;
use tests\ramp\mocks\model\MockSelectFrom;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockField;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockBusinessModelWithErrors;
use tests\ramp\mocks\model\MockSqlBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\field\Field.
 */
class FieldTest extends \tests\ramp\model\business\RecordComponentTest
{
  #region Setup
  // #[\Override]
  // protected function preSetup() : void {
  //   // MockSqlBusinessModelManager::reset();
  //   // \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
  //   // \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockSqlBusinessModelManager';
  //   // $this->dataObject = new \StdClass();
  //   // $this->record = new MockRecord($this->dataObject);
  //   $this->record->reset();
  // }
  #[\Override]
  protected function getTestObject() : RAMPObject { return $this->record->aProperty; }
  // #[\Override]
  // protected function postSetup() : void {
  //   $this->name = $this->record->propertyName;
  //   $this->expectedChildCountNew = 0;
  // }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\field\Field::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\RecordComponent}
   * - assert is instance of {@see \ramp\model\field\Field}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \Countable}
   * @see \ramp\model\business\field\Field
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject);
  }

  #region Sub model templates model setup
  #[\Override]
  protected function populateSubModelTree() : void
  {
    // $this->testObject[0]->setParentField($this->testObject);
    // $this->testObject[1]->setParentField($this->testObject);
    // $this->testObject[2]->setParentField($this->testObject);
    // $this->testObject[0] = new Option(0, Str::set('DESCRIPTION 1'));
    // $this->testObject[1] = new Option(1, Str::set('DESCRIPTION 2'));
    // $this->testObject[2] = new Option(2, Str::set('DESCRIPTION 3'));
    $this->expectedChildCountExisting = 0;
    $this->childErrorIndexes = array(0);
    $this->postData = PostData::build(array(
      'mock-record:new:a-property' => 'BadValue'
    ));
  }
  #[\Override]
  protected function complexModelIterationTypeCheck() : void
  {
    // $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    // $this->assertEquals('mock-business-model business-model', (string)$this->testObject[0]->type);
    // $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
    // $this->assertEquals('mock-business-model business-model', (string)$this->testObject[1]->type);
    // $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
    // $this->assertEquals('mock-business-model business-model', (string)$this->testObject[2]->type);
    $this->assertFalse(isset($this->testObject[0]));
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
  #[\Override]
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
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
   * Correct return of ramp\model\Model::__toString().
   * - assert {@see \ramp\model\Model::__toString()} returns string 'class name'
   * @see \ramp\model\Model::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Minimumal BusinessModel initial state.
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
   * @see \ramp\model\business\BusinessModel::$type
   * @see \ramp\model\business\BusinessModel::getIterator()
   * @see \ramp\model\business\BusinessModel::offsetExists()
   * @see \ramp\model\business\BusinessModel::$count
   * @see \ramp\model\business\BusinessModel::$hasErrors
   * @see \ramp\model\business\BusinessModel::$Errors
   */
  #[\Override]
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessible on \ramp\model\business\BusinessModel::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\BusinessModel::id
   */
  #[\Override]
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessible on \ramp\model\business\BusinessModel::$type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'.
   * @see \ramp\model\business\BusinessModel::type
   */
  #[\Override]
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessible.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'.
   */
  #[\Override]
  public function testGetChildrenBadPropertyCallException() : void
  {
    parent::testGetChildrenBadPropertyCallException();

  }

  /**
   * Index beyond bounds with \ramp\model\business\BusinessModel::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\BusinessModel::offsetGet()
   */
  #[\Override]
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();
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
      '\ramp\core\iOption',
      new MockBusinessModel(),
      'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
    );
  }

  /**
   * Index editing of children with offsetSet and offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @see \ramp\model\business\BusinessModel::offsetSet()
   * @see \ramp\model\business\BusinessModel::offsetUnset()
   */
  #[\Override]
  public function testOffsetSetOffsetUnset(?BusinessModel $o = NULL) : void
  {
    parent::testOffsetSetOffsetUnset(new Option(1, Str::set('DESCRIPTION 1')));
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert foreach loop, iterates through each expected object:
   *   - assert returns object that is an instance of {@see \Traversable}
   *   - assert foreach returned object matches expected.
   * - assert expected object returned at its expected index.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @see \ramp\model\business\BusinessModel::getIterator()
   * @see \ramp\model\business\BusinessModel::offsetGet()
   * @see \ramp\model\business\Relatable::offsetExists()
   * @see \ramp\model\business\BusinessModel::$count
   */
  #[\Override]
  public function testComplexModelIteration() : void
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @see \ramp\model\business\BusinessModel::validate()
   * @see \ramp\model\business\BusinessModel::$hasErrors
   */
  #[\Override]
  public function testTouchValidityAndErrorMethods($touchCountTest = TRUE) : void
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    if ($touchCountTest) {
      $this->assertSame(1, $this->testObject->validateCount);
      $this->assertSame(1, $this->testObject->hasErrorsCount);
    }
  }
  
  /**
   * Error reporting within complex models.
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\BusinessModel::$errors
   */
  #[\Override]
  public function testErrorReportingPropagation($message = 'Error MESSAGE BadValue Submited!') : void
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertSame(count($this->childErrorIndexes), $this->testObject->errors->count);
    $this->assertSame($message, (string)$this->testObject->errors[0]);
  }

  /**
   * Hold reference back to associated parent Record, propertyName and value.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * @see \ramp\model\business\field\Field::record
   * @see \ramp\model\business\field\Field::parentProppertyName
   */
  #[\Override]
  public function testStateChangesRecordComponent() : void
  {
    parent::testStateChangesRecordComponent();
  }

  /**
   * RecordComponent (default) value returns same as parent Record::getPropertyValue(name).
   * - assert current record->getPropertyValue and RecordComponent->value return same instance.
   * @see \ramp\model\business\RecordComponent::$value
   * @see \ramp\model\business\Record::getPropertyValue()
   */
  #[\Override]
  public function testRecordComponentValue() : void
  {
    parent::testRecordComponentValue();
  }

  /**
   * Set 'record' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @see \ramp\model\business\field\Field::record
   */
  #[\Override]
  public function testSetParentRecordPropertyNotSetException() : void
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @see \ramp\model\business\field\Field::propertyName
   */
  #[\Override]
  public function testSetParentPropertyNamePropertyNotSetException() : void
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Minimumal Field initial 'new' state.
   * - assert property 'id' is gettable:
   *   - assert returned value instance of {@see \ramp\core\Str}.
   *   - assert returned value matches expected result, in the format:
   *     - lowercase and hypenated colon seperated [class-name]:[key].
   * - assert isEditable matches expected values, some defaults are NOT overridable:
   *   - assert always returns TRUE while state is 'new' (no primaryKey value)
   * - assert isRequired defaults FALSE (as NOT explicitly set at registration).
   * @see \ramp\model\business\Record::id
   * @see \ramp\model\business\Record::primarykey
   * @see \ramp\model\business\field\Field::$isEditable
   */
  public function testStateChangesField($fieldName = 'aProperty', $defaultValue = NULL, $value = 'VALUE', $newValue = 'NEW_VALUE') : void
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->record), TRUE) . ':new:' . Str::hyphenate($this->name), (string)$this->testObject->id);
    $this->assertSame($this->record->title, $this->testObject->title);
    $this->assertFalse($this->testObject->isRequired);
    // isEdiatable always remains TRUE while state is 'new'
    $this->assertTrue($this->testObject->isEditable);
    // even after requested preferance change.
    $this->testObject->isEditable = FALSE;
    $this->assertTrue($this->testObject->isEditable);
    // Now.. Update associated Record as 'validAtSource'
    $this->dataObject->keyA = 1;
    $this->dataObject->keyB = 1;
    $this->dataObject->keyC = 1;
    $this->record->updated();
    $this->assertTrue($this->record->isValid);
    $this->assertFalse($this->record->isNew);
    // isEditable NOW follows most recently set preference
    $this->assertFalse($this->testObject->isEditable);
    // but allows state change.
    $this->testObject->isEditable = TRUE;
    $this->assertTrue($this->testObject->isEditable);
    $this->assertSame($defaultValue, $this->testObject->value);
    $this->dataObject->$fieldName = $value;
    $expectedValue = ($this->testObject->value instanceof Option) ? $this->testObject->value->key : $this->testObject->value;
    $this->assertSame($value, $expectedValue);
    $this->testObject->validate(PostData::build(array('mock-record:1|1|1:' . $fieldName => $newValue)));
    $expectedValue = ($this->testObject->value instanceof Option) ? $this->testObject->value->key :  (
      ($newValue === 'on') ? TRUE : $this->testObject->value
    );
    $newValue = ($newValue === 'on') ? TRUE : $newValue;
    $this->assertSame($newValue, $expectedValue);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::isRequired.
   * - assert isRequired defaults TRUE when explicitly set at registration.
   * @see \ramp\model\business\field\Field::isRequired
   */
  public function testCheckIsRequiredWhenSet() : void
  {
    $testRecord = new MockRecord($this->dataObject, TRUE);
    $fieldName = (string)$this->name;
    $this->assertTrue($testRecord->$fieldName->isRequired);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::label.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'label'
   * - assert property 'label' is gettable.
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @see \ramp\model\business\field\Field::label
   *
  public function testGet_label() : void
  {
    try {
      $this->testObject->label = "LABEL";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->label is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->label);
      $this->assertSame('A Property', (string)$this->testObject->label);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }*/
  #endregion
}
