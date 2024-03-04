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

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';

use ramp\core\Str;
use ramp\model\business\SimpleBusinessModelDefinition;

/**
 * Collection of tests for \ramp\model\business\SimpleBusinessModelDefinition.
 */
class SimpleBusinessModelDefinitionTest extends \PHPUnit\Framework\TestCase {

  private $recordName;
  private $recordKey;
  private $propertyName;

  /**
   * Setup - add variables
   */
  public function setup() : void
  {
    $this->recordName = Str::set('Record');
    $this->recordKey = Str::set('key');
    $this->propertyName = Str::set('property');
  }

  /**
   * Collection of assertions for ramp\model\business\SimpleBusinessModelDefinition.
   * - assert is instance of {@see \ramp\model\iBusinessModelDefinition}.
   * @see \ramp\model\business\SimpleBusinessModelDefinition
   */
  public function test__Construct()
  {
    $testObject = new SimpleBusinessModelDefinition($this->recordName);
    $this->assertInstanceOf('ramp\model\business\iBusinessModelDefinition', $testObject);
  }

  /**
   * The following methods are accessable and return as defined in constructor.
   * - getRecordName() equals Str with the value 'Record'
   * - getRecordKey() equals null
   * - getPropertyName() equals null
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getRecordName()
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getRecordKey()
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getPropertyName()
   */
  public function testRecordOnly() : void
  {
    $testObject = new SimpleBusinessModelDefinition($this->recordName);
    $this->assertSame($this->recordName, $testObject->recordName);
    $this->assertNull($testObject->recordKey);
    $this->assertNull($testObject->propertyName);
  }

  /**
   * The following methods are accessable and return as defined in constructor.
   * - getRecordName() equals Str with the value 'Record'
   * - getRecordKey() equals Str with the value 'key'
   * - getPropertyName() equals null
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getRecordName()
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getRecordKey()
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getPropertyName()
   */
  public function testRecordWithKey() : void
  {
    $testObject = new SimpleBusinessModelDefinition($this->recordName, $this->recordKey);
    $this->assertSame($this->recordName, $testObject->recordName);
    $this->assertSame($this->recordKey, $testObject->recordKey);
    $this->assertNull($testObject->propertyName);
  }

  /**
   * The following methods are accessable and return as defined in constructor.
   * - getRecordName() equals Str with the value 'Record'
   * - getRecordKey() equals Str with the value 'key'
   * - getPropertyName() equals Str with the value 'property'
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getRecordName()
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getRecordKey()
   * @see \ramp\model\business\SimpleBusinessModelDefinition::getPropertyName()
   */
  public function testRecordKeyProperty() : void
  {
    $testObject = new SimpleBusinessModelDefinition($this->recordName, $this->recordKey, $this->propertyName);
    $this->assertSame($this->recordName, $testObject->recordName);
    $this->assertSame($this->recordKey, $testObject->recordKey);
    $this->assertSame($this->propertyName, $testObject->propertyName);
  }
}