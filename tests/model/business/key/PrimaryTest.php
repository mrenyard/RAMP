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

require_once '/usr/share/php/tests/ramp/model/business/key/KeyTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/business/key/Primary.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockKeyPrimary.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelation.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;

use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockRecordComponent;

/**
 * Collection of tests for \ramp\model\business\key\Primary.
 */
class PrimaryTest extends \tests\ramp\model\business\key\KeyTest
{
  #region Setup
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    // \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockBusinessModelManager';
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
  }
  protected function getTestObject() : RAMPObject {
    return $this->record->primaryKey;
  }
  protected function postSetup() : void {
    $this->name = $this->testObject->name;

    $this->expectedChildCountNew = 3;
    $this->expectedChildCountExisting = 0;
    $this->touchChildrenValidateCount = 0;
    $this->allowsGrandchildErrorReporting = FALSE;
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\key\Primary::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\key\Primary}
   * - assert is instance of {@link \ramp\core\iList}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\buiness\key\Primary}   
   * @link ramp.model.business.key.Primary ramp\model\business\key\Primary
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\key\Primary', $this->testObject);
  }

  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->expectedChildCountExisting = 3;
    $this->postData = PostData::build(array(
      'mock-record:new:primary-key' => array('keyA' => 1, 'keyB' => 1, 'keyC' => 'BadValue')
    ));
    $this->childErrorIndexes = array(1);
    $this->childErrorIDs = array(1);
  }
  protected function complexModelIterationTypeCheck()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject['keyA']->type);
    $this->assertSame('mock-field field', (string)$this->testObject['keyA']->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject['keyB']->type);
    $this->assertSame('mock-field field', (string)$this->testObject['keyB']->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject['keyB']->type);
    $this->assertSame('mock-field field', (string)$this->testObject['keyB']->type);
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\buiness\key\Primary::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.business.key.Primary#method__set ramp\model\buiness\key\Primary::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\buiness\key\Primary::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.business.key.Primary#method__get ramp\model\buiness\key\Primary::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\buiness\key\Primary::__get() and \ramp\model\buiness\key\Primary::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.business.key.Primary#method___set \ramp\model\buiness\key\Primary::__set()
   * @link ramp.model.business.key.Primary#method___get \ramp\model\buiness\key\Primary::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\buiness\key\Primary::__toString().
   * - assert {@link \ramp\model\buiness\key\Primary::__toString()} returns string 'class name'
   * @link ramp.model.business.key.Primary#method___toString \ramp\model\buiness\key\Primary::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }

  /**
   * Minimumal key\Primary initial state.
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
   * @link ramp.model.business.key.Primary#method_get_type ramp\model\business\key\Primary::type
   * @link ramp.model.business.key.Primary#method_getIterator ramp\model\business\key\Primary::getIterator()
   * @link ramp.model.business.key.Primary#method_offsetExists ramp\model\business\key\Primary::offsetExists()
   * @link ramp.model.business.key.Primary#method_count ramp\model\business\key\Primary::count()
   * @link ramp.model.business.key.Primary#method_hasErrors ramp\model\business\key\Primary::hasErrors()
   * @link ramp.model.business.key.Primary#method_getErrors ramp\model\business\key\Primary::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\key\Primary::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.key\Primary#method_set_id ramp\model\business\key\Primary::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\key\Primary::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.key.Primary#method_set_type ramp\model\business\key\Primary::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\key\Primary::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.key.Primary#method_get_children ramp\model\business\key\Primary::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\key\Primary::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.key.Primary#method_offsetGet ramp\model\business\key\Primary::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Index editing of children through \ramp\model\business\key\Primary::offsetSet and
   * for \ramp\model\business\key\Primary::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.key.Primary#method_offsetSet ramp\model\business\key\Primary::offsetSet()
   * @link ramp.model.business.key.Primary#method_offsetUnset ramp\model\business\key\Primary::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset();
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable key\Primary.
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
   * @link ramp.model.business.key.Primary#method_setChildren ramp\model\business\key\Primary::children
   * @link ramp.model.business.key.Primary#method_get_type ramp\model\business\key\Primary::type
   * @link ramp.model.business.key.Primary#method_getIterator ramp\model\business\key\Primary::getIterator()
   * @link ramp.model.business.key.Primary#method_offsetGet ramp\model\business\key\Primary::offsetGet()
   * @link ramp.model.business.key.Primary#method_offsetExists ramp\model\business\key\Primary::offsetExists()
   * @link ramp.model.business.key.Primary#method_count ramp\model\business\key\Primary::count
   */
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable key\Primary.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link ramp.model.business.key.Primary#method_setChildren ramp\model\business\key\Primary::children
   * @link ramp.model.business.key.Primary#method_validate ramp\model\business\key\Primary::validate()
   * @link ramp.model.business.key.Primary#method_hasErrors ramp\model\business\key\Primary::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    parent::testTouchValidityAndErrorMethods();
  }

  /**
   * Error reporting within complex models using \ramp\model\business\key\Primary::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link ramp.model.business.key.Primary#method_getErrors ramp\model\business\key\Primary::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }

  /**
   * Hold reference back to associated parent Record, propertyName and value.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * @link ramp.model.business.key.Primary#method_get_parentRecord ramp\model\business\key\Primary::record
   * @link ramp.model.business.key.Primary#method_get_parentProppertyName ramp\model\business\key\Primary::parentProppertyName
   */
  public function testInitStateRecordComponent(?string $propertyName = NULL)
  {
    parent::testInitStateRecordComponent('primary-key');
  }

  /**
   * Set 'record' NOT accessable ramp\model\business\key\Primary::record.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @link ramp.model.business.key.Primary#method_set_parentRecord ramp\model\business\key\Primary::record
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\key\Primary::propertyName.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @link ramp.model.business.key.Primary#method_set_parentPropertyName ramp\model\business\key\Primary::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException()
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  #endregion

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

  /*
  public function testRecordWithKeyAlreadyExists()
  {
    $this->testObject[0] = new MockField($this->keyField1, $this->record);
    $this->testObject[1] = new MockField($this->keyField2, $this->record);
    $this->testObject[2] = new MockField($this->keyField3, $this->record);
    $this->dataObject->keyA = 1;
    $this->dataObject->keyB = 1;
    $this->assertNull($this->testObject->value);
    // $this->dataObject->keyC = 1;

    $this->assertNull($this->record->validate(PostData::build(array('mock-record:new:key-c' => '1'))));
    $this->assertSame('1|1|1', (string)$this->testObject->value);

    $this->assertSame(0, $this->testObject[0]->validateCount); // touched
    $this->assertSame(0, $this->testObject[1]->validateCount); // touched
    $this->assertSame(0, $this->testObject[2]->validateCount); // touched

    $this->assertSame(0, $this->testObject[0]->hasErrorsCount);
    $this->assertSame(0, $this->testObject[1]->hasErrorsCount);
    $this->assertSame(0, $this->testObject[2]->hasErrorsCount);
    $this->assertTrue($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(1, $errors->count);
    $this->assertSame('An entry already exists for this record!', (string)$errors[0]);
  }*/
}
