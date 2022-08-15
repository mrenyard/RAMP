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
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';

require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordTest/ConcreteValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordCollectionTest/ConcreteValidationRule2.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordCollectionTest/TestRecordCollection.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RecordCollectionTest/TestRecord.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;

use tests\ramp\model\business\mocks\RecordCollectionTest\TestRecordCollection;
use tests\ramp\model\business\mocks\RecordCollectionTest\TestRecord;

/**
 * Collection of tests for \ramp\model\business\RecordCollection.
 */
class RecordCollectionTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $children;
  private $testChild1;
  private $testChild2;
  private $testChild3;
  private $child1Data;
  private $child2Data;
  private $child3Data;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\mocks\RecordCollectionTest';
    $this->children = new Collection();
    $this->testObject = new TestRecordCollection();
    $this->child1Data = new \stdClass();
    $this->child1Data->keyProperty = 'key1';
    $this->child2Data = new \stdClass();
    $this->child2Data->keyProperty = 'key2';
    $this->child3Data = new \stdClass();
    $this->child3Data->keyProperty = 'key3';
    $this->testChild1 = new TestRecord($this->child1Data);
    $this->testChild2 = new TestRecord($this->child2Data);
    $this->testChild3 = new TestRecord($this->child3Data);
    $this->children->add($this->testChild1);
    $this->children->add($this->testChild2);
    $this->children->add($this->testChild3);
    $this->testObject->add($this->testChild1);
    $this->testObject->add($this->testChild2);
    $this->testObject->add($this->testChild3);
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\RecordCollection}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\core\iCollection}
   * @link ramp.model.business.RecordCollection ramp\model\business\RecordCollection
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\ramp\core\iCollection', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.RecordCollection#method_get_id ramp\model\business\RecordCollection::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame('test-record-collection', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.RecordCollection#method_get_type ramp\model\business\RecordCollection::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertSame('test-record-collection record-collection', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link ramp.model.business.RecordCollection#method_getIterator ramp\model\business\RecordCollection::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->children->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertSame('test-record:key' . ++$i, (string)$child->id);
      $iterator->next();
    }
    $this->assertEquals(3, $i);
    $this->assertSame('test-record-collection', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link ramp.model.business.RecordCollection#method_offsetGet ramp\model\business\RecordCollection::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject[0]);
      $this->assertSame($this->testChild1, $this->testObject[0]);
      $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject[1]);
      $this->assertSame($this->testChild2, $this->testObject[1]);
      $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject[2]);
      $this->assertSame($this->testChild3, $this->testObject[2]);
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link ramp.model.business.RecordCollection#method_offsetExists ramp\model\business\RecordCollection::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertFalse(isset($this->testObject[3]));
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::offsetSet and
   * for \ramp\model\business\RecordCollection::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.RecordCollection#method_offsetSet ramp\model\business\RecordCollection::offsetSet()
   * @link ramp.model.business.RecordCollection#method_offsetUnset ramp\model\business\RecordCollection::offsetUnset()
   */
  public function testOffsetSetOffsetUnset()
  {
    try {
      $this->testObject[3] = new Collection();
    } catch (\InvalidArgumentException $expected) {
        $this->assertSame('ramp\core\Collection NOT instanceof ramp\model\business\BusinessModel', $expected->getMessage());
        $object = new TestRecord();
        $this->testObject[3] = $object;
        $this->assertSame($object, $this->testObject[3]);
        unset($this->testObject[3]);
        $this->assertFalse(isset($this->testObject[3]));
        return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::validate().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children.
   * @link ramp.model.business.RecordCollection#method_validate ramp\model\business\RecordCollection::validate()
   */
  public function testValidate()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
     'test-record:key1:a-property' => 'GOOD',
     'test-record:key2:a-property' => 'BAD',
     'test-record:key3:a-property' => 'GOOD'
    ))));
    $this->assertEquals('GOOD', $this->child1Data->aProperty);
    $this->assertNull($this->child2Data->aProperty);
    $this->assertEquals('GOOD', $this->child3Data->aProperty);
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::hasErrors().
   * - assert returns True when any child (Record) has recorded errors.
   * - assert propagates through children until reaches one that has recorded errors.
   * - assert each child returns whether it has errors called.
   * @link ramp.model.business.RecordCollection#method_hasErrors ramp\model\business\RecordCollection::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
     'test-record:key1:a-property' => 'BAD',
     'test-record:key2:a-property' => 'GOOD',
     'test-record:key3:a-property' => 'INCORRECT'
    ))));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertTrue($this->testChild1->hasErrors);
    $this->assertFalse($this->testChild2->hasErrors);
    $this->assertTrue($this->testChild3->hasErrors);
  }

  /**
   * Collection of assertions for \ramp\model\business\RecordCollection::getErrors().
   * - assert following validate(), the expected iCollection of error messages are returned.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * @link ramp.model.business.RecordCollection#method_getErrors ramp\model\business\RecordCollection::getErrors()
   */
  public function testGetErrors()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
     'test-record:key1:a-property' => 'BAD',
     'test-record:key2:a-property' => 'GOOD',
     'test-record:key3:a-property' => 'INCORRECT'
    ))));
    $errors = $this->testObject->errors;
    $this->assertEquals('$value does NOT evaluate to GOOD', (string)$errors[0]);
    $this->assertEquals('$value does NOT evaluate to GOOD', (string)$errors[1]);
    $this->assertEquals(2, $errors->count);

    $errors2 = $this->testObject->errors;
    $this->assertEquals('$value does NOT evaluate to GOOD', (string)$errors[0]);
    $this->assertEquals('$value does NOT evaluate to GOOD', (string)$errors[1]);
    $this->assertEquals(2, $errors2->count);

    $child1Errors = $this->testChild1->errors;
    $this->assertEquals('$value does NOT evaluate to GOOD', (string)$errors[0]);
    $this->assertEquals(1, $child1Errors->count);

    $child2Errors = $this->testChild2->errors;
    $this->assertEquals(0, $child2Errors->count);

    $child3Errors = $this->testChild3->errors;
    $this->assertEquals('$value does NOT evaluate to GOOD', (string)$errors[0]);
    $this->assertEquals(1, $child3Errors->count);
  }

   /**
   * Collection of assertions for \ramp\model\business\RecordCollection::count().
   * - assert return expected int value related to the number of child RecordCollections held.
   * @link ramp.model.business.RecordCollection#method_count ramp\model\business\RecordCollection::count()
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count);
  }
}
