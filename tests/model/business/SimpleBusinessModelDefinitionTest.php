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
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';

use svelte\core\Str;
use svelte\model\business\SimpleBusinessModelDefinition;

/**
 * Collection of tests for \svelte\model\business\SimpleBusinessModelDefinition.
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
   * Collection of assertions for svelte\model\business\SimpleBusinessModelDefinition::__construct().
   * - assert is instance of {@link \svelte\model\iBusinessModelDefinition}.
   * @link svelte.model.business.SimpleBusinessModelDefinition svelte\model\business\SimpleBusinessModelDefinition
   */
  public function test__Construct()
  {
    $testObject = new SimpleBusinessModelDefinition($this->recordName);
    $this->assertInstanceOf('svelte\model\business\iBusinessModelDefinition', $testObject);
  }

  /**
   * The following methods are accessable and return as defined in constructor.
   * - getRecordName() equals Str with the value 'Record'
   * - getRecordKey() equals null
   * - getPropertyName() equals null
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getRecordName svelte\model\business\SimpleBusinessModelDefinition::getRecordName()
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getRecordKey svelte\model\business\SimpleBusinessModelDefinition::getRecordKey()
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getPropertyName svelte\model\business\SimpleBusinessModelDefinition::getPropertyName()
   */
  public function testRecordOnly()
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
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getRecordName svelte\model\business\SimpleBusinessModelDefinition::getRecordName()
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getRecordKey svelte\model\business\SimpleBusinessModelDefinition::getRecordKey()
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getPropertyName svelte\model\business\SimpleBusinessModelDefinition::getPropertyName()
   */
  public function testRecordWithKey()
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
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getRecordName svelte\model\business\SimpleBusinessModelDefinition::getRecordName()
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getRecordKey svelte\model\business\SimpleBusinessModelDefinition::getRecordKey()
   * @link svelte.model.business.SimpleBusinessModelDefinition#method_getPropertyName svelte\model\business\SimpleBusinessModelDefinition::getPropertyName()
   */
  public function testRecordKeyProperty()
  {
    $testObject = new SimpleBusinessModelDefinition($this->recordName, $this->recordKey, $this->propertyName);
    $this->assertSame($this->recordName, $testObject->recordName);
    $this->assertSame($this->recordKey, $testObject->recordKey);
    $this->assertSame($this->propertyName, $testObject->propertyName);
  }
}
