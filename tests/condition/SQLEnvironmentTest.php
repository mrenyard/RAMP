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

require_once '/usr/share/php/tests/ramp/condition/EnvironmentTest.php';

require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';

use \ramp\core\RAMPObject;
use \ramp\core\Str;
use \ramp\core\PropertyNotSetException;
use \ramp\condition\SQLEnvironment;

/**
 * Collection of tests for \ramp\condition\Environment.
 * .
 */
class SQLEnvironmentTest extends \tests\ramp\condition\EnvironmentTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void {
    $this->instance = SQLEnvironment::getInstance();
    $this->className = 'ramp\condition\SQLEnvironment';
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return SQLEnvironment::getInstance(); }
  #endregion

  /**
   * Collection of assertions for \ramp\condition\Environment::getInstance().
   * - assert __constructor inaccessible (protected).
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Environment}
   * - assert is instance of {@see \ramp\condition\iEnvironment}
   * - assert returns the SAME instance on every call.
   * @see \ramp\condition\Environment\getInstance()
   */
  #[\Override]
  public function testConstruct() : void
  {
    try {
      $o = new SQLEnvironment();
    } catch (\Error $expected) {
      parent::testConstruct();
      $this->assertInstanceOf('\ramp\condition\SQLEnvironment', $this->testObject);
      return;
    }
    $this->fail('An expected \Error has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see ramp\core\RAMPObject::__get()
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
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$memberAccess.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'memberAccess'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'memberAccess'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction (.).
   * @see \ramp\condition\Environment::$memberAccess
   */
  #[\Override]
  public function testMemberAccess($expectedOutput = '.') : void
  {
    parent::testMemberAccess($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$assignment.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'assignment'
   *   - with message: *'[className]->assignment is NOT settable'*.
   * - assert allows retrieval of 'assignment'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction (=).
   * @see \ramp\condition\Environment::$assignment
   */
  #[\Override]
  public function testAssignment($expectedOutput = '=') : void
  {
    parent::testAssignment($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$equalTo.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'equalTo'
   *   - with message: *'[className]->equalTo is NOT settable'*.
   * - assert allows retrieval of 'equalTo'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( = ).
   * @see \ramp\condition\Environment::$equalTo
   */
  #[\Override]
  public function testEqualTo($expectedOutput = ' = ') : void
  {
    parent::testEqualTo($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$notEqualTo.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'notEqualTo'
   *   - with message: *'[className]->notEqualTo is NOT settable'*.
   * - assert allows retrieval of 'notEqualTo'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( <> ).
   * @see \ramp\condition\Environment::$notEqualTo
   */
  #[\Override]
  public function testNotEqualTo($expectedOutput = ' <> ') : void
  {
    parent::testNotEqualTo($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$lessThan.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'lessThan'
   *   - with message: *'[className]->lessThan is NOT settable'*.
   * - assert allows retrieval of 'lessThan'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( < ).
   * @see \ramp\condition\Environment::$lessThan
   */
  #[\Override]
  public function testLessThan($expectedOutput = ' < ') : void
  {
    parent::testLessThan($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$greaterThan.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'greaterThan'
   *   - with message: *'[className]->greaterThan is NOT settable'*.
   * - assert allows retrieval of 'greaterThan'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( > ).
   * @see \ramp\condition\Environment::$greaterThan
   */
  #[\Override]
  public function testGreaterThan($expectedOutput = ' > ') : void
  {
    parent::testGreaterThan($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$and.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'and'
   *   - with message: *'[className]->and is NOT settable'*.
   * - assert allows retrieval of 'and'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( AND ).
   * @see \ramp\condition\Environment::$and
   */
  #[\Override]
  public function testAnd($expectedOutput = ' AND ') : void
  {
    parent::testAnd($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$or.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'or'
   *   - with message: *'[className]->or is NOT settable'*.
   * - assert allows retrieval of 'or'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( OR ).
   * @see \ramp\condition\Environment::$or
   */
  #[\Override]
  public function testOr($expectedOutput = ' OR ') : void
  {
    parent::testOr($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$openingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'openingParenthesis'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'openingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction (").
   * @see \ramp\condition\Environment::$openingParenthesis
   */
  #[\Override]
  public function testOpeningParenthesis($expectedOutput = '"') : void
  {
    parent::testOpeningParenthesis($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$closingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'closingParenthesis'
   *   - with message: *'[className]->closingParenthesis is NOT settable'*.
   * - assert allows retrieval of 'closingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction (").
   * @see \ramp\condition\Environment::$closingParenthesis
   */
  #[\Override]
  public function testClosingParenthesis($expectedOutput = '"') : void
  {
    parent::testClosingParenthesis($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$openingGroupingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'openingGroupingParenthesis'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'openingGroupingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction [(].
   * @see \ramp\condition\Environment::$openingGroupingParenthesis
   */
  #[\Override]
  public function testOpeningGroupingParenthesis($expectedOutput = '(') : void
  {
    parent::testOpeningGroupingParenthesis($expectedOutput);
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$closingGroupingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'closingGroupingParenthesis'
   *   - with message: *'[className]->closingGroupingParenthesis is NOT settable'*.
   * - assert allows retrieval of 'closingGroupingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction [)].
   * @see \ramp\condition\Environment::$closingGroupingParenthesis
   */
  #[\Override]
  public function testClosingGroupingParenthesis($expectedOutput = ')') : void
  {
    parent::testClosingGroupingParenthesis($expectedOutput);
  }
  #endregion
}
