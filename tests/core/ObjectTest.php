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
 * @package RAMP Testing
 * @version 0.0.9;
 */
namespace tests\ramp\core;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';

require_once '/usr/share/php/tests/ramp/core/mocks/ObjectTest/AnObject.class.php';

use ramp\core\RAMPObject;
use ramp\core\BadPropertyCallException;
use ramp\core\PropertyNotSetException;

use tests\ramp\core\mocks\ObjectTest\AnObject;

/**
 * Collection of tests for \ramp\core\RAMPObject.
 *
 * COLLABORATORS
 * - {@link \tests\ramp\condition\mocks\ObjectTest\AnObject}
 */
class RAMPObjectTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for ramp\core\RAMPObject::__construct().
   * - assert child RAMPObject is instance of the parent
   * @link ramp.core.RAMPObject \ramp\core\RAMPObject
   */
  public function test__construct()
  {
    $testRAMPObject = new AnObject();
    $this->assertInstanceOf('ramp\core\RAMPObject', $testRAMPObject);
    return $testRAMPObject;
  }

  /**
   * Collection of assertions for ramp\core\RAMPObject::__set() and \ramp\core\RAMPObject::__get().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @param \ramp\core\RAMPObject $testRAMPObject Instance of MockRAMPObject for testing
   * @depends test__construct
   * @link ramp.core.RAMPObject#method___set \ramp\core\RAMPObject::__set()
   * @link ramp.core.RAMPObject#method___get \ramp\core\RAMPObject::__get()
   */
  public function testSetGet(RAMPObject $testRAMPObject)
  {
    $value = new AnObject();
    try {
      $testRAMPObject->noProperty = $value;
    } catch (PropertyNotSetException $expected) {
      try {
        $value = $testRAMPObject->noProperty;
      } catch (BadPropertyCallException $expecrted) {
        $testRAMPObject->aProperty = $value;
        $this->assertSame($value, $testRAMPObject->aProperty);
        return;
      }
      $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised.');
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for ramp\core\RAMPObject::__toString().
   * - assert {@link \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @param \ramp\core\RAMPObject $testRAMPObject Instance of MockRAMPObject for testing
   * @depends test__construct
   * @link ramp.core.RAMPObject#method___toString \ramp\core\RAMPObject::__toString()
   */
  public function testToString(RAMPObject $testRAMPObject)
  {
    $this->assertSame('tests\ramp\core\mocks\ObjectTest\AnObject', (string)$testRAMPObject);
  }
}
