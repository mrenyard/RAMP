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
namespace tests\ramp\model\business\key;

require_once '/usr/share/php/tests/ramp/model/business/RecordComponentTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/URNQueryEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/model/business/key/Key.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\key\Key;

use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockKey;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockField;
use tests\ramp\mocks\model\MockRecordComponent;

/**
 * Collection of tests for \ramp\model\business\key\Key.
 */
class KeyTest extends \tests\ramp\model\business\RecordComponentTest
{
  protected $keyName;
  protected $subKey1;
  protected $subKey2;

  #region Setup
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
  }
  protected function getTestObject() : RAMPObject {
    return $this->record->foreignKey;
  }
  protected function postSetup() : void {
    $this->name = $this->record->foreignKeyName;
    $this->subKey1 = 'key1';
    $this->subKey2 = 'key2';
    $this->expectedChildCountNew = 0;
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\key\Key::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\key\Key}
   * - assert is instance of {@link \ramp\core\iList}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\buiness\key\Key}   
   * @link ramp.model.business.key.Key ramp\model\business\key\Key
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\key\Key', $this->testObject);
  }

  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->testObject[$this->subKey1] = new MockField(Str::set('foreignKey[' . $this->subKey1 . ']'), $this->record);
    $this->testObject[$this->subKey2] = new MockField(Str::set('foreignKey[' . $this->subKey2 . ']'), $this->record);
    $this->expectedChildCountExisting = 2;
    $this->postData = PostData::build(array(
      'mock-record:new:foreignKey' => array('key1' => 1, 'key2' => 'BadValue')
    ));
    $this->childErrorIndexes = array(1);
    $this->childErrorIDs = array(1);
  }
  protected function complexModelIterationTypeCheck()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject['key1']->type);
    $this->assertSame('mock-field field', (string)$this->testObject['key1']->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject['key2']->type);
    $this->assertSame('mock-field field', (string)$this->testObject['key2']->type);
  } 
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\buiness\key\Key::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.business.key.Key#method__set ramp\model\buiness\key\Key::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\buiness\key\Key::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.business.key.Key#method__get ramp\model\buiness\key\Key::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\buiness\key\Key::__get() and \ramp\model\buiness\key\Key::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.business.key.Key#method___set \ramp\model\buiness\key\Key::__set()
   * @link ramp.model.business.key.Key#method___get \ramp\model\buiness\key\Key::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\buiness\key\Key::__toString().
   * - assert {@link \ramp\model\buiness\key\Key::__toString()} returns string 'class name'
   * @link ramp.model.business.key.Key#method___toString \ramp\model\buiness\key\Key::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }

  /**
   * Minimumal key\Key initial state.
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
   * @link ramp.model.business.key.Key#method_get_type ramp\model\business\key\Key::type
   * @link ramp.model.business.key.Key#method_getIterator ramp\model\business\key\Key::getIterator()
   * @link ramp.model.business.key.Key#method_offsetExists ramp\model\business\key\Key::offsetExists()
   * @link ramp.model.business.key.Key#method_count ramp\model\business\key\Key::count()
   * @link ramp.model.business.key.Key#method_hasErrors ramp\model\business\key\Key::hasErrors()
   * @link ramp.model.business.key.Key#method_getErrors ramp\model\business\key\Key::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\key\Key::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.key\Key#method_set_id ramp\model\business\key\Key::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\key\Key::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.key.Key#method_set_type ramp\model\business\key\Key::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\key\Key::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.key.Key#method_get_children ramp\model\business\key\Key::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\key\Key::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.key.Key#method_offsetGet ramp\model\business\key\Key::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Index editing of children through \ramp\model\business\key\Key::offsetSet and
   * for \ramp\model\business\key\Key::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.key.Key#method_offsetSet ramp\model\business\key\Key::offsetSet()
   * @link ramp.model.business.key.Key#method_offsetUnset ramp\model\business\key\Key::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset(new MockField(Str::set('KeyA'), $this->record));
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable key\Key.
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
   * @link ramp.model.business.key.Key#method_setChildren ramp\model\business\key\Key::children
   * @link ramp.model.business.key.Key#method_get_type ramp\model\business\key\Key::type
   * @link ramp.model.business.key.Key#method_getIterator ramp\model\business\key\Key::getIterator()
   * @link ramp.model.business.key.Key#method_offsetGet ramp\model\business\key\Key::offsetGet()
   * @link ramp.model.business.key.Key#method_offsetExists ramp\model\business\key\Key::offsetExists()
   * @link ramp.model.business.key.Key#method_count ramp\model\business\key\Key::count
   */
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Error reporting within complex models using \ramp\model\business\key\Key::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link ramp.model.business.key.Key#method_getErrors ramp\model\business\key\Key::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }

  /**
   * Set 'record' NOT accessable ramp\model\business\key\Key::record.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @link ramp.model.business.key.Key#method_set_parentRecord ramp\model\business\key\Key::record
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\key\Key::propertyName.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @link ramp.model.business.key.Key#method_set_parentPropertyName ramp\model\business\key\Key::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException()
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  #endregion
  
  /**
   * Hold reference back to associated parent Record, propertyName and value.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * @link ramp.model.business.key.Key#method_get_parentRecord ramp\model\business\key\Key::record
   * @link ramp.model.business.key.Key#method_get_parentProppertyName ramp\model\business\key\Key::parentProppertyName
   */
  public function testInitStateRecordComponent(string $propertyName = NULL)
  {
    $propertyName = ($propertyName === NULL) ? 'foreign-key' :  $propertyName;
    $this->assertSame('mock-record:new:' . $propertyName, (string)$this->testObject->id);
    parent::testInitStateRecordComponent();
  }

  /**
   * Offset addition minimum type checking test
   * - assert {@link \InvalidArgumentException} thrown when offset type outside of acceptable scope
   *   and expected associated record and unique to 'Key' propertyName.
   * @link ramp.model.business.key.Key#method_offsetSet ramp\model\business\key\Key::offsetSet()
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
   * Touch Validity checking and error checking within complex models.
   * - assert validate method returns void (null) when called.
   * - assert hasErrors reports as expected.
   * @link ramp.model.business.key.Key#method_setChildren ramp\model\business\key\Key::children
   * @link ramp.model.business.key.Key#method_validate ramp\model\business\key\Key::validate()
   * @link ramp.model.business.key.Key#method_hasErrors ramp\model\business\key\Key::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    // NOTE Validation through processValidationRule NOT validate and hasErrors.
    $i = 0;
    foreach ($this->testObject as $child) {
      $this->assertSame(0, $child->validateCount);
      $this->assertSame(0, $child->hasErrorsCount);
      $i++;
    }
  }
}
