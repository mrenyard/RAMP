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
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordMockRelation.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationA.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockSqlBusinessModelManager.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\RecordCollection;
use ramp\model\business\Relation;

use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockRelatable;
use tests\ramp\mocks\model\MockRecordMockRelation;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockMinRecord;
use tests\ramp\mocks\model\MockMinRecordCollection;
use tests\ramp\mocks\model\MockSqlBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\Relation.
 */
class RelationTest extends \tests\ramp\model\business\RecordComponentTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void {
    MockSqlBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockSqlBusinessModelManager';
    $this->dataObject = new \StdClass();
    $this->dataObject->fk = NULL;
    $this->record = new MockRecordMockRelation($this->dataObject);
    $this->name = $this->record->relationAlphaName;
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return $this->record->relationAlpha; }
  #[\Override]
  protected function postSetup() : void { $this->expectedChildCountNew = 0; }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\Relation.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iOption}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\RecordComponent}
   * - assert is instance of {@see \ramp\model\business\Relation}
   * @see \ramp\model\business\Relation
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Relation', $this->testObject);
  }
  
  #region Sub model templates model setup
  #[\Override]
  protected function populateSubModelTree() : void
  {
    $this->postData = new PostData();
    $this->testObject->with = new RecordCollection();
    $this->testObject->with->add(new MockMinRecord(new \stdClass));
    $this->testObject->with->add(new MockMinRecord(new \stdClass, TRUE));
    $this->testObject->with->add(new MockMinRecord(new \stdClass));
    $this->expectedChildCountExisting = 3;
    $this->childErrorIndexes = array(1);
  }
  #[\Override]
  protected function complexModelIterationTypeCheck() : void
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    $this->assertSame('mock-min-record record', (string)$this->testObject[0]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
    $this->assertSame('mock-min-record record', (string)$this->testObject[1]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
    $this->assertSame('mock-min-record record', (string)$this->testObject[2]->type);
    $this->assertArrayNotHasKey(3, $this->testObject);
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT settable.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property.
   * @see \ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\core\RAMPObject::__get()
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
   * Correct return of string.
   * - assert {@see \ramp\model\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Minimumal Relation initial state.
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
   * @see \ramp\model\business\BusinessModel::$errors
   */
  #[\Override]
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'.
   * @see \ramp\model\business\BusinessModel::$id
   */
  #[\Override]
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'.
   * @see \ramp\model\business\BusinessModel::$type
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
   * Index beyond bounds with \ramp\model\business\Relation::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children.
   * @see \ramp\model\business\BusinessModel::offsetGet()
   */
  #[\Override]
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();
  }

  /**
   * Offset addition minimum type checking test.
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\BusinessModel::offsetSet()
   */
  #[\Override]
  public function testOffsetSetTypeCheckException(?string $MinAllowedType = NULL, ?RAMPObject $objectOutOfScope = NULL, ?string $errorMessage = NULL) : void
  {
    parent::testOffsetSetTypeCheckException('Relatable', new MockBusinessModel, 'Adding properties through offsetSet BLOKED!');
  }

  /**
   * Index editing of children through offsetSet and offsetUnset.
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
    parent::testOffsetSetOffsetUnset($o);
  }
  #[\Override]
  protected function offsetSet(BusinessModel $o, int $i)
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Adding properties through offsetSet BLOKED!');
    $this->testObject[$i] = $o;
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
   * @see \ramp\model\business\BusinessModel::offsetExists()
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
    parent::testTouchValidityAndErrorMethods($touchCountTest);
  }

  /**
   * Error reporting within complex models.
   * - assert following validate(), the expected iCollection of error messages returned from
   *    $error are as expected, depending on which level they are called.
   * - assert any following call to $hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all $errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevant sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\BusinessModel::$errors
   */
  #[\Override]
  public function testErrorReportingPropagation($message = 'Error MESSAGE BadValue Submited!') : void
  {
    parent::testErrorReportingPropagation($message);
  }

  /**
   * Hold reference back to associated 'parent' Record, property 'name' and format for id.
   * - assert record as passed to constructor.
   * - assert name as passed to constructor.
   * - assert id as expected in format record:key:name.
   * @see \ramp\model\business\RecordComponent::$parent
   * @see \ramp\model\business\RecordComponent::$name
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
  public function testRecordComponentValue(string $expectedValue = '') : void
  {
    $this->assertEquals($expectedValue, $this->testObject->value);
  }

  /**
   * Set 'parent' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'.
   * @see \ramp\model\business\RecordComponent::$parent
   */
  #[\Override]
  public function testSetParentRecordPropertyNotSetException() : void
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'name' NOT accessible.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'name'.
   * @see \ramp\model\business\RecordComponent::$name
   */
  #[\Override]
  public function testSetParentPropertyNamePropertyNotSetException() : void
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Check assigned protected propery $manager holds referance to expected BusinessModelManager.
   * - assert protected property $manager referances same object. 
   * @see \ramp\model\business\Relation::$manager
   */
  public function testModelManager() : void
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $expectedManager = $MODEL_MANAGER::getInstance();
    $this->assertSame($expectedManager, $this->testObject->getModelManager());
  }

  /**
   * Test protected static buildMapping().
   *  - assert returns array with `key => value` pair in expected format.
   * @see \ramp\model\business\Relation::buildMapping()
   */
  public function testBuildMapping() : void
  {
    $fromData = new \stdClass();
    $from = new MockRecord($fromData);
    $toData = new \stdClass();
    $to = new MockMinRecord($toData);
    $fromPropertyName = Str::set('Property1');
    $keyMap = $this->testObject->callBuildMapping($from, $to, $fromPropertyName);
    $i = 0;
    foreach ($keyMap as $subKey => $subForeignKey) {
      $i++;
      $this->assertSame('key' . $i, $subKey);
      $this->assertSame('fk_Property1_MockMinRecord_key' . $i, $subForeignKey);
    }
  }

  /**
   * Check NONE connection of Relation (to ONE) beyond second level (URL(model) + first Chuldren).
   * TODO:mrenyard: Max Relation depth test ((string)/ramp/http/Request::current()->modelURN == (string)$parent->id)
   */
  public function testMaxRelationDepth() : void
  {
    $this->assertTrue(TRUE);
  }
  #endregion
}