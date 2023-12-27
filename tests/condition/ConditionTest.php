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

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/ConditionTest/ConcreteCondition.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\Condition;
use ramp\condition\Operator;
use ramp\condition\Environment;

use tests\ramp\condition\mocks\ConditionTest\ConcreteCondition;

/**
 * Collection of tests for \ramp\condition\Condition.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\condition\mocks\ConditionTest\ConcreteCondition}
 */
class ConditionTest extends \PHPUnit\Framework\TestCase
{
  private $attribute;
  private $operator;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->attribute = Str::set('attributeName');
    $this->operator = Operator::EQUAL_TO();
  }

  /**
   * Default base constructor.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Condition}
   * @see \ramp\condition\Condition
   */
  public function test__construct()
  {
    $testObject = new ConcreteCondition($this->attribute, $this->operator);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\condition\Condition', $testObject);
  }

  /**
   * Collection of assertions for BusinessCondition::$attribute.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'attribute'
   *   - with message: *'[className]->attribute is NOT settable'*
   * - assert allows retrieval of 'attribute'
   * - assert retrieved is a {@see \ramp\core\Str}
   * - assert 'attribute' is composite of [property]->[property]
   * @see \ramp\condition\Condition::$attribute
   */
  public function testAttribute() : void
  {
    $testObject = new ConcreteCondition($this->attribute, $this->operator);
    try {
      $testObject->attribute = $this->attribute;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\ramp\condition\mocks\ConditionTest\ConcreteCondition->attribute is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $testObject->attribute);
      $this->assertSame($this->attribute, $testObject->attribute);
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
   * @see \ramp\condition\Condition::$operator.
   */
  public function testOperator() : void
  {
    $testObject = new ConcreteCondition($this->attribute, $this->operator);
    try {
      $testObject->operator = $this->operator;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\ramp\condition\mocks\ConditionTest\ConcreteCondition->operator is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\condition\Operator', $testObject->operator);
      $this->assertSame($this->operator, $testObject->operator);
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
    $testObject = new ConcreteCondition($this->attribute, $this->operator);
    $this->assertNull($testObject->comparable);
    $testObject->comparable = 'GOOD';
    $this->assertSame('GOOD', $testObject->comparable);
    $testObject2 = new ConcreteCondition($this->attribute, $this->operator, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $testObject2->comparable);
  }
}
