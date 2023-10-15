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

require_once '/usr/share/php/tests/ramp/model/business/BusinessModelTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Key.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordComponent.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockKey.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelation.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;

use tests\ramp\mocks\core\AnObject;
use ramp\model\business\BusinessModel;
use ramp\model\business\RecordComponent;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockRecordComponent;

/**
 * Collection of tests for \ramp\model\business\Relatable.
 */
class RecordComponentTest extends \tests\ramp\model\business\BusinessModelTest
{
  protected $dataObject;
  protected $record;
  protected $name;

  #region Setup
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
    $this->name = Str::set('aProperty');
  }
  protected function getTestObject() : RAMPObject {
    return new MockRecordComponent($this->name, $this->record);
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\RecordComponent::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\core\iList}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\model\business\RecordComponent}
   * @link ramp.model.business.RecordComponent ramp\model\business\RecordComponent
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\RecordComponent::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.Model#method__set ramp\model\RecordComponent::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\RecordComponent::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.Model#method__get ramp\model\RecordComponent::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\RecordComponent::__get() and \ramp\model\RecordComponent::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.Model#method___set \ramp\model\RecordComponent::__set()
   * @link ramp.model.Model#method___get \ramp\model\RecordComponent::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\RecordComponent::__toString().
   * - assert {@link \ramp\model\RecordComponent::__toString()} returns string 'class name'
   * @link ramp.model.Model#method___toString \ramp\model\RecordComponent::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }

  /**
   * Minimumal RecordComponent initial state.
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
   * @link ramp.model.business.RecordComponent#method_get_type ramp\model\business\RecordComponent::type
   * @link ramp.model.business.RecordComponent#method_getIterator ramp\model\business\RecordComponent::getIterator()
   * @link ramp.model.business.RecordComponent#method_offsetExists ramp\model\business\RecordComponent::offsetExists()
   * @link ramp.model.business.RecordComponent#method_count ramp\model\business\RecordComponent::count()
   * @link ramp.model.business.RecordComponent#method_hasErrors ramp\model\business\RecordComponent::hasErrors()
   * @link ramp.model.business.RecordComponent#method_getErrors ramp\model\business\RecordComponent::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\RecordComponent::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.RecordComponent#method_set_id ramp\model\business\RecordComponent::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\RecordComponent::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.RecordComponent#method_set_type ramp\model\business\RecordComponent::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\RecordComponent::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.RecordComponent#method_get_children ramp\model\business\RecordComponent::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\RecordComponent::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.RecordComponent#method_offsetGet ramp\model\business\RecordComponent::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Offset addition minimum type checking test
   * - assert {@link \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @link ramp.model.business.Record#method_offsetSet ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetTypeCheckException(string $MinAllowedType = NULL, RAMPObject $objectOutOfScope = NULL, string $errorMessage = NULL)
  {
    parent::testOffsetSetTypeCheckException($MinAllowedType, $objectOutOfScope, $errorMessage);
  }

  /**
   * Index editing of children through \ramp\model\business\RecordComponent::offsetSet and
   * for \ramp\model\business\RecordComponent::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.RecordComponent#method_offsetSet ramp\model\business\RecordComponent::offsetSet()
   * @link ramp.model.business.RecordComponent#method_offsetUnset ramp\model\business\RecordComponent::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset($o);
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable RecordComponent.
   * - assert property 'type' is gettable:
   *   - assert returned value is of type {@link \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert foreach loop, iterates through each expected object:
   *   - assert returns object that is an instance of {@link \Traversable}
   *   - assert foreach returned object matches expected.
   * - assert expected object returned at its expected index.
   * - assert offsetExists returns correctly:
   *   - assert True returned on isset() when within expected bounds.
   *   - assert False returned on isset() when outside expected bounds.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link ramp.model.business.RecordComponent#method_setChildren ramp\model\business\RecordComponent::children
   * @link ramp.model.business.RecordComponent#method_get_type ramp\model\business\RecordComponent::type
   * @link ramp.model.business.RecordComponent#method_getIterator ramp\model\business\RecordComponent::getIterator()
   * @link ramp.model.business.RecordComponent#method_offsetGet ramp\model\business\RecordComponent::offsetGet()
   * @link ramp.model.business.RecordComponent#method_offsetExists ramp\model\business\RecordComponent::offsetExists()
   * @link ramp.model.business.RecordComponent#method_count ramp\model\business\RecordComponent::count
   */
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable RecordComponent.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link ramp.model.business.RecordComponent#method_setChildren ramp\model\business\RecordComponent::children
   * @link ramp.model.business.RecordComponent#method_validate ramp\model\business\RecordComponent::validate()
   * @link ramp.model.business.RecordComponent#method_hasErrors ramp\model\business\RecordComponent::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    parent::testTouchValidityAndErrorMethods();
  }

  /**
   * Error reporting within complex models using \ramp\model\business\RecordComponent::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link ramp.model.business.RecordComponent#method_getErrors ramp\model\business\RecordComponent::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }
  #endregion

  /**
   * Hold reference back to associated parent Record, propertyName and value.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * @link ramp.model.business.RecordComponent#method_get_parentRecord ramp\model\business\RecordComponent::record
   * @link ramp.model.business.RecordComponent#method_get_parentProppertyName ramp\model\business\RecordComponent::parentProppertyName
   */
  public function testStateChangesRecordComponent()
  {
    $this->assertEquals($this->name, $this->testObject->name);
    $this->assertSame($this->record, $this->testObject->parent);
    $this->assertNull($this->testObject->value);
    $this->assertEquals(
      (string)Str::COLON()->prepend($this->record->id)->append(Str::hyphenate($this->name)),
      (string)$this->testObject->id
    );
    $value = 'VALUE';
    $name = $this->name;
    $this->dataObject->$name = $value;
    $this->assertSame($value, $this->record->getPropertyValue($this->name));
    $this->assertSame($value, $this->testObject->value);
  }

  /**
   * Set 'record' NOT accessable ramp\model\business\RecordComponent::record.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @link ramp.model.business.RecordComponent#method_set_parentRecord ramp\model\business\RecordComponent::record
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->record is NOT settable');
    $this->testObject->record = 'PARENTRECORD';
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\RecordComponent::propertyName.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @link ramp.model.business.RecordComponent#method_set_parentPropertyName ramp\model\business\RecordComponent::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException()
  {
    $name = $this->name;
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->' . $name .' is NOT settable');
    $this->testObject->$name = 'PARENTPROPERTYNAME';
  }
}
