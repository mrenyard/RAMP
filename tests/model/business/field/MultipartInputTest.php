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

require_once '/usr/share/php/tests/ramp/model/business/field/InputTest.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockMultipartInput.class.php';

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
 * Collection of tests for \ramp\model\business\field\MultipartInput.
 */
class MultipartInputTest extends \tests\ramp\model\business\field\InputTest
{
  protected string $pattern;

  #region Setup
  #[\Override]
  protected function getTestObject() : RAMPObject { return $this->record->multipartInput; }
  #[\Override]
  protected function postSetup() : void {
    $this->name = $this->record->multipartInputName;
    $this->title = $this->record->title;
    $this->expectedChildCountNew = 0;

    $this->format = $this->record->multipartFormat;
    $this->hint = 
      $this->record->multipartHint1 . $this->record->db1From . ' to ' . $this->record->db1To . ' followed by ' .
      $this->record->multipartHint2 . $this->record->db2From . ' to ' . $this->record->db2To . ' ' .
      $this->record->multipartHint3 . '(' . $this->format . ') ' .
      'total characters: ' . strlen($this->format);
    $this->inputType = 'text'; // default
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\field\MultipartInput::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\RecordComponent}
   * - assert is instance of {@see \ramp\model\field\Field}
   * - assert is instance of {@see \ramp\model\field\Input}
   * - assert is instance of {@see \ramp\model\field\MultipartInput}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \Countable}
   * @see \ramp\model\business\field\MultipartInput
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\field\MultipartInput', $this->testObject);
  }

  #region Sub model templates model setup
  #[\Override]
  protected function populateSubModelTree() : void
  {
    $this->dataObject->keyA = 'A';
    $this->dataObject->keyB = 'A';
    $this->dataObject->keyC = 'A';
    $this->testObject->parent->updated();
    $this->expectedChildCountExisting = 0;
    // $this->postData = PostData::build(array('mock-record:a|a|a:multipartInput' => 'BADVALUE'));
    $this->childErrorIndexes = array(0);
  }
  #[\Override]
  protected function complexModelIterationTypeCheck() : void
  {
    $this->assertFalse(isset($this->testObject[0]));
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable.
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
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
    $this->assertSame('input field', (string)$this->testObject->type);
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0; foreach ($this->testObject as $child) { $i++; }
    $this->assertSame($this->expectedChildCountNew, $i);
    $this->assertFalse(isset($this->testObject[$this->expectedChildCountExisting]));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertInstanceOf('\ramp\core\StrCollection', $this->testObject->errors);
    $this->assertSame(0, $this->testObject->errors->count);
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\BusinessModel::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\BusinessModel::id
   */
  #[\Override]
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\BusinessModel::$type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'.
   * @see \ramp\model\business\BusinessModel::type
   */
  #[\Override]
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable.
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
   * Offset addition \BadMethodCallException test.
   * - assert {@see https://www.php.net/manual/class.badmethodcallexception.php \BadMethodCallException} thrown.
   * @see \ramp\model\business\Record::offsetSet()
   */
  #[\Override]
  public function testOffsetSetTypeCheckException(?string $minAllowedType = NULL, ?RAMPObject $objectOutOfScope = NULL, ?string $errorMessage = NULL) : void
  {
    $this->expectException(\BadMethodCallException::class);
    $this->testObject[0] =  new AnObject();
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
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage('Array access setting is not allowed');
    parent::testOffsetSetOffsetUnset(new Option(0, Str::set('DESCRIPTION 1')));
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
  public function testTouchValidityAndErrorMethods($touchCountTest = FALSE) : void
  {
    $this->postData = PostData::build(array('mock-record:a|a|a:multipartInput' => 'BADVALUE'));
    parent::testTouchValidityAndErrorMethods($touchCountTest);
    // $this->populateSubModelTree();
    // $this->assertNull($this->testObject->validate($this->postData)); // 1 validation + 1 hasErrors
    // $this->assertTrue($this->testObject->hasErrors); // +1 hasErrors
    $this->assertSame(1, $this->testObject->validateCount); // 1
    $this->assertSame(2, $this->testObject->hasErrorsCount); // 2
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
    $this->postData = PostData::build(array('mock-record:a|a|a:multipartInput' => 'BADVALUE'));
    parent::testErrorReportingPropagation('Invalid character length!');

    $this->postData = PostData::build(array('mock-record:a|a|a:multipartInput' => '1900-12'));
    parent::testErrorReportingPropagation('monthYear Expected an integer within the range 1901 - 2155');

    $this->postData = PostData::build(array('mock-record:a|a|a:multipartInput' => '1901-13'));
    parent::testErrorReportingPropagation('monthNumber Expected an integer within the range 1 - 12');
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
  public function testRecordComponentValue(string $propertyName = 'multipartInput') : void
  {
    parent::testRecordComponentValue($propertyName);
  }

  /**
   * Set 'record' NOT accessable.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @see \ramp\model\business\field\Field::record
   */
  #[\Override]
  public function testSetParentRecordPropertyNotSetException() : void
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @see \ramp\model\business\field\Field::propertyName
   */
  #[\Override]
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
  #[\Override]
  public function testStateChangesField($fieldName = 'multipartInput', $defaultValue = NULL, $value = '1901-12', $newValue = ['1901','12']) : void
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->record), TRUE) . ':new:' . Str::hyphenate($this->name), (string)$this->testObject->id);
    $this->assertSame($this->record->title, $this->testObject->title);
    $this->assertFalse($this->testObject->isRequired);
    // isEdiatable always remains TRUE while state is 'new'
    $this->assertTrue($this->testObject->isEditable);
    // even after requested change.
    $this->isEditable = FALSE;
    $this->assertTrue($this->testObject->isEditable);
    // Now.. Update associated Record as 'validAtSource'
    $this->dataObject->keyA = 1;
    $this->dataObject->keyB = 1;
    $this->dataObject->keyC = 1;
    $this->record->updated();
    $this->assertTrue($this->record->isValid);
    $this->assertFalse($this->record->isNew);
    // isEditable still defaults to TRUE
    $this->assertTrue($this->testObject->isEditable);
    // but allows state change.
    $this->testObject->isEditable = FALSE;
    $this->assertFalse($this->testObject->isEditable);
    $this->assertSame($defaultValue, $this->testObject->value);
    $this->dataObject->monthYear = 1901;
    $this->dataObject->monthNumber = 12;
    $this->assertSame('1901-12', $this->testObject->value);
    $this->testObject->isEditable = TRUE; // Reset editable
    $this->testObject->validate(PostData::build(array('mock-record:1|1|1:' . $fieldName => $newValue)));
    $this->assertSame('1901-12', $this->testObject->value);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::isRequired.
   * - assert isRequired defaults TRUE when explicitly set at registration.
   * @see \ramp\model\business\field\Field::isRequired
   */
  #[\Override]
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
   * Collection of assertions relateing to common set of input element attribute API.
   * - assert hint equal to the component parts of each rules errorHint value concatenated with spaces between. 
   * - assert expected 'attribute value' expected defaults for data type, test scenarios, or thet provided by mock rules in that sequance.
   * @see \ramp\model\business\validation\ValidationRule::hint
   * @see \ramp\model\business\validation\ValidationRule::inputType
   * @see \ramp\model\business\validation\ValidationRule::placeholder
   * @see \ramp\model\business\validation\ValidationRule::minlength
   * @see \ramp\model\business\validation\ValidationRule::maxlength
   * @see \ramp\model\business\validation\ValidationRule::min
   * @see \ramp\model\business\validation\ValidationRule::max
   * @see \ramp\model\business\validation\ValidationRule::step
   */
  #[\Override]
  public function testExpectedAttributeValues()
  {
    $this->assertEquals($this->hint, $this->testObject->hint);
    $this->assertEquals($this->inputType, $this->testObject->inputType);
    $this->assertNull($this->testObject->placeholder);
    $this->assertNull($this->testObject->minlength);
    $this->assertNull($this->testObject->maxlength);
    $this->assertNull($this->testObject->pattern);
    $this->assertNull($this->testObject->min);
    $this->assertNull($this->testObject->max);
    $this->assertNull($this->testObject->step);
  }
  #endregion
}