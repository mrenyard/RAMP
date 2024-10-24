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
 * @author mrenyard (renyard.m@gmail.com)
 * @version 0.0.9;
 * @package RAMP.test
 */
namespace tests\ramp\condition;

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteCondition.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\Condition;
use ramp\condition\Operator;
use ramp\condition\Environment;

use tests\ramp\mocks\condition\ConcreteCondition;

/**
 * Collection of tests for \ramp\condition\Condition.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\ConcreteCondition}
 */
class ConditionTest extends \tests\ramp\core\ObjectTest
{
  protected $type;
  protected $attribute;
  protected $operator;

  #region Setup
  protected function preSetup() : void {
    $this->attribute = Str::set('attributeName');
    $this->operator = Operator::EQUAL_TO();
  }
  protected function getTestObject() : RAMPObject {
    return new ConcreteCondition($this->attribute, $this->operator);
  }
  #endregion

  /**
   * Default base constructor.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Condition}
   * @see \ramp\condition\Condition
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\condition\Condition', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
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
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Collection of assertions for BusinessCondition::$attribute.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'attribute'
   *   - with message: *'[className]->attribute is NOT settable'*
   * - assert allows retrieval of 'attribute'
   * - assert retrieved is a {@see \ramp\core\Str}
   * - assert 'attribute' SAME as provided at contruction.
   * @see \ramp\condition\Condition::$attribute
   */
  public function testAttribute() : void
  {
    try {
      $this->testObject->attribute = $this->attribute;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->attribute is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->attribute);
      $this->assertSame($this->attribute, $this->testObject->attribute);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for BusinessCondition::$operator.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'operator'
   *   - with message: *'[className]->operator is NOT settable'*.
   * - assert allows retrieval of 'operator'.
   * - assert retreved is an instance of {@see \ramp\condition\Operator}.
   * - assert retreved is same as provided to constructor.
   * @see \ramp\condition\Condition::$operator
   */
  public function testOperator() : void
  {
    try {
      $this->testObject->operator = $this->operator;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->operator is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\condition\Operator', $this->testObject->operator);
      $this->assertSame($this->operator, $this->testObject->operator);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for BusinessCondition::$comparable.
   * - assert 'comparable' default is NULL
   * - assert allows setting of 'comparable'
   * - assert allows retrieval of 'comparable'
   * - assert 'comparable' equal to recently set
   * - assert 'comparable' equal to that provided at creation
   * @see \ramp\condition\Condition::$comparable
   */
  public function testComparable() : void
  {
    $this->assertNull($this->testObject->comparable);
    $this->testObject->comparable = 'GOOD';
    $this->assertSame('GOOD', $this->testObject->comparable);
    $this->testObject2 = new ConcreteCondition($this->attribute, $this->operator, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $this->testObject2->comparable);
  }
  #endregion
}
