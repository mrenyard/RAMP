<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
namespace tests\svelte\condition;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/FilterCondition.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/svelte/condition/mocks/FilterConditionTest/Field.class.php';
require_once '/usr/share/php/tests/svelte/condition/mocks/FilterConditionTest/Record.class.php';
require_once '/usr/share/php/tests/svelte/condition/mocks/FilterConditionTest/MockEnvironment.class.php';

use svelte\core\Str;
use svelte\condition\FilterCondition;
use svelte\condition\Operator;
use svelte\condition\SQLEnvironment;

use tests\svelte\condition\mocks\FilterConditionTest\MockEnvironment;

/**
 * Collection of tests for \svelte\condition\FilterCondition.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\condition\mocks\FilterConditionTest\MockEnvironment}
 * - {@link \tests\svelte\condition\mocks\FilterConditionTest\Property}
 * - {@link \tests\svelte\condition\mocks\FilterConditionTest\Record}
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
    \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='tests\svelte\condition\mocks\FilterConditionTest';
    $this->record = Str::set('Record');
    $this->property = Str::set('property');
    $this->comparable = 'GOOD';
  }

  /**
   * Collection of assertions for \svelte\FilterCondition::__construct.
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\condition\Condition}
   * - assert is instance of {@link \svelte\condition\BusinessCondition}
   * - assert is instance of {@link \svelte\condition\FilterCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * - assert throws \DomainException when $value does Not validate against associated property
   *   - with message: <em>'Supplied argument does Not validate against associated property'</em>
   * @link svelte.condition.FilterCondition#method___construct svelte\condition\FilterCondition
   */
  public function test__Construct()
  {
    $testObject = new FilterCondition($this->record, $this->property, $this->comparable);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\svelte\condition\Condition', $testObject);
    $this->assertInstanceOf('\svelte\condition\BusinessCondition', $testObject);
    $this->assertInstanceOf('\svelte\condition\FilterCondition', $testObject);

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
   * Collection of assertions for \svelte\condition\FilterCondition::__invoke().
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
   * @link svelte.condition.FilterCondition#method__invoke svelte\condition\FilterCondition::__invoke()
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
