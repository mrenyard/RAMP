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
 * - {@link \tests\ramp\condition\mocks\ConditionTest\ConcreteCondition}
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
   * Collection of assertions for \ramp\Condition::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\condition\Condition}
   * @link ramp.condition.Condition#method___construct ramp\condition\Condition
   */
  public function test__construct()
  {
    $testObject = new ConcreteCondition($this->attribute, $this->operator);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\condition\Condition', $testObject);
  }

  /**
   * Collection of assertions for \ramp\condition\Condition::attribute.
   * - assert throws {@link \ramp\core\PropertyNotSetException} when trying to set 'attribute'
   *   - with message: <em>'[className]->attribute is NOT settable'</em>.
   * - assert allows retrieval of 'attribute'.
   * - assert retreved is an instance of {@link \ramp\core\Str}.
   * - assert retreved is same as provided to constructor.
   * @link ramp.condition.Condition#method_get_attribute ramp\condition\Condition::attribute
   */
  public function testAttribute()
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
   * Collection of assertions for \ramp\condition\Condition::operator.
   * - assert throws {@link \ramp\core\PropertyNotSetException} when trying to set 'operator'
   *   - with message: <em>'[className]->operator is NOT settable'</em>.
   * - assert allows retrieval of 'operator'.
   * - assert retreved is an instance of {@link \ramp\condition\Operator}.
   * - assert retreved is same as provided to constructor.
   * @link ramp.condition.Condition#method_get_operator ramp\condition\Condition::operator.
   */
  public function testOperator()
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
   * Collection of assertions for \ramp\condition\Condition::comparable.
   * - assert 'comparable' default is NULL.
   * - assert allows setting of 'comparable'.
   * - assert allows retrieval of 'comparable'.
   * - assert 'comparable' equal to recently set.
   * - assert 'comparable' equal to that provided to constructor when provided.
   * @link ramp.condition.Condition#method_get_comparable ramp\condition\Condition::comparable (get)
   * @link ramp.condition.Condition#method_set_comparable ramp\condition\Condition::comparable (set)
   */
  public function testComparable()
  {
    $testObject = new ConcreteCondition($this->attribute, $this->operator);
    $this->assertNull($testObject->comparable);
    $testObject->comparable = 'GOOD';
    $this->assertSame('GOOD', $testObject->comparable);
    $testObject2 = new ConcreteCondition($this->attribute, $this->operator, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $testObject2->comparable);
  }
}
