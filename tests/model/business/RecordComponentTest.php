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
namespace tests\ramp\model\business;

require_once '/usr/share/php/tests/ramp/model/business/BusinessModelTest.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordComponent.class.php';

use ramp\core\RAMPObject;

use tests\ramp\mocks\core\AnObject;
use ramp\model\business\RecordComponent;
use tests\ramp\mocks\model\MockRecordComponent;

/**
 * Collection of tests for \ramp\model\business\Relatable.
 */
class RecordComponentTest extends \tests\ramp\model\business\BusinessModelTest
{
  /**
   * Template method inc. factory for TestObject instance.
   */
  protected function getTestObject() : RAMPObject { return new MockRecordComponent(); }

  /**
   * Collection of assertions for \ramp\model\business\RecordComponent::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\RecordComponent}
   * @link ramp.model.business.RecordComponent ramp\model\business\RecordComponent
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject);
  }
}
