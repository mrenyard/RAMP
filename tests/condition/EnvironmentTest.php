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

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteEnvironment.class.php';

use \ramp\core\RAMPObject;
use \ramp\core\Str;
use \ramp\core\PropertyNotSetException;

use tests\ramp\mocks\condition\ConcreteEnvironment;

/**
 * Collection of tests for \ramp\condition\Environment.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\ConcreteEnvironment}
 */
class EnvironmentTest extends \tests\ramp\core\ObjectTest
{
  protected $instance;
  protected $className;

  #region Setup
  protected function preSetup() : void {
    $this->instance = ConcreteEnvironment::getInstance();
    $this->className = 'tests\ramp\mocks\condition\ConcreteEnvironment';
  }
  protected function getTestObject() : RAMPObject { return ConcreteEnvironment::getInstance(); }
  #endregion

  /**
   * Collection of assertions for \ramp\condition\Environment::getInstance().
   * - assert __constructor inaccessible (protected).
   * - assert is instance of {@see \ramp\condition\Environment}
   * - assert is instance of {@see \ramp\condition\iEnvironment}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every call.
   * @see \ramp\condition\Environment\getInstance()
   */
  public function testConstruct() : void
  {
    try {
      $o = new ConcreteEnvironment();
    } catch (\Error $expected) {
      parent::testConstruct();
      $this->assertInstanceOf('\ramp\condition\Environment', $this->testObject);
      $this->assertInstanceOf('\ramp\condition\iEnvironment', $this->testObject);
      $this->assertSame($this->instance, $this->testObject);
      return;
    }
    $this->fail('An expected \Error has NOT been raised');
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
  #endregion

  #region Specialist Tests
  /**
   * Collection of assertions for \ramp\condition\Environment::$memberAccess.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'memberAccess'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'memberAccess'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$memberAccess
   */
  public function testMemberAccess($expectedOutput = 'memberAccess') : void
  {
    try {
      $this->testObject->memberAccess = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->memberAccess is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->memberAccess);
      $this->assertSame($expectedOutput, (string)$this->testObject->memberAccess);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$assignment.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'assignment'
   *   - with message: *'[className]->assignment is NOT settable'*.
   * - assert allows retrieval of 'assignment'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$assignment
   */
  public function testAssignment($expectedOutput = 'assignment') : void
  {
    try {
      $this->testObject->assignment = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->assignment is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->assignment);
      $this->assertSame($expectedOutput, (string)$this->testObject->assignment);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$qualTo.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'equalTo'
   *   - with message: *'[className]->equalTo is NOT settable'*.
   * - assert allows retrieval of 'equalTo'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$equalTo
   */
  public function testEqualTo($expectedOutput = ' equalTo ') : void
  {
    try {
      $this->testObject->equalTo = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->equalTo is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->equalTo);
      $this->assertSame($expectedOutput, (string)$this->testObject->equalTo);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$notEqualTo.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'notEqualTo'
   *   - with message: *'[className]->notEqualTo is NOT settable'*.
   * - assert allows retrieval of 'notEqualTo'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$notEqualTo
   */
  public function testNotEqualTo($expectedOutput = ' notEqualTo ') : void
  {
    try {
      $this->testObject->notEqualTo = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->notEqualTo is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->notEqualTo);
      $this->assertSame($expectedOutput, (string)$this->testObject->notEqualTo);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }
  /**
   * Collection of assertions for \ramp\condition\Environment::$lessThan.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'lessThan'
   *   - with message: *'[className]->and is NOT settable'*.
   * - assert allows retrieval of 'lessThan'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$lessThan
   */
  public function testLessThan($expectedOutput = ' lessThan ') : void
  {
    try {
      $this->testObject->lessThan = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->lessThan is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->lessThan);
      $this->assertSame($expectedOutput, (string)$this->testObject->lessThan);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }
  /**
   * Collection of assertions for \ramp\condition\Environment::&greaterThan.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'greaterThan'
   *   - with message: *'[className]->and is NOT settable'*.
   * - assert allows retrieval of 'greaterThan'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$greaterThan
   */
  public function testGreaterThan($expectedOutput = ' greaterThan ') : void
  {
    try {
      $this->testObject->greaterThan = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->greaterThan is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->greaterThan);
      $this->assertSame($expectedOutput, (string)$this->testObject->greaterThan);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$and.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'and'
   *   - with message: *'[className]->and is NOT settable'*.
   * - assert allows retrieval of 'and'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$and
   */
  public function testAnd($expectedOutput = ' and ') : void
  {
    try {
      $this->testObject->and = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->and is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->and);
      $this->assertSame($expectedOutput, (string)$this->testObject->and);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$or.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'or'
   *   - with message: *'[className]->or is NOT settable'*.
   * - assert allows retrieval of 'or'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$or
   */
  public function testOr($expectedOutput = ' or ') : void
  {
    try {
      $this->testObject->or = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->or is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->or);
      $this->assertSame($expectedOutput, (string)$this->testObject->or);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$openingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'openingParenthesis'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'openingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$openingParenthesis
   */
  public function testOpeningParenthesis($expectedOutput = 'openingParenthesis') : void
  {
    try {
      $this->testObject->openingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->openingParenthesis is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->openingParenthesis);
      $this->assertSame($expectedOutput, (string)$this->testObject->openingParenthesis);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$closingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'closingParenthesis'
   *   - with message: *'[className]->closingParenthesis is NOT settable'*.
   * - assert allows retrieval of 'closingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$closingParenthesis
   */
  public function testClosingParenthesis($expectedOutput = 'closingParenthesis') : void
  {
    try {
      $this->testObject->closingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->closingParenthesis is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->closingParenthesis);
      $this->assertSame($expectedOutput, (string)$this->testObject->closingParenthesis);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$openingGroupingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'openingGroupingParenthesis'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'openingGroupingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$openingGroupingParenthesis
   */
  public function testOpeningGroupingParenthesis($expectedOutput = 'openingGroupingParenthesis') : void
  {
    try {
      $this->testObject->openingGroupingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->openingGroupingParenthesis is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->openingGroupingParenthesis);
      $this->assertSame($expectedOutput, (string)$this->testObject->openingGroupingParenthesis);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$closingGroupingParenthesis.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'closingGroupingParenthesis'
   *   - with message: *'[className]->closingGroupingParenthesis is NOT settable'*.
   * - assert allows retrieval of 'closingGroupingParenthesis'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction.
   * @see \ramp\condition\Environment::$closingGroupingParenthesis
   */
  public function testClosingGroupingParenthesis($expectedOutput = 'closingGroupingParenthesis') : void
  {
    try {
      $this->testObject->closingGroupingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->className . '->closingGroupingParenthesis is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->closingGroupingParenthesis);
      $this->assertSame($expectedOutput, (string)$this->testObject->closingGroupingParenthesis);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }
  #endregion
}