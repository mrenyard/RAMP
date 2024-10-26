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

require_once '/usr/share/php/tests/ramp/model/business/BusinessModelTest.php';

require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRelatable.class.php';

use ramp\core\RAMPObject;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;

use tests\ramp\mocks\model\MockRelatable;
use tests\ramp\mocks\model\MockBusinessModel;

/**
 * Collection of tests for \ramp\model\business\Relatable.
 */
class RelatableTest extends \tests\ramp\model\business\BusinessModelTest
{
  #region Setup
  protected function getTestObject() : RAMPObject { return new MockRelatable(); }
  #endregion

  /**
   * Default base constructor assertions \ramp\model\business\Relatable.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\core\iOption}
   * - assert is instance of {@see \IteratorAggregate}
   * - assert is instance of {@see \Countable}
   * - assert is instance of {@see \ArrayAccess}
   * - assert is instance of {@see \ramp\model\business\Relatable}
   * @see \ramp\model\business\Relatable
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject);
  }

  #region Sub model setup
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
   * Bad property (name) NOT accessable on \ramp\model\Relatable::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Relatable::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Relatable::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Relatable::__get()
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
   * Correct return of ramp\model\Relatable::__toString().
   * - assert {@see \ramp\model\Relatable::__toString()} returns string 'class name'
   * @see \ramp\model\Relatable::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Minimumal Relatable initial state.
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
   * @see \ramp\model\business\Relatable::type
   * @see \ramp\model\business\Relatable::getIterator()
   * @see \ramp\model\business\Relatable::offsetExists()
   * @see \ramp\model\business\Relatable::count()
   * @see \ramp\model\business\Relatable::hasErrors()
   * @see \ramp\model\business\Relatable::getErrors()
   */
  public function testInitStateMin() : void
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Relatable::id.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @see \ramp\model\business\Relatable::id
   */
  public function testSetIdPropertyNotSetException() : void
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable.
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @see \ramp\model\business\Relatable::$type
   */
  public function testSetTypePropertyNotSetException() : void
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessable.
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   */
  public function testGetChildrenBadPropertyCallException() : void
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds (offsetGet).
   * - assert {@see \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @see \ramp\model\business\Relatable::offsetGet()
   */
  public function testOffsetGetOutOfBounds() : void
  {
    parent::testOffsetGetOutOfBounds();

  }

  /**
   * Offset addition minimum type checking test
   * - assert {@see \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @see \ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetTypeCheckException(?string $minAllowedType = NULL, ?RAMPObject $objectOutOfScope = NULL, ?string $errorMessage = NULL) : void
  {
    parent::testOffsetSetTypeCheckException($minAllowedType, $objectOutOfScope, $errorMessage);
  }

  /**
   * Index editing of children through \ramp\model\business\Relatable::offsetSet and
   * for \ramp\model\business\Relatable::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @see \ramp\model\business\Relatable::offsetSet()
   * @see \ramp\model\business\Relatable::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(?BusinessModel $o = NULL) : void
  {
    parent::testOffsetSetOffsetUnset($o);
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
    parent::testComplexModelIteration();
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
    parent::testTouchValidityAndErrorMethods($touchCountTest);
  }

  /**
   * Error reporting within complex models using \ramp\model\business\Relatable::getErrors().
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
    parent::testErrorReportingPropagation($message);
  }
  #endregion
}
