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
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/FilterTest/Field.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/FilterTest/Record.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/FilterTest/MockEnvironment.class.php';

use ramp\core\Str;
use ramp\condition\Filter;
use ramp\condition\Operator;
use ramp\condition\SQLEnvironment;

use tests\ramp\condition\mocks\FilterTest\MockEnvironment;

/**
 * Collection of tests for \ramp\condition\Filter.
 *
 * COLLABORATORS
 * - {@link \tests\ramp\condition\mocks\FilterTest\MockEnvironment}
 * - {@link \tests\ramp\condition\mocks\FilterTest\Property}
 * - {@link \tests\ramp\condition\mocks\FilterTest\Record}
 */
class FilterTest extends \PHPUnit\Framework\TestCase
{
  private $record;
  private $goodArray;
  private $complexArray;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\condition\mocks\FilterTest';
    $this->record = Str::set('Record');

    $this->goodArray = array(
      'property-a' => 'valueA',
      'property-b' => 'valueB',
      'property-int' => '10'
    );

    $this->complexArray = array(
      'property-a' => 'valueA|valueB|valueC',
      'property-b|not' => 'valueA|valueC',
      'property-c' => 'valueA|valueB|valueC',
      'property-int|lt' => '11',
      'property-int|gt' => '4.5'
    );

