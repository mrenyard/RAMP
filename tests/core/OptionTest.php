<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/Option.class.php';

use svelte\core\Str;
use svelte\core\Option;
use svelte\core\PropertyNotSetException;

/**
 * Collection of tests for \svelte\core\Option.
 */
class OptionTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $description;
  private $key;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    $this->key = 1;
    $this->description = Str::set('DESCRIPTION');
    $this->testObject = new Option($this->key, $this->description);
  }

  /**
   * Collection of assertions for \svelte\core\Option::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\core\iOption}
   * - assert is instance of {@link \svelte\core\Option}
   * @link svelte.core.Option svelte\core\Option
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\core\iOption', $this->testObject);
    $this->assertInstanceOf('\svelte\core\Option', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\core\Option::key.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown
   *   when trying to set property 'key'
   * - assert property 'key' is gettable.
   * - assert returned value matches expected result.
   * @link svelte.core.Option#method_get_key svelte\core\Option::key
   */
  public function testGet_key()
  {
    try {
      $this->testObject->key = 1;
    } catch (PropertyNotSetException $expected) {
      $this->assertEquals('svelte\core\Option->key is NOT settable', $expected->getMessage());
      $this->assertSame($this->key, $this->testObject->key);
    }
  }

  /**
   * Collection of assertions for \svelte\core\Option::description.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown
   *   when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.core.Option#method_get_description svelte\core\Option::description
   */
  public function testGet_description()
  {
    try {
      $this->testObject->description = 1;
    } catch (PropertyNotSetException $expected) {
      $this->assertEquals('svelte\core\Option->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->description);
      $this->assertSame($this->description, $this->testObject->description);
    }
  }
}
