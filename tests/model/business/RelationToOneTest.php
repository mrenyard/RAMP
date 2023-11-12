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

require_once '/usr/share/php/tests/ramp/model/business/RelationTest.php';

require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
// require_once '/usr/share/php/ramp/model/business/RelationToMany.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRelatable.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';
// require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToMany.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\Relation;
use ramp\model\business\RecordCollection;

use tests\ramp\mocks\model\MockRelatable;
use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockMinRecord;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockRelationToOne;
use tests\ramp\mocks\model\MockRelationToMany;
use tests\ramp\mocks\model\MockBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\RelationToOne.
 */
class RelationToOneTest extends \tests\ramp\model\business\RelationTest
{
  #region Setup
  protected function preSetup() : void {
    MockBusinessModelManager::reset();
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockBusinessModelManager';
    $this->dataObject = new \StdClass();
    $this->record = new MockRecord($this->dataObject);
    $this->name = $this->record->relationBetaName;
    $this->with = $this->record->relationBetaWith;
  }
  protected function getTestObject() : RAMPObject {
    return $this->record->relationBeta;
  }
  protected function postSetup() : void {
    $this->expectedChildCountNew = 3;
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\Relation::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iOption}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\model\business\RecordComponent}
   * - assert is instance of {@see \ramp\model\business\Relation}
   * - assert is instance of {@see \ramp\model\business\RelationToOne}
   * @see ramp.model.business.Relation ramp\model\business\Relation
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\RelationToOne', $this->testObject);
  }
  
  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->expectedChildCountExisting = 3;
    $this->postData = new PostData();
    $this->testObject->with = new RecordCollection();
    $this->testObject->with->add(new MockMinRecord(new \stdClass));
    $this->testObject->with->add(new MockMinRecord(new \stdClass, TRUE));
    $this->testObject->with->add(new MockMinRecord(new \stdClass));
    $this->childErrorIndexes = array(1);
  }
  protected function complexModelIterationTypeCheck()
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
   * Bad property (name) NOT accessable on \ramp\model\Relation::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Relation::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Relation::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Relation::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\Relation::__get() and \ramp\model\Relation::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @see \ramp\model\Relation::__set()
   * @see \ramp\model\Relation::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Relation::__toString().
   * - assert {@see \ramp\model\Relation::__toString()} returns string 'class name'
   * @see \ramp\model\Relation::__toString()
   */
  public function testToString()
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
   * @see \ramp\model\business\Relation::type
   * @see \ramp\model\business\Relation::getIterator()
   * @see \ramp\model\business\Relation::offsetExists()
   * @see \ramp\model\business\Relation::count()
   * @see \ramp\model\business\Relation::hasErrors()
   * @see \ramp\model\business\Relation::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Relation::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\Relation::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\Relation::type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @see \ramp\model\business\Relation::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\Relation::children.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @see \ramp\model\business\Relation::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\Relation::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\Relation::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
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
    parent::testOffsetSetTypeCheckException('Relatable', new MockBusinessModel, 'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!');
  }

  /**
   * Index editing of children through \ramp\model\business\Relation::offsetSet and
   * for \ramp\model\business\Relation::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - assert successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @see \ramp\model\business\Relation::offsetSet()
   * @see \ramp\model\business\Relation::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    $this->expectException(\InvalidArgumentException::class);
    parent::testOffsetSetOffsetUnset(new MockRecord());
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable Relation.
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
   * @see \ramp\model\business\Relation::children
   * @see \ramp\model\business\Relation::type
   * @see \ramp\model\business\Relation::getIterator()
   * @see \ramp\model\business\Relation::offsetGet()
   * @see \ramp\model\business\Relation::offsetExists()
   * @see \ramp\model\business\Relation::count
   */
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable Relation.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @see \ramp\model\business\Relation::children
   * @see \ramp\model\business\Relation::validate()
   * @see \ramp\model\business\Relation::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    parent::testTouchValidityAndErrorMethods();
  }

  /**
   * Error reporting within complex models using \ramp\model\business\Relation::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\Relation::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }

  /**
   * Set 'record' NOT accessable ramp\model\business\Relation::record.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @see \ramp\model\business\Relation::record
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\Relation::propertyName.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @see \ramp\model\business\Relation::propertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException()
  {
    parent::testSetParentPropertyNamePropertyNotSetException();
  }

  /**
   * Hold reference back to associated parent Record, propertyName and format for id.
   * - assert record as passed to constructor.
   * - assert propertyName as passed to constructor.
   * - assert id as expected in format record:key:propertyName.
   * @see ramp.model.business.RecordComponent#method_get_parent ramp\model\business\RecordComponent::parent
   * @see ramp.model.business.RecordComponent#method_get_name ramp\model\business\RecordComponent::name
   */
  public function testStateChangesRecordComponent()
  {
    parent::testStateChangesRecordComponent();
  }

  /**
   * RecordComponent (default) value returns same as parent Record::getPropertyValue(name).
   * - assert current record->getPropertyValue and RecordComponent->value return same instance.
   * @see ramp.model.business.RecordComponent#method_get_value ramp\model\business\RecordComponent::value
   * @see \ramp\model\business\Record::getPropertyValue()
   */
  public function testRecordComponentValue(string $expectedValue = 'mock-min-record:new')
  {
    parent::testRecordComponentValue($expectedValue);
  }
  
  /**
   * Check assigned protected propery \ramp\model\business\Relation::$manager holds referance to expected BusinessModelManager.
   * - assert protected property $manager referances same object. 
   * @see \ramp\model\business\Relation::$manager
   */
  public function testModelManager()
  {
    parent::testModelManager();
  }

  /**
   * Test protected static \ramp\model\business\Relation::buildMapping().
   *  - assert returns array with key => value pair in expected format.
   * @see \ramp\model\business\Relation::buildMapping()
   */
  public function testBuildMapping()
  {
    parent::testBuildMapping();
  }
  #endregion

  /**
   * Check NONE connection of Relation (to ONE) beyond second level (URL(model) + first Chuldren).
   * TODO:mrenyard: Max Relation depth test ((string)/ramp/http/Request::current()->modelURN == (string)$parent->id)
   */
  public function testMaxRelationDepth()
  {
    $this->assertTrue(TRUE);
  }
}

