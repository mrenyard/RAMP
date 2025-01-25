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

require_once '/usr/share/php/tests/ramp/model/business/BusinessModelTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Text.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Flag.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectMany.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToMany.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockOption.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockSqlBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockInput.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockFlag.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockSelectFrom.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToMany.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\OptionList;
use ramp\core\StrCollection;
use ramp\core\PropertyNotSetException;
use ramp\core\BadPropertyCallException;
use ramp\condition\PostData;
use ramp\model\business\field\Field;
use ramp\model\business\BusinessModel;

use tests\ramp\mocks\core\AnObject;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockSelectFrom;
use tests\ramp\mocks\model\MockOption;
use tests\ramp\mocks\model\MockSqlBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\field\Option.
 */
class OptionTest extends \tests\ramp\model\business\BusinessModelTest
{
  // protected $expectedChildCountNew;
  // protected $expectedChildCountExisting;
  // protected $childErrorIndexes;
  protected $dataObject;
  // protected $postData;
  protected $record;
  protected $field;

  #region Setup
  protected function preSetup() : void {
    MockSqlBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockSqlBusinessModelManager';
    $this->dataObject = new \stdClass();
    $this->record = new MockRecord($this->dataObject);
    $this->field = $this->record->selectFrom;
  }
  protected function getTestObject() : RAMPObject { return $this->field[1]; }
  protected function postSetup() : void { $this->expectedChildCountNew = 0; }
  #endregion

  /**
   * Default base constructor assertions \ramp\model\business\field\Option::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\field\Option}
   * @see \ramp\model\business\field\Option
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\field\Option', $this->testObject);
  }
  
  #region Sub model templates model setup
  protected function populateSubModelTree() : void
  {
    $this->expectedChildCountExisting = 0;
    $this->childErrorIndexes = array(0);
    $this->postData = PostData::build(array(
      'mock-record:new:select-from' => 3
    ));
  }
  protected function complexModelIterationTypeCheck() : void
  {
    $this->assertFalse(isset($this->testObject[0]));
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\model\business\BusinessModel::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\business\BusinessModel::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\model\business\BusinessModel::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\business\BusinessModel::__get()
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
   * Correct return of ramp\model\business\BusinessModel::__toString().
   * - assert {@see \ramp\model\business\BusinessModel::__toString()} returns string 'class name'
   * @see \ramp\model\business\BusinessModel::__toString()
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
   * Set 'id' NOT accessible on \ramp\model\business\BusinessModel::$id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'.
   * @see \ramp\model\business\BusinessModel::$id
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
   * Offset addition minimum type checking test.
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\Record::offsetSet()
   * @see \ramp\model\business\Record::offsetUnsSet()
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
    parent::testOffsetSetOffsetUnset($o);
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
  public function testTouchValidityAndErrorMethods($touchCountTest = TRUE) : void
  {
    $this->populateSubModelTree();
    $this->assertNull($this->field->validate($this->postData)); // Call
    $this->assertTrue($this->field->hasErrors);
    $this->assertSame(1, $this->field->validateCount);
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
    $this->populateSubModelTree();
    $this->assertNull($this->field->validate($this->postData)); // Call
    $this->assertTrue($this->field->hasErrors);
    $errors = $this->field->errors;
    $this->assertSame(count($this->childErrorIndexes), $errors->count);
    $i = 0;
    do {
      $this->assertSame($message, (string)$errors[$i++]);
    } while  ($i < $errors->count);
  }
  #endregion

  #region New Specialist Tests
  /**
   * Check Option initial state.
   * - assert $key is expected value and type.
   * - assert $description is same as provide at creation.
   * - assert $isSelected reports correct init state.
   * @see \ramp\model\business\field\Option::$key
   * @see \ramp\model\business\field\Option::$description
   * @see \ramp\model\business\field\Option::$isSelected
   */
  public function testOptionInitState()
  {
    $this->assertIsInt($this->testObject->key);
    $this->assertSame(1, $this->testObject->key);
    $this->assertSame($this->record->selectDescriptionOne, $this->testObject->description);
    $this->assertFalse($this->testObject->isSelected);
    $testObject = $this->record->selectMany[1];
    $this->assertIsInt($testObject->key);
    $this->assertSame(1, $testObject->key);
    $this->assertSame($this->record->selectDescriptionOne, $this->testObject->description);
    $this->assertFalse($testObject->isSelected);
  }

  /**
   * IsSelected state change depending on PostData validation.
   * -assert isSelected returns TRUE or FALSE based on changes instructed by validation of PostData.
   * @see \ramp\condition\PostData
   * @see \ramp\model\business\field\Field::vaidation()
   * @see \ramp\model\business\field\Option::$isSelected
   */
  public function testIsSelectedStatus()
  {
    $this->field->validate(PostData::build(array('mock-record:new:select-from' => 1)));
    $this->assertSame(1, $this->dataObject->selectFrom);
    $this->assertTrue($this->testObject->isSelected);
    $field = $this->record->selectMany;
    $this->assertTrue($field[0]->isSelected);
    $this->assertFalse($field[1]->isSelected);
    $this->assertFalse($field[2]->isSelected);
    $this->assertFalse($field[3]->isSelected);
    $field->validate(PostData::build(array('mock-record:new:select-many' => array(1,3))));
    $this->assertSame('1|3', $this->dataObject->selectMany);
    $this->assertFalse($field[0]->isSelected);
    $this->assertTrue($field[1]->isSelected);
    $this->assertFalse($field[2]->isSelected);
    $this->assertTrue($field[3]->isSelected);
  }

  /**
   * Throws BadPropertyCallException when isSelected called without parentField first being set.
   * - assert BadPropertyCallException thrown
   * - assert error message reads *'Must set parentField before calling isSelected.'*.
   */
  public function testIsSelectedBadPropertyCall()
  {
    $testObject = new MockOption(1, Str::set('DESCRIPTION ONE'));
    $this->expectException(BadPropertyCallException::class);
    $this->expectExceptionMessage('Must set parentField before calling isSelected.');
    $testObject->isSelected;
  }
  #endregion
}
