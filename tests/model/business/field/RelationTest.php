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
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/TinyInt.class.php';
require_once '/usr/share/php/ramp/http/Request.class.php';

require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/MockField.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/ContainingRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/RelationTest/MockBusinessModelManager.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\FailedValidationException;
use ramp\model\business\field\Relation;
use ramp\model\business\validation\dbtype\TinyInt;

use tests\ramp\model\business\field\mocks\RelationTest\MockField;
use tests\ramp\model\business\field\mocks\RelationTest\MockRecord;
use tests\ramp\model\business\field\mocks\RelationTest\ContainingRecord;
use tests\ramp\model\business\field\mocks\RelationTest\MockBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\field\Field.
 * TODO:mrenyard: Change Record to change visability of PrimaryKeys only on 'new' all but PKs ediatable on added
 * TODO:mrenyard: Add a new property to be tested of URL for all Field
 * URL = the url for Relation to equal that of related object.
 * TODO:mrenyard: Need to look how many-to-many will work.  
 */
class RelationTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $dataObject;
  private $containingRecord;
  private $relationObjectTableName;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\field\mocks\RelationTest';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\field\mocks\RelationTest\MockBusinessModelManager';
    $this->dataObject = new \stdClass();
    $this->dataObject->key = 3;
    $this->dataObject->relationAlpha = 1;
    $this->containingRecord = new ContainingRecord($this->dataObject);
    $this->testObject = $this->containingRecord->relationAlpha;
    MockField::reset();
  }

  /**
    * Collection of assertions for \ramp\model\business\field\Relation::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\field\Field}
   * - assert is instance of {@link \ramp\model\field\Relation}
   * @link ramp.model.business.field.Relation ramp\model\business\field\Relation
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\Relation', $this->testObject);
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
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertEquals('containing-record:3:relation-alpha', (string)$this->testObject->id);
      return;
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
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertEquals('relation field', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert returned object is {@link \Traversable} relatedObject.
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link ramp.model.business.field.Relation#method_getIterator ramp\model\business\field\Relation::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    // $this->assertSame(MockBusinessModelManager::$relatedObjectOne, $this->testObject->getIterator());
    $i = 0;
    foreach ($this->testObject as $property) {
      $this->assertInstanceOf('\ramp\model\business\field\Field', $property);
      if ($i == 0) { $this->assertSame(MockBusinessModelManager::$relatedObjectOne[-1], $property); }
      if ($i == 1) { $this->assertSame(MockBusinessModelManager::$relatedObjectOne[1], $property); }
      $i++;
    }

    $this->dataObject->relationBeta = Str::NEW();
    $testObjectBeta = new Relation(Str::set('relationBeta'), $this->containingRecord, Str::set('MockRecord'));
    $relatedRecord = $testObjectBeta->getIterator();
    // $this->assertInstanceOf('tests\ramp\model\business\field\mocks\RelationTest\MockRecord', $relatedRecord);
    // $this->assertSame('mock-record:new', (string)$relatedRecord->id);

    // $this->dataObject->relationGamma = NULL;
    // $testObjectGamma = new Relation(Str::set('relationGamma'), $this->containingRecord, Str::set('MockRecord'));
    // $relatedField = $testObjectGamma->getIterator();
    // $this->assertInstanceOf('ramp\model\business\field\Link', $relatedField);
    // $this->assertSame('mock-record:new', (string)$relatedRecord->id);
    // $testObjectGamma->getIterator();
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
      $this->testObject->containingRecord = $this->containingRecord;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->containingRecord, $this->testObject->containingRecord);
      return;
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
      $this->testObject[0];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject[-1]);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[-1], $this->testObject[-1]);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject[1]);
      $this->assertSame(MockBusinessModelManager::$relatedObjectOne[1], $this->testObject[1]);
      return;
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
    $this->assertFalse(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[-1]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertFalse(isset($this->testObject[2])); // Not a property
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
    $this->testObject[2] = $object; // New property
    $this->assertSame($object, $this->testObject[2]);
    unset($this->testObject[2]);
    $this->assertFalse(isset($this->testObject[2]));
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
      $this->testObject->value = 2;
    } catch (PropertyNotSetException $expected) {
      $this->dataObject->relationAlpha = 2;
      $this->assertSame($this->dataObject->relationAlpha, $this->testObject->value);
      $this->assertSame(2, $this->testObject->value);
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
    $this->assertNull($this->testObject->validate(new PostData()));
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
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:1:property' => 'GOOD'
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
      $this->testObject->processValidationRule(3);
    } catch (FailedValidationException $expected) {
      $this->assertSame('Relation NOT found in data storage!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }

  /**
   * 
   */
  public function testValidateRelationUpdated()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'containing-record:3:relationAlpha' => 2
    ))));
    $this->assertSame(2, $this->dataObject->relationAlpha);
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'containing-record:3:relationAlpha' => 1
    ))));
    $this->assertSame(1, $this->dataObject->relationAlpha);
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
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
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
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
    $this->assertSame(0, $errors->count);
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));

    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'containing-record:3:relationAlpha' => 'BAD'
    ))));
    // $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame(1, $this->dataObject->relationAlpha);
    $errors = $this->testObject->errors;
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
    $this->assertSame(1, $errors->count);
    $this->assertSame('Relation NOT found in data storage!', (string)$errors[0]);
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($errors, $secondCallOnErrors);
    $this->assertTrue(isset($secondCallOnErrors[0]));

    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'containing-record:3:relationAlpha' => 2
    ))));
    // $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame(2, $this->dataObject->relationAlpha);
    $this->assertFalse($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
    $this->assertSame(0, $errors->count);
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));

    $this->assertNull($this->testObject->validate(PostData::build(array(
      'containing-record:3:relationAlpha' => 1
    ))));
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Relation::count.
   * - assert return expected int value of related BusinessModel's number of child.
   * @link ramp.model.business.field.Relation#method_count ramp\model\business\field\Relation::count
   */
  public function testCount()
  {
    $this->assertSame(2 ,$this->testObject->count);
  }
}
