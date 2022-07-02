<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\RAMPObject;

/**
 * Simple concrete implementation of \ramp\model\SimpleBusinessModelDefinition,
 *  defining Model indentifiers (record, key, property).
 */
class SimpleBusinessModelDefinition extends RAMPObject implements iBusinessModelDefinition
{
  private $recordName;
  private $recordKey;
  private $propertyName;

  /**
   * Constructs a SimpleBusinessModelDefinition.
   * @param \ramp\core\Str $recordName Value for RecordName
   * @param \ramp\core\Str $recordKey Value for RecordKey
   * @param \ramp\core\Str $propertyName Value for PropertyName
   */
  public function __construct(Str $recordName, Str $recordKey = null, Str $propertyName = null)
  {
    $this->recordName = $recordName;
    $this->recordKey = $recordKey;
    $this->propertyName = $propertyName;
  }

  /**
   * Returns name of requested Record one or collection.
   * **DO NOT CALL DIRECTLY, USE this->recordName;**
   * @return \ramp\core\Str Name of requested Record one or collection.
   */
  public function get_recordName() : Str
  {
    return $this->recordName;
  }

  /**
   * Returns primary key value of requested ramp\model\business\Record or NULL.
   * **DO NOT CALL DIRECTLY, USE this->recordKey;**
   * @return \ramp\core\Str Primary key for requested Record if any.
   */
  public function get_recordKey() : ?Str
  {
    return $this->recordKey;
  }

  /**
   * Returns name of requested Property of ramp\model\business\Record or NULL.
   * **DO NOT CALL DIRECTLY, USE this->propertyName;**
   * @return \ramp\core\Str Name of requested Property if any.
   */
  public function get_propertyName() : ?Str
  {
    return $this->propertyName;
  }
}
