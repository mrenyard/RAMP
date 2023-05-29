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
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/URNQueryEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/InputDataConditionTest/Field.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/InputDataConditionTest/Record.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/InputDataConditionTest/MockEnvironment.class.php';

use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\InputDataCondition;
use ramp\condition\Operator;
use ramp\condition\URNQueryEnvironment;

use tests\ramp\condition\mocks\InputDataConditionTest\MockEnvironment;

/**
 * Collection of tests for \ramp\condition\InputDataCondition.
 *
 * COLLABORATORS
 * - {@link \tests\ramp\condition\mocks\InputDataConditionTest\MockEnvironment}
 * - {@link \tests\ramp\condition\mocks\InputDataConditionTest\Property}
 * - {@link \tests\ramp\condition\mocks\InputDataConditionTest\Record}
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
  public function setUp() : void
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\condition\mocks\InputDataConditionTest';
    $this->primaryKeyValue = Str::set('key');
    $this->record = Str::set('Record');
    $this->property = Str::set('property');
    $this->value = 'GOOD';
  }

  /**
   * Collection of assertions for \ramp\conditon\InputDataCondition::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\condition\Condition}
   * - assert is instance of {@link \ramp\condition\BusinessCondition}
   * - assert is instance of {@link \ramp\condition\InputDataCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * @link ramp.condition.InputDataCondition#method___construct ramp\condition\InputDataCondition
   */
  public function test__Construct()
  {
    $testRAMPObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testRAMPObject);
    $this->assertInstanceOf('\ramp\condition\Condition', $testRAMPObject);
    $this->assertInstanceOf('\ramp\condition\BusinessCondition', $testRAMPObject);
    $this->assertInstanceOf('\ramp\condition\InputDataCondition', $testRAMPObject);

    try {
      $testRAMPObject = new InputDataCondition(Str::set('NotARecord'), $this->primaryKeyValue, $this->property, $this->value);
    } catch (\DomainException $expected) {
      $this->AssertSame(
        'Invalid: NotARecord->property, does NOT match business model',
        $expected->getMessage()
      );
      try {
        $testRAMPObject = new InputDataCondition($this->record, $this->primaryKeyValue, Str::set('NotAProperty'), $this->value);
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
   * Collection of assertions for \ramp\condition\InputDataCondition::$primaryKeyValue.
   * - assert throws {@link \ramp\core\PropertyNotSetException} trying to set '$primaryKeyValue'
   *   - with message: <em>'[className]->$primaryKeyValue is NOT settable'</em>
   * - assert allows retrieval of '$primaryKeyValue'
   * - assert '$primaryKeyValue' is a {@link \ramp\core\Str}
   * - assert '$primaryKeyValue' equal to provided at creation
   * @link ramp.condition.InputDataCondition#method_get_$primaryKeyValue ramp\condition\InputDataCondition::$primaryKeyValue
   */
  public function testPrimaryKeyValue()
  {
    $testObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);
    try {
      $testObject->primaryKeyValue = $this->primaryKeyValue;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\InputDataCondition->primaryKeyValue is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $testObject->primaryKeyValue);
      $this->assertSame($this->primaryKeyValue, $testObject->primaryKeyValue);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\InputDataCondition::value.
   * - assert throws {@link \ramp\core\PropertyNotSetException} trying to set 'value'
   *   - with message: <em>'[className]->value is NOT settable'</em>
   * - assert allows setting of 'comparable'
   * - assert allows retrieval of 'value'
   * - assert 'value' equal to recently set 'comparable'
   * - assert 'value' equal to that provided creation
   * @link ramp.condition.InputDataCondition#method_get_value ramp\condition\InputDataCondition::value
   */
  public function testValue()
  {
    $testObject = new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);

    try {
      $testObject->value = $this->value;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\InputDataCondition->value is NOT settable',
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
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\InputDataCondition::__invoke().
   * - assert returns expected string with URNQueryEnvironment operation values in the form:
   *  - [record]:[key]:[property]=[value] as default.
   * - assert returns expected string when first argument (iEnvironment) differs from default in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][assignmentOperator][openingParenthesisOperator][value][closingParenthesisOperator]
   * - assert returns expected string when new value passed as second argument in the form:
   *  - [record]:[key]:[property]=[newValue] where new values is the passes second argument.
   * - assert returns expected string where both arguments are suplied in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][assignmentOperator][openingParenthesisOperator][passedValue][closingParenthesisOperator]
   * @link ramp.condition.InputDataCondition#method__invoke ramp\condition\InputDataCondition::__invoke()
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
