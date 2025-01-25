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

require_once '/usr/share/php/tests/ramp/condition/BusinessConditionTest.php';

require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/Field.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/Record.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteEnvironment.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\Operator;
use ramp\condition\SQLEnvironment;
use ramp\condition\FilterCondition;

use tests\ramp\mocks\condition\ConcreteEnvironment;

/**
 * Collection of tests for \ramp\condition\FilterCondition.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\ConcreteEnvironment}
 * - {@see \tests\ramp\mocks\condition\Property}
 * - {@see \tests\ramp\mocks\condition\Record}
 */
class FilterConditionTest extends \tests\ramp\condition\BusinessConditionTest
{
  protected $comparable;

  #region Setup
  #[\Override]
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\mocks\condition';
    $this->record = Str::set('Record');
    $this->property = Str::set('propertyA');
    $this->operator = Operator::EQUAL_TO();
    $this->comparable = 'GOOD';
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new FilterCondition($this->record, $this->property, $this->comparable);
  }
  #[\Override]
  protected function postSetup() : void { $this->attribute = $this->testObject->attribute; }
  #endregion

  /**
   * Collection of assertions for \ramp\FilterCondition.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Condition}
   * - assert is instance of {@see \ramp\condition\BusinessCondition}
   * - assert is instance of {@see \ramp\condition\FilterCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: *'Invalid $record $property $comparable arguments, do NOT match business model'*
   * @see \ramp\condition\FilterCondition
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\condition\FilterCondition', $this->testObject);
    try {
      $testObject = new FilterCondition(Str::set('NotARecord'), $this->property, 'GOOD');
    } catch (\DomainException $expected) {
      $this->AssertSame(
        'Invalid: NotARecord->propertyA, does NOT match business model',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \DomianException has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__get().
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
    $this->assertSame('GOOD', $this->testObject->comparable);
    $this->testObject->comparable = 'valueA';
    $this->assertSame('valueA', $this->testObject->comparable);

    $this->testObject2 = new FilterCondition($this->record, $this->property, 'COMPARABLE');
    $this->assertSame('COMPARABLE', $this->testObject2->comparable);
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
  #[\Override]
  public function testRecord() : void
  {
    parent::testRecord();
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
  #[\Override]
  public function testProperty() : void
  {
    parent::testProperty();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Collection of assertions for \ramp\condition\FilterCondition::__invoke().
   * - assert returns expected string with SQLEnvironment operation values in the form:
   *  - [record].[property] = "[value]" as default.
   * - assert returns expected string with non default operation values in the form:
   *  - [record].[property] [nonDefaultOperator] "[value]" as default.
   * - assert returns expected string when first argument (iEnvironment) differs from default in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][ComparisonOperator][openingParenthesisOperator][value][closingParenthesisOperator]
   * - assert returns expected string when new value passed as second argument in the form:
   *  - [record].[property] = "[newValue]" where new values is the passes second argument.
   * - assert returns expected string when new value passed as second argument in the form:
   *  - [record].[property] [nonDefaultOperator] "[newValue]" where new values is the passes second argument.
   * - assert returns expected string where both arguments are suplied in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][ComparisonOperator][openingParenthesisOperator][passedValue][closingParenthesisOperator]
   * - assert throws \DomainException on setting invalid second argument for value when it
   *   does Not validate against its associated property's processValidationRules() method
   *   - with message: *'Supplied argument does Not validate against associated property'*
   * @see \ramp\condition\FilterCondition::__invoke()
   */
  public function test__invoke()
  {
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $equalToOperator = Operator::EQUAL_TO();
    $nonDefaultOperator = Operator::NOT_EQUAL_TO();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    $sqlEnvironment = SQLEnvironment::getInstance();
    $concreteEnvironment = ConcreteEnvironment::getInstance();

    $testObject = $this->testObject;
    $testObjectNonDefaultOperator = new FilterCondition(
      $this->record, $this->property, $this->comparable, $nonDefaultOperator
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($sqlEnvironment) . $this->property .
          $equalToOperator($sqlEnvironment) . $openingParenthesisOperator($sqlEnvironment) .
            $this->comparable . $closingParenthesisOperator($sqlEnvironment),
      $testObject()
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($sqlEnvironment) . $this->property .
          $nonDefaultOperator($sqlEnvironment) . $openingParenthesisOperator($sqlEnvironment) .
            $this->comparable . $closingParenthesisOperator($sqlEnvironment),
      $testObjectNonDefaultOperator()
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($concreteEnvironment) . $this->property .
          $equalToOperator($concreteEnvironment) . $openingParenthesisOperator($concreteEnvironment) .
            $this->comparable . $closingParenthesisOperator($concreteEnvironment),
      $testObject($concreteEnvironment)
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($concreteEnvironment) . $this->property .
          $nonDefaultOperator($concreteEnvironment) . $openingParenthesisOperator($concreteEnvironment) .
            $this->comparable . $closingParenthesisOperator($concreteEnvironment),
      $testObjectNonDefaultOperator($concreteEnvironment)
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($sqlEnvironment) . $this->property .
          $equalToOperator($sqlEnvironment) . $openingParenthesisOperator($sqlEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($sqlEnvironment),
      $testObject(null, 'NEW COMPARABLE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($sqlEnvironment) . $this->property .
          $nonDefaultOperator($sqlEnvironment) . $openingParenthesisOperator($sqlEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($sqlEnvironment),
      $testObjectNonDefaultOperator(null, 'NEW COMPARABLE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($concreteEnvironment) . $this->property .
          $equalToOperator($concreteEnvironment) . $openingParenthesisOperator($concreteEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($concreteEnvironment),
      $testObject($concreteEnvironment, 'NEW COMPARABLE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($concreteEnvironment) . $this->property .
          $nonDefaultOperator($concreteEnvironment) . $openingParenthesisOperator($concreteEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($concreteEnvironment),
      $testObjectNonDefaultOperator($concreteEnvironment, 'NEW COMPARABLE')
    );

    try {
      $testObject(null, 'BAD COMPARABLE');
    } catch (\DomainException $expected) {
      $this->assertSame(
        'Supplied argument does Not validate against associated property',
        $expected->getMessage()
      );
      try {
        $testObjectNonDefaultOperator(null, 'BAD COMPARABLE');
      } catch (\DomainException $expected) {
        $this->assertSame(
          'Supplied argument does Not validate against associated property',
          $expected->getMessage()
        );
      return;
      }
    }
    $this->fail('An expected \DomainException has NOT been raised');
  }
  #endregion
}
