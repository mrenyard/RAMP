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
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\http;

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/http/Method.class.php';

require_once '/usr/share/php/tests/ramp/mocks/http/ExtendedMethod.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\http\Method;

use tests\ramp\mocks\http\ExtendedMethod;

/**
 * Collection of tests for \ramp\http\Method.
 */
class MethodTest extends \tests\ramp\core\ObjectTest
{
  #region Setup
  #[\Override]
  protected function getTestObject() : RAMPObject { return ExtendedMethod::SUCCEED(); }
  #endregion

  /**
   * Collection of assertions for \ramp\http\Method::GET().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert throws InvalidArgumentException When first constructor arguments NOT an int
   *   - with message: *'[className]::constructor expects first argument of type int.'*
   * @see \ramp\http\Method::__construct()
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\http\Method', $this->testObject);
    $this->assertSame(ExtendedMethod::SUCCEED(), $this->testObject);
    $this->assertSame('VERB', (string)$this->testObject);

    // try {
    //   $o = ExtendedMethod::FAIL();
    // } catch (\InvalidArgumentException $expected) {
    //   $this->assertSame(
    //     'tests\ramp\mocks\http\ExtendedMethod::constructor expects first argument of type int.', $expected->getMessage()
    //   );
      // $o = ExtendedMethod::SUCCEED();
      // return;
    // }
    // $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
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
  #endregion

  #region New Specialist Tests
  /**
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    $this->assertSame('VERB', (string)$this->testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::GET().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'GET' when cast to string
   * @see \ramp\http\Method::GET()
   */
  public function testGet() : void
  {
    $testObject = Method::GET();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::GET(), $testObject);
    $this->assertSame('GET', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::POST().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'POST' when cast to string
   * @see \ramp\http\Method::POST()
   */
  public function testPOST() : void
  {
    $testObject = Method::POST();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::POST(), $testObject);
    $this->assertSame('POST', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::LOCK().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'LOCK' when cast to string
   * @see \ramp\http\Method::LOCK()
   */
  public function testLOCK() : void
  {
    $testObject = Method::LOCK();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::LOCK(), $testObject);
    $this->assertSame('LOCK', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::UNLOCK().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'UNLOCK' when cast to string
   * @see \ramp\http\Method::UNLOCK()
   */
  public function testUNLOCK() : void
  {
    $testObject = Method::UNLOCK();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::UNLOCK(), $testObject);
    $this->assertSame('UNLOCK', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::PUT().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'PUT' when cast to string
   * @see \ramp\http\Method::PUT()
   */
  public function testPUT() : void
  {
    $testObject = Method::PUT();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::PUT(), $testObject);
    $this->assertSame('PUT', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::MOVE().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'MOVE' when cast to string
   * @see \ramp\http\Method::MOVE()
   */
  public function testMOVE() : void
  {
    $testObject = Method::MOVE();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::MOVE(), $testObject);
    $this->assertSame('MOVE', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::DELETE().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'DELETE' when cast to string
   * @see \ramp\http\Method::DELETE()
   */
  public function testDELETE() : void
  {
    $testObject = Method::DELETE();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::DELETE(), $testObject);
    $this->assertSame('DELETE', (string)$testObject);
  }
  #endregion
}
