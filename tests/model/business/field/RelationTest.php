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

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/URNQueryEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/key/Key.class.php';
require_once '/usr/share/php/ramp/model/business/key/Foreign.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/key/Primary.class.php';
require_once '/usr/share/php/ramp/model/business/field/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/TinyInt.class.php';
require_once '/usr/share/php/ramp/http/Request.class.php';

require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/MockField.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/ToRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/FromRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/MockBusinessModelManager.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\FailedValidationException;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\field\Relation;

use tests\ramp\model\business\field\mocks\RelationTest\MockField;
use tests\ramp\model\business\field\mocks\RelationTest\MockBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\field\Relation.
 */
class RelationTest extends \PHPUnit\Framework\TestCase
{
  private $modelManager;
  private $testObjectAlpha;
  private $testObjectBeta;
  private $dataObject;
  private $fromRecord;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    MockField::reset();
    MockBusinessModelManager::reset();
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\field\mocks\RelationTest';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\field\mocks\RelationTest\MockBusinessModelManager';
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->fromRecord = $this->modelManager->getBusinessModel(new SimpleBusinessModelDefinition(Str::set('FromRecord'), Str::set('3')));
    $this->testObjectAlpha = $this->fromRecord->relationAlpha;
    $this->testObjectBeta = $this->fromRecord->relationBeta;
  }

  /**
    * Collection of assertions for \ramp\model\business\field\Relation::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\field\Field}
   * - assert is instance of {@link \ramp\model\business\field\Relation}
   * @link ramp.model.business.field.Relation ramp\model\business\field\Relation
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObjectAlpha);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObjectBeta);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObjectAlpha);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObjectBeta);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObjectAlpha);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObjectBeta);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObjectAlpha);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObjectBeta);
    $this->assertInstanceOf('\Countable', $this->testObjectAlpha);
    $this->assertInstanceOf('\Countable', $this->testObjectBeta);
    $this->assertInstanceOf('\ArrayAccess', $this->testObjectAlpha);
    $this->assertInstanceOf('\ArrayAccess', $this->testObjectBeta);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectAlpha);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta);
    $this->assertInstanceOf('\ramp\model\business\field\Relation', $this->testObjectAlpha);
    $this->assertInstanceOf('\ramp\model\business\field\Relation', $this->testObjectBeta);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned id value matches that of related {@link BusinessModel}.
   * - assert returned id value matches expected result.
   * @link ramp.model.business.field.Relation#method_get_id ramp\model\business\field\Relation::id
   */
  public function testGet_id()
  {
    try {
      $this->testObjectAlpha->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObjectAlpha) . '->id is NOT settable', $expected->getMessage());
      try {
        $this->testObjectBeta->id = "ID";
      } catch (PropertyNotSetException $expected) {
        $this->assertSame(get_class($this->testObjectBeta) . '->id is NOT settable', $expected->getMessage());
      
        $this->assertInstanceOf('\ramp\core\Str', $this->testObjectAlpha->id);
        $this->assertInstanceOf('\ramp\core\Str', $this->testObjectBeta->id);
        $this->assertEquals('from-record:3:relation-alpha', (string)$this->testObjectAlpha->id);
        $this->assertEquals('from-record:3:relation-beta', (string)$this->testObjectBeta->id);
        return;
      }
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.Relation#method_get_type ramp\model\business\field\Relation::type
   */
  public function testGet_type()
  {
    try {
      $this->testObjectAlpha->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObjectAlpha) . '->type is NOT settable', $expected->getMessage());
      try {
        $this->testObjectBeta->type = "TYPE";
      } catch (PropertyNotSetException $expected) {
        $this->assertSame(get_class($this->testObjectBeta) . '->type is NOT settable', $expected->getMessage());

        $this->assertInstanceOf('\ramp\core\Str', $this->testObjectAlpha->type);
        $this->assertInstanceOf('\ramp\core\Str', $this->testObjectBeta->type);
        $this->assertEquals('relation field', (string)$this->testObjectAlpha->type);
        $this->assertEquals('relation field', (string)$this->testObjectBeta->type);
        return;
      }
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through expected fields of either related record
   *   or primaryKey fields where a link is yet to be established.
   * @link ramp.model.business.field.Relation#method_getIterator ramp\model\business\field\Relation::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObjectAlpha->getIterator());
    $i = 0;
    foreach ($this->testObjectAlpha as $property) {
      $this->assertInstanceOf('\ramp\model\business\field\Field', $property);
      $this->assertSame('to-record:1|1|1', (string)$property->parentRecord->id);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[$i], $property);
      $i++;
    }
    $this->assertSame(4, $i);
    $this->assertInstanceOf('\Traversable', $this->testObjectBeta->getIterator());
    $i = 0;
    foreach ($this->testObjectBeta as $property) {
      $this->assertInstanceOf('\ramp\model\business\field\Field', $property);
      $this->assertInstanceOf('\ramp\model\business\key\Composite', $property);
      $this->assertSame('from-record:3', (string)$property->parentRecord->id);
      $i++;
    }
    $this->assertSame(3, $i);
    $this->fromRecord->validate(PostData::build(array(
      'from-record:3:relation-beta' => array('key-c' => 3, 'key-b' => 2, 'key-a' => 1)
    )));
    $this->assertSame('1|2|3', MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertInstanceOf('\Traversable', $this->testObjectBeta->getIterator());
    $i = 0;
    foreach ($this->testObjectBeta as $property) {
      $this->assertInstanceOf('\ramp\model\business\field\Field', $property);
      $this->assertSame('to-record:1|2|3', (string)$property->parentRecord->id);
      $this->assertSame(MockBusinessModelManager::$relatedObjectTwo[$i], $property);
      $i++;
    }
    $this->assertSame(4, $i);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::containingRecord.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'containingRecord'
   * - assert property 'containingRecord' is gettable.
   * - assert returned Record matches Record as provided construct.
   * @link ramp.model.business.field.Relation#method_get_containingRecord ramp\model\business\field\Relation::containingRecord
   */
  public function testGet_containingRecord()
  {
    try {
      $this->testObjectAlpha->parentRecord = $this->fromRecord;
    } catch (PropertyNotSetException $expected) {
      try {
        $this->testObjectBeta->parentRecord = $this->fromRecord;
      } catch (PropertyNotSetException $expected) {
        $this->assertSame($this->fromRecord, $this->testObjectAlpha->parentRecord);
        $this->assertSame($this->fromRecord, $this->testObjectBeta->parentRecord);
        return;
      }
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link ramp.model.business.field.Relation#method_offsetGet ramp\model\business\field\Relation::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObjectAlpha[4];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectAlpha[0]);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[0], $this->testObjectAlpha[0]);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectAlpha[1]);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[1], $this->testObjectAlpha[1]);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectAlpha[2]);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[2], $this->testObjectAlpha[2]);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectAlpha[3]);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[3], $this->testObjectAlpha[3]);
      try {
        $this->testObjectBeta[4];
      } catch (\OutOfBoundsException $expected) {
        $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[0]);
        $this->assertInstanceOf('\ramp\model\business\key\Composite', $this->testObjectBeta[0]);
        $this->assertSame('from-record:3:relation-beta[key-a]', (string)$this->testObjectBeta[0]->id);
        $this->assertNull($this->testObjectBeta[0]->value);
        $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[1]);
        $this->assertInstanceOf('\ramp\model\business\key\Composite', $this->testObjectBeta[1]);
        $this->assertSame('from-record:3:relation-beta[key-b]', (string)$this->testObjectBeta[1]->id);
        $this->assertNull($this->testObjectBeta[1]->value);
        $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[2]);
        $this->assertInstanceOf('\ramp\model\business\key\Composite', $this->testObjectBeta[2]);
        $this->assertSame('from-record:3:relation-beta[key-c]', (string)$this->testObjectBeta[2]->id);
        $this->assertNull($this->testObjectBeta[2]->value);

        $this->assertNull($this->testObjectBeta->validate(PostData::build(array(
          'from-record:3:relation-beta' => array('key-c' => 3, 'key-b' => 2, 'key-a' => 1)
        ))));
        try {
          $this->testObjectBeta[4];
        } catch (\OutOfBoundsException $expected) {
          $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[0]);
          $this->assertSame(MockBusinessModelManager::$relatedObjectTwo[0], $this->testObjectBeta[0]);
          $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[1]);
          $this->assertSame(MockBusinessModelManager::$relatedObjectTwo[1], $this->testObjectBeta[1]);
          $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[2]);
          $this->assertSame(MockBusinessModelManager::$relatedObjectTwo[2], $this->testObjectBeta[2]);
          $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectBeta[3]);
          $this->assertSame(MockBusinessModelManager::$relatedObjectTwo[3], $this->testObjectBeta[3]);
          return;
        }
      }
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link ramp.model.business.field.Relation#method_offsetExists ramp\model\business\field\Relation::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObjectAlpha[0]));
    $this->assertTrue(isset($this->testObjectAlpha[1]));
    $this->assertTrue(isset($this->testObjectAlpha[2]));
    $this->assertTrue(isset($this->testObjectAlpha[3]));
    $this->assertFalse(isset($this->testObjectAlpha[4])); // Not a property

    $this->assertTrue(isset($this->testObjectBeta[0]));
    $this->assertTrue(isset($this->testObjectBeta[1]));
    $this->assertTrue(isset($this->testObjectBeta[2]));
    $this->assertFalse(isset($this->testObjectBeta[3])); // Not a ForeignKey
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation:offsetSet and
   * for \ramp\model\business\BusinessModel::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - assert successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.field.Relation#method_offsetSet ramp\model\business\field\Relation::offsetSet()
   * @link ramp.model.business.field.Relation#method_offsetUnset ramp\model\business\field\Relation::offsetUnset()
   */
  public function testOffsetSetOffsetUnset()
  {
    $object =  new MockField(Str::set('propertNew'), MockBusinessModelManager::$relatedObjectOne);
    $this->testObjectAlpha[2] = $object; // New property
    $this->assertSame($object, $this->testObjectAlpha[2]);
    unset($this->testObjectAlpha[2]);
    $this->assertFalse(isset($this->testObjectAlpha[2]));
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::value.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.Relation#method_get_value ramp\model\business\field\Relation::value
   */
  public function testGet_value()
  {
    try {
      $this->testObjectAlpha->value = '2|2|2';
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(MockBusinessModelManager::$fromDataObject->FK_relationAlpha, $this->testObjectAlpha->value);
      $this->assertSame('1|1|1', $this->testObjectAlpha->value);
      MockBusinessModelManager::$fromDataObject->FK_relationAlpha = '2|2|2';
      $this->assertSame(MockBusinessModelManager::$fromDataObject->FK_relationAlpha, $this->testObjectAlpha->value);
      $this->assertSame('2|2|2', $this->testObjectAlpha->value);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::validate() where PostData
   * does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *    matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert validate method is NOT propagated through to its children and grandchildren.
   * - assert throws \ramp\model\business\DataFetchException when provided value in NOT the key to a Valid BusinessModel in our data store.
   *   - with message: <em>'Relation NOT found in data storage!'</em>
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateProcessValidationRuleNotCalled()
  {
    $this->assertNull($this->testObjectAlpha->validate(new PostData()));
    $this->assertSame(0, MockField::$processValidationRuleCount);
 }

  /**
   * Further collection of assertions for \ramp\model\business\field\Relation::validate(), where
   * PostData contains an InputDataCondition with an attribute that matches the related BusinessModel.
   * - assert returns void (null) when called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the related BusinessModel, then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the related BusinessModel and its processValidationRule method is called and passes, then its
   *    containingRecord setPropertyMethod is called.
   * - assert validate method is propagated through to its children and grandchildren.
   * @link ramp.model.business.field\Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateProcessValidationRuleCalled()
  {
    $this->assertNull($this->testObjectAlpha->validate(PostData::build(array(
      'to-record:1|1|1:property' => 'GOOD'
    ))));
    $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame('GOOD', MockBusinessModelManager::$relatedDataObjectOne->property);
  }
  
  /**
   * Collection of assertions for \ramp\model\business\field\Relation::processValidationRule() where PostData
   * does NOT contain a valid key that relates to a BusinessModel entry in the data store.
   * - assert throws \ramp\model\business\FailedValidationException when provided value of key is NOT an int.
   *   - with message: <em>'Relation Key NOT valid!'</em>
   * - assert throws \ramp\model\business\FailedValidationException when provided value in NOT the key to a Valid BusinessModel in our data store.
   *   - with message: <em>'Relation NOT found in data storage!'</em>
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateProcessFailedValidationException()
  {
    try {
      $this->testObjectAlpha->processValidationRule(3);
    } catch (FailedValidationException $expected) {
      $this->assertSame('Relation NOT found in data storage!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::processValidationRule() where PostData
   * changes the valid key to another that relates to a BusinessModel entry in the data store.
   * - assert expected values both prior to validation and post validation.
   * - assert parent record isModifed flag TRUE post validation.
   * - assert relevant modelManager updateAny method updates stored record.
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateRelationUpdatedAlpha()
  {
    $this->assertSame('1|1|1', MockBusinessModelManager::$fromDataObject->FK_relationAlpha);
    $this->assertNull($this->testObjectAlpha->validate(PostData::build(array(
      'from-record:3:relation-alpha' => array('key-c' => 3, 'key-b' => 2, 'key-a' => 1)
    ))));
    $this->assertSame('1|2|3', MockBusinessModelManager::$fromDataObject->FK_relationAlpha);
    $this->assertTrue($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );
    $this->assertFalse($this->fromRecord->isModified);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::processValidationRule() where PostData
   * contains a valid key that relates to a BusinessModel entry in the data store where no relation previously existed.
   * - assert expected values both prior (NULL) to validation and post validation (new valid key).
   * - assert parent record isModifed flag TRUE post validation
   * - assert relevant modelManager updateAny method updates stored record.
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateRelationUpdatedBeta()
  {
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertNull($this->testObjectBeta->validate(PostData::build(array(
      'from-record:3:relation-beta' => array('key-c' => 1, 'key-b' => 1, 'key-a' => 1)
    ))));
    $this->assertSame('1|1|1', MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertTrue($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );
    $this->assertFalse($this->fromRecord->isModified);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::processValidationRule()
   * where PostData contains to few argument for a valid key.
   * - assert expected values both prior (NULL) to validation and no change post validation.
   * - assert parent record isModifed flag FALSE post validation
   * - assert relevant modelManager updateAny method DOES NOT updates stored record.
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateRelationMismachedKeyCount()
  {
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertNull($this->testObjectBeta->validate(PostData::build(array(
      'from-record:3:relation-beta' => array('key-b' => 1, 'key-c' => 1)
    ))));
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertFalse($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayNotHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::processValidationRule()
   * where PostData contains a key with no value, NULL or empty string.
   * - assert expected values both prior (NULL) to validation and no change post validation.
   * - assert parent record isModifed flag FALSE post validation
   * - assert relevant modelManager updateAny method DOES NOT updates stored record.
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateRelationEmptyKey()
  {
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertNull($this->testObjectBeta->validate(PostData::build(array(
      'from-record:3:relation-beta' => array('key-c', 'key-b' => 1, 'key-a' => 1)
    ))));
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertFalse($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayNotHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );

    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertNull($this->testObjectBeta->validate(PostData::build(array(
      'from-record:3:relation-beta' => array('key-c' => NULL, 'key-b' => 1, 'key-a' => 1)
    ))));
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertFalse($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayNotHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );

    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertNull($this->testObjectBeta->validate(PostData::build(array(
      'from-record:3:relation-beta' => array('key-c' => "", 'key-b' => 1, 'key-a' => 1)
    ))));
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationBeta);
    $this->assertFalse($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayNotHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::processValidationRule()
   * where PostData contains an unset flag.
   * - assert expected values both prior to validation (valid key), with NULL post validation.
   * - assert parent record isModifed flag TRUE post validation.
   * - assert related children revert to ForeignKeyParts.
   * - assert relevant modelManager updateAny method updates stored record.
   * @link ramp.model.business.field.Relation#method_validate ramp\model\business\field\Relation::validate()
   */
  public function testValidateRelationUnset()
  {
    $this->assertSame('1|1|1', MockBusinessModelManager::$fromDataObject->FK_relationAlpha);
    $this->assertArrayNotHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );
    $this->assertNull($this->fromRecord->validate(PostData::build(array(
      'from-record:3:relation-alpha' => array('unset' => 'on', 'key-a' => 1, 'key-b' => 1, 'key-c' => 1)
    ))));
    $this->assertNull(MockBusinessModelManager::$fromDataObject->FK_relationAlpha);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObjectAlpha[0]);
    $this->assertInstanceOf('\ramp\model\business\key\Composite', $this->testObjectAlpha[0]);
    $this->assertTrue($this->fromRecord->isModified);
    $this->modelManager->updateAny();
    $this->assertArrayHasKey(
      'tests\ramp\model\business\field\mocks\RelationTest\FromRecord:3',
      MockBusinessModelManager::$updateLog
    );
    $this->assertFalse($this->fromRecord->isModified);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::hasErrors().
   * - assert returns False when PostData does NOT contain an InputDataCondition with an attribute
   *   that matches the testObject's id.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert does NOT propagates through to its child/grandchild.
   * @link ramp.model.business.field.Relation#method_hasErrors ramp\model\business\field\Relation::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObjectAlpha->validate(new PostData()));
    $this->assertFalse($this->testObjectAlpha->hasErrors);
    $this->assertSame(0, MockField::$processValidationRuleCount);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::getErrors().
   * - assert returns an empty iCollection when PostData does NOT contain an InputDataCondition
   *   with an attribute that matches the testObject's id or it related BusinesModel.
   * - assert when provided PostData does NOT contain an InputDataCondition with an attribute that matches
   *   the testObject's id or it related BusinesModel then its processValidationRule method, is NOT called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the related BusinessModel then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method is called and fails, throwing a
   *    FailedValidationException then its message is added to its errorCollection for retrieval
   *    by its hasErrors and getErrors methods.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the related BusinessModel and its processValidationRule method is called and fails, throwing a
   *    FailedValidationException then its message is added to its errorCollection for retrieval
   *    by its hasErrors and getErrors methods.
   * - assert following validate(), the expected iCollection of error messages are returned.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * @link ramp.model.business.field.Relation#method_getErrors ramp\model\business\field\Relation::getErrors()
   */
  public function testGetErrors()
  {
    // PostData does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObjectAlpha->validate(new PostData()));
    $this->assertFalse($this->testObjectAlpha->hasErrors);
    $errors = $this->testObjectAlpha->errors;
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
    $this->assertSame(0, $errors->count);
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObjectAlpha->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));

    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObjectAlpha->validate(PostData::build(array(
      'from-record:3:relationAlpha' => array('key-c' => 3, 'key-b' => 2, 'key-a' => 'BAD')
    ))));
    // $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame('1|1|1', MockBusinessModelManager::$fromDataObject->FK_relationAlpha);
    $errors = $this->testObjectAlpha->errors;
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
    $this->assertSame(1, $errors->count);
    $this->assertSame('Relation NOT found in data storage!', (string)$errors[0]);
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObjectAlpha->errors;
    $this->assertEquals($errors, $secondCallOnErrors);
    $this->assertTrue(isset($secondCallOnErrors[0]));

    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObjectAlpha->validate(PostData::build(array(
      'from-record:3:relationAlpha' => array('key-c' => 3, 'key-b' => 2, 'key-a' => 1)
    ))));
    // $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame('1|2|3', MockBusinessModelManager::$fromDataObject->FK_relationAlpha);
    $this->assertFalse($this->testObjectAlpha->hasErrors);
    $errors = $this->testObjectAlpha->errors;
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
    $this->assertSame(0, $errors->count);
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObjectAlpha->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::count.
   * - assert return expected int value of related BusinessModel's number of child.
   * @link ramp.model.business.field.Relation#method_count ramp\model\business\field\Relation::count
   */
  public function testCount()
  {
    $this->assertSame(4 ,$this->testObjectAlpha->count);
    $this->assertSame(3 ,$this->testObjectBeta->count);
  }
}
