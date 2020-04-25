<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
 */
namespace tests\svelte\condition;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';

require_once '/usr/share/php/tests/svelte/condition/mocks/BusinessConditionTest/Field.class.php';
require_once '/usr/share/php/tests/svelte/condition/mocks/BusinessConditionTest/Record.class.php';
require_once '/usr/share/php/tests/svelte/condition/mocks/BusinessConditionTest/ConcreteBusinessCondition.class.php';

use svelte\core\Str;
use svelte\core\PropertyNotSetException;
use svelte\condition\Operator;

use tests\svelte\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition;

/**
 * Collection of tests for \svelte\condition\BusinessCondition.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition}
 * - {@link \tests\svelte\condition\mocks\BusinessConditionTest\Property}
 * - {@link \tests\svelte\condition\mocks\BusinessConditionTest\Record}
 */
class BusinessConditionTest extends \PHPUnit\Framework\TestCase
{
  private $property;
  private $record;
  private $operator;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='tests\svelte\condition\mocks\BusinessConditionTest';
    $this->record = Str::set('Record');
    $this->property = Str::set('property');
    $this->operator = Operator::EQUAL_TO();
    $this->expectedAttribute = $this->property->prepend($this->record->append(Str::set('->')));
  }

  /**
   * Collection of assertions for \svelte\BusinessCondition::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\condition\Condition}
   * - assert is instance of {@link \svelte\condition\BusinessCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * @link svelte.condition.BusinessCondition#method___construct svelte\condition\BusinessCondition
   */
  public function test__construct()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\svelte\condition\Condition', $testObject);
    $this->assertInstanceOf('\svelte\condition\BusinessCondition', $testObject);

    try {
      $testObject = new ConcreteBusinessCondition(Str::set('NotARecord'), $this->property, $this->operator);
    } catch (\DomainException $expected) {
      $this->AssertSame(
        'Invalid: NotARecord->property, does NOT match business model',
        $expected->getMessage()
      );
      try {
        $testObject = new ConcreteBusinessCondition($this->record, Str::set('NotAProperty'), $this->operator);
      } catch (\DomainException $expected) {
        $this->AssertSame(
          'Invalid: Record->NotAProperty, does NOT match business model',
          $expected->getMessage()
        );
        return;
      }
    }
    $this->fail('An expected \DomianException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\BusinessCondition::attribute.
   * - assert throws {@link \svelte\core\PropertyNotSetException} trying to set 'attribute'
   *   - with message: <em>'[className]->attribute is NOT settable'</em>
   * - assert allows retrieval of 'attribute'
   * - assert retrieved is a {@link \svelte\core\Str}
   * - assert 'attribute' is composite of [property]->[property]
   * @link svelte.condition.BusinessCondition#method_get_attribute svelte\condition\BusinessCondition::attribute
   */
  public function testAttribute()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    $this->assertInstanceOf('\svelte\core\Str', $testObject->attribute);
    try {
      $testObject->attribute = Str::set($this->expectedAttribute);
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\svelte\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->attribute is NOT settable',
        $expected->getMessage()
      );
      $this->assertEquals($this->expectedAttribute, $testObject->attribute);
      return;
    }
    $this->fail('An expected svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\BusinessCondition::record.
   * - assert throws {@link \svelte\core\PropertyNotSetException} trying to set 'record'
   *   - with message: <em>'[className]->record is NOT settable'</em>
   * - assert allows retrieval of 'record'
   * - assert 'record' is a {@link \svelte\core\Str}
   * - assert 'record' equal to provided at creation
   * @link svelte.condition.BusinessCondition#method_get_record svelte\condition\BusinessCondition::record
   */
  public function testRecord()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    try {
      $testObject->record = $this->record;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\svelte\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->record is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $testObject->record);
      $this->assertSame($this->record, $testObject->record);
      return;
    }
    $this->fail('An expected svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\BusinessCondition::property.
   * - assert throws {@link \svelte\core\PropertyNotSetException} trying to set 'property'
   *   - with message: <em>'[className]->property is NOT settable'</em>
   * - assert allows retrieval of 'property'
   * - assert 'property' is a {@link \svelte\core\Str}
   * - assert 'property' equal to provided at creation
   * @link svelte.condition.BusinessCondition#method_get_property svelte\condition\BusinessCondition::property
   */
  public function testProperty()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    try {
      $testObject->property = $this->property;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\svelte\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->property is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $testObject->property);
      $this->assertSame($this->property, $testObject->property);
      return;
    }
    $this->fail('An expected svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\BusinessCondition::operator.
   * - assert throws {@link \svelte\core\PropertyNotSetException} when trying to set 'operator'
   *   - with message: <em>'[className]->operator is NOT settable'</em>.
   * - assert allows retrieval of 'operator'.
   * - assert retreved is an instance of {@link \svelte\condition\Operator}.
   * - assert retreved is same as provided to constructor.
   * @link svelte.condition.BusinessCondition#method_get_operator svelte\condition\BusinessCondition::operator.
   */
  public function testOperator()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    try {
      $testObject->operator = $this->operator;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\svelte\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->operator is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\condition\Operator', $testObject->operator);
      $this->assertSame($this->operator, $testObject->operator);
      return;
    }
    $this->fail('An expected svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\BusinessCondition::comparable.
   * - assert 'comparable' default is NULL
   * - assert allows setting of 'comparable'
   * - assert allows retrieval of 'comparable'
   * - assert 'comparable' equal to recently set
   * - assert 'comparable' equal to that provided at creation
   * @link svelte.condition.BusinessCondition#method_get_comparable svelte\condition\BusinessCondition::comparable (get)
   * @link svelte.condition.BusinessCondition#method_set_comparable svelte\condition\BusinessCondition::comparable (set)
   */
  public function testComparable()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    $this->assertNull($testObject->comparable);
    $testObject->comparable = 'GOOD';
    $this->assertSame('GOOD', $testObject->comparable);

    $testObject2 = new ConcreteBusinessCondition($this->record, $this->property, $this->operator, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $testObject2->comparable);
  }
}
