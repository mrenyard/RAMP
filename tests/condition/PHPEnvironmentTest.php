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

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';

use \ramp\core\Str;
use \ramp\core\PropertyNotSetException;
use \ramp\condition\PHPEnvironment;

/**
 * Collection of tests for \ramp\condition\Environment.
 * .
 */
class PHPEnvironmentTest extends \PHPUnit\Framework\TestCase
{
  private $testInstance;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->testInstance = PHPEnvironment::getInstance();
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::getInstance().
   * - assert __constructor inaccessible (protected).
   * - assert is instance of {@see \ramp\condition\Environment}
   * - assert is instance of {@see \ramp\condition\iEnvironment}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every call.
   * @see \ramp\condition\Environment\getInstance()
   */
  public function testGetInstance()
  {
    try {
      $testObject = new PHPEnvironment();
    } catch (\Error $expected) {
      $this->assertInstanceOf('\ramp\condition\Environment', $this->testInstance);
      $this->assertInstanceOf('\ramp\condition\iEnvironment', $this->testInstance);
      $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testInstance);
      $this->assertSame(PHPEnvironment::getInstance(), $this->testInstance);
      return;
    }
    $this->fail('An expected \Error has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$memberAccess.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'memberAccess'
   *   - with message: *'[className]->memberAccess is NOT settable'*.
   * - assert allows retrieval of 'memberAccess'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction (->).
   * @see \ramp\condition\Environment::$memberAccess
   */
  public function testMemberAccess()
  {
    try {
      $this->testInstance->memberAccess = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->memberAccess);
      $this->assertSame('->', (string)$this->testInstance->memberAccess);
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
   * - assert retreved is same string as provided at construction (=).
   * @see \ramp\condition\Environment::$assignment
   */
  public function testAssignment()
  {
    try {
      $this->testInstance->assignment = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->assignment is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->assignment);
      $this->assertSame('=', (string)$this->testInstance->assignment);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Environment::$equalTo.
   * - assert throws {@see \ramp\core\PropertyNotSetException} when trying to set 'equalTo'
   *   - with message: *'[className]->equalTo is NOT settable'*.
   * - assert allows retrieval of 'equalTo'.
   * - assert retreved is an instance of {@see \ramp\core\Str}.
   * - assert retreved is same string as provided at construction ( == ).
   * @see \ramp\condition\Environment::$equalTo
   */
  public function testEqualTo()
  {
    try {
      $this->testInstance->equalTo = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->equalTo is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->equalTo);
      $this->assertSame(' == ', (string)$this->testInstance->equalTo);
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
   * - assert retreved is same string as provided at construction ( != ).
   * @see \ramp\condition\Environment::$notEqualTo
   */
  public function testNotEqualTo()
  {
    try {
      $this->testInstance->notEqualTo = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->notEqualTo is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->notEqualTo);
      $this->assertSame(' != ', (string)$this->testInstance->notEqualTo);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
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
  public function testLessThan()
  {
    try {
      $this->testInstance->lessThan = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->lessThan is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->lessThan);
      $this->assertSame(' < ', (string)$this->testInstance->lessThan);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
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
  public function testGreaterThan()
  {
    try {
      $this->testInstance->greaterThan = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->greaterThan is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->greaterThan);
      $this->assertSame(' > ', (string)$this->testInstance->greaterThan);
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
   * - assert retreved is same string as provided at construction ( && ).
   * @see \ramp\condition\Environment::$and
   */
  public function testAnd()
  {
    try {
      $this->testInstance->and = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->and is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->and);
      $this->assertSame(' && ', (string)$this->testInstance->and);
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
   * - assert retreved is same string as provided at construction ( || ).
   * @see \ramp\condition\Environment::$or
   */
  public function testOr()
  {
    try {
      $this->testInstance->or = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->or is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->or);
      $this->assertSame(' || ', (string)$this->testInstance->or);
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
   * - assert retreved is same string as provided at construction (').
   * @see \ramp\condition\Environment::$openingParenthesis
   */
  public function testOpeningParenthesis()
  {
    try {
      $this->testInstance->openingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->openingParenthesis is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->openingParenthesis);
      $this->assertSame("'", (string)$this->testInstance->openingParenthesis);
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
   * - assert retreved is same string as provided at construction (').
   * @see \ramp\condition\Environment::$closingParenthesis
   */
  public function testClosingParenthesis()
  {
    try {
      $this->testInstance->closingParenthesis = Str::set('not settable');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\condition\PHPEnvironment->closingParenthesis is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $this->testInstance->closingParenthesis);
      $this->assertSame("'", (string)$this->testInstance->closingParenthesis);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised');
  }
}
