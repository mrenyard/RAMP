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
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\model\business;

require_once '/usr/share/php/tests/ramp/model/ModelTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModelWithErrors.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\core\PropertyNotSetException;
use ramp\core\BadPropertyCallException;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;

use tests\ramp\mocks\core\AnObject;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\model\MockBusinessModelCollection;
use tests\ramp\mocks\model\MockBusinessModelWithErrors;

/**
 * Collection of tests for \ramp\model\business\BusinessModel.
 */
class BusinessModelTest extends \tests\ramp\model\ModelTest
{
  protected $expectedChildCount;

  private $testChild1;
  private $testChild2;
  private $testChild3;
  private $grandChild;

  /**
   * Template method inc. factory for TestObject instance.
   */
  protected function getTestObject() : RAMPObject { return new MockBusinessModel('Top object'); }
  protected function postSetup() : void { $this->expectedChildCount = 0; }

  /**
   * Default base constructor assertions \ramp\model\business\BusinessModel::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\core\iList}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * @link ramp.model.business.BusinessModel ramp\model\business\BusinessModel::__construct()
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\ramp\core\iList', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
  }

  /**
   * Returns Business model type without namespace from full class name.
   * @param string $classFullName Full class name including path/namespace
   * @param \ramp\core\Boolean $hyphenate Whether model type should be returned hyphenated
   * @return \ramp\core\Str *This* business model type (without namespace)
   */
  protected function processType($classFullName, bool $hyphenate = null) : Str
  {
    $pathNode = explode('\\', $classFullName);
    $modelName = explode('_', array_pop($pathNode));
    $type = Str::set(array_pop($modelName));
    return ($hyphenate)? Str::hyphenate($type) : $type;
  }

