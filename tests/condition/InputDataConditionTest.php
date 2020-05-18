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
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/URNQueryEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';

require_once '/usr/share/php/tests/svelte/condition/mocks/InputDataConditionTest/Field.class.php';
require_once '/usr/share/php/tests/svelte/condition/mocks/InputDataConditionTest/Record.class.php';
require_once '/usr/share/php/tests/svelte/condition/mocks/InputDataConditionTest/MockEnvironment.class.php';

use svelte\core\Str;
use svelte\core\PropertyNotSetException;
use svelte\condition\InputDataCondition;
use svelte\condition\Operator;
use svelte\condition\URNQueryEnvironment;

use tests\svelte\condition\mocks\InputDataConditionTest\MockEnvironment;

/**
 * Collection of tests for \svelte\condition\InputDataCondition.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\condition\mocks\InputDataConditionTest\MockEnvironment}
 * - {@link \tests\svelte\condition\mocks\InputDataConditionTest\Property}
 * - {@link \tests\svelte\condition\mocks\InputDataConditionTest\Record}
 */
class InputDataConditionTest extends \PHPUnit\Framework\TestCase
{
  private $primaryKeyValue;
  private $record;
  private $property;
  private $value;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='tests\svelte\condition\mocks\InputDataConditionTest';
    $this->primaryKeyValue = Str::set('key');
    $this->record = Str::set('Record');
    $this->property = Str::set('property');
    $this->value = 'GOOD';
  }

  /**
   * Collection of assertions for \svelte\conditon\InputDataCondition::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\condition\Condition}
   * - assert is instance of {@link \svelte\condition\BusinessCondition}
   * - assert is instance of {@link \svelte\condition\InputDataCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * @link svelte.condition.InputDataCondition#method___construct svelte\condition\InputDataCondition
   */
  public function test__Construct()
  {
    $testSvelteObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testSvelteObject);
    $this->assertInstanceOf('\svelte\condition\Condition', $testSvelteObject);
    $this->assertInstanceOf('\svelte\condition\BusinessCondition', $testSvelteObject);
    $this->assertInstanceOf('\svelte\condition\InputDataCondition', $testSvelteObject);

    try {
      $testSvelteObject = new InputDataCondition(Str::set('NotARecord'), $this->primaryKeyValue, $this->property, $this->value);
    } catch (\DomainException $expected) {
      $this->AssertSame(
        'Invalid: NotARecord->property, does NOT match business model',
        $expected->getMessage()
      );
      try {
        $testSvelteObject = new InputDataCondition($this->record, $this->primaryKeyValue, Str::set('NotAProperty'), $this->value);
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
   * Collection of assertions for \svelte\condition\InputDataCondition::$primaryKeyValue.
   * - assert throws {@link \svelte\core\PropertyNotSetException} trying to set '$primaryKeyValue'
   *   - with message: <em>'[className]->$primaryKeyValue is NOT settable'</em>
   * - assert allows retrieval of '$primaryKeyValue'
   * - assert '$primaryKeyValue' is a {@link \svelte\core\Str}
   * - assert '$primaryKeyValue' equal to provided at creation
   * @link svelte.condition.InputDataCondition#method_get_$primaryKeyValue svelte\condition\InputDataCondition::$primaryKeyValue
   */
  public function testPrimaryKeyValue()
  {
    $testObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);
    try {
      $testObject->primaryKeyValue = $this->primaryKeyValue;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\InputDataCondition->primaryKeyValue is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\svelte\core\Str', $testObject->primaryKeyValue);
      $this->assertSame($this->primaryKeyValue, $testObject->primaryKeyValue);
      return;
    }
    $this->fail('An expected svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\InputDataCondition::value.
   * - assert throws {@link \svelte\core\PropertyNotSetException} trying to set 'value'
   *   - with message: <em>'[className]->value is NOT settable'</em>
   * - assert allows setting of 'comparable'
   * - assert allows retrieval of 'value'
   * - assert 'value' equal to recently set 'comparable'
   * - assert 'value' equal to that provided creation
   * @link svelte.condition.InputDataCondition#method_get_value svelte\condition\InputDataCondition::value
   */
  public function testValue()
  {
    $testObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);

    try {
      $testObject->value = $this->value;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'svelte\condition\InputDataCondition->value is NOT settable',
        $expected->getMessage()
      );

      $testObject->comparable = 'COMPARABLE';
      $this->assertSame('COMPARABLE', $testObject->value);
      $testObject2 = new InputDataCondition(
        $this->record, $this->primaryKeyValue, $this->property, 'GOOD'
      );
      $this->assertSame('GOOD', $testObject2->value);
      return;
    }
    $this->fail('An expected svelte\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\condition\InputDataCondition::__invoke().
   * - assert returns expected string with URNQueryEnvironment operation values in the form:
   *  - [record]:[key]:[property]=[value] as default.
   * - assert returns expected string when first argument (iEnvironment) differs from default in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][assignmentOperator][openingParenthesisOperator][value][closingParenthesisOperator]
   * - assert returns expected string when new value passed as second argument in the form:
   *  - [record]:[key]:[property]=[newValue] where new values is the passes second argument.
   * - assert returns expected string where both arguments are suplied in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][assignmentOperator][openingParenthesisOperator][passedValue][closingParenthesisOperator]
   * @link svelte.condition.InputDataCondition#method__invoke svelte\condition\InputDataCondition::__invoke()
   */
  public function test__invoke()
  {
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $assignmentOperator = Operator::ASSIGNMENT();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    $URNQueryEnvironment = URNQueryEnvironment::getInstance();
    $MockEnvironment = MockEnvironment::getInstance();

    $testObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);

    $this->assertSame(
      Str::hyphenate($this->record) . $memberAccessOperator($URNQueryEnvironment) . $this->primaryKeyValue .
        $memberAccessOperator($URNQueryEnvironment) . Str::hyphenate($this->property) .
          $assignmentOperator($URNQueryEnvironment) . $openingParenthesisOperator($URNQueryEnvironment) .
            $this->value . $closingParenthesisOperator($URNQueryEnvironment),
      $testObject()
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($MockEnvironment) . $this->primaryKeyValue .
        $memberAccessOperator($MockEnvironment) . $this->property .
          $assignmentOperator($MockEnvironment) . $openingParenthesisOperator($MockEnvironment) .
            $this->value . $closingParenthesisOperator($MockEnvironment),
      $testObject($MockEnvironment)
    );

    $this->assertSame(
      Str::hyphenate($this->record) . $memberAccessOperator($URNQueryEnvironment) .
       $this->primaryKeyValue .  $memberAccessOperator($URNQueryEnvironment) .
        Str::hyphenate($this->property) . $assignmentOperator($URNQueryEnvironment) .
         $openingParenthesisOperator($URNQueryEnvironment) . 'NEW VALUE' .
          $closingParenthesisOperator($URNQueryEnvironment),
      $testObject(null, 'NEW VALUE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($MockEnvironment) . $this->primaryKeyValue .
        $memberAccessOperator($MockEnvironment) . $this->property .
          $assignmentOperator($MockEnvironment) . $openingParenthesisOperator($MockEnvironment) .
            'NEW VALUE' . $closingParenthesisOperator($MockEnvironment),
      $testObject($MockEnvironment, 'NEW VALUE')
    );
  }
}
