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
namespace svelte\core;

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\core\iOption;

/**
 * A Single option.
 *
 * RESPONSIBILITIES
 * - Define a simple base implementation of iOption for easy reuse.
 *
 * @property-read mixed $key Returns key (enum:int|URN:Str).
 * @property-read \svelte\core\Str $description Returns description.
 */
class Option extends SvelteObject implements iOption
{
  private $key;
  private $description;

  /**
   * Constructor for new instance of LoginAccountTypeOption.
   * @param int $key  Value to be set for key.
   * @param \svelte\core\Str $description String value to be set for description.
   */
  public function __construct(int $key, Str $description)
  {
    $this->key = $key;
    $this->description = $description;
  }

  /**
   * Get key (enum:int|URN:Str)
   * **DO NOT CALL DIRECTLY, USE this->key;**
   * @return mixed Key
   */
  public function get_key()
  {
    return $this->key;
  }

  /**
   * Get Description.
   * **DO NOT CALL DIRECTLY, USE this->description;**
   * @return Str Description
   */
  public function get_description() : Str
  {
    return $this->description;
  }
}
