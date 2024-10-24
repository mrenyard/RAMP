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

require_once '/usr/share/php/ramp/condition/URNQueryEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/Record.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteEnvironment.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\InputDataCondition;
use ramp\condition\Operator;
use ramp\condition\URNQueryEnvironment;

use tests\ramp\mocks\condition\ConcreteEnvironment;

/**
 * Collection of tests for \ramp\condition\InputDataCondition.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\ConcreteEnvironment}
 * - {@see \tests\ramp\mocks\condition\Property}
 * - {@see \tests\ramp\mocks\condition\Record}
 */
class InputDataConditionTest extends \tests\ramp\condition\BusinessConditionTest
{
  protected $primaryKeyValue;
  protected $value;

  #region Setup
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\mocks\condition';
    $this->record = Str::set('Record');
    $this->primaryKeyValue = Str::set('key');
    $this->property = Str::set('propertyA');
    $this->operator = Operator::ASSIGNMENT();
    $this->value = 'GOOD';
  }
  protected function getTestObject() : RAMPObject {
    return new InputDataCondition($this->record, $this->primaryKeyValue, $this->property, $this->value);
  }
  protected function postSetup() : void { $this->attribute = $this->testObject->attribute; }
  #endregion

  /**
   * Collection of assertions for \ramp\conditon\InputDataCondition.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Condition}
   * - assert is instance of {@see \ramp\condition\BusinessCondition}
   * - assert is instance of {@see \ramp\condition\InputDataCondition}
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * - assert throws \DomainException when Supplied arguments DO NOT match business model
   *   - with message: *'Invalid $record $property arguments, do NOT match business model'*
   * - assert throws \DomainException when $value does Not validate against associated property
   *   - with message: *'Supplied argument does Not validate against associated property'*
   * @see \ramp\condition\InputDataCondition
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\condition\InputDataCondition', $this->testObject);
    try {
      $this->testObject1 = new InputDataCondition(Str::set('NotARecord'), $this->primaryKeyValue, $this->property, $this->value);
    } catch (\DomainException $expected) {
      $this->assertSame(
        'Invalid: NotARecord->propertyA, does NOT match business model',
        $expected->getMessage()
      );
      try {
        $this->testObject12 = new InputDataCondition($this->record, $this->primaryKeyValue, Str::set('NotAProperty'), $this->value);
      } catch (\DomainException $expected) {
        $this->assertSame(
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
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
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
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
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
  public function testComparable() : void
  {
    $this->assertSame('GOOD',$this->testObject->comparable);
    $this->testObject->comparable = 'valueA';
    $this->assertSame('valueA', $this->testObject->comparable);

    $this->testObject2 = new InputDataCondition($this->record,  $this->primaryKeyValue, $this->property, 'COMPARABLE');
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
  public function testProperty() : void
  {
    parent::testProperty();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Collection of assertions for \ramp\condition\InputDataCondition::$primaryKeyValue.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set '$primaryKeyValue'
   *   - with message: *'[className]->$primaryKeyValue is NOT settable'*
   * - assert allows retrieval of '$primaryKeyValue'
   * - assert '$primaryKeyValue' is a {@see \ramp\core\Str}
   * - assert '$primaryKeyValue' equal to provided at creation
   * @see \ramp\condition\InputDataCondition::$primaryKeyValue
   */
  public function testPrimaryKeyValue() : void
  {
    try {
      $this->testObject->primaryKeyValue = $this->primaryKeyValue;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->primaryKeyValue is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->primaryKeyValue);
      $this->assertSame($this->primaryKeyValue, $this->testObject->primaryKeyValue);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\InputDataCondition::$value.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'value'
   *   - with message: *'[className]->value is NOT settable'*
   * - assert allows setting of 'comparable'
   * - assert allows retrieval of 'value'
   * - assert 'value' equal to recently set 'comparable'
   * - assert 'value' equal to that provided creation
   * @see \ramp\condition\InputDataCondition::$value
   */
  public function testValue() : void
  {
    try {
      $this->testObject->value = $this->value;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->value is NOT settable', $expected->getMessage());
      $this->testObject->comparable = 'COMPARABLE';
      $this->assertSame('COMPARABLE', $this->testObject->value);
      $this->testObject2 = new InputDataCondition(
        $this->record, $this->primaryKeyValue, $this->property, 'GOOD'
      );
      $this->assertSame('GOOD', (string)$this->testObject2->value);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

    /**
   * Collection of assertions for \ramp\condition\InputDataCondition::$attributeURN.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'attributeURN'
   *   - with message: *'[className]->attributeURN is NOT settable'*
   * - assert allows retrieval of 'attributeURN'
   * - assert retreved is an instance of {@see \ramp\core\Str}
   * - assert 'attributeURN' equal to record:key:property
   * @see \ramp\condition\InputDataCondition::$attributeURN
   */
  public function testAttributeURN() : void
  {
    try {
      $this->testObject->attributeURN = Str::set('U:R:N');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->attributeURN is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->attributeURN);
      $this->assertSame(
        (string)$this->record
          ->append(Str::COLON())
          ->append($this->primaryKeyValue)
          ->append(Str::COLON())
          ->append(Str::hyphenate($this->property))
          ->lowercase,
        (string)$this->testObject->attributeURN
      );
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
   * @see \method__invoke ramp\condition\InputDataCondition::__invoke()
   */
  public function test__invoke() : void
  {
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $assignmentOperator = Operator::ASSIGNMENT();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();

    $urnQueryEnvironment = URNQueryEnvironment::getInstance();
    $concreteEnvironment = ConcreteEnvironment::getInstance();

    $testObject = $this->testObject;

    $this->assertSame(
      Str::hyphenate($this->record) . $memberAccessOperator($urnQueryEnvironment) . $this->primaryKeyValue .
        $memberAccessOperator($urnQueryEnvironment) . Str::hyphenate($this->property) .
          $assignmentOperator($urnQueryEnvironment) . $openingParenthesisOperator($urnQueryEnvironment) .
            $this->value . $closingParenthesisOperator($urnQueryEnvironment),
      $testObject()
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($concreteEnvironment) . $this->primaryKeyValue .
        $memberAccessOperator($concreteEnvironment) . $this->property .
          $assignmentOperator($concreteEnvironment) . $openingParenthesisOperator($concreteEnvironment) .
            $this->value . $closingParenthesisOperator($concreteEnvironment),
      $testObject($concreteEnvironment)
    );

    $this->assertSame(
      Str::hyphenate($this->record) . $memberAccessOperator($urnQueryEnvironment) .
       $this->primaryKeyValue .  $memberAccessOperator($urnQueryEnvironment) .
        Str::hyphenate($this->property) . $assignmentOperator($urnQueryEnvironment) .
         $openingParenthesisOperator($urnQueryEnvironment) . 'NEW VALUE' .
          $closingParenthesisOperator($urnQueryEnvironment),
      $testObject(null, 'NEW VALUE')
    );

    $this->assertSame(
      $this->record . $memberAccessOperator($concreteEnvironment) . $this->primaryKeyValue .
        $memberAccessOperator($concreteEnvironment) . $this->property .
          $assignmentOperator($concreteEnvironment) . $openingParenthesisOperator($concreteEnvironment) .
            'NEW VALUE' . $closingParenthesisOperator($concreteEnvironment),
      $testObject($concreteEnvironment, 'NEW VALUE')
    );
  }
  #endregion
}
