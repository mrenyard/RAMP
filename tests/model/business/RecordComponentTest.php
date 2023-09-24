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

require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordComponent.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;

use tests\ramp\mocks\core\AnObject;
use ramp\model\business\RecordComponent;
use tests\ramp\mocks\model\MockRecordComponent;
use tests\ramp\mocks\model\MockRecord;

/**
 * Collection of tests for \ramp\model\business\Relatable.
 */
class RecordComponentTest extends \tests\ramp\model\business\BusinessModelTest
{
  private $parentPropertyName;
  private $parentRecord;

  /**
   * Template method inc. factory for TestObject instance.
   */
  protected function preSetup() : void {
    $this->parentPropertyName = Str::set('aProperty');
    $this->parentRecord = new MockRecord();
  }
  protected function getTestObject() : RAMPObject {
    return new MockRecordComponent($this->parentPropertyName, $this->parentRecord);
  }

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

  /**
   * Hold reference back to associated parent Record and propertyName
   * - assert parentRecord as passed to constructor.
   * - assert parentPropertyName as passed to constructor.
   * @link ramp.model.business.RecordComponent#method_get_parentRecord ramp\model\business\RecordComponent::parentRecord
   * @link ramp.model.business.RecordComponent#method_get_parentProppertyName ramp\model\business\RecordComponent::parentProppertyName
   */
  public function testInitStateRecordComponentParent()
  {
    $this->assertSame($this->parentRecord, $this->testObject->parentRecord);
    $this->assertSame($this->parentPropertyName, $this->testObject->parentPropertyName);
  }

  /**
   * Set 'parentRecord' NOT accessable ramp\model\business\RecordComponent::parentRecord.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'parentRecord'
   * @link ramp.model.business.RecordComponent#method_set_parentRecord ramp\model\business\RecordComponent::parentRecord
   */
  public function testSetParentRecordPropertyNotSetException()
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->parentRecord is NOT settable');
    $this->testObject->parentRecord = 'PARENTRECORD';
  }

  /**
   * Set 'parentPropertyName' NOT accessable ramp\model\business\RecordComponent::parentPropertyName.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'parentPropertyName'
   * @link ramp.model.business.RecordComponent#method_set_parentPropertyName ramp\model\business\RecordComponent::parentPropertyName
   */
  public function testSetParentPropertyNamePropertyNotSetException()
  {
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage(get_class($this->testObject) . '->parentPropertyName is NOT settable');
    $this->testObject->parentPropertyName = 'PARENTPROPERTYNAME';
  }
}
