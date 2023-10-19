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
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationB.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModelManager.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\RecordCollection;
use ramp\model\business\Relation;

use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockRelatable;
use tests\ramp\mocks\model\MockRelationB;
use tests\ramp\mocks\model\MockRecordMockRelation;
use tests\ramp\mocks\model\MockMinRecord;
use tests\ramp\mocks\model\MockMinRecordCollection;

/**
 * Collection of tests for \ramp\model\business\Relation.
 */
class RelationTest extends \tests\ramp\model\business\RecordComponentTest
{
  protected $with;

  #region Setup
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockBusinessModelManager';
    $this->dataObject = new \StdClass();
    $this->dataObject->fk = NULL;
    $this->record = new MockRecordMockRelation($this->dataObject);
    $this->name = $this->record->relationAlphaName;
    $this->with = $this->record->relationAlphaWith;
  }
  protected function getTestObject() : RAMPObject {
    return $this->record->relationAlpha;
  }
  protected function postSetup() : void {
    $this->expectedChildCountNew = 0;
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\Relation::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\model\business\RecordComponent}
   * - assert is instance of {@link \ramp\model\business\Relation}
   * @link ramp.model.business.Relation ramp\model\business\Relation
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Relation', $this->testObject);
  }
  
  #region Sub model setup
  protected function populateSubModelTree()
  {
    $this->expectedChildCountExisting = 1;
    $this->postData = new PostData();
    $d = new \stdClass();
    $this->testObject[$this->testObject->count] = new MockMinRecord($d, TRUE);
    $this->childErrorIndexes = array(2);
  }
  protected function complexModelIterationTypeCheck()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    $this->assertSame('mock-min-record record', (string)$this->testObject[0]->type);
}
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Relation::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.business.Relation#method__set ramp\model\Relation::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Relation::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.business.Relation#method__get ramp\model\Relation::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\Relation::__get() and \ramp\model\Relation::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.business.Relation#method___set \ramp\model\Relation::__set()
   * @link ramp.model.business.Relation#method___get \ramp\model\Relation::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Relation::__toString().
   * - assert {@link \ramp\model\Relation::__toString()} returns string 'class name'
   * @link ramp.model.business.Relation#method___toString \ramp\model\Relation::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }

  /**
   * Minimumal Relation initial state.
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
   * @link ramp.model.business.Relation#method_get_type ramp\model\business\Relation::type
   * @link ramp.model.business.Relation#method_getIterator ramp\model\business\Relation::getIterator()
   * @link ramp.model.business.Relation#method_offsetExists ramp\model\business\Relation::offsetExists()
   * @link ramp.model.business.Relation#method_count ramp\model\business\Relation::count()
   * @link ramp.model.business.Relation#method_hasErrors ramp\model\business\Relation::hasErrors()
   * @link ramp.model.business.Relation#method_getErrors ramp\model\business\Relation::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Relation::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.Relation#method_set_id ramp\model\business\Relation::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\Relation::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.Relation#method_set_type ramp\model\business\Relation::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\Relation::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.Relation#method_get_children ramp\model\business\Relation::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\Relation::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.Relation#method_offsetGet ramp\model\business\Relation::offsetGet()
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
    parent::testOffsetSetTypeCheckException('Relatable', new MockBusinessModel, 'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!');
  }

  /**
   * Index editing of children through \ramp\model\business\Relation::offsetSet and
   * for \ramp\model\business\Relation::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - assert successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.Relation#method_offsetSet ramp\model\business\Relation::offsetSet()
   * @link ramp.model.business.Relation#method_offsetUnset ramp\model\business\Relation::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset(new MockRecordMockRelation());
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable Relation.
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
   * @link ramp.model.business.Relation#method_setChildren ramp\model\business\Relation::children
   * @link ramp.model.business.Relation#method_get_type ramp\model\business\Relation::type
   * @link ramp.model.business.Relation#method_getIterator ramp\model\business\Relation::getIterator()
   * @link ramp.model.business.Relation#method_offsetGet ramp\model\business\Relation::offsetGet()
   * @link ramp.model.business.Relation#method_offsetExists ramp\model\business\Relation::offsetExists()
   * @link ramp.model.business.Relation#method_count ramp\model\business\Relation::count
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
   * @link ramp.model.business.Relation#method_setChildren ramp\model\business\Relation::children
   * @link ramp.model.business.Relation#method_validate ramp\model\business\Relation::validate()
   * @link ramp.model.business.Relation#method_hasErrors ramp\model\business\Relation::hasErrors()
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
   * @link ramp.model.business.Relation#method_getErrors ramp\model\business\Relation::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }


  /**
   * Set 'record' NOT accessable ramp\model\business\Relation::record.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'record'
   * @link ramp.model.business.Relation#method_set_parentRecord ramp\model\business\Relation::record
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    parent::testSetParentRecordPropertyNotSetException();
  }

  /**
   * Set 'propertyName' NOT accessable ramp\model\business\Relation::propertyName.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'propertyName'
   * @link ramp.model.business.Relation#method_set_parentPropertyName ramp\model\business\Relation::propertyName
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
   * @link ramp.model.business.Relation#method_get_parentRecord ramp\model\business\Relation::record
   * @link ramp.model.business.Relation#method_get_parentProppertyName ramp\model\business\Relation::parentProppertyName
   */
  public function testStateChangesRecordComponent()
  {
    $this->assertEquals($this->name, $this->testObject->name);
    $this->assertSame($this->record, $this->testObject->parent);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $this->testObject->value);
    $this->assertEquals(1, $this->testObject->value->count);
    $this->assertTrue($this->testObject->value[0]->isNew);
    $this->assertEquals(
      (string)Str::COLON()->prepend($this->record->id)->append(Str::hyphenate($this->name)),
      (string)$this->testObject->id
    );
  }

  /**
   * Succsesfully construct Relation toMANY with consistent keys plus final 'new'.
   * - Set data on Record with pirmaryKey ready to recive foreignKey associated collection
   * - Populate $with consistent foreignKeys + one final new (Record).
   * - Create Relation SHOULD NOT throw Exception.
   * - Assert interation matches passed collection.
   */
  public function testConsistentKeyWithCollection()
  {
    // Set data and update to existing Record.
    $this->dataObject->keyA = 1;
    $this->dataObject->keyB = 1;
    $this->dataObject->keyC = 1;
    $this->record->updated();
    $this->assertEquals('mock-record-mock-relation:1|1|1', (string)$this->record->id);
    $this->assertFalse($this->record->isNew);
    // Populate $with consistent foreignKeys + one final new (Record).
    $with = new RecordCollection();
    $d = new \stdClass();
    $d->fk_relationGamma_MockRecordMockRelation_keyA = 1;
    $d->fk_relationGamma_MockRecordMockRelation_keyB = 1;
    $d->fk_relationGamma_MockRecordMockRelation_keyC = 1;
    $with->add(new MockMinRecord($d));
    $d = new \stdClass();
    $d->fk_relationGamma_MockRecordMockRelation_keyA = 1;
    $d->fk_relationGamma_MockRecordMockRelation_keyB = 1;
    $d->fk_relationGamma_MockRecordMockRelation_keyC = 1;
    $with->add(new MockMinRecord($d));
    $d = new \stdClass();
    $d->fk_relationGamma_MockRecordMockRelation_keyA = 1;
    $d->fk_relationGamma_MockRecordMockRelation_keyB = 1;
    $d->fk_relationGamma_MockRecordMockRelation_keyC = 1;
    $with->add(new MockMinRecord($d));
    $with->add(new MockMinRecord());
    // Create Relation SHOULD NOT throw Exception.
    $testObject = new MockRelationB(Str::set('relationGamma'), $this->record, $with);
    // Assert interation matches passed collection
    $i = 0;
    foreach ($testObject as $relatedRecord) {
      $this->assertSame($with[$i++], $relatedRecord);
    }
    $this->assertEquals($with->count, $testObject->count);
  }

  /**
   * Unsuccsesfully construct Relation toMANY with inconsistent keys
   * - Assert throws Exception
   * - Set data on Record with pirmaryKey ready to recive foreignKey associated collection
   * - Populate $with one inconsistent foreignKey to be detected.
   * - Create Relation and expecet Exception.
   */
  public function testInconsistentKeyWithCollection()
  {
    // Set data and update to existing Record.
    $this->dataObject->keyA = 1;
    $this->dataObject->keyB = 1;
    $this->dataObject->keyC = 1;
    $this->record->updated();
    $this->assertEquals('mock-record-mock-relation:1|1|1', (string)$this->record->id);
    $this->assertFalse($this->record->isNew);
    // Populate $with including one inconsistent Record
    $with = new RecordCollection();
    $d = new \stdClass();
    $d->FK_relationGamma_MockRecord_keyA = 1;
    $d->FK_relationGamma_MockRecord_keyB = 1;
    $d->FK_relationGamma_MockRecord_keyC = 1;
    $with->add(new MockMinRecord($d));
    $d = new \stdClass();
    $d->FK_relationGamma_MockRecord_keyA = 1;
    $d->FK_relationGamma_MockRecord_keyB = 1;
    $d->FK_relationGamma_MockRecord_keyC = 1;
    $with->add(new MockMinRecord($d));
    $d = new \stdClass();
    $d->FK_relationGamma_MockRecord_keyA = 1;
    $d->FK_relationGamma_MockRecord_keyB = 2; // Inconsistency 
    $d->FK_relationGamma_MockRecord_keyC = 1;
    $inconsistent = new MockMinRecord($d);
    $with->add($inconsistent);
    $with->add(new MockMinRecord());
    // Create Relation SHOULD throw Exception.
    try {
      $testObject = new MockRelationB(Str::set('relationGamma'), $this->record, $with);
    } catch (\InvalidArgumentException $expected) {
      $this->assertEquals(
        'Argument 3($with) contains inconsistent foreign key (' . $inconsistent->id . ')',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('InvalidArgumentException NOT thrown!');
  }
}