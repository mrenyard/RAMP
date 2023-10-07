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
namespace tests\ramp\model;

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockModel.class.php';

use ramp\core\RAMPObject;
use ramp\model\Model;

use tests\ramp\mocks\model\MockModel;

/**
 * Collection of tests for \ramp\model\Model.
 */
class ModelTest extends \tests\ramp\core\ObjectTest
{
  /**
   * Template method inc. factory for TestObject instance.
   */
  protected function getTestObject() : RAMPObject { return new MockModel(); }

  /**
   * Default base constructor assertions \ramp\model\Model::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * @link ramp.model.Model ramp\model\Model
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @link ramp.model.Model#method__set ramp\model\Model::__set()
   */
  public function testPropertyNotSetExceptionOn__set()
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @link ramp.model.Model#method__get ramp\model\Model::__get()
   */
  public function testBadPropertyCallExceptionOn__get()
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Good property is accessable on \ramp\model\Model::__get() and \ramp\model\Model::__set()
   * - assert get <i>RAMPObject->aProperty</i> returns same as set <i>RAMPObject->aProperty = $value</i>
   * @link ramp.model.Model#method___set \ramp\model\Model::__set()
   * @link ramp.model.Model#method___get \ramp\model\Model::__get()
   */
  public function testAccessPropertyWith__set__get()
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert {@link \ramp\model\Model::__toString()} returns string 'class name'
   * @link ramp.model.Model#method___toString \ramp\model\Model::__toString()
   */
  public function testToString()
  {
    parent::testToString();
  }
}
