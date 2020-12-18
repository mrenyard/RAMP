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

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';

require_once '/usr/share/php/tests/svelte/model/business/mocks/BusinessModelTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/BusinessModelTest/MockBusinessModelWithErrors.class.php';

use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;

use tests\svelte\model\business\mocks\BusinessModelTest\MockBusinessModelWithErrors;
use tests\svelte\model\business\mocks\BusinessModelTest\MockBusinessModel;

/**
 * Collection of tests for \svelte\model\business\BusinessModel.
 */
class BusinessModelTest extends \PHPUnit\Framework\TestCase
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
    MockBusinessModel::reset();
    $this->children = new Collection();
    $this->grandchildren = new Collection();
    $this->testObject = new MockBusinessModel('Top object', $this->children);
    $this->testChild1 = new MockBusinessModel('First child');
    $this->children->add($this->testChild1);
    $this->testChild2 = new MockBusinessModelWithErrors('Second child');
    $this->children->add($this->testChild2);
    $this->testChild3 = new MockBusinessModel('Third child', $this->grandchildren);
    $this->children->add($this->testChild3);
    $this->grandchild = new MockBusinessModelWithErrors('First grandchild');
    $this->grandchildren->add($this->grandchild);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \svelte\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * @link svelte.model.business.BusinessModel svelte\model\business\BusinessModel
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.BusinessModel#method_get_id svelte\model\business\BusinessModel::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
      $this->assertSame('uid-0', (string)$this->testObject->id);
      $this->assertSame('uid-1', (string)$this->testChild1->id);
      $this->assertSame('uid-2', (string)$this->testChild2->id);
      $this->assertSame('uid-3', (string)$this->testChild3->id);
      $this->assertSame('uid-4', (string)$this->grandchild->id);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::description.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned same as 'id'.
   * - assert returned value matches expected result.
   * @link svelte.model.business.BusinessModel#method_get_description svelte\model\business\BusinessModel::description
   *
  public function testGet_description()
  {
    try {
      $this->testObject->description = "DESCRIPTION";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->description);
      $this->assertEquals($this->testObject->id, $this->testObject->description);
      $this->assertEquals('uid-0', (string)$this->testObject->description);
      $this->assertEquals('uid-1', (string)$this->testChild1->description);
      $this->assertEquals('uid-2', (string)$this->testChild2->description);
      $this->assertEquals('uid-3', (string)$this->testChild3->description);
      $this->assertEquals('uid-4', (string)$this->grandchild->description);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }*/

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.BusinessModel#method_get_type svelte\model\business\BusinessModel::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testObject->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testChild1->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->testChild2->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testChild3->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->grandchild->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link svelte.model.business.BusinessModel#method_getIterator svelte\model\business\BusinessModel::getIterator()
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
    $this->assertSame('uid-0', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link svelte.model.business.BusinessModel#method_offsetGet svelte\model\business\BusinessModel::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[0]);
      $this->assertSame($this->testChild1, $this->testObject[0]);
      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[1]);
      $this->assertSame($this->testChild2, $this->testObject[1]);
      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[2]);
      $this->assertSame($this->testChild3, $this->testObject[2]);
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link svelte.model.business.BusinessModel#method_offsetExists svelte\model\business\BusinessModel::offsetExists()
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
   * Collection of assertions for \svelte\model\business\BusinessModel::offsetSet and
   * for \svelte\model\business\BusinessModel::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link svelte.model.business.BusinessModel#method_offsetSet svelte\model\business\BusinessModel::offsetSet()
   * @link svelte.model.business.BusinessModel#method_offsetUnset svelte\model\business\BusinessModel::offsetUnset()
   */
  public function testOffsetSetOffsetUnset()
  {
    $object = new MockBusinessModel('Forth child');
    $this->testObject[3] = $object;
    $this->assertSame($object, $this->testObject[3]);
    unset($this->testObject[3]);
    $this->assertFalse(isset($this->testObject[3]));
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::validate().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of
   *  its children and grandchildren.
   * @link svelte.model.business.BusinessModel#method_validate svelte\model\business\BusinessModel::validate()
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
   * Collection of assertions for \svelte\model\business\BusinessModel::hasErrors().
   * - assert returns True when any child/grandchild has recorded errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link svelte.model.business.BusinessModel#method_hasErrors svelte\model\business\BusinessModel::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertSame(1, $this->testObject->hasErrorsCount);
    $this->assertSame(1, $this->testChild1->hasErrorsCount);
    $this->assertSame(1, $this->testChild2->hasErrorsCount);
    $this->assertSame(0, $this->testChild3->hasErrorsCount);
    $this->assertSame(0, $this->grandchild->hasErrorsCount);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   * getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *  of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link svelte.model.business.BusinessModel#method_getErrors svelte\model\business\BusinessModel::getErrors()
   */
  public function testGetErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertTrue($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    // All errors including children and grandchildren of top testObject returned in a single collection.
    $this->assertSame('Second child\'s first error occurred during validation!', (string)$errors[0]);
    $this->assertSame('Second child\'s second error occurred during validation!', (string)$errors[1]);
    $this->assertSame('Second child\'s third error occurred during validation!', (string)$errors[2]);
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$errors[3]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$errors[4]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$errors[5]);
    $this->assertFalse(isset($errors[6]));
    // Returns same results on subsequent call, while BusinessModels are in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[6]));
    // Calls on sub BusinessModels return expected sub set of Errors.
    $child2Errors = $this->testChild2->errors;
    $this->assertSame('Second child\'s first error occurred during validation!', (string)$child2Errors[0]);
    $this->assertSame('Second child\'s second error occurred during validation!', (string)$child2Errors[1]);
    $this->assertSame('Second child\'s third error occurred during validation!', (string)$child2Errors[2]);
    // Calls on sub BusinessModels return expected sub set of Errors, even on grandchildren.
    $grandchildErrros = $this->grandchild->errors;
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$grandchildErrros[0]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$grandchildErrros[1]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$grandchildErrros[2]);
    $this->assertFalse(isset($child3Errros[3]));
    // Because testChild3 in the parent of grandchild it returns grandchild errors alone with any of own.
    $child3Errros = $this->testChild3->errors;
    $this->assertSame('First grandchild\'s first error occurred during validation!', (string)$child3Errros[0]);
    $this->assertSame('First grandchild\'s second error occurred during validation!', (string)$child3Errros[1]);
    $this->assertSame('First grandchild\'s third error occurred during validation!', (string)$child3Errros[2]);
    $this->assertFalse(isset($child3Errros[3]));
  }

   /**
   * Collection of assertions for \svelte\model\business\BusinessModel::count and
   * \svelte\model\business\BusinessModel::count()
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link svelte.model.business.BusinessModel#method_count svelte\model\business\BusinessModel::count()
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count);
    $this->assertSame(3 ,$this->testObject->count());
  }
}
