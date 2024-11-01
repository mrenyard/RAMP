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

/**
 * Collection of tests for \ramp\model\business\BusinessModel.
 */
class BusinessModelTest extends \tests\ramp\model\ModelTest
{
  protected $constructorChildren;
  protected $expectedChildCountNew;
  protected $expectedChildCountExisting;
  protected $childErrorIndexes;
  protected $postData;

  #region Setup
  protected function getTestObject() : RAMPObject { return new MockBusinessModel(); }
  protected function postSetup() : void { $this->expectedChildCountNew = 0; }
  #endregion

  /**
   * Default base constructor assertions \ramp\model\business\BusinessModel.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\model\business\BusinessModel}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \Countable}
   * @see \ramp\model\business\BusinessModel
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\core\iList', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
  }
  
  #region Sub model templates model setup
  protected function populateSubModelTree() : void
  {
    $this->testObject[0] = new MockBusinessModel();
    $this->testObject[1] = new MockBusinessModel();
    $this->testObject[1][0] = new MockBusinessModel(TRUE);
    $this->testObject[2] = new MockBusinessModel(TRUE);
    $this->expectedChildCountExisting = 3;
    $this->childErrorIndexes = array(1,2);
    $this->postData = new PostData();
  }
  protected function complexModelIterationTypeCheck() : void
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[0]->type);
    $this->assertSame('mock-business-model business-model', (string)$this->testObject[0]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1]->type);
    $this->assertSame('mock-business-model business-model', (string)$this->testObject[1]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[1][0]->type);
    $this->assertSame('mock-business-model business-model', (string)$this->testObject[1][0]->type);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject[2]->type);
    $this->assertSame('mock-business-model business-model', (string)$this->testObject[2]->type);
  }
  #endregion

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\business\BusinessModel::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\business\BusinessModel::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\business\BusinessModel::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\business\BusinessModel::__get()
   */
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\business\BusinessModel::__toString().
   * - assert {@see \ramp\model\business\BusinessModel::__toString()} returns string 'class name'
   * @see \ramp\model\business\BusinessModel::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }
  #endregion

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
   *   - assert returned value is of type {@see \ramp\core\Str}.
   *   - assert returned value matches expected result.
   * - assert getIterator() returns object instance of {@see \Traversable}
   * - assert foreach iterates zero times as no properties are present.
   * - assert OffsetExists False returned on isset() when indexed with invalid index (0).
   * - assert return expected int value related to the number of child Records held (0).
   * - assert hasErrors returns FALSE.
   * - assert returned errors are as expected:
   *   - assert errors instance of {@see \ramp\core\StrCollection}.
   *   - assert errors count is 0.
   * @see \ramp\model\business\BusinessModel::$type
   * @see \ramp\model\business\BusinessModel::getIterator()
   * @see \ramp\model\business\BusinessModel::offsetExists()
   * @see \ramp\model\business\BusinessModel::$count
   * @see \ramp\model\business\BusinessModel::$hasErrors
   * @see \ramp\model\business\BusinessModel::$Errors
   */
  public function testInitStateMin() : void
  {
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
    $type1 = $this->processType(get_class($this->testObject), TRUE);
    $type2 = $this->processType(get_parent_class($this->testObject), TRUE);
    $this->assertSame($type1 . ' ' . $type2, (string)$this->testObject->type);
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0; foreach ($this->testObject as $child) { $i++; }
    $this->assertSame($this->expectedChildCountNew, $i);
    $this->assertFalse(isset($this->testObject[$this->expectedChildCountExisting]));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertInstanceOf('\ramp\core\StrCollection', $this->testObject->errors);
    $this->assertSame(0, $this->testObject->errors->count);
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\BusinessModel::$id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'.
   * @see \ramp\model\business\BusinessModel::$id
   */
  public function testSetIdPropertyNotSetException() : void
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->id is NOT settable');
    $this->testObject->id = 'ID';
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\BusinessModel::$type.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'.
   * @see \ramp\model\business\BusinessModel::type
   */
  public function testSetTypePropertyNotSetException() : void
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->type is NOT settable');
    $this->testObject->type = 'TYPE';
  }

  /**
   * Get 'children' NOT accessable.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'.
   */
  public function testGetChildrenBadPropertyCallException() : void
  {
    $this->expectException(BadPropertyCallException::class);
    $this->expectExceptionMessage('Unable to locate \'children\' of \'' . get_class($this->testObject) . '\'');
    $o = $this->testObject->children;
  }

  /**
   * Index beyond bounds with \ramp\model\business\BusinessModel::offsetGet.
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\BusinessModel::offsetGet()
   */
  public function testOffsetGetOutOfBounds() : void
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->expectExceptionMessage('Offset out of bounds');
    $o = $this->testObject[$this->expectedChildCountExisting];
  }

  /**
   * Offset addition minimum type checking test.
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\Record::offsetSet()
   * @see \ramp\model\business\Record::offsetUnsSet()
   */
  public function testOffsetSetTypeCheckException(?string $minAllowedType = NULL, ?RAMPObject $objectOutOfScope = new AnObject(), ?string $errorMessage = NULL) : void
  {
    $minAllowedType = ($minAllowedType === NULL) ? 'ramp\model\business\BusinessModel' : $minAllowedType;
    $objectOutOfScope = ($objectOutOfScope === NULL) ? new AnObject() : $objectOutOfScope;
    $errorMessage = ($errorMessage === NULL) ? "$objectOutOfScope NOT instanceof $minAllowedType" : $errorMessage;
    try {
      $this->testObject[0] = $objectOutOfScope;
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame($errorMessage, $expected->getMessage());
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Index editing of children with offsetSet and offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @see \ramp\model\business\BusinessModel::offsetSet()
   * @see \ramp\model\business\BusinessModel::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(?BusinessModel $o = NULL) : void
  {
    $o = (isset($o)) ? $o : new MockBusinessModel();
    $i = $this->testObject->count;
    $this->offsetSet($o, $i);
    $this->offsetUnset($i);
  }
  protected function offsetSet(BusinessModel $o, int $i)
  {
    $this->testObject[$i] = $o;
    $this->assertSame($o, $this->testObject[$i]);
  }
  protected function offsetUnset(int $i)
  {
    unset($this->testObject[$i]);
    $this->assertFalse(isset($this->testObject[$i]));
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert foreach loop, iterates through each expected object:
   *   - assert returns object that is an instance of {@see \Traversable}
   *   - assert foreach returned object matches expected.
   * - assert expected object returned at its expected index.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @see \ramp\model\business\BusinessModel::getIterator()
   * @see \ramp\model\business\BusinessModel::offsetGet()
   * @see \ramp\model\business\Relatable::offsetExists()
   * @see \ramp\model\business\BusinessModel::$count
   */
  public function testComplexModelIteration() : void
  {
    $this->populateSubModelTree();
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->testObject->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child) {
      $this->assertSame($child, $iterator->current());
      $this->assertInstanceOf('\ramp\model\business\BusinessModel', $iterator->current());
      $iterator->next();
    }
    $this->complexModelIterationTypeCheck();
    $this->assertSame($this->expectedChildCountExisting, $this->testObject->count);
    $this->assertSame($this->expectedChildCountExisting, $this->testObject->count());
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @see \ramp\model\business\BusinessModel::validate()
   * @see \ramp\model\business\BusinessModel::$hasErrors
   */
  public function testTouchValidityAndErrorMethods($touchCountTest = TRUE) : void
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    if ($touchCountTest) {
      $i = 0;
      foreach ($this->testObject as $child) {
        $touch = ($i <= $this->childErrorIndexes[0]) ? 1 : 0;
        $this->assertSame(1, $child->validateCount);
        $this->assertGreaterThanOrEqual($touch, $child->hasErrorsCount);
        $i++;
      }
      $this->assertEquals($this->expectedChildCountExisting, $i);
    }
  }

  /**
   * Error reporting within complex models.
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @see \ramp\model\business\BusinessModel::$errors
   */
  public function testErrorReportingPropagation($message = 'Error MESSAGE BadValue Submited!') : void
  {
    $this->populateSubModelTree();
    $this->assertNull($this->testObject->validate($this->postData)); // Call
    $this->assertTrue($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(count($this->childErrorIndexes), $errors->count);
    $i = 0;
    do {
    $this->assertSame($message, (string)$errors[$i++]);
    } while  ($i < $errors->count);
  }
  #endregion
}
