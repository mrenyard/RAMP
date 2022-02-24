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
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
// require_once '/usr/share/php/svelte/core/iOption.class.php';
// require_once '/usr/share/php/svelte/core/OptionList.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Flag.class.php';
// require_once '/usr/share/php/svelte/model/business/field/Option.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/Flag.class.php';

require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FlagTest/MockRecord.class.php';
// require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockField.class.php';

// use svelte\SETTING;
use svelte\core\Str;
// use svelte\core\Collection;
// use svelte\core\OptionList;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
// use svelte\model\business\Record;
// use svelte\model\business\field\Option;
use svelte\model\business\field\Flag;

use tests\svelte\model\business\field\mocks\FlagTest\MockRecord;

/**
 * Collection of tests for \svelte\model\business\field\Flag.
 */
class FlagTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $dataObject;
  private $mockRecord;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    MockRecord::reset();
    $this->dataObject = new \stdClass();
    $this->dataObject->aProperty = NULL;
    $this->mockRecord = new MockRecord($this->dataObject);
    $this->testObject = new Flag(Str::set('aProperty'), $this->mockRecord);
    \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\field\mocks\FlagTest';
  }

  /**
    * Collection of assertions for \svelte\model\business\field\Flag::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \svelte\model\field\Field}
   * - assert is instance of {@link \svelte\model\field\Flag}
   * @link svelte.model.business.field.Flag svelte\model\business\field\Flag
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
    $this->assertInstanceOf('\svelte\model\business\field\Flag', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Flag::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Flag#method_get_id svelte\model\business\field\Flag::id
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
   * Collection of assertions for \svelte\model\business\field\Flag::value.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert default 'value' returns FASLE.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Flag#method_get_value svelte\model\business\field\Flag::value
   */
  public function testGet_value()
  {
    try {
      $this->testObject->value = TRUE;
    } catch (PropertyNotSetException $expected) {

      $this->assertNull($this->dataObject->aProperty);
      $this->assertFalse($this->testObject->value);

      $this->dataObject->aProperty = FALSE;
      $this->assertSame($this->dataObject->aProperty, $this->testObject->value);
      $this->assertFalse($this->testObject->value);

      $this->dataObject->aProperty = TRUE;
      $this->assertSame($this->dataObject->aProperty, $this->testObject->value);
      $this->assertTrue($this->testObject->value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Flag::containingRecord.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'containingRecord'
   * - assert property 'containingRecord' is gettable.
   * - assert returned Record matches Record as provided construct.
   * @link svelte.model.business.field.Flag#method_get_containingRecord svelte\model\business\field\Flag::containingRecord
   */
  public function testGet_containingRecord()
  {
    try {
      $this->testObject->containingRecord = $this->mockRecord;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->mockRecord, $this->testObject->containingRecord);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Flag::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Flag#method_get_type svelte\model\business\field\Flag::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertEquals(' flag field', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Flag::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through NO objects, as there are NO children.
   * @link svelte.model.business.field.Flag#method_getIterator svelte\model\business\field\Flag::getIterator()
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
   * Collection of assertions for \svelte\model\business\field\Flag::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds as NO children
   * @link svelte.model.business.field.Flag#method_offsetGet svelte\model\business\field\Flag::offsetGet()
   */
  public function testOffsetGet()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->testObject[0];
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Flag::offsetExists.
   * - assert False returned on isset() NO children outside expected bounds.
   * @link svelte.model.business.field.Flag#method_offsetExists svelte\model\business\field\Flag::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Collection of assertions for svelte\model\business\field\Flag::offsetSet().
   * - assert throws BadMethodCallException as this method should be inaccessible
   *   - with message: <em>'Array access setting is not allowed, please use add.'</em>
   * @link svelte.model.business.field.Flag#method_offsetSet \svelte\model\business\field\Flag::offsetSet()
   */
  public function testOffsetSet()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access setting is not allowed.';
    $this->testObject[0] = 'VALUE';
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Flag::offsetUnset.
   * - assert throws BadMethodCallException whenever offsetUnset is called
   *  - with message *Array access unsetting is not allowed.*
   * @link svelte.model.business.field.Flag#method_offsetUnset svelte\model\business\field\Flag::offsetUnset()
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
   *    matches the testObject's id, then its containing Record's setPropertyValue method is NOT called
   *    and NOT modified.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(0, MockRecord::$setPropertyCallCount);
    $this->assertFalse($this->mockRecord->isModified);
  }

  /**
   * Further collection of assertions for \svelte\model\business\field\Field::validate(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method passes, then its
   *    containingRecord setPropertyMethod is called
   *    and the nessasary change to the property have occurred.
   * - assert validate method is NOT propagated through to its children and grandchildren.
   * @link svelte.model.business.field.Field#method_validate svelte\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleCalled()
  {
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:new:a-property' => TRUE
    ))));
    $this->assertSame(1, MockRecord::$setPropertyCallCount);
    $this->assertTrue($this->mockRecord->isModified);
    $this->assertTrue($this->dataObject->aProperty);
    $this->assertTrue($this->testObject->value);

    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:new:a-property' => FALSE
    ))));
    $this->assertSame(2, MockRecord::$setPropertyCallCount);
    $this->assertTrue($this->mockRecord->isModified);
    $this->assertFalse($this->dataObject->aProperty);
    $this->assertFalse($this->testObject->value);
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
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::getErrors().
   * - assert returns an empty iCollection when PostData does NOT contain an InputDataCondition
   *   with an attribute that matches the testObject's id.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its containing Record's $setPropertyValue method, is NOT called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id, then its containing Record's $setPropertyValue method is called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its containing Record's $setPropertyValue method is called and fails,
   *    throwing a FailedValidationException then its message is added to its errorCollection for retrieval
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
    $this->assertSame(0, MockRecord::$setPropertyCallCount);
    $this->assertInstanceOf('\svelte\core\iCollection', $errors);
    $this->assertSame(0, $errors->count);
    $this->assertFalse(isset($errors[0]));
    // Returns same results on subsequent call, while Field in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[0]));
    // PostData does contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:new:a-property' => 'BAD'
    ))));
    $this->assertSame(0, MockRecord::$setPropertyCallCount);
    $this->assertNull($this->dataObject->aProperty);
    // $this->assertFalse($this->testObject->value);
    $thirdCallOnErrors = $this->testObject->errors;
    $this->assertInstanceOf('\svelte\core\iCollection', $thirdCallOnErrors);
    $this->assertSame(1, $thirdCallOnErrors->count);
    $this->assertSame('Flag input can only be one of True or False.', (string)$thirdCallOnErrors[0]);
    // Returns same results on subsequent call, while Field in same state.
    $forthCallOnErrors = $this->testObject->errors;
    $this->assertEquals($forthCallOnErrors, $thirdCallOnErrors);
    $this->assertTrue(isset($thirdCallOnErrors[0]));
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::count.
   * - assert return expected int value related to the number of children (NO children).
   * @link svelte.model.business.field.Field#method_count svelte\model\business\field\Field::count
   */
  public function testCount()
  {
    $this->assertSame(0, $this->testObject->count);
  }
}
