<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
namespace tests\svelte\model\business;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Input.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/VarChar.class.php';

require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordTest/ConcreteValidationRule.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordCollectionTest/ConcreteValidationRule2.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordCollectionTest/TestRecordCollection.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordCollectionTest/TestRecord.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;

use tests\svelte\model\business\mocks\RecordCollectionTest\TestRecordCollection;
use tests\svelte\model\business\mocks\RecordCollectionTest\TestRecord;

/**
 * Collection of tests for \svelte\model\business\RecordCollection.
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
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\mocks\RecordCollectionTest';
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
   * Collection of assertions for \svelte\model\business\RecordCollection::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\RecordCollection}
   * - assert is instance of {@link \svelte\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \svelte\core\iCollection}
   * @link svelte.model.business.RecordCollection svelte\model\business\RecordCollection
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\RecordCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\svelte\core\iCollection', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\RecordCollection::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.RecordCollection#method_get_id svelte\model\business\RecordCollection::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
      $this->assertSame('test-record-collection', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\RecordCollection::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.RecordCollection#method_get_type svelte\model\business\RecordCollection::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertSame(' test-record-collection record-collection', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\RecordCollection::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link svelte.model.business.RecordCollection#method_getIterator svelte\model\business\RecordCollection::getIterator()
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
   * Collection of assertions for \svelte\model\business\RecordCollection::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link svelte.model.business.RecordCollection#method_offsetGet svelte\model\business\RecordCollection::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\svelte\model\business\Record', $this->testObject[0]);
      $this->assertSame($this->testChild1, $this->testObject[0]);
      $this->assertInstanceOf('\svelte\model\business\Record', $this->testObject[1]);
      $this->assertSame($this->testChild2, $this->testObject[1]);
      $this->assertInstanceOf('\svelte\model\business\Record', $this->testObject[2]);
      $this->assertSame($this->testChild3, $this->testObject[2]);
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\RecordCollection::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link svelte.model.business.RecordCollection#method_offsetExists svelte\model\business\RecordCollection::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertFalse(isset($this->testObject[3]));
  }

  /**
   * Collection of assertions for \svelte\model\business\RecordCollection::offsetSet and
   * for \svelte\model\business\RecordCollection::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link svelte.model.business.RecordCollection#method_offsetSet svelte\model\business\RecordCollection::offsetSet()
   * @link svelte.model.business.RecordCollection#method_offsetUnset svelte\model\business\RecordCollection::offsetUnset()
   */
  public function testOffsetSetOffsetUnset()
  {
    try {
      $this->testObject[3] = new TestRecord();
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals('Array access setting is not allowed.', $expected->getMessage());
      try {
        unset($this->testObject[2]);
      } catch (\BadMethodCallException $expected) {
        $this->assertEquals('Array access unsetting is not allowed.', $expected->getMessage());
        return;
      }
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\RecordCollection::validate().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children.
   * @link svelte.model.business.RecordCollection#method_validate svelte\model\business\RecordCollection::validate()
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
   * Collection of assertions for \svelte\model\business\RecordCollection::hasErrors().
   * - assert returns True when any child (Record) has recorded errors.
   * - assert propagates through children until reaches one that has recorded errors.
   * - assert each child returns whether it has errors called.
   * @link svelte.model.business.RecordCollection#method_hasErrors svelte\model\business\RecordCollection::hasErrors()
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
   * Collection of assertions for \svelte\model\business\RecordCollection::getErrors().
   * - assert following validate(), the expected iCollection of error messages are returned.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * @link svelte.model.business.RecordCollection#method_getErrors svelte\model\business\RecordCollection::getErrors()
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
   * Collection of assertions for \svelte\model\business\RecordCollection::count().
   * - assert return expected int value related to the number of child RecordCollections held.
   * @link svelte.model.business.RecordCollection#method_count svelte\model\business\RecordCollection::count()
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count);
  }
}
