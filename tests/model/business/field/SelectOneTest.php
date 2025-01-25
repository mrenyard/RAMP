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

require_once '/usr/share/php/tests/ramp/model/business/field/SelectFromTest.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\field\Option;

use tests\ramp\mocks\core\AnObject;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockSqlBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\field\SelectOne.
 */
class SelectOneTest extends \tests\ramp\model\business\field\SelectFromTest
{
  #region Setup
  protected function getTestObject() : RAMPObject { return $this->record->selectOne; }
  protected function postSetup() : void {
    $this->name = $this->record->selectOneName;
    // $this->title = $this->record->title;
    $this->expectedChildCountNew = 3;
  }
  #endregion

  /**
    * Collection of assertions for \ramp\model\business\field\Field::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\field\Field}
   * - assert is instance of {@see \ramp\model\field\SelectFrom}
   * - assert is instance of {@see \ramp\model\field\SelectOne}
   * @see \ramp\model\business\field\Flag
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\field\SelectOne', $this->testObject);
  }

  #region Sub model templates model setup
  protected function populateSubModelTree() : void
  {
    $this->expectedChildCountExisting = 3;
    $this->postData = PostData::build(array(
      'mock-record:new:select-one' => 3 // NOT an Option
    ));
    $this->childErrorIndexes = array(0);
  }
  protected function complexModelIterationTypeCheck() : void
  {
    $this->assertSame($this->record->selectOneList[0], $this->testObject[0]);
    $this->assertSame($this->record->selectOneList[1], $this->testObject[1]);
    $this->assertSame($this->record->selectOneList[2], $this->testObject[2]);
    $this->assertFalse(isset($this->testObject[3]));
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
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
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert {@see \ramp\model\Model::__toString()} returns string 'class name'
   * @see \ramp\model\Model::__toString()
   */
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
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessible on \ramp\model\business\BusinessModel::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\BusinessModel::id
   */
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessible on \ramp\model\business\BusinessModel::$type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'.
   * @see \ramp\model\business\BusinessModel::type
   */
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessible.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'.
   */
  public function testGetChildrenBadPropertyCallException() : void
  {
    parent::testGetChildrenBadPropertyCallException();

  }

  /**
   * Index beyond bounds with \ramp\model\business\BusinessModel::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\BusinessModel::offsetGet()
   */
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();
  }
  
  /**
   * Offset addition \BadMethodCallException test.
   * - assert {@see https://www.php.net/manual/class.badmethodcallexception.php \BadMethodCallException} thrown.
   * @see \ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetTypeCheckException(?string $minAllowedType = NULL, ?RAMPObject $objectOutOfScope = NULL, ?string $errorMessage = NULL) : void
  {
    parent::testOffsetSetTypeCheckException($minAllowedType, $objectOutOfScope, $errorMessage);
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
  public function testTouchValidityAndErrorMethods($touchCountTest = FALSE) : void
  {
    parent::testTouchValidityAndErrorMethods($touchCountTest);
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
  public function testErrorReportingPropagation($message = 'Selected value NOT an avalible option!') : void
  {
    parent::testErrorReportingPropagation($message);
  }

  /**
   * Hold reference back to associated parent Record, propertyName and value.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * @see \ramp\model\business\field\Field::record
   * @see \ramp\model\business\field\Field::parentProppertyName
   */
  public function testStateChangesRecordComponent() : void
  {
    parent::testStateChangesRecordComponent();
  }

  /**
   * RecordComponent (default) value returns expected option at parent Record::getPropertyValue(name) index.
   * - assert RecordComponent->value returns option at current record->getPropertyValue index.
   * @see \ramp\model\business\RecordComponent::$value
   * @see \ramp\model\business\Record::getPropertyValue()
   */
  public function testRecordComponentValue() : void
  {
    $this->assertSame($this->record->selectOneList[0], $this->testObject->value);
  }

  /**
   * Set 'record' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @see \ramp\model\business\field\Field::record
   */
  public function testSetParentRecordPropertyNotSetException() : void
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @see \ramp\model\business\field\Field::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException() : void
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  
  /**
   * Minimumal Field initial 'new' state.
   * - assert property 'id' is gettable:
   *   - assert returned value instance of {@see \ramp\core\Str}.
   *   - assert returned value matches expected result, in the format:
   *     - lowercase and hypenated colon seperated [class-name]:[key].
   * - assert isEditable matches expected values, some defaults are NOT overridable:
   *   - assert always returns TRUE while state is 'new' (no primaryKey value)
   * @see \ramp\model\business\Record::id
   * @see \ramp\model\business\Record::primarykey
   * @see \ramp\model\business\field\Field::$isEditable
   */
  public function testStateChangesField($fieldName = 'selectOne', $defaultValue = 0, $value = 1, $newValue = 2) : void
  {
    parent::testStateChangesField($fieldName, $defaultValue, $value, $newValue);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::isRequired.
   * - assert isRequired defaults TRUE when explicitly set at registration.
   * @see \ramp\model\business\field\Field::isRequired
   */
  public function testCheckIsRequiredWhenSet() : void
  {
    parent::testCheckIsRequiredWhenSet();
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

  /**
   * InvalidArgumentException reported when 3rd argument on constructor NOT compositeType \ramp\model\business\field\Option.
   * - assert throws \InvalidArgumentException when supplied 3rd arguments NOT compositeType \ramp\model\business\field\Option
   *   - with message: *'OptionList $options compositeType MUST be \ramp\model\business\field\Option'*
   * @see \ramp\model\business\field\SelectFrom
   */
  public function testConstructorInvalidArgumentException()
  {
    parent::testConstructorInvalidArgumentException();
  }
#endregion
}
