<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\model\business;

use svelte\core\Str;

/**
 * Simple concrete implementation of \svelte\model\SimpleBusinessModelDefinition,
 *  defining Model indentifiers (record, key, property).
 */
class SimpleBusinessModelDefinition implements iBusinessModelDefinition
{
  private $recordName;
  private $recorddKey;
  private $propertyName;

  /**
   * Constructs a SimpleBusinessModelDefinition.
   * @param \svelte\core\Str $recordName Value for RecordName
   * @param \svelte\core\Str $recordKey Value for RecordKey
   * @param \svelte\core\Str $propertyName Value for PropertyName
   */
  public function __construct(Str $recordName, Str $recordKey = null, Str $propertyName = null)
  {
    $this->recordName = $recordName;
    $this->recordKey = $recordKey;
    $this->propertyName = $propertyName;
  }

  /**
   * Returns name of requested Record / Record Set or NULL.
   * @return \svelte\core\Str Name of requested Record / Record Set or NULL.
   */
  public function getRecordName() : Str
  {
    return $this->recordName;
  }

  /**
   * Returns primary key value of requested svelte\model\Record or NULL.
   * @return \svelte\core\Str Primary key for requested Record if any.
   */
  public function getRecordKey() : ?Str
  {
    return $this->recordKey;
  }

  /**
   * Returns name of requested svelte\model\Property of Record or NULL.
   * @return \svelte\core\Str Name of requested Property if any.
   */
  public function getPropertyName() : ?Str
  {
    return $this->propertyName;
  }
}
