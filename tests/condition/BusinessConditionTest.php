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

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/BusinessConditionTest/Record.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/BusinessConditionTest/ConcreteBusinessCondition.class.php';

use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\Operator;

use tests\ramp\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition;

/**
 * Collection of tests for \ramp\condition\BusinessCondition.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition}
 * - {@see \tests\ramp\condition\mocks\BusinessConditionTest\Property}
 * - {@see \tests\ramp\condition\mocks\BusinessConditionTest\Record}
 */
class BusinessConditionTest extends \PHPUnit\Framework\TestCase
{
  private $property;
  private $record;
  private $operator;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\condition\mocks\BusinessConditionTest';
    $this->record = Str::set('Record');
    $this->property = Str::set('property');
    $this->operator = Operator::EQUAL_TO();
    $this->expectedAttribute = $this->property->prepend($this->record->append(Str::set('->')));
  }

  /**
   * Default base constructor.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Condition}
   * - assert is instance of {@see \ramp\condition\BusinessCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * @see \ramp\condition\BusinessCondition
   */
  public function test__construct()
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\condition\Condition', $testObject);
    $this->assertInstanceOf('\ramp\condition\BusinessCondition', $testObject);

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
   * Collection of assertions for BusinessCondition::$attribute.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'attribute'
   *   - with message: *'[className]->attribute is NOT settable'*
   * - assert allows retrieval of 'attribute'
   * - assert retrieved is a {@see \ramp\core\Str}
   * - assert 'attribute' is composite of [property]->[property]
   * @see \ramp\condition\Condition::attribute
   */
  public function testAttribute() : void
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    $this->assertInstanceOf('\ramp\core\Str', $testObject->attribute);
    try {
      $testObject->attribute = Str::set($this->expectedAttribute);
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\ramp\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->attribute is NOT settable',
        $expected->getMessage()
      );
      $this->assertEquals($this->expectedAttribute, $testObject->attribute);
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
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    try {
      $testObject->operator = $this->operator;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\ramp\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->operator is NOT settable',
        $expected->getMessage()
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
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    $this->assertNull($testObject->comparable);
    $testObject->comparable = 'GOOD';
    $this->assertSame('GOOD', $testObject->comparable);

    $testObject2 = new ConcreteBusinessCondition($this->record, $this->property, $this->operator, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $testObject2->comparable);
  }

  /**
   * Collection of assertions for BusinessCondition::$record.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'record'
   *   - with message: *'[className]->record is NOT settable'*
   * - assert allows retrieval of 'record'
   * - assert 'record' is a {@see \ramp\core\Str}
   * - assert 'record' equal to provided at creation
   * @see \ramp\condition\BusinessCondition::$record
   */
  public function testRecord() : void
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    try {
      $testObject->record = $this->record;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\ramp\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->record is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $testObject->record);
      $this->assertSame($this->record, $testObject->record);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for BusinessCondition::$property.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'property'
   *   - with message: *'[className]->property is NOT settable'*
   * - assert allows retrieval of 'property'
   * - assert 'property' is a {@see \ramp\core\Str}
   * - assert 'property' equal to provided at creation
   * @see \ramp\condition\BusinessCondition::$property
   */
  public function testProperty() : void
  {
    $testObject = new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
    try {
      $testObject->property = $this->property;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'tests\ramp\condition\mocks\BusinessConditionTest\ConcreteBusinessCondition->property is NOT settable', $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $testObject->property);
      $this->assertSame($this->property, $testObject->property);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }
}
