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
namespace svelte\core;

/**
 * Interface representing a choice, single or one of several.
 *
 * RESPONSIBILITIES
 * - Describe base api for a choice.
 *
 * @property-read mixed $key Returns unique identifier (enum:int|URN:string|etc).
 * @property-read \svelte\core\Str $description Returns description.
 * @property-read bool $isSelected Returns whether this has been chosen.
 */
interface iOption
{
  /**
   * Get key unique identifier (enum:int|URN:Str)
   * **DO NOT CALL DIRECTLY, USE this->key;**
   * @return mixed Key
   */
  public function get_key();

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
