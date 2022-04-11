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
 */
namespace tests\ramp\condition;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/FilterConditionTest/Field.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/FilterConditionTest/Record.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/FilterConditionTest/MockEnvironment.class.php';

use ramp\core\Str;
use ramp\condition\FilterCondition;
use ramp\condition\Operator;
use ramp\condition\SQLEnvironment;

use tests\ramp\condition\mocks\FilterConditionTest\MockEnvironment;

/**
 * Collection of tests for \ramp\condition\FilterCondition.
 *
 * COLLABORATORS
 * - {@link \tests\ramp\condition\mocks\FilterConditionTest\MockEnvironment}
 * - {@link \tests\ramp\condition\mocks\FilterConditionTest\Property}
 * - {@link \tests\ramp\condition\mocks\FilterConditionTest\Record}
 */
class FilterConditionTest extends \PHPUnit\Framework\TestCase {

  private $record;
  private $property;
  private $comparable;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\condition\mocks\FilterConditionTest';
    $this->record = Str::set('Record');
    $this->property = Str::set('property');
    $this->comparable = 'GOOD';
  }

  /**
   * Collection of assertions for \ramp\FilterCondition::__construct.
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\condition\Condition}
   * - assert is instance of {@link \ramp\condition\BusinessCondition}
   * - assert is instance of {@link \ramp\condition\FilterCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * - assert throws \DomainException when $value does Not validate against associated property
   *   - with message: <em>'Supplied argument does Not validate against associated property'</em>
   * @link ramp.condition.FilterCondition#method___construct ramp\condition\FilterCondition
   */
  public function test__Construct()
  {
    $testObject = new FilterCondition($this->record, $this->property, $this->comparable);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\condition\Condition', $testObject);
    $this->assertInstanceOf('\ramp\condition\BusinessCondition', $testObject);
    $this->assertInstanceOf('\ramp\condition\FilterCondition', $testObject);

    try {
      $testObject = new FilterCondition(Str::set('NotARecord'), $this->property, 'GOOD');
    } catch (\DomainException $expected) {
      $this->AssertSame(
        'Invalid: NotARecord->property, does NOT match business model',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \DomianException has NOT been raised');
  }

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
   *   - with message: <em>'Supplied argument does Not validate against associated property'</em>
   * @link ramp.condition.FilterCondition#method__invoke ramp\condition\FilterCondition::__invoke()
   */
  public function test__invoke()
  {
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $equalToOperator = Operator::EQUAL_TO();
    $nonDefaultOperator = Operator::NOT_EQUAL_TO();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    $SQLEnvironment = SQLEnvironment::getInstance();
    $MockEnvironment = MockEnvironment::getInstance();

    $testObject = new FilterCondition($this->record, $this->property, $this->comparable);
    $testObjectNonDefaultOperator = new FilterCondition(
      $this->record, $this->property, $this->comparable, $nonDefaultOperator
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($SQLEnvironment) . $this->property .
          $equalToOperator($SQLEnvironment) . $openingParenthesisOperator($SQLEnvironment) .
            $this->comparable . $closingParenthesisOperator($SQLEnvironment),
      $testObject()
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($SQLEnvironment) . $this->property .
          $nonDefaultOperator($SQLEnvironment) . $openingParenthesisOperator($SQLEnvironment) .
            $this->comparable . $closingParenthesisOperator($SQLEnvironment),
      $testObjectNonDefaultOperator()
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($MockEnvironment) . $this->property .
          $equalToOperator($MockEnvironment) . $openingParenthesisOperator($MockEnvironment) .
            $this->comparable . $closingParenthesisOperator($MockEnvironment),
      $testObject($MockEnvironment)
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($MockEnvironment) . $this->property .
          $nonDefaultOperator($MockEnvironment) . $openingParenthesisOperator($MockEnvironment) .
            $this->comparable . $closingParenthesisOperator($MockEnvironment),
      $testObjectNonDefaultOperator($MockEnvironment)
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($SQLEnvironment) . $this->property .
          $equalToOperator($SQLEnvironment) . $openingParenthesisOperator($SQLEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($SQLEnvironment),
      $testObject(null, 'NEW COMPARABLE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($SQLEnvironment) . $this->property .
          $nonDefaultOperator($SQLEnvironment) . $openingParenthesisOperator($SQLEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($SQLEnvironment),
      $testObjectNonDefaultOperator(null, 'NEW COMPARABLE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($MockEnvironment) . $this->property .
          $equalToOperator($MockEnvironment) . $openingParenthesisOperator($MockEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($MockEnvironment),
      $testObject($MockEnvironment, 'NEW COMPARABLE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($MockEnvironment) . $this->property .
          $nonDefaultOperator($MockEnvironment) . $openingParenthesisOperator($MockEnvironment) .
            'NEW COMPARABLE' . $closingParenthesisOperator($MockEnvironment),
      $testObjectNonDefaultOperator($MockEnvironment, 'NEW COMPARABLE')
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
}
