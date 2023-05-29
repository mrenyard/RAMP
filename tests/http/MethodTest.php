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

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/http/Method.class.php';

require_once '/usr/share/php/tests/ramp/http/mocks/MethodTest/ExtendedMethod.class.php';

use ramp\core\Str;
use ramp\http\Method;

use tests\ramp\http\mocks\MethodTest\ExtendedMethod;

/**
 * Collection of tests for \ramp\http\Method.
 */
class MethodTest extends \PHPUnit\Framework\TestCase {

  /**
   * Collection of assertions for \ramp\http\Method::GET().
   * - assert throws InvalidArgumentException When first constructor arguments NOT an int
   *   - with message: <em>'[className]::constructor expects first argument of type int.'</em>
   * @link ramp.http.Method#method__construct ramp\http\Method::__construct()
   */
  public function test__Construct()
  {
    try {
      $o = ExtendedMethod::FAIL();
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\http\mocks\MethodTest\ExtendedMethod::constructor expects first argument of type int.', $expected->getMessage()
      );
      $o = ExtendedMethod::SUCCEED();
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\http\Method::GET().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'GET' when cast to string
   * @link ramp.http.Method#method_GET ramp\http\Method::GET()
   */
  public function testGet()
  {
    $testObject = Method::get();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::GET(), $testObject);
    $this->assertSame('GET', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::POST().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'POST' when cast to string
   * @link ramp.http.Method#method_POST ramp\http\Method::POST()
   */
  public function testPOST()
  {
    $testObject = Method::POST();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::POST(), $testObject);
    $this->assertSame('POST', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::LOCK().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'LOCK' when cast to string
   * @link ramp.http.Method#method_LOCK ramp\http\Method::LOCK()
   */
  public function testLOCK()
  {
    $testObject = Method::LOCK();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::LOCK(), $testObject);
    $this->assertSame('LOCK', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::UNLOCK().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'UNLOCK' when cast to string
   * @link ramp.http.Method#method_UNLOCK ramp\http\Method::UNLOCK()
   */
  public function testUNLOCK()
  {
    $testObject = Method::UNLOCK();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::UNLOCK(), $testObject);
    $this->assertSame('UNLOCK', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::PUT().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'PUT' when cast to string
   * @link ramp.http.Method#method_PUT ramp\http\Method::PUT()
   */
  public function testPUT()
  {
    $testObject = Method::PUT();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::PUT(), $testObject);
    $this->assertSame('PUT', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::MOVE().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'MOVE' when cast to string
   * @link ramp.http.Method#method_MOVE ramp\http\Method::MOVE()
   */
  public function testMOVE()
  {
    $testObject = Method::MOVE();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::MOVE(), $testObject);
    $this->assertSame('MOVE', (string)$testObject);
  }

  /**
   * Collection of assertions for \ramp\http\Method::DELETE().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\http\Method}
   * - assert always returns the exact same object
   * - assert returns 'DELETE' when cast to string
   * @link ramp.http.Method#method_DELETE ramp\http\Method::DELETE()
   */
  public function testDELETE()
  {
    $testObject = Method::DELETE();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\http\Method', $testObject);
    $this->assertSame(Method::DELETE(), $testObject);
    $this->assertSame('DELETE', (string)$testObject);
  }
}
