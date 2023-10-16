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

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/core/MockOption.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\field\Option;

use tests\ramp\mocks\core\MockOption;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockField;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockBusinessModelWithErrors;

/**
 * Collection of tests for \ramp\model\business\field\Field.
 */
class FieldTest extends \tests\ramp\model\business\RecordComponentTest
{
  #region Setup
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
  }
  protected function getTestObject() : RAMPObject {
    return $this->record->aProperty;
  }
  protected function postSetup() : void {
    $this->name = $this->record->propertyName;
    $this->expectedChildCountNew = 0;
  }
  #endregion

  /**
    * Collection of assertions for \ramp\model\business\field\Field::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\field\Field}
   * @link ramp.model.business.field.Field ramp\model\business\field\Field
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject);
  }

  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->testObject[0] = new Option(0, Str::set('DESCRIPTION 1'));
    $this->testObject[1] = new Option(1, Str::set('DESCRIPTION 2'));
    $this->testObject[2] = new Option(2, Str::set('DESCRIPTION 3'));
    $this->expectedChildCountExisting = 3;
    $this->postData = PostData::build(array(
      'mock-record:new:a-property' => 'BadValue'
    ));
    $this->childErrorIndexes = array(1);
  }
  protected function complexModelIterationTypeCheck()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    $this->assertSame('option business-model', (string)$this->testObject[0]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
    $this->assertSame('option business-model', (string)$this->testObject[1]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
    $this->assertSame('option business-model', (string)$this->testObject[2]->type);
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\field\Field::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.Model#method__set ramp\model\field\Field::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\field\Field::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.Model#method__get ramp\model\field\Field::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\field\Field::__get() and \ramp\model\field\Field::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.Model#method___set \ramp\model\field\Field::__set()
   * @link ramp.model.Model#method___get \ramp\model\field\Field::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\field\Field::__toString().
   * - assert {@link \ramp\model\field\Field::__toString()} returns string 'class name'
   * @link ramp.model.Model#method___toString \ramp\model\field\Field::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }

  /**
   * Minimumal field\Field initial state.
   * - assert property 'type' is gettable:
   *   - assert returned value is of type {@link \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert getIterator() returns object instance of {@link \Traversable}
   * - assert foreach iterates zero times as no properties are present.
   * - assert OffsetExists False returned on isset() when indexed with invalid index (0).
   * - assert return expected int value related to the number of child Records held (0).
   * - assert hasErrors returns FALSE.
   * - assert returned errors are as expected:
   *   - assert errors instance of {@link \ramp\core\StrCollection}.
   *   - assert errors count is 0.
   * @link ramp.model.business.field\Field#method_get_type ramp\model\business\field\Field::type
   * @link ramp.model.business.field\Field#method_getIterator ramp\model\business\field\Field::getIterator()
   * @link ramp.model.business.field\Field#method_offsetExists ramp\model\business\field\Field::offsetExists()
   * @link ramp.model.business.field\Field#method_count ramp\model\business\field\Field::count()
   * @link ramp.model.business.field\Field#method_hasErrors ramp\model\business\field\Field::hasErrors()
   * @link ramp.model.business.field\Field#method_getErrors ramp\model\business\field\Field::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\field\Field::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.field\Field#method_set_id ramp\model\business\field\Field::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\field\Field::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.field\Field#method_set_type ramp\model\business\field\Field::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\field\Field::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.field\Field#method_get_children ramp\model\business\field\Field::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();

  }

  /**
   * Index beyond bounds with \ramp\model\business\field\Field::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.field\Field#method_offsetGet ramp\model\business\field\Field::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
  {
    parent::testOffsetGetOutOfBounds();

  }

  /**
   * Index editing of children through \ramp\model\business\field\Field::offsetSet and
   * for \ramp\model\business\field\Field::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.field\Field#method_offsetSet ramp\model\business\field\Field::offsetSet()
   * @link ramp.model.business.field\Field#method_offsetUnset ramp\model\business\field\Field::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset(new Option(0, Str::set('DESCRIPTION 1')));
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable field\Field.
   * - assert foreach loop, iterates through each expected object:
   *   - assert returns object that is an instance of {@link \Traversable}
   *   - assert foreach returned object matches expected.
   * - assert expected object returned at its expected index.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link ramp.model.business.field\Field#method_setChildren ramp\model\business\field\Field::children
   * @link ramp.model.business.field\Field#method_getIterator ramp\model\business\field\Field::getIterator()
   * @link ramp.model.business.field\Field#method_offsetGet ramp\model\business\field\Field::offsetGet()
   * @link ramp.model.business.field\Field#method_count ramp\model\business\field\Field::count
   */
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Hold reference back to associated parent Record, propertyName and value.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * @link ramp.model.business.field\Field#method_get_parentRecord ramp\model\business\field\Field::record
   * @link ramp.model.business.field\Field#method_get_parentProppertyName ramp\model\business\field\Field::parentProppertyName
   */
  public function testStateChangesRecordComponent()
  {
    parent::testStateChangesRecordComponent();
  }

  /**
   * Set 'record' NOT accessable ramp\model\business\field\Field::record.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @link ramp.model.business.field\Field#method_set_parentRecord ramp\model\business\field\Field::record
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\field\Field::propertyName.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @link ramp.model.business.field\Field#method_set_parentPropertyName ramp\model\business\field\Field::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException()
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  #endregion

  /**
   * Minimumal Field initial 'new' state.
   * - assert property 'id' is gettable:
   *   - assert returned value instance of {@link \ramp\core\Str}.
   *   - assert returned value matches expected result, in the format:
   *     - lowercase and hypenated colon seperated [class-name]:[key].
   * - assert isEditable matches expected values, some defaults are NOT overridable:
   *   - assert always returns TRUE while state is 'new' (no primaryKey value)
   *   - assert 
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::id
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::primarykey
   * @link ramp.model.business.field.Field#method_get_isEditable ramp\model\business\field\Field::isEditable
   * @link ramp.model.business.field.Field#method_set_isEditable ramp\model\business\field\Field::isEditable
   */
  public function testStateChangesField()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->record), TRUE) . ':new:' . Str::hyphenate($this->name), (string)$this->testObject->id);
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
    $this->assertFalse($this->record->isNew);
    $this->assertTrue($this->record->isValid);
    // isEditable still defaults to TRUE
    $this->assertTrue($this->testObject->isEditable);
    // but allows state change.
    $this->testObject->isEditable = FALSE;
    $this->assertFalse($this->testObject->isEditable);
    $this->assertNull($this->testObject->value);
    $this->dataObject->aProperty = 'VALUE';
    $this->assertSame('VALUE', $this->testObject->value);
    $this->testObject->isEditable = TRUE; // Reset editable
    $this->testObject->validate(PostData::build(array('mock-record:1|1|1:a-property' => 'NEW_VALUE')));
    $this->assertSame('NEW_VALUE', $this->testObject->value);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::label.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'label'
   * - assert property 'label' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.Field#method_get_label ramp\model\business\field\Field::label
   *
  public function testGet_label()
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
   * Offset addition minimum type checking test
   * - assert {@link \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @link ramp.model.business.Record#method_offsetSet ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetTypeCheckException(string $MinAllowedType = NULL, RAMPObject $objectOutOfScope = NULL, string $errorMessage = NULL)
  {
    parent::testOffsetSetTypeCheckException(
      'ramp\model\business\BusinessModel',
      new MockOption(0, Str::set('DESCRIPTION')),
      'tests\ramp\mocks\core\MockOption NOT instanceof ramp\model\business\BusinessModel'
    );
    parent::testOffsetSetTypeCheckException(
      '\ramp\core\iOption',
      new BusinessModel(),
      'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
    );
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable field\Field.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link ramp.model.business.field\Field#method_setChildren ramp\model\business\field\Field::children
   * @link ramp.model.business.field\Field#method_validate ramp\model\business\field\Field::validate()
   * @link ramp.model.business.field\Field#method_hasErrors ramp\model\business\field\Field::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertSame(1, $this->testObject->validateCount);
    $this->assertSame(1, $this->testObject->hasErrorsCount);
  }

  /**
   * Error reporting within complex models using \ramp\model\business\field\Field::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link ramp.model.business.field\Field#method_getErrors ramp\model\business\field\Field::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertSame(count($this->childErrorIndexes), $this->testObject->errors->count);
    $this->assertSame('Error MESSAGE BadValue Submited!', (string)$this->testObject->errors[0]);
  }
}