  /**
   * Minimumal BusinessModel initial state.
   * - assert property 'type' is gettable:
   *   - assert returned value is of type {@link \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert getIterator() returns object instance of {@link \Traversable}
   * - assert foreach iterates zero times as no properties are present.
   * - assert OffsetExists False returned on isset() when indexed with invalid index (0).
   * - assert return expected int value related to the number of child Records held (0).
   * - assert hasErrors returns FALSE.
   * - assert returned errors are as expected:
   *   - assert errors instance of {@link \ramp\core\StrCollection}.
   *   - assert errors count is 0.
   * @link ramp.model.business.BusinessModel#method_get_type ramp\model\business\BusinessModel::type
   * @link ramp.model.business.BusinessModel#method_getIterator ramp\model\business\BusinessModel::getIterator()
   * @link ramp.model.business.BusinessModel#method_offsetExists ramp\model\business\BusinessModel::offsetExists()
   * @link ramp.model.business.BusinessModel#method_count ramp\model\business\BusinessModel::count()
   * @link ramp.model.business.BusinessModel#method_hasErrors ramp\model\business\BusinessModel::hasErrors()
   * @link ramp.model.business.BusinessModel#method_getErrors ramp\model\business\BusinessModel::getErrors()
   */
  public function testInitStateMin()
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
    $type1 = $this->processType(get_class($this->testObject), TRUE);
    $type2 = $this->processType(get_parent_class($this->testObject), TRUE);
    $this->assertSame($type1 . ' ' . $type2, (string)$this->testObject->type);
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0; foreach ($this->testObject as $child) { $i++; }
    $this->assertSame($this->expectedChildCount, $i);
    $this->assertFalse(isset($this->testObject[$this->expectedChildCount]));
    $this->assertSame($this->expectedChildCount, $this->testObject->count);
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertInstanceOf('\ramp\core\StrCollection', $this->testObject->errors);
    $this->assertSame(0, $this->testObject->errors->count);
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\BusinessModel::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.BusinessModel#method_set_id ramp\model\business\BusinessModel::id
   */
  public function testSetIdPropertyNotSetException()
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->id is NOT settable');
    $this->testObject->id = 'ID';
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\BusinessModel::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.BusinessModel#method_set_type ramp\model\business\BusinessModel::type
   */
  public function testSetTypePropertyNotSetException()
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->type is NOT settable');
    $this->testObject->type = 'TYPE';
  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\BusinessModel::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.BusinessModel#method_get_children ramp\model\business\BusinessModel::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    $this->expectException(BadPropertyCallException::class);
    $this->expectExceptionMessage('Unable to locate children of \'' . get_class($this->testObject) . '\'');
    $o = $this->testObject->children;
  }

  /**
   * Index beyond bounds with \ramp\model\business\BusinessModel::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.BusinessModel#method_offsetGet ramp\model\business\BusinessModel::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->expectExceptionMessage('Offset out of bounds');
    $o = $this->testObject[$this->expectedChildCount];
  }

  /**
   * Index editing of children through \ramp\model\business\BusinessModel::offsetSet and
   * for \ramp\model\business\BusinessModel::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.BusinessModel#method_offsetSet ramp\model\business\BusinessModel::offsetSet()
   * @link ramp.model.business.BusinessModel#method_offsetUnset ramp\model\business\BusinessModel::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    $o = (isset($o)) ? $o : new MockBusinessModel('Forth child');
    $this->testObject[0] = $o;
    $this->assertSame($o, $this->testObject[0]);
    unset($this->testObject[0]);
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Populates $testObject with hierarchal model for testing against. 
   */
  private function populateModelChildren()
  {
    MockBusinessModel::reset();
    $children = new MockBusinessModelCollection();
    $grandchildren = new MockBusinessModelCollection();
    $this->testChild1 = new MockBusinessModel('First child');
    $children->add($this->testChild1);
    $this->testChild2 = new MockBusinessModelWithErrors('Second child');
    $children->add($this->testChild2);
    $this->testChild3 = new MockBusinessModel('Third child', $grandchildren);
    $children->add($this->testChild3);
    $this->grandchild = new MockBusinessModelWithErrors('First grandchild');
    $grandchildren->add($this->grandchild);
    $this->testObject->children = $children;
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable BusinessModel.
   * - assert property 'type' is gettable:
   *   - assert returned value is of type {@link \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert foreach loop, iterates through each expected object:
   *   - assert returns object that is an instance of {@link \Traversable}
   *   - assert foreach returned object matches expected.
   * - assert expected object returned at its expected index.
   * - assert offsetExists returns correctly:
   *   - assert True returned on isset() when within expected bounds.
   *   - assert False returned on isset() when outside expected bounds.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link ramp.model.business.BusinessModel#method_setChildren ramp\model\business\BusinessModel::children
   * @link ramp.model.business.BusinessModel#method_get_type ramp\model\business\BusinessModel::type
   * @link ramp.model.business.BusinessModel#method_getIterator ramp\model\business\BusinessModel::getIterator()
   * @link ramp.model.business.BusinessModel#method_offsetGet ramp\model\business\BusinessModel::offsetGet()
   * @link ramp.model.business.BusinessModel#method_offsetExists ramp\model\business\BusinessModel::offsetExists()
   * @link ramp.model.business.BusinessModel#method_count ramp\model\business\BusinessModel::count
   */
  public function testComplexModelIteration()
  {
    $this->populateModelChildren();

    $this->assertInstanceOf('\ramp\core\Str', $this->testChild1->type);
    $this->assertSame('mock-business-model business-model', (string)$this->testChild1->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testChild2->type);
    $this->assertSame('mock-business-model-with-errors mock-business-model', (string)$this->testChild2->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testChild3->type);
    $this->assertSame('mock-business-model business-model', (string)$this->testChild3->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->grandchild->type);
    $this->assertSame('mock-business-model-with-errors mock-business-model', (string)$this->grandchild->type);

    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->testObject->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertSame('uid-' . $i++, (string)$child->id);
      $iterator->next();
    }
    $this->assertSame(3, $i);

    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject[0]);
    $this->assertSame($this->testChild1, $this->testObject[0]);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject[1]);
    $this->assertSame($this->testChild2, $this->testObject[1]);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject[2]);
    $this->assertSame($this->testChild3, $this->testObject[2]);

    $this->assertTrue(isset($this->testObject[0]));
    $this->assertTrue(isset($this->testObject[1]));
    $this->assertTrue(isset($this->testObject[2]));
    $this->assertTrue(isset($this->testObject[2][0]));
    $this->assertFalse(isset($this->testObject[2][1]));
    $this->assertFalse(isset($this->testObject[3]));

    $this->assertSame(3 ,$this->testObject->count);
    $this->assertSame(3 ,$this->testObject->count());
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable BusinessModel.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link ramp.model.business.BusinessModel#method_setChildren ramp\model\business\BusinessModel::children
   * @link ramp.model.business.BusinessModel#method_validate ramp\model\business\BusinessModel::validate()
   * @link ramp.model.business.BusinessModel#method_hasErrors ramp\model\business\BusinessModel::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    $this->populateModelChildren();

    $this->assertNull($this->testObject->validate(new PostData())); // Call
    $this->assertSame(1, $this->testChild1->validateCount); // touched
    $this->assertSame(1, $this->testChild2->validateCount); // touched
    $this->assertSame(1, $this->testChild3->validateCount); // touched
    $this->assertSame(1, $this->grandchild->validateCount); // touched

    $this->assertTrue($this->testObject->hasErrors); // Call
    $this->assertSame(1, $this->testChild1->hasErrorsCount); // no errors
    $this->assertSame(1, $this->testChild2->hasErrorsCount); // first with-errors (stops here)
    $this->assertSame(0, $this->testChild3->hasErrorsCount);
    $this->assertSame(0, $this->grandchild->hasErrorsCount);
  }

  /**
   * Error reporting within complex models using \ramp\model\business\BusinessModel::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link ramp.model.business.BusinessModel#method_getErrors ramp\model\business\BusinessModel::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    $this->populateModelChildren();

    $this->assertNull($this->testObject->validate(new PostData())); // Call
    $this->assertTrue($this->testObject->hasErrors);

    $errors = $this->testObject->errors;
    // All errors including children and grandchildren of top testObject returned in a single collection.
    $this->assertSame('Second child error message', (string)$errors[0]);
    $this->assertSame('First grandchild error message', (string)$errors[1]);
    $this->assertFalse(isset($errors[2]));

    // Returns same results on subsequent call, while BusinessModels are in same state.
    $secondCallOnErrors = $this->testObject->errors;
    $this->assertEquals($secondCallOnErrors, $errors);
    $this->assertFalse(isset($secondCallOnErrors[2]));

    // Calls on sub BusinessModels return expected sub set of Errors.
    $child2Errors = $this->testChild2->errors;
    $this->assertSame('Second child error message', (string)$errors[0]);
    $this->assertSame('First grandchild error message', (string)$errors[1]);

    // Calls on sub BusinessModels return expected sub set of Errors, even on grandchildren.
    $grandchildErrros = $this->grandchild->errors;
    $this->assertSame('First grandchild error message', (string)$grandchildErrros[0]);
    $this->assertFalse(isset($child3Errros[0]));

    // Because testChild3 in the parent of grandchild it returns grandchild errors alone with any of own.
    $child3Errros = $this->testChild3->errors;
    $this->assertSame('First grandchild error message', (string)$child3Errros[0]);
    $this->assertFalse(isset($child3Errros[1]));
  }
}
