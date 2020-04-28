<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
namespace tests\svelte\model\business\field;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/field/SelectMany.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/OptionList.class.php';

require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/Record.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockBusinessModelWithErrors.class.php';

use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\OptionList;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
use svelte\model\business\field\SelectMany;


use tests\svelte\model\business\field\mocks\FieldTest\MockBusinessModel;
use tests\svelte\model\business\field\mocks\FieldTest\MockBusinessModelWithErrors;

use svelte\model\business\Record;

/**
 * Collection of tests for \svelte\model\business\field\SelectMany.
 */
class SelectManyTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $mockRecord;

  private $options;
  private $option1;
  private $option2;
  private $option3;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    Record::reset();
    MockBusinessModel::reset();
    $this->options = new Collection();
    $this->option1 = new MockBusinessModel('First child');
    $this->option2 = new MockBusinessModelWithErrors('Second child');
    $this->option3 = new MockBusinessModel('Third child');
    $this->options->add($this->option1);
    $this->options->add($this->option2);
    $this->options->add($this->option3);
    $this->mockRecord = new Record();
    $this->testObject = new SelectMany(
      Str::set('aProperty'),
      $this->mockRecord,
      new OptionList($this->options)
    );
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \svelte\model\field\Field}
   * - assert is instance of {@link \svelte\model\field\SelectMany}
   * @link svelte.model.business.field.SelectMany svelte\model\business\field\SelectMany
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\field\SelectMany', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.SelectMany#method_get_id svelte\model\business\field\SelectMany::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
      $this->assertSame($this->mockRecord->id . ':a-property', (string)$this->testObject->id);
      $this->assertSame('mock-business-model:0', (string)$this->option1->id);
      $this->assertSame('mock-business-model:1', (string)$this->option2->id);
      $this->assertSame('mock-business-model:2', (string)$this->option3->id);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::description.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned same as 'id'.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.SelectMany#method_get_description svelte\model\business\field\SelectMany::description
   */
  public function testGet_description()
  {
    try {
      $this->testObject->description = "DESCRIPTION";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->description);
      $this->assertEquals($this->testObject->id, $this->testObject->description);
      $this->assertSame($this->mockRecord->id . ':a-property', (string)$this->testObject->description);
      $this->assertEquals('mock-business-model:0', (string)$this->option1->description);
      $this->assertEquals('mock-business-model:1', (string)$this->option2->description);
      $this->assertEquals('mock-business-model:2', (string)$this->option3->description);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::value.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.SelectMany#method_get_value svelte\model\business\field\SelectMany::value
   */
  public function testGet_value()
  {
    try {
      $this->testObject->value = 'VALUE';
    } catch (PropertyNotSetException $expected) {
      $value = $this->testObject->value;
      $this->assertSame(1, Record::$getPropertyValueCount);
      $this->assertSame($this->mockRecord->getPropertyValue('aProperty'), $value);
      $this->assertSame('VALUE', $value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.SelectMany#method_get_type svelte\model\business\field\SelectMany::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertEquals(' select-many field', (string)$this->testObject->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->option1->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->option2->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->option3->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link svelte.model.business.field.SelectMany#method_getIterator svelte\model\business\field\SelectMany::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->options->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertSame('mock-business-model:' . $i++, (string)$child->id);
      $iterator->next();
    }
    $this->assertSame('record:new:a-property', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link svelte.model.business.field.SelectMany#method_offsetGet svelte\model\business\field\SelectMany::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[0]);
      $this->assertSame($this->option1, $this->testObject[0]);
      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[1]);
      $this->assertSame($this->option2, $this->testObject[1]);
      $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject[2]);
      $this->assertSame($this->option3, $this->testObject[2]);
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link svelte.model.business.field.SelectMany#method_offsetExists svelte\model\business\field\SelectMany::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertFalse(isset($this->testObject[3]));
  }

  /**
   * Collection of assertions for svelte\model\business\field\SelectMany::offsetSet().
   * - assert throws BadMethodCallException as this method should be inaccessible
   *   - with message: <em>'Array access setting is not allowed, please use add.'</em>
   * @link svelte.model.business.field.SelectMany#method_offsetSet \svelte\model\business\field\SelectMany::offsetSet()
   */
  public function testOffsetSet()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access setting is not allowed.';
    $this->testObject[3] = new MockBusinessModel('Forth child');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::offsetUnset.
   * - assert throws BadMethodCallException whenever offsetUnset is called
   *  - with message *Array access unsetting is not allowed.*
   * @link svelte.model.business.field.SelectMany#method_offsetUnset svelte\model\business\field\SelectMany::offsetUnset()
   */
  public function testOffsetUnset()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access unsetting is not allowed.';
    unset($this->testObject[0]);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::validate() where PostData
   * does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *    matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert validate method is NOT propagated through to its children and grandchildren.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(0, $this->option1->validateCount);
    $this->assertSame(0, $this->option2->validateCount);
    $this->assertSame(0, $this->option3->validateCount);
  }

  /**
   * Further collection of assertions for \svelte\model\business\field\Field::validate(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method is called and passes, then its
   *    containingRecord setPropertyMethod is called.
   * - assert validate method is NOT propagated through to its children and grandchildren.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleCalled()
  {
    $this->assertSame(0, Record::$setPropertyValueCount);
    $this->testObject->validate(PostData::build(array(
      'record:new:a-property' => Array(
        'mock-business-model:0',
        'mock-business-model:2'
      )
    )));
    $this->assertSame(1, Record::$setPropertyValueCount);
    $this->assertSame(0, $this->option1->validateCount);
    $this->assertSame(0, $this->option2->validateCount);
    $this->assertSame(0, $this->option3->validateCount);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::hasErrors().
   * - assert returns False when PostData does NOT contain an InputDataCondition with an attribute
   *   that matches the testObject's id.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert does NOT propagates through to its child/grandchild.
   * @link svelte.model.business.field.Field#method_hasErrors svelte\model\business\field\Field::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors());
    $this->assertSame(0, Record::$setPropertyValueCount);
    $this->assertSame(0, $this->option1->hasErrorsCount);
    $this->assertSame(0, $this->option2->hasErrorsCount);
    $this->assertSame(0, $this->option3->hasErrorsCount);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::getErrors().
   * - assert returns an empty iCollection when PostData does NOT contain an InputDataCondition
   *   with an attribute that matches the testObject's id.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its processValidationRule method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *   the testObject's id and its value is NOT an array, a BadMethodCallException is thrown
   *  - with message *$value parameter must be an array*
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method is called and fails, throwing a
   *    FailedValidationException then its message is added to its errorCollection for retrieval
   *    by its hasErrors and getErrors methods.
   * - assert following validate(), the expected iCollection of error messages are returned.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * @link svelte.model.business.field.Field#method_getErrors svelte\model\business\field\Field::getErrors()
   */
  public function testGetErrors()
  {
    // PostData does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors());
    $errors = $this->testObject->getErrors();
    $this->assertSame(0, Record::$setPropertyValueCount);
    $this->assertInstanceOf('\svelte\core\iCollection', $errors);
    $this->assertSame(0, $errors->count());
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObject->getErrors();
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));
    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    try {
      $this->testObject->validate(PostData::build(array('record:new:a-property' => 'BAD')));
    } catch (\BadMethodCallException $expected) {
      $this->assertSame('$value parameter must be an array', $expected->getMessage());
      $this->testObject->validate(PostData::build(array(
        'record:new:a-property' => Array(
          'mock-business-model:0',
          'mock-business-model:BAD'
        )
      )));
      $this->assertSame(0, Record::$setPropertyValueCount);
      $thirdCallOnErrors = $this->testObject->getErrors();
      $this->assertInstanceOf('\svelte\core\iCollection', $thirdCallOnErrors);
      $this->assertSame(1, $thirdCallOnErrors->count());
      $this->assertSame(
        'At least one selected value is NOT an available option!', (string)$thirdCallOnErrors[0]
      );
      // Returns same results on subsequent call, while Field in same state.
      $forthCallOnErrors = $this->testObject->getErrors();
      $this->assertEquals($forthCallOnErrors, $thirdCallOnErrors);
      $this->assertTrue(isset($thirdCallOnErrors[0]));
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::count().
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link svelte.model.business.field.SelectMany#method_count svelte\model\business\field\SelectMany::count()
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count());
  }
}
