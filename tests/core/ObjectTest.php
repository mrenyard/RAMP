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
namespace tests\ramp\core;

require_once '/usr/share/php/tests/ramp/TestBase.php';

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';

require_once '/usr/share/php/tests/ramp/mocks/core/AnObject.class.php';

use tests\ramp\TestBase;

use ramp\core\RAMPObject;
use ramp\core\BadPropertyCallException;
use ramp\core\PropertyNotSetException;

use tests\ramp\mocks\core\AnObject;

/**
 * Collection of tests for \ramp\core\RAMPObject.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\condition\mocks\ObjectTest\AnObject}
 */
class ObjectTest extends TestBase
{
  protected $testObject;

  #region Setup
  protected function preSetup() : void { }
  protected function getTestObject() : RAMPObject { return new AnObject(); }
  protected function postSetup() : void { }
  #endregion

  /**
   * Default base constructor assertions \ramp\core\RAMPObject::__construct().
   * - assert child RAMPObject is instance of the parent
   * @see \ramp\core\RAMPObject
   */
  public function testConstruct() : void
  {
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
  }

  #region New Specialist Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->badProperty is NOT settable');
    $this->testObject->badProperty = 1;
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see ramp\core\RAMPObject::__get()
   */
  public function testBadPropertyCallExceptionOn__get() : void
  {
    $this->expectException(BadPropertyCallException::class);
    $this->expectExceptionMessage('Unable to locate \'badProperty\' of \'' . get_class($this->testObject) . '\'');
    $o = $this->testObject->badProperty;
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
    $localTestObject = new AnObject();
    $value = 'VALUE';
    $localTestObject->aProperty = $value;
    $this->assertSame($localTestObject->aProperty, $value);
  }

  /**
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  public function testToString() : void
  {
    $this->assertSame(get_class($this->testObject), (string)$this->testObject);
  }
  #endregion
}
