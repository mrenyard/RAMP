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
namespace tests\svelte\model\business\field;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/OptionList.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/SelectMany.class.php';
require_once '/usr/share/php/svelte/model/business/field/Option.class.php';

require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockRecord.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\OptionList;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
use svelte\model\business\field\Option;
use svelte\model\business\field\SelectMany;
use svelte\model\business\Record;

use tests\svelte\model\business\field\mocks\FieldTest\MockRecord;


/**
 * Collection of tests for \svelte\model\business\field\SelectMany.
 */
class SelectManyTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $mockRecord;
  private $dataObject;

  private $options;
  private $option1;
  private $option2;
  private $option3;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    SETTING::$SVELTE_LOCAL_DIR = '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/';
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\field\mocks\FieldTest';
    $this->options = new Collection();
    $this->option0 = new Option(0, Str::set('Select from:'));
    $this->option1 = new Option(1, Str::set('First child'));
    $this->option2 = new Option(2, Str::set('Second child'));
    $this->option3 = new Option(3, Str::set('Third child'));
    $this->options->add($this->option0);
    $this->options->add($this->option1);
    $this->options->add($this->option2);
    $this->options->add($this->option3);
    $this->dataObject = new \stdClass();
    $this->dataObject->aProperty = NULL;
    $this->mockRecord = new MockRecord($this->dataObject);
    $this->testObject = new SelectMany(
      Str::set('aProperty'),
      $this->mockRecord,
      new OptionList($this->options, Str::set('\svelte\model\business\field\Option'))
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
      $expectedValues = array(1,3);
      $this->dataObject->aProperty = $expectedValues;
      $selected = $this->testObject->value;
      $this->assertInstanceOf('\svelte\core\Collection', $selected);
      foreach ($selected as $item) {
        $this->assertSame((string)array_shift($expectedValues), (string)$item->id);
      }
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
      //$this->assertSame('mock-business-model:' . $i++, (string)$child->id);
      $iterator->next();
    }
    $this->assertSame('mock-record:new:a-property', (string)$this->testObject->id);
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
      $this->assertInstanceOf('\svelte\model\business\field\Option', $this->testObject[0]);
      $this->assertSame($this->option0, $this->testObject[0]);
      $this->assertInstanceOf('\svelte\model\business\field\Option', $this->testObject[1]);
      $this->assertSame($this->option1, $this->testObject[1]);
      $this->assertInstanceOf('\svelte\model\business\field\Option', $this->testObject[2]);
      $this->assertSame($this->option2, $this->testObject[2]);
      $this->assertInstanceOf('\svelte\model\business\field\Option', $this->testObject[3]);
      $this->assertSame($this->option3, $this->testObject[3]);
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
    $this->assertTrue(isset($this->testObject[3]));
    $this->assertFalse(isset($this->testObject[4]));
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
    $this->testObject[3] = new Option(4, Str::set('Forth child'));
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
    $this->testObject->validate(new PostData());
    $this->assertFalse($this->mockRecord->isModified);
    $this->assertNull($this->dataObject->aProperty);
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
   * - assert relevant options isSelected return true.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleCalled()
  {
    $selected = Array(1,3);
    $this->assertNull($this->dataObject->aProperty);
    $this->assertSame('mock-record:new:a-property', (string)$this->testObject->id);
    $this->testObject->validate(PostData::build(Array(
      'mock-record:new:a-property' => $selected
    )));
    $this->assertTrue($this->mockRecord->isModified);
    $this->assertSame($selected, $this->dataObject->aProperty);

    $this->assertTrue($this->option1->isSelected);
    $this->assertFalse($this->option2->isSelected);
    $this->assertTrue($this->option3->isSelected);
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
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertNull($this->dataObject->aProperty);
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
    $this->assertFalse($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertNull($this->dataObject->aProperty);
    $this->assertInstanceOf('\svelte\core\iCollection', $errors);
    $this->assertSame(0, $errors->count);
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));
    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    try {
      $this->testObject->validate(PostData::build(array('mock-record:new:a-property' => 'BAD')));
    } catch (\BadMethodCallException $expected) {
      $this->assertSame('$value parameter must be an array', $expected->getMessage());
      $this->testObject->validate(PostData::build(array(
        'mock-record:new:a-property' => Array(
          '1',
          'BAD'
        )
      )));
      $this->assertNull($this->dataObject->aProperty);
      $thirdCallOnErrors = $this->testObject->errors;
      $this->assertInstanceOf('\svelte\core\iCollection', $thirdCallOnErrors);
      $this->assertSame(1, $thirdCallOnErrors->count);
      $this->assertSame(
        'At least one selected value is NOT an available option!', (string)$thirdCallOnErrors[0]
      );
      // Returns same results on subsequent call, while Field in same state.
      $forthCallOnErrors = $this->testObject->errors;
      $this->assertEquals($forthCallOnErrors, $thirdCallOnErrors);
      $this->assertTrue(isset($thirdCallOnErrors[0]));
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\SelectMany::count.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link svelte.model.business.field.SelectMany#method_count svelte\model\business\field\SelectMany::count
   */
  public function testCount()
  {
    $this->assertSame(4 ,$this->testObject->count);
  }
}
