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
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
// require_once '/usr/share/php/ramp/core/iOption.class.php';
// require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Flag.class.php';
// require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Flag.class.php';

require_once '/usr/share/php/tests/ramp/model/business/field/mocks/FlagTest/MockRecord.class.php';
// require_once '/usr/share/php/tests/ramp/model/business/field/mocks/FieldTest/MockField.class.php';

// use ramp\SETTING;
use ramp\core\Str;
// use ramp\core\Collection;
// use ramp\core\OptionList;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
// use ramp\model\business\Record;
// use ramp\model\business\field\Option;
use ramp\model\business\field\Flag;

use tests\ramp\model\business\field\mocks\FlagTest\MockRecord;

/**
 * Collection of tests for \ramp\model\business\field\Flag.
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
    \ramp\SETTING::$RAMPE_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\field\mocks\FlagTest';
  }

  /**
    * Collection of assertions for \ramp\model\business\field\Flag::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\field\Field}
   * - assert is instance of {@link \ramp\model\field\Flag}
   * @link ramp.model.business.field.Flag ramp\model\business\field\Flag
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
    $this->assertInstanceOf('\ramp\model\business\field\Flag', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.Flag#method_get_id ramp\model\business\field\Flag::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame($this->mockRecord->id . ':a-property', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::value.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert default 'value' returns FASLE.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.Flag#method_get_value ramp\model\business\field\Flag::value
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
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::containingRecord.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'containingRecord'
   * - assert property 'containingRecord' is gettable.
   * - assert returned Record matches Record as provided construct.
   * @link ramp.model.business.field.Flag#method_get_containingRecord ramp\model\business\field\Flag::containingRecord
   */
  public function testGet_containingRecord()
  {
    try {
      $this->testObject->containingRecord = $this->mockRecord;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->mockRecord, $this->testObject->containingRecord);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.Flag#method_get_type ramp\model\business\field\Flag::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertEquals(' flag field', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through NO objects, as there are NO children.
   * @link ramp.model.business.field.Flag#method_getIterator ramp\model\business\field\Flag::getIterator()
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
   * Collection of assertions for \ramp\model\business\field\Flag::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds as NO children
   * @link ramp.model.business.field.Flag#method_offsetGet ramp\model\business\field\Flag::offsetGet()
   */
  public function testOffsetGet()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->testObject[0];
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::offsetExists.
   * - assert False returned on isset() NO children outside expected bounds.
   * @link ramp.model.business.field.Flag#method_offsetExists ramp\model\business\field\Flag::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Collection of assertions for ramp\model\business\field\Flag::offsetSet().
   * - assert throws BadMethodCallException as this method should be inaccessible
   *   - with message: <em>'Array access setting is not allowed, please use add.'</em>
   * @link ramp.model.business.field.Flag#method_offsetSet \ramp\model\business\field\Flag::offsetSet()
   */
  public function testOffsetSet()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access setting is not allowed.';
    $this->testObject[0] = 'VALUE';
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Flag::offsetUnset.
   * - assert throws BadMethodCallException whenever offsetUnset is called
   *  - with message *Array access unsetting is not allowed.*
   * @link ramp.model.business.field.Flag#method_offsetUnset ramp\model\business\field\Flag::offsetUnset()
   */
  public function testOffsetUnset()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access unsetting is not allowed.';
    unset($this->testObject[0]);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::validate() where PostData
   * does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *    matches the testObject's id, then its containing Record's setPropertyValue method is NOT called
   *    and NOT modified.
   * @link ramp.model.business.field.Field#method_validate ramp\model\business\field\Field::validate()
   */
  public function testValidateProcessValidationRuleNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(0, MockRecord::$setPropertyCallCount);
    $this->assertFalse($this->mockRecord->isModified);
  }

  /**
   * Further collection of assertions for \ramp\model\business\field\Field::validate(), where
   * PostData contains an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData contains an InputDataCondition with an attribute that matches
   *    the testObject's id and its processValidationRule method passes, then its
   *    containingRecord setPropertyMethod is called
   *    and the nessasary change to the property have occurred.
   * - assert validate method is NOT propagated through to its children and grandchildren.
   * @link ramp.model.business.field.Field#method_validate ramp\model\business\field\Field::validate()
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
   * Collection of assertions for \ramp\model\business\field\Field::hasErrors().
   * - assert returns False when PostData does NOT contain an InputDataCondition with an attribute
   *   that matches the testObject's id.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *   matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert does NOT propagates through to its child/grandchild.
   * @link ramp.model.business.field.Field#method_hasErrors ramp\model\business\field\Field::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::getErrors().
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
   * @link ramp.model.business.field.Field#method_getErrors ramp\model\business\field\Field::getErrors()
   */
  public function testGetErrors()
  {
    // PostData does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(0, MockRecord::$setPropertyCallCount);
    $this->assertInstanceOf('\ramp\core\iCollection', $errors);
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
    $this->assertInstanceOf('\ramp\core\iCollection', $thirdCallOnErrors);
    $this->assertSame(1, $thirdCallOnErrors->count);
    $this->assertSame('Flag input can only be one of True or False.', (string)$thirdCallOnErrors[0]);
    // Returns same results on subsequent call, while Field in same state.
    $forthCallOnErrors = $this->testObject->errors;
    $this->assertEquals($forthCallOnErrors, $thirdCallOnErrors);
    $this->assertTrue(isset($thirdCallOnErrors[0]));
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Field::count.
   * - assert return expected int value related to the number of children (NO children).
   * @link ramp.model.business.field.Field#method_count ramp\model\business\field\Field::count
   */
  public function testCount()
  {
    $this->assertSame(0, $this->testObject->count);
  }
}
