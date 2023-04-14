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
namespace tests\ramp\model\business;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectMany.class.php';
require_once '/usr/share/php/ramp/model/business/field/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';

require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/ConcreteRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/ConcreteRecordMultiKey.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/ConcreteValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/ConcreteOptionList.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\field\Input;
use ramp\model\business\field\SelectOne;
use ramp\model\business\field\SelectMany;
use ramp\model\business\validation\dbtype\VarChar;

use tests\ramp\model\business\mocks\RecordTest\MockBusinessModelManager;
use tests\ramp\model\business\mocks\RecordTest\ConcreteRecord;
use tests\ramp\model\business\mocks\RecordTest\ConcreteRecordMultiKey;
use tests\ramp\model\business\mocks\RecordTest\ConcreteValidationRule;
use tests\ramp\model\business\mocks\RecordTest\ConcreteOptionList;
use tests\ramp\model\business\mocks\RecordTest\ConcreteOption;

/**
 * Collection of tests for \ramp\model\business\Record.
 */
class RecordTest extends \PHPUnit\Framework\TestCase
{
  private $dataObject;
  private $testObject;
  private $testObjectProperties;
  private $concreteRecordpropertyNames;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\mocks\RecordTest\MockBusinessModelManager';
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\mocks\RecordTest';
    $this->dataObject = new \stdClass();
    $this->testObject = new ConcreteRecord($this->dataObject);
    $this->concreteRecordPropertyCount = 4;
    $this->concreteRecordPropertyNames = array(0 => 'propertyA', 1 => 'property1', 2 => 'property2', 3 => 'alphRelation');
    $this->concreteRecordProperties = new Collection(Str::set('ramp\model\business\field\Field'));
    $className = __NAMESPACE__ . '\mocks\RecordTest\ConcreteRecord';
    foreach (get_class_methods($className) as $methodName)
    {
      if (strpos($methodName, 'get_') === 0)
      {
        foreach (get_class_methods('\ramp\model\business\Record') as $parentMethod)
        {
          if ($methodName == $parentMethod) { continue 2; }
        }
        $property = str_replace('get_', '', $methodName);
        $this->concreteRecordProperties->add($this->testObject->$property);
      }
    }
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\model\business\Record}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert dataObject provided at construction is now populated with NULL valued properties
   *   that match the testObjects (Record) get_ methods.
   * @link ramp.model.business.Record ramp\model\business\Record
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $i = 0;
    $properties = get_object_vars($this->dataObject);
    foreach ($properties as $name => $value)
    {
      if ($i == 3) {
        $this->assertEquals($this->concreteRecordPropertyNames[$i++] . 'KEY', $name);
        $this->assertNull($value);
        continue;
      }
      $this->assertEquals($this->concreteRecordPropertyNames[$i++], $name);
      $this->assertNull($value);
    }
    $this->assertEquals($i, count($properties));
    $this->assertEquals($this->concreteRecordPropertyCount, $i);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result, in the format:
   *   - lowercase and hypenated colon seperated [class-name]:[key].
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame('concrete-record:new', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::primarykey.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'primarykey'
   * - assert property 'primarykey' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result value of 'new' when new.
   * - assert returned value matches expected result of relevant property.
   * - assert returned value matches expected result of relevant multiple properties
   *   bar [|] seperated when Object has a multipart primary key.
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::primarykey
   */
  public function testGet_primaryKey()
  {
    try {
      $this->testObject->primarykey = "KEY";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->primarykey is NOT settable', $expected->getMessage());

      $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject->primarykey);
      $this->assertNull($this->testObject->primarykey->value);

      $testObjectMultiKey = new ConcreteRecordMultiKey($this->dataObject);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $testObjectMultiKey->primarykey);
      $this->assertNull($testObjectMultiKey->primaryKey->value);
      $this->dataObject->propertyA = '1';
      $this->dataObject->propertyB = '2';
      $this->dataObject->propertyC = '3';
      $this->assertSame(
        $this->dataObject->propertyA . '|' . $this->dataObject->propertyB . '|' . $this->dataObject->propertyC,
        $testObjectMultiKey->primarykey->value
      );
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.Record#method_get_type ramp\model\business\Record::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertSame('concrete-record record', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop iterates through, and on each iteration, returns a relivant
   *   {@link field\Field} as defined by the relevant property of the testObject (Record).
   * @link ramp.model.business.Record#method_getIterator ramp\model\business\Record::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->concreteRecordProperties->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child)
    {
      $this->assertSame($child, $iterator->current());
      $this->assertEquals(Str::hyphenate(Str::set('concrete-record:new:' . $this->concreteRecordPropertyNames[$i++])), (string)$child->id);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $child);
      $iterator->next();
    }
    $this->assertEquals($this->concreteRecordProperties->count, $i);
    $properties = get_object_vars($this->dataObject);
    $this->assertEquals($i, count($properties));
    $this->assertEquals($this->concreteRecordPropertyCount, $i);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index [(propertyname)].
   * @link ramp.model.business.Record#method_offsetGet ramp\model\business\Record::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      for ($i=0; $i < $this->concreteRecordProperties->count; $i++)
      {
        $this->assertInstanceOf(
          '\ramp\model\business\field\Field', $this->testObject[$i]
        );
        $this->assertSame(
          $this->concreteRecordProperties[$i],
          $this->testObject[$i]
        );
      }
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::offsetExists.
   * - assert True returned on isset() when indexed with valid index.
   * - assert False returned on isset() when indexed with invalid index.
   * @link ramp.model.business.Record#method_offsetExists ramp\model\business\Record::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertTrue(isset($this->testObject[3]));
    $this->assertFalse(isset($this->testObject[4]));
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::offsetSet and
   * for \ramp\model\business\Record::offsetUnset.
   * - assert adding anything other that property fields through offsetSet fails, and even then
   *   they MUST meet strict criteria for propertyName index and object type or it throws a
   *   BadMethodCallException with the message:
   *   - *Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!*
   * - assert unsetting properties already populated with a valid value throws a
   *   BadMethodCallException with the message:
   *   - *Unsetting properties already populated with a valid value NOT alowed!*
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - assert successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.Record#method_offsetSet ramp\model\business\Record::offsetSet()
   * @link ramp.model.business.Record#method_offsetSet ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetUnset()
  {
    $object = new Input(
      Str::set('property3'),
      $this->testObject,
      new VarChar(
        10,
        new ConcreteValidationRule(),
        Str::set('My error message HERE!')
      )
    );
    $this->testObject[3] = $object;
    $this->assertSame($object, $this->testObject[3]);
    unset($this->testObject[3]);
    $this->assertFalse(isset($this->testObject[3]));
    try {
    $this->testObject[4] = new ConcreteValidationRule();
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!',
        $expected->getMessage()
      );
      $property4 = $this->testObject[4] = new Input(
        Str::set('property4'),
        $this->testObject,
        new VarChar(
          10,
          new ConcreteValidationRule(),
          Str::set('My error message HERE!')
        )
      );
      $this->testObject->setPropertyValue('property4', 'GOOD');
      $this->assertEquals('GOOD', $this->dataObject->property4);
      try {
        unset($this->testObject['property4']);
      } catch (\OutOfBoundsException $expected) {
        $this->assertEquals(
          // 'Unsetting properties already populated with a valid value NOT alowed!',
          'Value is not a valid index.',
          $expected->getMessage()
        );
        return;
      }
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions to check 'new', 'complete' record validation 
   * (\ramp\model\business\Record::validate()) of \ramp\model\business\Record::primarykey.
   * - assert property 'primarykey' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result value of 'new' when new.
   * - assert returned value matches expected result of relevant property.
   * - assert returned value matches expected result of relevant multiple properties
   *   bar [|] seperated when Object has a multipart primary key.
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::primarykey
   */
  public function testPrimaryKeyValidation()
  {
    $dataObject = new \stdClass();
    $testObjectMultiKey = new ConcreteRecordMultiKey($dataObject);
    $this->assertSame(4, $testObjectMultiKey->count);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $testObjectMultiKey->primarykey);
    $this->assertNull($testObjectMultiKey->primaryKey->value);
    $this->assertSame(4, $testObjectMultiKey->count);
    $currentCallCount = MockBusinessModelManager::$callCount;
    $testObjectMultiKey->validate(PostData::build(array(
      'concrete-record-multi-key:new:property-a' => '1',
      'concrete-record-multi-key:new:property-b' => '2',
      'concrete-record-multi-key:new:property-c' => '3'
    )));
    $this->assertSame(1, $testObjectMultiKey->propertyA->value);
    $this->assertSame(2, $testObjectMultiKey->propertyB->value);
    $this->assertSame(3, $testObjectMultiKey->propertyC->value);
    $this->assertSame('1|2|3', $testObjectMultiKey->primaryKey->value);
    $this->assertSame(($currentCallCount + 1), MockBusinessModelManager::$callCount);
    $this->assertTrue($testObjectMultiKey->isModified);
    $this->assertTrue($testObjectMultiKey->isValid);
    $this->assertTrue($testObjectMultiKey->isNew);
    $this->assertTrue($testObjectMultiKey->hasErrors);
    $errors = $testObjectMultiKey->errors;
    $this->assertSame(1, $errors->count);
    $this->assertSame('An entry already exists for this record!', (string)$errors[0]);
    $this->assertSame(4, $testObjectMultiKey->count);
    $testObjectMultiKey->updated();
    $this->assertSame('concrete-record-multi-key:1|2|3', (string)$testObjectMultiKey->id);
    // TODO:mrenyard: Add check ModelManager called from field\PrimaryKey

    // $getBusinessModelCount = MockBusinessModelManager::$callCount;
    // $this->dataObject->aProperty = 4;
    // $this->dataObject->bProperty = 5;
    // $this->dataObject->cProperty = 6;
    // $this->assertTrue($this->mockRecord->isNew);
    // $this->assertTrue($this->mockRecord->isValid);
    // $this->testObject->Validate(new PostData());
    // $this->assertSame(++$getBusinessModelCount, MockBusinessModelManager::$callCount);
    // $this->assertFalse($this->testObject->hasErrors);
    // $errors = $this->testObject->errors;
    // $this->assertSame(0, $errors->count);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::validate(),
   * \ramp\model\business\Record::hasErrors() and \ramp\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link ramp.model.business.Record#method_validate ramp\model\business\Record::validate()
   * @link ramp.model.business.Record#method_hasErrors ramp\model\business\Record::hasErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::getErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::isModified()
   */
  public function testValidateHasGetErrorsNewAllGood()
  {
    $selection = array(1,4,6);
    $_POST1 = array(
      'concrete-record:new:property-a' => 'key',
      'concrete-record:new:property-1' => 3,
      'concrete-record:new:property-2' => $selection
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST1)));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertEquals(0, $this->testObject->errors->count);
    $this->assertEquals(0, $this->testObject->propertyA->errors->count);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertEquals('key', $dataObjectProperties['propertyA']);
    $this->assertSame($dataObjectProperties['propertyA'], $this->testObject->propertyA->value);
    $this->assertEquals(3, $dataObjectProperties['property1']);
    $this->assertSame((string)$dataObjectProperties['property1'], (string)$this->testObject->property1->value->id);    
    $this->assertEquals($selection, $dataObjectProperties['property2']);
    foreach ($this->testObject->property2->value as $item) {
      $this->assertSame((string)array_shift($selection), (string)$item->id);
    }
    $this->assertTrue($this->testObject->isModified);
    $this->assertNull($this->testObject->validate(PostData::build($_POST1)));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertEquals(0, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::validate(),
   * \ramp\model\business\Record::hasErrors() and \ramp\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link ramp.model.business.Record#method_validate ramp\model\business\Record::validate()
   * @link ramp.model.business.Record#method_hasErrors ramp\model\business\Record::hasErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewP1Bad()
  {
    $selection = array(1,2,6);
    $_POST2 = array(
      'concrete-record:new:property-A' => 'BAD',
      'concrete-record:new:property-1' => 4,
      'concrete-record:new:property-2' => $selection
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST2)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(1, $errorMessages->count);
    $this->assertEquals('$value does NOT evaluate to KEY', $errorMessages[0]);
    $this->assertEquals(1, $this->testObject->propertyA->errors->count);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertNull($dataObjectProperties['propertyA']);
    $this->assertSame($dataObjectProperties['propertyA'], $this->testObject->propertyA->value);
    $this->assertEquals('4', $dataObjectProperties['property1']);
    $this->assertSame((string)$dataObjectProperties['property1'], (string)$this->testObject->property1->value->id);
    $this->assertEquals($selection, $dataObjectProperties['property2']);
    foreach ($this->testObject->property2->value as $item) {
      $this->assertSame((string)array_shift($selection), (string)$item->id);
    }
    $this->assertNull($this->testObject->validate(PostData::build($_POST2)));
    $this->assertTrue($this->testObject->hasErrors);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::validate(),
   * \ramp\model\business\Record::hasErrors() and \ramp\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link ramp.model.business.Record#method_validate ramp\model\business\Record::validate()
   * @link ramp.model.business.Record#method_hasErrors ramp\model\business\Record::hasErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewP2Bad()
  {
    $selection = array('1','2','6');
    $_POST3 = array(
      'concrete-record:new:property-a' => 'key',
      'concrete-record:new:property-1' => 7, // BAD - Beyond index
      'concrete-record:new:property-2' => $selection
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST3)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(1, $errorMessages->count);
    $this->assertEquals('Selected value NOT an avalible option!', $errorMessages[0]);
    $this->assertEquals(0, $this->testObject->propertyA->errors->count);
    $this->assertEquals(1, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertEquals('key', $dataObjectProperties['propertyA']);
    $this->assertSame($dataObjectProperties['propertyA'], $this->testObject->propertyA->value);
    $this->assertNull($dataObjectProperties['property1']);
    $this->assertSame('0', (string)$this->testObject->property1->value->id);
    $this->assertSame('Please choose:', (string)$this->testObject->property1->value->description);
    $this->assertEquals($selection, $dataObjectProperties['property2']);
    foreach ($this->testObject->property2->value as $item) {
      $this->assertSame((string)array_shift($selection), (string)$item->id);
    }
    $_POST4 = array(
      'concrete-record:new:property-1' => '8', // BAD - Beyond index
      'concrete-record:new:property-2' => $selection
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST4)));
    $this->assertTrue($this->testObject->hasErrors);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::validate(),
   * \ramp\model\business\Record::hasErrors() and \ramp\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link ramp.model.business.Record#method_validate ramp\model\business\Record::validate()
   * @link ramp.model.business.Record#method_hasErrors ramp\model\business\Record::hasErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewP3Bad()
  {
    $selection = array('1','8','6'); // BAD - Second argument beyond index
    $_POST5 = array(
      'concrete-record:new:property-a' => 'key',
      'concrete-record:new:property-1' => '5',
      'concrete-record:new:property-2' => $selection // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST5)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(1, $errorMessages->count);
    $this->assertEquals(
      'At least one selected value is NOT an available option!',
      $errorMessages[0]
    );
    $this->assertEquals(0, $this->testObject->propertyA->errors->count);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(1, $this->testObject->property2->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertEquals('key', $dataObjectProperties['propertyA']);
    $this->assertSame($dataObjectProperties['propertyA'], $this->testObject->propertyA->value);
    $this->assertEquals('5', $dataObjectProperties['property1']);
    $this->assertSame((string)$dataObjectProperties['property1'], (string)$this->testObject->property1->value->id);
    $this->assertNull($dataObjectProperties['property2']);
    $this->assertSame(0, $this->testObject->property2->value->count);
    $_POST6 = array(
      'concrete-record:new:property-1' => '5',
      'concrete-record:new:property-2' => $selection // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST6)));
    $this->assertTrue($this->testObject->hasErrors);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::validate(),
   * \ramp\model\business\Record::hasErrors() and \ramp\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link ramp.model.business.Record#method_validate ramp\model\business\Record::validate()
   * @link ramp.model.business.Record#method_hasErrors ramp\model\business\Record::hasErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewAllBad()
  {
    $selection = array('1','8','6'); // BAD - Second argument beyond index
    $_POST7 = array(
      'concrete-record:new:property-a' => 'BAD',
      'concrete-record:new:property-1' => '7', // BAD - Beyond index
      'concrete-record:new:property-2' => $selection // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST7)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(3, $errorMessages->count);
    $this->assertEquals('$value does NOT evaluate to KEY', (string)$errorMessages[0]);
    $this->assertEquals(
      'Selected value NOT an avalible option!',
      (string)$errorMessages[1]
    );
    $this->assertEquals(
      'At least one selected value is NOT an available option!',
      $errorMessages[2]
    );
    $this->assertEquals(1, $this->testObject->propertyA->errors->count);
    $this->assertEquals(1, $this->testObject->property1->errors->count);
    $this->assertEquals(1, $this->testObject->property2->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertNull($dataObjectProperties['propertyA']);
    $this->assertSame($dataObjectProperties['propertyA'], $this->testObject->propertyA->value);
    $this->assertNull($dataObjectProperties['property1']);
    $this->assertSame('0', (string)$this->testObject->property1->value->id);
    $this->assertSame('Please choose:', (string)$this->testObject->property1->value->description);
    $this->assertNull($dataObjectProperties['property2']);
    foreach ($this->testObject->property2->value as $item) {
      $this->assertSame((int)array_shift($selection), (int)$item->key);
    }
    $_POST8 = array(
      'concrete-record:new:property-a' => 'BAD',
      'concrete-record:new:property-1' => '7', // BAD - Beyond index
      'concrete-record:new:property-2' => $selection // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST8)));
    $this->assertTrue($this->testObject->hasErrors);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::validate(),
   * \ramp\model\business\Record::hasErrors() and \ramp\model\business\Record::getErrors().
   * - assert PrimaryKey is NOT updated.
   * - assert validate returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link ramp.model.business.Record#method_validate ramp\model\business\Record::validate()
   * @link ramp.model.business.Record#method_hasErrors ramp\model\business\Record::hasErrors()
   * @link ramp.model.business.Record#method_getErrors ramp\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsExistingAllGood()
  {
    $this->dataObject->propertyA = 'pkey';
    $this->dataObject->property1 = 2;
    $this->dataObject->property2 = array('1','2','6');
    $this->assertNull($this->testObject->updated());
    $this->assertFalse($this->testObject->isNew);
    $this->assertTrue($this->testObject->isValid);
    $selection = array('3','4','5');
    $_POST9 = array(
      'concrete-record:pkey:property-1' => '5',
      'concrete-record:pkey:property-2' => $selection
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST9)));
    $this->assertFalse($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(0, $errorMessages->count);
    $this->assertEquals(0, $this->testObject->propertyA->errors->count);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    // NOTE the primaryKey is NOT updated
    $this->assertEquals('pkey', $dataObjectProperties['propertyA']);
    $this->assertSame($dataObjectProperties['propertyA'], $this->testObject->propertyA->value);
    $this->assertEquals('5', $dataObjectProperties['property1']);
    $this->assertSame((string)$dataObjectProperties['property1'], (string)$this->testObject->property1->value->id);
    $this->assertEquals($selection, $dataObjectProperties['property2']);
    foreach ($this->testObject->property2->value as $item) {
      $this->assertSame((string)array_shift($selection), (string)$item->id);
    }
    $_POST10 = array(
      'concrete-record:pkey:property-1' => '5',
      'concrete-record:pkey:property-2' => $selection
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST10)));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertEquals(0, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \ramp\model\business\Record::__set(),
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable
   *   to set undefined or inaccessible property
   * @link ramp.model.business.Record#method__set ramp\model\business\Record::__set()
   */
  public function test__set()
  {
    $this->expectException(PropertyNotSetException::class);
    $this->testObject->propertyB = 1;
  }

   /**
   * Collection of assertions for \ramp\model\business\Record::count.
   * - assert return expected int value related to the number of child Records held.
   * @link ramp.model.business.Record#method_count ramp\model\business\Record::count
   */
  public function testCount()
  {
    $this->assertSame(4 ,$this->testObject->count);
  }
}