    $this->multiPartPrimaryArray = array(
      'key' => '1:2:3'
    );
  }

  /**
   * Collection of assertions for \ramp\condition\Filter::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\core\Collection}
   * - assert is instance of {@link \ramp\condition\Filter}
   * - assert is composite type {@link \ramp\condition\FilterCondition}
   * @link ramp.condition.Filter#method___construct ramp\condition\Filter
   */
  public function test__Construct()
  {
    $testObject = new Filter();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\core\Collection', $testObject);
    $this->assertInstanceOf('\ramp\condition\Filter', $testObject);

    $this->assertTrue($testObject->isCompositeType(
      Str::set('ramp\condition\FilterCondition')
    ));
  }

  /**
   * Collection of assertions for \ramp\condition\Filter::build().
   * - assert throws \LengthException when $filters is empty
   *   - with message: <em>'2nd argument $filters, MUST NOT be empty'</em>
   * - assert throws \DomainException when RECORD does NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * - assert throws \DomainException when any $filters PROPERTY does NOT match business model
   *   - with message: <em>'Invalid $record $property arguments, do NOT match business model'</em>
   * - assert throws \DomainException when any $filter VALUE does NOT validate against associated property
   *   - with message: <em>'Supplied argument does Not validate against associated property'</em>
   * @link ramp.condition.Filter#method___build ramp\condition\Filter::build()
   */
  public function testBuildErrors()
  {
    $badPropertyArray = array(
      'property-a' => 'valueA',
      'property-b' => 'valueB',
      'not-a-property' => 'valueC'
    );
    $badValueArray = array(
      'property-a' => 'valueA',
      'property-b' => 'BAD',
      'property-int' => '10'
    );
    try {
      Filter::build($this->record, array());
    } catch (\LengthException $expected) {
      $this->assertSame('2nd argument $filters, MUST NOT be empty', $expected->getMessage());
      try {
        Filter::build(Str::set('not-a-record'), $this->goodArray);
      } catch(\DomainException $expected) {
        $this->assertSame(
          'Invalid: NotARecord->propertyA, does NOT match business model',
          $expected->getMessage()
        );
        try {
          Filter::build($this->record, $badPropertyArray);
        } catch (\DomainException $expected) {
          $this->AssertSame(
            'Invalid: Record->notAProperty, does NOT match business model',
            $expected->getMessage()
          );
          try {
            Filter::build($this->record, $badValueArray);
          } catch (\DomainException $expected) {
            $this->AssertSame(
              'Supplied argument does Not validate against associated property',
              $expected->getMessage()
            );
            return;
          }
        }
      }
      $this->fail('An expected \DomainException has NOT been raised');
    }
    $this->fail('An expected \LengthException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Filter::build().
   * - assert where valid, produces like for like representation of provied array as Filter object
   * @link ramp.condition.Filter#method___build ramp\condition\Filter::build()
   */
  public function testBuildGood()
  {
    $testObject = Filter::build($this->record, $this->goodArray);
    $i=0;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyA', (string)$testObject[$i]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject[$i]->operator);
    $this->assertSame('valueA', $testObject[$i]->comparable);
    $i++;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyB', (string)$testObject[$i]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject[$i]->operator);
    $this->assertSame('valueB', $testObject[$i]->comparable);
    $i++;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyInt', (string)$testObject[$i]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject[$i]->operator);
    $this->assertSame(10, $testObject[$i]->comparable);
  }

  /**
   * Collection of assertions for \ramp\condition\Filter::build().
   * - assert where valid, produces like for like representation of provied array as Filter object
   * @link ramp.condition.Filter#method___build ramp\condition\Filter::build()
   */
  public function testBuildComplex()
  {
    $testObject = Filter::build($this->record, $this->complexArray);
    $i=0; $j=0;
    $this->assertSame('Record', (string)$testObject->subOrGroups[$i][$j]->record);
    $this->assertSame('propertyA', (string)$testObject->subOrGroups[$i][$j]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject->subOrGroups[$i][$j]->operator);
    $this->assertSame('valueA', $testObject->subOrGroups[$i][$j]->comparable);
    $j++;
    $this->assertSame('Record', (string)$testObject->subOrGroups[$i][$j]->record);
    $this->assertSame('propertyA', (string)$testObject->subOrGroups[$i][$j]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject->subOrGroups[$i][$j]->operator);
    $this->assertSame('valueB', $testObject->subOrGroups[$i][$j]->comparable);
    $j++;
    $this->assertSame('Record', (string)$testObject->subOrGroups[$i][$j]->record);
    $this->assertSame('propertyA', (string)$testObject->subOrGroups[$i][$j]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject->subOrGroups[$i][$j]->operator);
    $this->assertSame('valueC', $testObject->subOrGroups[$i][$j]->comparable);
    $i++; $j=0;
    $this->assertSame('Record', (string)$testObject->subOrGroups[$i][$j]->record);
    $this->assertSame('propertyC', (string)$testObject->subOrGroups[$i][$j]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject->subOrGroups[$i][$j]->operator);
    $this->assertSame('valueA', $testObject->subOrGroups[$i][$j]->comparable);
    $j++;
    $this->assertSame('Record', (string)$testObject->subOrGroups[$i][$j]->record);
    $this->assertSame('propertyC', (string)$testObject->subOrGroups[$i][$j]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject->subOrGroups[$i][$j]->operator);
    $this->assertSame('valueB', $testObject->subOrGroups[$i][$j]->comparable);
    $j++;
    $this->assertSame('Record', (string)$testObject->subOrGroups[$i][$j]->record);
    $this->assertSame('propertyC', (string)$testObject->subOrGroups[$i][$j]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject->subOrGroups[$i][$j]->operator);
    $this->assertSame('valueC', $testObject->subOrGroups[$i][$j]->comparable);
    $i=0;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyB', (string)$testObject[$i]->property);
    $this->assertSame(Operator::NOT_EQUAL_TO(), $testObject[$i]->operator);
    $this->assertSame('valueA', $testObject[$i]->comparable);
    $i++;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyB', (string)$testObject[$i]->property);
    $this->assertSame(Operator::NOT_EQUAL_TO(), $testObject[$i]->operator);
    $this->assertSame('valueC', $testObject[$i]->comparable);
    $i++;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyInt', (string)$testObject[$i]->property);
    $this->assertSame(Operator::LESS_THAN(), $testObject[$i]->operator);
    $this->assertSame(11, $testObject[$i]->comparable);
    $i++;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('propertyInt', (string)$testObject[$i]->property);
    $this->assertSame(Operator::GREATER_THAN(), $testObject[$i]->operator);
    $this->assertSame(4.5, $testObject[$i]->comparable);
  }

  /**
   * Collection of assertions for \ramp\condition\Filter::build().
   * - assert where valid, produces like for like representation of provied array as Filter object
   * @link ramp.condition.Filter#method___build ramp\condition\Filter::build()
   *
  public function testBuildMultiPartPrimary()
  {
    $testObject = Filter::build($this->record, $this->multiPartPrimaryArray);
    $i=0;
    $this->assertSame('Record', (string)$testObject[$i]->record);
    $this->assertSame('key', (string)$testObject[$i]->property);
    $this->assertSame(Operator::EQUAL_TO(), $testObject[$i]->operator);
    $this->assertSame('1:2:3', $testObject[$i]->comparable);
  }*/

  /**
   * Collection of assertions for \ramp\condition\Filter::__invoke().
   * - assert returns expected string with SQLEnvironment operation values in the form:
   *  - [record].[property] = "[value]" AND [record].[prop... as default.
   * - assert returns expected string when first argument (iEnvironment) differs from default in the form:
   *  - [record][memberAccessOperator][key][memberAccessOperator][property][assignmentOperator]
   * [openingParenthesisOperator][value][closingParenthesisOperator][andOperator][record][member...
   * @link ramp.condition.Filter#method___invoke ramp\condition\Filter::__invoke()
   */
  public function test__invoke()
  {
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $andOperator = Operator::AND();
    $equalToOperator = Operator::EQUAL_TO();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    $SQLEnvironment = SQLEnvironment::getInstance();
    $MockEnvironment = MockEnvironment::getInstance();

    $testObject = Filter::build($this->record, $this->goodArray);

    $expectedDefault = Str::_EMPTY();
    foreach ($this->goodArray as $name => $value) {
      $expectedDefault = $expectedDefault->append(
        Str::set($this->record . $memberAccessOperator($SQLEnvironment) .
          Str::camelCase(Str::set($name), TRUE) . $equalToOperator($SQLEnvironment) .
            $openingParenthesisOperator($SQLEnvironment) . $value .
              $closingParenthesisOperator($SQLEnvironment) . $andOperator($SQLEnvironment)
        )
      );
    }
    $expectedDefault = $expectedDefault->trimEnd(Str::set($andOperator($SQLEnvironment)));
    $this->assertSame((string)$expectedDefault, $testObject());

    $expectedMock = Str::_EMPTY();
    foreach ($this->goodArray as $name => $value) {
      $expectedMock = $expectedMock->append(
        Str::set($this->record . $memberAccessOperator($MockEnvironment) .
          Str::camelCase(Str::set($name), TRUE) . $equalToOperator($MockEnvironment) .
            $openingParenthesisOperator($MockEnvironment) . $value .
              $closingParenthesisOperator($MockEnvironment) . $andOperator($MockEnvironment)
        )
      );
    }
    $expectedMock = $expectedMock->trimEnd(Str::set($andOperator($MockEnvironment)));
    $this->assertSame((string)$expectedMock, $testObject($MockEnvironment));

    $testObject = Filter::build($this->record, $this->complexArray);

    $expectedComplex = Str::set(
      'Record.propertyB <> "valueA" AND Record.propertyB <> "valueC"' .
      ' AND Record.propertyInt < "11" AND Record.propertyInt > "4.5"' .
      ' AND (Record.propertyA = "valueA" OR Record.propertyA = "valueB"' .
      ' OR Record.propertyA = "valueC") AND (Record.propertyC = "valueA"' .
      ' OR Record.propertyC = "valueB" OR Record.propertyC = "valueC")'
    );
    $this->assertSame((string)$expectedComplex, $testObject());
  }
}
