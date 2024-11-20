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

require_once '/usr/share/php/tests/ramp/condition/ConditionTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/Record.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteBusinessCondition.class.php';

use \ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\Operator;

use tests\ramp\mocks\condition\ConcreteBusinessCondition;

/**
 * Collection of tests for \ramp\condition\BusinessCondition.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\ConcreteBusinessCondition}
 * - {@see \tests\ramp\mocks\condition\Property}
 * - {@see \tests\ramp\mocks\condition\Record}
 */
class BusinessConditionTest extends \tests\ramp\condition\ConditionTest
{
  protected $property;
  protected $record;

  #region Setup
  #[\Override]
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\mocks\condition';
    $this->record = Str::set('Record');
    $this->property = Str::set('propertyA');
    $this->operator = Operator::EQUAL_TO();
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new ConcreteBusinessCondition($this->record, $this->property, $this->operator);
  }
  #[\Override]
  protected function postSetup() : void { $this->attribute = $this->testObject->attribute; }
  #endregion

  /**
   * Default base constructor.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Condition}
   * - assert is instance of {@see \ramp\condition\BusinessCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * @see \ramp\condition\BusinessCondition
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\condition\BusinessCondition', $this->testObject);
    try {
      $testObject1 = new ConcreteBusinessCondition(Str::set('NotARecord'), $this->property, $this->operator);
    } catch (\DomainException $expected) {
      $this->AssertSame(
        'Invalid: NotARecord->propertyA, does NOT match business model',
        $expected->getMessage()
      );
      try {
        $testObject2 = new ConcreteBusinessCondition($this->record, Str::set('NotAProperty'), $this->operator);
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

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
  #[\Override]
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
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Collection of assertions for BusinessCondition::$attribute.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'attribute'
   *   - with message: *'[className]->attribute is NOT settable'*
   * - assert allows retrieval of 'attribute'
   * - assert retrieved is a {@see \ramp\core\Str}
   * - assert 'attribute' SAME as provided at contruction.
   * - assert 'attribute' is composite of [record]->[property]
   * @see \ramp\condition\Condition::$attribute
   */
  #[\Override]
  public function testAttribute() : void
  {
    parent::testAttribute();
    $this->assertSame('Record->propertyA', (string)$this->attribute);
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
  #[\Override]
  public function testOperator() : void
  {
    parent::testOperator();
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
  #[\Override]
  public function testComparable() : void
  {
    $this->assertNull($this->testObject->comparable);
    $this->testObject->comparable = 'GOOD';
    $this->assertSame('GOOD', $this->testObject->comparable);

    $this->testObject2 = new ConcreteBusinessCondition($this->record, $this->property, $this->operator, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $this->testObject2->comparable);
  }
  #endregion

  #region New Specialist Tests
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
    try {
      $this->testObject->record = $this->record;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->record is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->record);
      $this->assertSame($this->record, $this->testObject->record);
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
    try {
      $this->testObject->property = $this->property;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->property is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->property);
      $this->assertSame($this->property, $this->testObject->property);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }
  #endregion
}
