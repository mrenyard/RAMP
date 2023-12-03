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
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToMany.class.php';
require_once '/usr/share/php/ramp/model/business/Key.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordComponent.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockInput.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToMany.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModelManager.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\RecordComponent;

use tests\ramp\mocks\core\AnObject;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockRecordComponent;
use tests\ramp\mocks\model\MockBusinessModelManager;

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
    MockBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockBusinessModelManager';
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
    $this->name = Str::set('aProperty');
  }
  protected function getTestObject() : RAMPObject { return new MockRecordComponent($this->name, $this->record); }
  protected function postSetup() : void { $this->expectedChildCountNew = 0; }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\RecordComponent::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\RecordComponent}
   * @see ramp.model.business.RecordComponent ramp\model\business\RecordComponent
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject);
  }

  #region Sub model setup
  // protected function populateSubModelTree()
  // {
  //   $this->testObject[0] = new MockBusinessModel();
  //   $this->testObject[1] = new MockBusinessModel();
  //   $this->testObject[1][0] = new MockBusinessModel(TRUE);
  //   $this->testObject[2] = new MockBusinessModel(TRUE);
  //   $this->expectedChildCountExisting = 3;
  //   $this->childErrorIndexes = array(1,2);
  //   $this->postData = new PostData();
  // }
  // protected function complexModelIterationTypeCheck()
  // {
  //   $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
  //   $this->assertSame('mock-business-model business-model', (string)$this->testObject[0]->type);
  //   $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
  //   $this->assertSame('mock-business-model business-model', (string)$this->testObject[1]->type);
  //   $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1][0]->type);
  //   $this->assertSame('mock-business-model business-model', (string)$this->testObject[1][0]->type);
  //   $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
  //   $this->assertSame('mock-business-model business-model', (string)$this->testObject[2]->type);
  // }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\RecordComponent::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\RecordComponent::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\RecordComponent::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\RecordComponent::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\RecordComponent::__get() and \ramp\model\RecordComponent::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @see \ramp\model\RecordComponent::__set()
   * @see \ramp\model\RecordComponent::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\RecordComponent::__toString().
   * - assert {@see \ramp\model\RecordComponent::__toString()} returns string 'class name'
   * @see \ramp\model\RecordComponent::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Minimumal RecordComponent initial state.
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
   * @see ramp.model.business.RecordComponent#method_get_type ramp\model\business\RecordComponent::type
   * @see ramp.model.business.RecordComponent#method_getIterator ramp\model\business\RecordComponent::getIterator()
   * @see ramp.model.business.RecordComponent#method_offsetExists ramp\model\business\RecordComponent::offsetExists()
   * @see ramp.model.business.RecordComponent#method_count ramp\model\business\RecordComponent::count()
   * @see ramp.model.business.RecordComponent#method_hasErrors ramp\model\business\RecordComponent::hasErrors()
   * @see ramp.model.business.RecordComponent#method_getErrors ramp\model\business\RecordComponent::getErrors()
   */
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\RecordComponent::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see ramp.model.business.RecordComponent#method_set_id ramp\model\business\RecordComponent::id
   */
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\RecordComponent::type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @see ramp.model.business.RecordComponent#method_set_type ramp\model\business\RecordComponent::type
   */
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\RecordComponent::children.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @see ramp.model.business.RecordComponent#method_get_children ramp\model\business\RecordComponent::children
   */
  public function testGetChildrenBadPropertyCallException() : void
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\RecordComponent::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see ramp.model.business.RecordComponent#method_offsetGet ramp\model\business\RecordComponent::offsetGet()
   */
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Offset addition minimum type checking test
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\Record::offsetSet()
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
   * @see ramp.model.business.RecordComponent#method_offsetSet ramp\model\business\RecordComponent::offsetSet()
   * @see ramp.model.business.RecordComponent#method_offsetUnset ramp\model\business\RecordComponent::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset($o);
  }
  // protected function offsetUnset(int $i)
  // {
  //   $this->expectException(\InvalidArgumentException::class);
  //   $this->expectExceptionMessage('');
  //   unset($this->testObject[0]);
  // }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable RecordComponent.
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
   * @see ramp.model.business.RecordComponent#method_setChildren ramp\model\business\RecordComponent::children
   * @see ramp.model.business.RecordComponent#method_get_type ramp\model\business\RecordComponent::type
   * @see ramp.model.business.RecordComponent#method_getIterator ramp\model\business\RecordComponent::getIterator()
   * @see ramp.model.business.RecordComponent#method_offsetGet ramp\model\business\RecordComponent::offsetGet()
   * @see ramp.model.business.RecordComponent#method_offsetExists ramp\model\business\RecordComponent::offsetExists()
   * @see ramp.model.business.RecordComponent#method_count ramp\model\business\RecordComponent::count
   */
  public function testComplexModelIteration() : void
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
   * @see ramp.model.business.RecordComponent#method_setChildren ramp\model\business\RecordComponent::children
   * @see ramp.model.business.RecordComponent#method_validate ramp\model\business\RecordComponent::validate()
   * @see ramp.model.business.RecordComponent#method_hasErrors ramp\model\business\RecordComponent::hasErrors()
   */
  public function testTouchValidityAndErrorMethods() : void
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
   * @see ramp.model.business.RecordComponent#method_getErrors ramp\model\business\RecordComponent::getErrors()
   */
  public function testErrorReportingPropagation() : void
  {
    parent::testErrorReportingPropagation();
  }
  #endregion

  /**
   * Hold reference back to associated parent Record, propertyName and format for id.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * - assert id as expected in format record:key:propertyName.
   * @see ramp.model.business.RecordComponent#method_get_parent ramp\model\business\RecordComponent::parent
   * @see ramp.model.business.RecordComponent#method_get_name ramp\model\business\RecordComponent::name
   */
  public function testStateChangesRecordComponent() : void
  {
    $this->assertEquals($this->name, $this->testObject->name);
    $this->assertSame($this->record, $this->testObject->parent);
    $this->assertEquals(
      (string)Str::COLON()->prepend($this->record->id)->append(Str::hyphenate($this->name)),
      (string)$this->testObject->id
    );
  }

  /**
   * RecordComponent (default) value returns same as parent Record::getPropertyValue(name).
   * - assert current record->getPropertyValue and RecordComponent->value return same instance.
   * @see ramp.model.business.RecordComponent#method_get_value ramp\model\business\RecordComponent::value
   * @see \ramp\model\business\Record::getPropertyValue()
   */
  public function testRecordComponentValue() : void
  {
    $this->assertSame($this->record->getPropertyValue($this->name), $this->testObject->value);
  }

  /**
   * Set 'parent' NOT accessable.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'parent'.
   * @see \ramp\model\business\RecordComponent::$parent
   */
  public function testSetParentRecordPropertyNotSetException() : void
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->record is NOT settable');
    $this->testObject->record = 'PARENTRECORD';
  }

  /**
   * Set 'name' NOT accessable.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'name'.
   * @see \ramp\model\business\RecordComponent::$name
   */
  public function testSetParentPropertyNamePropertyNotSetException() : void
  {
    $name = $this->name;
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->' . $name .' is NOT settable');
    $this->testObject->$name = 'PARENTPROPERTYNAME';
  }
}
