<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
 * @version 0.0.9;
 */
namespace tests\svelte\core;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';

require_once '/usr/share/php/tests/svelte/core/mocks/ObjectTest/AnObject.class.php';

use svelte\core\SvelteObject;
use svelte\core\BadPropertyCallException;
use svelte\core\PropertyNotSetException;

use tests\svelte\core\mocks\ObjectTest\AnObject;

/**
 * Collection of tests for \svelte\core\SvelteObject.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\condition\mocks\ObjectTest\AnObject}
 */
class SvelteObjectTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for svelte\core\SvelteObject::__construct().
   * - assert child SvelteObject is instance of the parent
   * @link svelte.core.SvelteObject \svelte\core\SvelteObject
   */
  public function test__construct()
  {
    $testSvelteObject = new AnObject();
    $this->assertInstanceOf('svelte\core\SvelteObject', $testSvelteObject);
    return $testSvelteObject;
  }

  /**
   * Collection of assertions for svelte\core\SvelteObject::__set() and \svelte\core\SvelteObject::__get().
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * - assert {@link \svelte\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * - assert get <i>SvelteObject->aProperty</i> returns same as set <i>SvelteObject->aProperty = $value</i>
   * @param \svelte\core\SvelteObject $testSvelteObject Instance of MockSvelteObject for testing
   * @depends test__construct
   * @link svelte.core.SvelteObject#method___set \svelte\core\SvelteObject::__set()
   * @link svelte.core.SvelteObject#method___get \svelte\core\SvelteObject::__get()
   */
  public function testSetGet(SvelteObject $testSvelteObject)
  {
    $value = new AnObject();
    try {
      $testSvelteObject->noProperty = $value;
    } catch (PropertyNotSetException $expected) {
      try {
        $value = $testSvelteObject->noProperty;
      } catch (BadPropertyCallException $expecrted) {
        $testSvelteObject->aProperty = $value;
        $this->assertSame($value, $testSvelteObject->aProperty);
        return;
      }
      $this->fail('An expected \svelte\core\BadPropertyCallException has NOT been raised.');
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for svelte\core\SvelteObject::__toString().
   * - assert {@link \svelte\core\SvelteObject::__toString()} returns string 'class name'
   * @param \svelte\core\SvelteObject $testSvelteObject Instance of MockSvelteObject for testing
   * @depends test__construct
   * @link svelte.core.SvelteObject#method___toString \svelte\core\SvelteObject::__toString()
   */
  public function testToString(SvelteObject $testSvelteObject)
  {
    $this->assertSame('tests\svelte\core\mocks\ObjectTest\AnObject', (string)$testSvelteObject);
  }
}
