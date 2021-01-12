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
require_once '/usr/share/php/svelte/core/iOption.class.php';
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
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockRecord.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockField.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/svelte/model/business/field/mocks/FieldTest/MockBusinessModelWithErrors.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
use svelte\model\business\Record;

use tests\svelte\model\business\field\mocks\FieldTest\MockField;
use tests\svelte\model\business\field\mocks\FieldTest\MockRecord;
use tests\svelte\model\business\field\mocks\FieldTest\MockBusinessModel;
use tests\svelte\model\business\field\mocks\FieldTest\MockBusinessModelWithErrors;

/**
 * Collection of tests for \svelte\model\business\field\Field.
 */
class FieldTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $dataObject;
  private $mockRecord;
  private $children;
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
    MockField::reset();
    MockBusinessModel::reset();
    $this->children = new Collection();
    $this->grandchildren = new Collection();
    $this->dataObject = new \stdClass();
    $this->dataObject->aProperty = NULL;
    $this->mockRecord = new MockRecord($this->dataObject);
    $this->testObject = new MockField(Str::set('aProperty'), $this->mockRecord, $this->children);
    $this->testChild1 = new MockBusinessModel('First child');
    $this->testChild2 = new MockBusinessModelWithErrors('Second child');
    $this->testChild3 = new MockBusinessModel('Third child', $this->grandchildren);
    $this->grandchild = new MockBusinessModelWithErrors('First grandchild');
    $this->children->add($this->testChild1);
    $this->children->add($this->testChild2);
    $this->children->add($this->testChild3);
    $this->grandchildren->add($this->grandchild);
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\field\mocks\FieldTest';
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \svelte\model\field\Field}
   * @link svelte.model.business.field.Field svelte\model\business\field\Field
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
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Field#method_get_id svelte\model\business\field\Field::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
      $this->assertSame($this->mockRecord->id . ':a-property', (string)$this->testObject->id);
      $this->assertSame('mock-business-model:0', (string)$this->testChild1->id);
      $this->assertSame('mock-business-model:1', (string)$this->testChild2->id);
      $this->assertSame('mock-business-model:2', (string)$this->testChild3->id);
      $this->assertSame('mock-business-model:3', (string)$this->grandchild->id);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::containingRecord.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'containingRecord'
   * - assert property 'containingRecord' is gettable.
   * - assert returned Record matches Record as provided construct.
   * @link svelte.model.business.field.Field#method_get_containingRecord svelte\model\business\field\Field::containingRecord
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
   * Collection of assertions for \svelte\model\business\field\Field::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.field.Field#method_get_type svelte\model\business\field\Field::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertEquals(' mock-field field', (string)$this->testObject->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testChild1->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->testChild2->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testChild3->type);
      $this->assertSame(' mock-business-model-with-errors mock-business-model', (string)$this->grandchild->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through each expected object.
   * - assert foreach returned object matches expected.
   * @link svelte.model.business.field.Field#method_getIterator svelte\model\business\field\Field::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->children->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertSame('mock-business-model:' . $i++, (string)$child->id);
      $iterator->next();
    }
    $this->assertSame('mock-record:new:a-property', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index.
   * @link svelte.model.business.field.Field#method_offsetGet svelte\model\business\field\Field::offsetGet()
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
   * Collection of assertions for \svelte\model\business\field\Field::offsetExists.
   * - assert True returned on isset() when within expected bounds.
   * - assert False returned on isset() when outside expected bounds.
   * @link svelte.model.business.field.Field#method_offsetExists svelte\model\business\field\Field::offsetExists()
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
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertSame(0, $this->testChild1->validateCount);
    $this->assertSame(0, $this->testChild2->validateCount);
    $this->assertSame(0, $this->testChild3->validateCount);
    $this->assertSame(0, $this->grandchild->validateCount);
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
    $this->assertNull($this->testObject->validate(PostData::build(array(
      'mock-record:new:a-property' => 'GOOD'
    ))));
    $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertSame('GOOD', $this->dataObject->aProperty);
    $this->assertSame(0, $this->testChild1->validateCount);
    $this->assertSame(0, $this->testChild2->validateCount);
    $this->assertSame(0, $this->testChild3->validateCount);
    $this->assertSame(0, $this->grandchild->validateCount);
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
    $this->assertSame(0, MockField::$processValidationRuleCount);
    $this->assertSame(0, $this->testChild1->hasErrorsCount);
    $this->assertSame(0, $this->testChild2->hasErrorsCount);
    $this->assertSame(0, $this->testChild3->hasErrorsCount);
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
    $this->assertSame(0, MockField::$processValidationRuleCount);
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
    $this->assertSame(1, MockField::$processValidationRuleCount);
    $this->assertNull($this->dataObject->aProperty);
    $thirdCallOnErrors = $this->testObject->errors;
    $this->assertInstanceOf('\svelte\core\iCollection', $thirdCallOnErrors);
    $this->assertSame(1, $thirdCallOnErrors->count);
    $this->assertSame('MockField\'s has error due to $value of BAD!', (string)$thirdCallOnErrors[0]);
    // Returns same results on subsequent call, while Field in same state.
    $forthCallOnErrors = $this->testObject->errors;
    $this->assertEquals($forthCallOnErrors, $thirdCallOnErrors);
    $this->assertTrue(isset($thirdCallOnErrors[0]));
  }

  /**
   * Collection of assertions for \svelte\model\business\field\Field::count.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link svelte.model.business.field.Field#method_count svelte\model\business\field\Field::count
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count);
  }
}
