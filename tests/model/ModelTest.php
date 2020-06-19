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
namespace tests\svelte\model;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';

use svelte\model\Model;

/**
 * Mock Concreate implementation of \svelte\model\Model for testing against.
 */
class MockModel extends Model { }

/**
 * Collection of tests for \svelte\model\Model.
 */
class ModelTest extends \PHPUnit\Framework\TestCase {

  /**
   * Collection of assertions for \svelte\model\Model::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * @link svelte.model.Model svelte\model\Model
   */
  public function testConstruct()
  {
    $testObject = new MockModel();
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\svelte\model\Model', $testObject);
  }
}
