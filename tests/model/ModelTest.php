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
namespace tests\ramp\model;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';

use ramp\model\Model;

/**
 * Mock Concreate implementation of \ramp\model\Model for testing against.
 */
class MockModel extends Model { }

/**
 * Collection of tests for \ramp\model\Model.
 */
class ModelTest extends \PHPUnit\Framework\TestCase {

  /**
   * Collection of assertions for \ramp\model\Model::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * @link ramp.model.Model ramp\model\Model
   */
  public function testConstruct()
  {
    $testObject = new MockModel();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\model\Model', $testObject);
  }
}
