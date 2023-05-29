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
 * @package RAMP Testing
 * @version 0.0.9;
 */
namespace tests\ramp\model\business;

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
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';

require_once '/usr/share/php/tests/ramp/model/business/mocks/RelatableTest/MockRelatable.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/RelatableTest/MockRelatableWithErrors.class.php';

use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\field\Option;

use tests\ramp\model\business\mocks\RelatableTest\MockRelatable;
use tests\ramp\model\business\mocks\RelatableTest\MockRelatableCollection;
use tests\ramp\model\business\mocks\RelatableTest\MockRelatableWithErrors;

/**
 * Collection of tests for \ramp\model\business\Relatable.
 */
class RelatableTest extends \PHPUnit\Framework\TestCase
{
  private $children;
  private $testObject;
  private $testChild1;
  private $testChild2;
  private $testChild3;
  private $grandchildren;
  private $grandchild;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    MockRelatable::reset();

    $this->children = new MockRelatableCollection();
    $this->grandchildren = new MockRelatableCollection();
    $this->testObject = new MockRelatable('Top object', $this->children);
    $this->assertSame(0, $this->children->count);
    $this->testChild1 = new MockRelatable('First child');
    $this->children->add($this->testChild1);
    $this->assertSame(1, $this->children->count);
    $this->testChild2 = new MockRelatableWithErrors('Second child');
    $this->children->add($this->testChild2);
    $this->assertSame(2, $this->children->count);
    $this->testChild3 = new MockRelatable('Third child', $this->grandchildren);
    $this->children->add($this->testChild3);
    $this->assertSame(3, $this->children->count);
    $this->grandchild = new MockRelatableWithErrors('First grandchild');
    $this->grandchildren->add($this->grandchild);
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\Relatable}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * @link ramp.model.business.Relatable ramp\model\business\Relatable
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\core\iList', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.Relatable#method_get_id ramp\model\business\Relatable::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame('uid-0', (string)$this->testObject->id);
      $this->assertSame('uid-1', (string)$this->testChild1->id);
      $this->assertSame('uid-2', (string)$this->testChild2->id);
      $this->assertSame('uid-3', (string)$this->testChild3->id);
      $this->assertSame('uid-4', (string)$this->grandchild->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::description.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned same as 'id'.
   * - assert returned value matches expected result.
   * @link ramp.model.business.Relatable#method_get_description ramp\model\business\Relatable::description
   *
  public function testGet_description()
  {
    try {
      $this->testObject->description = "DESCRIPTION";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->description);
      $this->assertEquals($this->testObject->id, $this->testObject->description);
      $this->assertEquals('uid-0', (string)$this->testObject->description);
      $this->assertEquals('uid-1', (string)$this->testChild1->description);
      $this->assertEquals('uid-2', (string)$this->testChild2->description);
      $this->assertEquals('uid-3', (string)$this->testChild3->description);
      $this->assertEquals('uid-4', (string)$this->grandchild->description);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }*/

  /**
   * Collection of assertions for \ramp\model\business\Relatable::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.Relatable#method_get_type ramp\model\business\Relatable::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertSame('mock-relatable relatable', (string)$this->testObject->type);
      $this->assertSame('mock-relatable relatable', (string)$this->testChild1->type);
      $this->assertSame('mock-relatable-with-errors mock-relatable', (string)$this->testChild2->type);
      $this->assertSame('mock-relatable relatable', (string)$this->testChild3->type);
      $this->assertSame('mock-relatable-with-errors mock-relatable', (string)$this->grandchild->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link ramp.model.business.Relatable#method_getIterator ramp\model\business\Relatable::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 1;
    $iterator = $this->children->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertSame('uid-' . $i++, (string)$child->id);
      $iterator->next();
    }
    $this->assertSame(3, $this->testObject->count);
    $this->assertSame('uid-0', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link ramp.model.business.Relatable#method_offsetGet ramp\model\business\Relatable::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject[0]);
      $this->assertSame($this->testChild1, $this->testObject[0]);
      $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject[1]);
      $this->assertSame($this->testChild2, $this->testObject[1]);
      $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject[2]);
      $this->assertSame($this->testChild3, $this->testObject[2]);
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link ramp.model.business.Relatable#method_offsetExists ramp\model\business\Relatable::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertTrue(isset($this->testObject[2][0]));
    $this->assertFalse(isset($this->testObject[3]));
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::offsetSet and
   * for \ramp\model\business\Relatable::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.Relatable#method_offsetSet ramp\model\business\Relatable::offsetSet()
   * @link ramp.model.business.Relatable#method_offsetUnset ramp\model\business\Relatable::offsetUnset()
   */
  public function testOffsetSetOffsetUnset()
  {
    try {
      $this->testObject[3] = new Option(3, Str::set('No Option'));
    } catch (\InvalidArgumentException $expected) {
        $this->assertSame('ramp\model\business\field\Option NOT instanceof tests\ramp\model\business\mocks\RelatableTest\MockRelatable', $expected->getMessage());

        $object = new MockRelatable('Forth child');
        $this->testObject[3] = $object;
        $this->assertSame($object, $this->testObject[3]);
        unset($this->testObject[3]);
        $this->assertFalse(isset($this->testObject[3]));
        return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::validate().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of
   *  its children and grandchildren.
   * @link ramp.model.business.Relatable#method_validate ramp\model\business\Relatable::validate()
   */
  public function testValidate()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(1, $this->testObject->validateCount);
    $this->assertSame(1, $this->testChild1->validateCount);
    $this->assertSame(1, $this->testChild2->validateCount);
    $this->assertSame(1, $this->testChild3->validateCount);
    $this->assertSame(1, $this->grandchild->validateCount);
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::hasErrors().
   * - assert returns True when any child/grandchild has recorded errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link ramp.model.business.Relatable#method_hasErrors ramp\model\business\Relatable::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertSame(1, $this->testObject->hasErrorsCount);
    $this->assertSame(1, $this->testChild1->hasErrorsCount);
    $this->assertSame(0, $this->testChild2->hasErrorsCount);
    $this->assertSame(0, $this->testChild3->hasErrorsCount);
    $this->assertSame(0, $this->grandchild->hasErrorsCount);
  }

  /**
   * Collection of assertions for \ramp\model\business\Relatable::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   * getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *  of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Relatables
   * @link ramp.model.business.Relatable#method_getErrors ramp\model\business\Relatable::getErrors()
   */
  public function testGetErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertTrue($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    // All errors including children and grandchildren of top testObject returned in a single collection.
    $this->assertSame('Second child error message', (string)$errors[0]);
    $this->assertSame('First grandchild error message', (string)$errors[1]);

    $this->assertFalse(isset($errors[6]));
    // Returns same results on subsequent call, while Relatables are in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[6]));
    // Calls on sub Relatables return expected sub set of Errors.
    $child2Errors = $this->testChild2->errors;
    $this->assertSame('Second child error message', (string)$errors[0]);
    $this->assertSame('First grandchild error message', (string)$errors[1]);

    // Calls on sub Relatables return expected sub set of Errors, even on grandchildren.
    $grandchildErrros = $this->grandchild->errors;
    $this->assertSame('First grandchild error message', (string)$grandchildErrros[0]);
    $this->assertFalse(isset($child3Errros[3]));

    // Because testChild3 in the parent of grandchild it returns grandchild errors alone with any of own.
    $child3Errros = $this->testChild3->errors;
    $this->assertSame('First grandchild error message', (string)$child3Errros[0]);
    $this->assertFalse(isset($child3Errros[1]));
  }

   /**
   * Collection of assertions for \ramp\model\business\Relatable::count and
   * \ramp\model\business\Relatable::count()
   * - assert return expected int value related to the number of child Relatables held.
   * @link ramp.model.business.Relatable#method_count ramp\model\business\Relatable::count()
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count);
    $this->assertSame(3 ,$this->testObject->count());
  }
}
