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

require_once '/usr/share/php/tests/ramp/core/CollectionTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/Field.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/Record.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteEnvironment.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iList;
use ramp\condition\Filter;
use ramp\condition\Operator;
use ramp\condition\SQLEnvironment;
use ramp\condition\FilterCondition;

use tests\ramp\mocks\condition\ConcreteEnvironment;

/**
 * Collection of tests for \ramp\condition\Filter.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\MockEnvironment}
 * - {@see \tests\ramp\mocks\condition\Property}
 * - {@see \tests\ramp\mocks\condition\Record}
 */
class FilterTest extends \tests\ramp\core\CollectionTest
{
  private $record;
  private $goodArray;
  private $complexArray;

  #region Setup
  protected function preSetup() : void
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\mocks\condition';
    $this->typeName = Str::set('ramp\condition\FilterCondition');
    $this->record = Str::set('Record');
    $this->expectedAtNameIndex = new FilterCondition($this->record, Str::set('propertyA'));
    $this->expectedAt0Index = new FilterCondition($this->record, Str::set('propertyB'));

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
  protected function getTestObject() : RAMPObject { return new Filter(); }
  #endregion

  /**
   * Collection of assertions for \ramp\condition\Filter::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \ramp\core\oList}
   * - assert is instance of {@see \ramp\core\iCollection}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\condition\Filter}
   * - assert implements \IteratorAggregate
   * - assert implements \ArrayAccess
   * - assert implements \Countable
   * - assert throws InvalidAgumentException if provided Str is NOT an accessible class name
   *   - with message: *'$compositeType MUST be an accesible class name'*
   * @see \ramp\condition\Filter
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\condition\Filter', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see ramp\core\RAMPObject::__get()
   */
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
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::isCompositeType().
   * - assert returns TRUE when $compositeType {@see \ramp\core\Str}
   * - assert returns FALSE when $compositeType name provided is NOT {@see \ramp\core\Str}
   * @see \ramp\core\StrCollection::isCompositeType()
   */
  public function testIsCompositeType() : void
  {
    parent::testIsCompositeType();
  }

  /**
   * Collection of assertions for ramp\core\List::offsetSet().
   * - assert {@see \ramp\core\List::OffsetSet()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert value set with name key is same as retived with same name key
   * - assert value set at index same as retived at index.
   * @see \ramp\mocks\core\List::offsetSet()
   */
  public function testOffsetSet($message = 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\condition\FilterCondition') : iList
  {
    return parent::testOffsetSet($message);
  }

  /**
   * Collection of assertions for ramp\core\oList::offsetUnset().
   * - assert value unset with name key is no longer retivable with same name key
   * - assert value set at index is no longer retivable at same index.
   * @depends testOffsetSet
   * @param iList The test object.
   * @param string Expected child type.
   * @see \ramp\mocks\core\List::offsetUnset()
   */
  public function testOffsetUnset(iList $testObject, $expectedChildType = 'ramp\condition\FilterCondition') : void
  {
    parent::testOffsetUnset($testObject, $expectedChildType);
  }

    /**
   * Collection of assertions for ramp\core\Collection::getIterator(), add() and count.
   * - assert handle unpopulated {@see \ramp\core\Collection} iteration without fail
   * - assert {@see \ramp\core\Collection::add()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert Count equal to number of objects added.
   * - assert collection object references occupy SAME position as added
   * - assert {@see \ramp\core\Collection::offsetGet}($outOfBoundsOffset) throws \OutOfBoundsException
   *   - with message: *'Offset out of bounds'*
   * @see \ramp\core\Collection::getIterator()
   * @see \ramp\core\Collection::add()
   * @see \ramp\core\Collection::count
   */
  public function testIteratorAddCount($message = NULL, $o1 = NULL, $o2 = NULL, $o3 = NULL, $o4 = NULL) : void
  {
    parent::testIteratorAddCount(
      ($message !== NULL) ? $message : 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\condition\FilterCondition',
      ($o1 !== NULL) ? $o1 : new FilterCondition($this->record, Str::set('propertyA')),
      ($o2 !== NULL) ? $o2 : new FilterCondition($this->record, Str::set('propertyB')),
      ($o3 !== NULL) ? $o3 : new FilterCondition($this->record, Str::set('propertyC')),
      ($o4 !== NULL) ? $o4 : new FilterCondition($this->record, Str::set('propertyInt')),
    );
  }

  /**
   * Collection of assertions for ramp\core\OptionList::__clone().
   * - assert Cloning (default) composite collection is referenced only
   * @see \ramp\mocks\core\Collection::__clone()
   * @see \ramp\mocks\core\Collection::__clone()
   */
  public function testClone() : void
  {
    $copy = clone $this->testObject;
    $this->assertNotSame($copy, $this->testObject);
    $this->assertEquals($copy, $this->testObject);
  }
  #endregion

  /**
   * Collection of assertions for \ramp\condition\Filter::build().
   * - assert throws \LengthException when $filters is empty
   *   - with message: *'2nd argument $filters, MUST NOT be empty'*
   * - assert throws \DomainException when RECORD does NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * - assert throws \DomainException when any $filters PROPERTY does NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * - assert throws \DomainException when any $filter VALUE does NOT validate against associated property
   *   - with message: *'Supplied argument does Not validate against associated property'*
   * @see \ramp\condition\Filter::build()
   */
  public function testBuildErrors() : void
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
   * @see \ramp\condition\Filter::build()
   */
  public function testBuildGood() : void
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
   * @see \ramp\condition\Filter::build()
   */
  public function testBuildComplex() : void
  {
    $testObject = Filter::build($this->record, $this->complexArray);
    $i=0; $j=0;
    $this->assertSame(
      'Record.propertyB <> "valueA" AND Record.propertyB <> "valueC" AND Record.propertyInt < "11" AND Record.propertyInt > "4.5" AND ' .
      '(Record.propertyA = "valueA" OR Record.propertyA = "valueB" OR Record.propertyA = "valueC") AND ' .
      '(Record.propertyC = "valueA" OR Record.propertyC = "valueB" OR Record.propertyC = "valueC")',
      $testObject()
    );
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
   * @see \ramp\condition\Filter::build()
   *
  public function testBuildMultiPartPrimary() : void
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
   * @see \ramp\condition\Filter::__invoke()
   */
  public function test__invoke()
  {
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $andOperator = Operator::AND();
    $equalToOperator = Operator::EQUAL_TO();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    $SQLEnvironment = SQLEnvironment::getInstance();
    $concreteEnvironment = ConcreteEnvironment::getInstance();

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
        Str::set($this->record . $memberAccessOperator($concreteEnvironment) .
          Str::camelCase(Str::set($name), TRUE) . $equalToOperator($concreteEnvironment) .
            $openingParenthesisOperator($concreteEnvironment) . $value .
              $closingParenthesisOperator($concreteEnvironment) . $andOperator($concreteEnvironment)
        )
      );
    }
    $expectedMock = $expectedMock->trimEnd(Str::set($andOperator($concreteEnvironment)));
    $this->assertSame((string)$expectedMock, $testObject($concreteEnvironment));

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
