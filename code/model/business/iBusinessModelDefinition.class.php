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

use \svelte\core\Str;

/**
 * Interface for defining Model identifiers (recordName, recordKey, propertyName).
 *
 * RESPONSIBILITIES
 * - Describe base api for defining Model identifiers.
 *
 * COLLABORATORS
 * - {@link \svelte\core\Str}
 */
interface iBusinessModelDefinition
{
  /**
   * Returns name of requested Record / Record Set or NULL.
   * @return \svelte\core\Str Name of requested Record / Record Set or NULL.
   */
  public function getRecordName() : Str;

  /**
   * Returns primary key value of requested svelte\model\Record or NULL.
   * @return \svelte\core\Str Primary key for requested Record if any.
   */
  public function getRecordKey() : ?Str;

  /**
   * Returns name of requested svelte\model\Property of Record or NULL.
   * @return \svelte\core\Str Name of requested Property if any.
   */
  public function getPropertyName(): ?Str;
}
