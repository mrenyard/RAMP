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

require_once '/usr/share/php/ramp/model/business/Relatable.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRelatable.class.php';

use ramp\core\RAMPObject;
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
   * Default base constructor assertions \ramp\model\business\Relatable::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\Relatable}
   * @link ramp.model.business.Relatable ramp\model\business\Relatable
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Relatable::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.Model#method__set ramp\model\Relatable::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Relatable::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.Model#method__get ramp\model\Relatable::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\Relatable::__get() and \ramp\model\Relatable::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.Model#method___set \ramp\model\Relatable::__set()
   * @link ramp.model.Model#method___get \ramp\model\Relatable::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Relatable::__toString().
   * - assert {@link \ramp\model\Relatable::__toString()} returns string 'class name'
   * @link ramp.model.Model#method___toString \ramp\model\Relatable::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }

  /**
   * Minimumal Relatable initial state.
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
   * @link ramp.model.business.Relatable#method_get_type ramp\model\business\Relatable::type
   * @link ramp.model.business.Relatable#method_getIterator ramp\model\business\Relatable::getIterator()
   * @link ramp.model.business.Relatable#method_offsetExists ramp\model\business\Relatable::offsetExists()
   * @link ramp.model.business.Relatable#method_count ramp\model\business\Relatable::count()
   * @link ramp.model.business.Relatable#method_hasErrors ramp\model\business\Relatable::hasErrors()
   * @link ramp.model.business.Relatable#method_getErrors ramp\model\business\Relatable::getErrors()
   */
  public function testInitStateMin()
  {
    parent::testInitStateMin();
  }

  /**
   * Set 'id' NOT accessable on \ramp\model\business\Relatable::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * @link ramp.model.business.Relatable#method_set_id ramp\model\business\Relatable::id
   */
  public function testSetIdPropertyNotSetException()
  {
    parent::testSetIdPropertyNotSetException();
  }

  /**
   * Set 'type' NOT accessable on \ramp\model\business\Relatable::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * @link ramp.model.business.Relatable#method_set_type ramp\model\business\Relatable::type
   */
  public function testSetTypePropertyNotSetException()
  {
    parent::testSetTypePropertyNotSetException();

  }

  /**
   * Get 'children' NOT accessable on \ramp\model\business\Relatable::children.
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling property 'children'
   * @link ramp.model.business.Relatable#method_get_children ramp\model\business\Relatable::children
   */
  public function testGetChildrenBadPropertyCallException()
  {
    parent::testGetChildrenBadPropertyCallException();
  }

  /**
   * Index beyond bounds with \ramp\model\business\Relatable::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.Relatable#method_offsetGet ramp\model\business\Relatable::offsetGet()
   */
  public function testOffsetGetOutOfBounds()
  {
    parent::testOffsetGetOutOfBounds();

  }

  /**
   * Offset addition minimum type checking test
   * - assert {@link \InvalidArgumentException} thrown when offset type outside of acceptable scope.
   * @link ramp.model.business.Record#method_offsetSet ramp\model\business\Record::offsetSet()
   */
  public function testOffsetSetTypeCheckException(string $MinAllowedType = NULL, RAMPObject $objectOutOfScope = NULL, string $errorMessage = NULL)
  {
    parent::testOffsetSetTypeCheckException($MinAllowedType, $objectOutOfScope, $errorMessage);
  }

  /**
   * Index editing of children through \ramp\model\business\Relatable::offsetSet and
   * for \ramp\model\business\Relatable::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.Relatable#method_offsetSet ramp\model\business\Relatable::offsetSet()
   * @link ramp.model.business.Relatable#method_offsetUnset ramp\model\business\Relatable::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL)
  {
    parent::testOffsetSetOffsetUnset($o);
  }

  /**
   * Handle complex iterative relations (model flexability).
   * - assert set 'children' modifies interable Relatable.
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
   * @link ramp.model.business.Relatable#method_setChildren ramp\model\business\Relatable::children
   * @link ramp.model.business.Relatable#method_get_type ramp\model\business\Relatable::type
   * @link ramp.model.business.Relatable#method_getIterator ramp\model\business\Relatable::getIterator()
   * @link ramp.model.business.Relatable#method_offsetGet ramp\model\business\Relatable::offsetGet()
   * @link ramp.model.business.Relatable#method_offsetExists ramp\model\business\Relatable::offsetExists()
   * @link ramp.model.business.Relatable#method_count ramp\model\business\Relatable::count
   */
  public function testComplexModelIteration()
  {
    parent::testComplexModelIteration();
  }

  /**
   * Touch Validity checking and error checking within complex models.
   * - assert set 'children' modifies interable Relatable.
   * - assert validate method returns void (null) when called.
   * - assert validate method is propagated through (touched on) testsObject and all of its children and grandchildren.
   * - assert returns True when any child/grandchild has recorded (a simulated) errors.
   * - assert propagates through child/grandchild until reaches one that has recorded errors.
   * @link ramp.model.business.Relatable#method_setChildren ramp\model\business\Relatable::children
   * @link ramp.model.business.Relatable#method_validate ramp\model\business\Relatable::validate()
   * @link ramp.model.business.Relatable#method_hasErrors ramp\model\business\Relatable::hasErrors()
   */
  public function testTouchValidityAndErrorMethods()
  {
    parent::testTouchValidityAndErrorMethods();
  }

  /**
   * Error reporting within complex models using \ramp\model\business\Relatable::getErrors().
   * - assert following validate(), the expected iCollection of error messages returned from
   *    getErrors() are as expected, depending on which level they are called.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors including children and grandchildren
   *    of top testObject returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub BusinessModels
   * @link ramp.model.business.Relatable#method_getErrors ramp\model\business\Relatable::getErrors()
   */
  public function testErrorReportingPropagation()
  {
    parent::testErrorReportingPropagation();
  }
  #endregion
}
