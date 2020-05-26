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
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Input.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';

require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockRecord.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/InputTest/MyValidationRule.class.php';

use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
use svelte\model\business\field\Input;
use svelte\model\business\validation\dbtype\VarChar;

use tests\svelte\model\business\field\mocks\FieldTest\MockRecord;
use tests\svelte\model\business\field\mocks\InputTest\MyValidationRule;

/**
 * Collection of tests for \svelte\model\business\field\Input.
 */
class InputTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $mockRecord;
  private $dataObject;
  private $erroMessage;
  private $myValidationRule;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    MyValidationRule::reset();
    $this->dataObject = new \stdClass();
    $this->dataObject->aProperty = NULL;
    $this->mockRecord = new MockRecord($this->dataObject);
    $this->errorMessage = 'MyValidationRule has error due to $value of BAD!';
    $this->myValidationRule = new VarChar(10, new MyValidationRule(), Str::set($this->errorMessage));
    $this->testObject = new Input(Str::set('aProperty'), $this->mockRecord, $this->myValidationRule);
    \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\field\mocks\FieldTest';
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \svelte\model\field\Field}
   * - assert is instance of {@link \svelte\model\field\Input}
   * @link svelte.model.business.field.Input svelte\model\business\field\Input
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
    $this->assertInstanceOf('\svelte\model\business\field\Input', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Input#method_get_id svelte\model\business\field\Input::id
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
   * Collection of assertions for \svelte\model\business\field\Input::value.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Input#method_get_value svelte\model\business\field\Input::value
   */
  public function testGet_value()
  {
    try {
      $this->testObject->value = 'VALUE';
    } catch (PropertyNotSetException $expected) {
      $this->dataObject->aProperty = 'VALUE';
      $value = $this->testObject->value;
      $this->assertSame($this->dataObject->aProperty, $value);
      $this->assertSame('VALUE', $value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Input#method_get_type svelte\model\business\field\Input::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertEquals(' input field', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through NO objects, as there are NO children.
   * @link svelte.model.business.field.Input#method_getIterator svelte\model\business\field\Input::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    foreach ($this->testObject as $child) {
      $i++;
    }
    $this->assertSame(0, $i);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds as NO children
   * @link svelte.model.business.field.Input#method_offsetGet svelte\model\business\field\Input::offsetGet()
   */
  public function testOffsetGet()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->testObject[0];
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::offsetExists.
   * - assert False returned on isset() NO children outside expected bounds.
   * @link svelte.model.business.field.Input#method_offsetExists svelte\model\business\field\Input::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Collection of assertions for svelte\model\business\field\Input::offsetSet().
   * - assert throws BadMethodCallException as this method should be inaccessible
   *   - with message: <em>'Array access setting is not allowed, please use add.'</em>
   * @link svelte.model.business.field.Input#method_offsetSet \svelte\model\business\field\Input::offsetSet()
   */
  public function testOffsetSet()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access setting is not allowed.';
    $this->testObject[0] = 'VALUE';
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::offsetUnset.
   * - assert throws BadMethodCallException whenever offsetUnset is called
   *  - with message *Array access unsetting is not allowed.*
   * @link svelte.model.business.field.Input#method_offsetUnset svelte\model\business\field\Input::offsetUnset()
   */
  public function testOffsetUnset()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access unsetting is not allowed.';
    unset($this->testObject[0]);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::validate() where PostData
   * does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its associated ValidationRule test() method, is NOT called.
   * @link svelte.model.business.field.Input#method_validate svelte\model\business\field\Input::validate()
   */
  public function testValidateValidationRuleTestNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(0, MyValidationRule::$testCallCount);
  }

  /**
   * Further collection of assertions for \svelte\model\business\field\Input::validate(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *   the testObject's id, then its associated ValidationRule test() method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *   the testObject's id and its associated ValidationRule test() method is called and passes,
   *   then its containingRecord setPropertyMethod is called.
   * @link svelte.model.business.field.Input#method_validate svelte\model\business\field\Input::validate()
   */
  public function testValidateValidationRuleTestCalled()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:new:a-property' => 'GOOD'
    ))));
    $this->assertSame(1, MyValidationRule::$testCallCount);
    $this->assertSame('GOOD', $this->dataObject->aProperty);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::hasErrors().
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its associated ValidationRule test() method, is NOT called.
   * - assert returns False when no errors are recorded.
   * @link svelte.model.business.field.Input#method_hasErrors svelte\model\business\field\Input::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertSame(0, MyValidationRule::$testCallCount);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::getErrors().
   * - assert following validate(), the expected iCollection of error messages are returned.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * @link svelte.model.business.field.Input#method_getErrors svelte\model\business\field\Input::getErrors()
   */
  public function testGetErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertSame(0, MyValidationRule::$testCallCount);
    $this->assertNull($this->dataObject->aProperty);
    $errors = $this->testObject->errors;
    $this->assertSame(0, $errors->count);
    // Returns same results on subsequent call.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));
    $this->assertSame(0, MyValidationRule::$testCallCount);
    $this->assertNull($this->dataObject->aProperty);
  }

  /**
   * Further collection of assertions for \svelte\model\business\field\Input::getErrors(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id
   * and its associated ValidationRule test() method is called and fails.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its associated ValidationRule test() method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *   the testObject's id and its associated ValidationRule test() method is called and fails,
   *   throwing a FailedValidationException then its message is added to its errorCollection for
   *   retrieval by its hasErrors and getErrors methods.
   * @link svelte.model.business.field.Input#method_getErrors svelte\model\business\field\Input::getErrors()
   */
  public function testHasErrorsValidationRuleTestCalled()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:new:a-property' => 'BAD'
    ))));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertSame(1, MyValidationRule::$testCallCount);
    $this->assertNull($this->dataObject->aProperty);
    $errors = $this->testObject->errors;
    $this->assertSame($this->errorMessage, (string)$errors[0]);
    $this->assertFalse(isset($errors[1]));
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Input::count.
   * - assert return expected int value related to the number of children (NO children).
   * @link svelte.model.business.field.Input#method_count svelte\model\business\field\Input::count
   */
  public function testCount()
  {
    $this->assertSame(0 ,$this->testObject->count);
  }
}
