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
namespace ramp\core;

/**
 * Interface representing a choice, single or one of several.
 *
 * RESPONSIBILITIES
 * - Describe base api for a choice.
 *
 * @property-read \ramp\core\Str Returns id unique identifier (URN:string).
 * @property-read int $key Returns key unique identifier (enum:int)
 * @property-read \ramp\core\Str $description Returns description.
 * @property-read bool $isSelected Returns whether this has been chosen.
 */
interface iOption
{
  /**
   * Get id unique identifier (URN:Str)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str id
   */
  public function get_id(): Str;

  /**
   * Get key unique identifier (enum:int)
   * **DO NOT CALL DIRECTLY, USE this->key;**
   * @return int Key
   */
   public function get_key(): int;

   /**
   * Get Description.
   * **DO NOT CALL DIRECTLY, USE this->description;**
   * @return Str Description
   */
  public function get_description() : Str;

  /**
   * Returns whether this has been chosen.
   * **DO NOT CALL DIRECTLY, USE this->isSelected;**
   */
  public function get_isSelected() : bool;
}
