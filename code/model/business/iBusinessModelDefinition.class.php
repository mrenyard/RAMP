<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use \ramp\core\Str;

/**
 * Interface for defining Model identifiers (recordName, recordKey, propertyName).
 *
 * RESPONSIBILITIES
 * - Describe base api for defining Model identifiers.
 *
 * COLLABORATORS
 * - {@link \ramp\core\Str}
 *
 * @property-read \ramp\core\Str $recordName Returns name of requested Record one or collection.
 * @property-read ?\ramp\core\Str $recordKey Returns primary key value of requested Record or NULL.
 * @property-read ?\ramp\core\Str $propertyName Returns name of requested Property of Record or NULL.
 */
interface iBusinessModelDefinition
{
  /**
   * Returns name of requested Record one or collection.
   * **DO NOT CALL DIRECTLY, USE this->recordName;**
   * @return \ramp\core\Str Name of requested Record one or collection.
   */
  public function get_recordName() : Str;

  /**
   * Returns primary key value of requested ramp\model\business\Record or NULL.
   * **DO NOT CALL DIRECTLY, USE this->recordKey;**
   * @return \ramp\core\Str Primary key for requested Record if any.
   */
  public function get_recordKey() : ?Str;

  /**
   * Returns name of requested Property of ramp\model\business\Record or NULL.
   * **DO NOT CALL DIRECTLY, USE this->propertyName;**
   * @return \ramp\core\Str Name of requested Property if any.
   */
  public function get_propertyName(): ?Str;
}
