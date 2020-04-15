<?php
/**
 * Svelte - Rapid web application development using best practice.
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

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\core\iOption;

/**
 * A Single login account type.
 * .
 */
class LoginAccountTypeOption extends SvelteObject implements iOption
{
  private $id;
  private $description;

  /**
   * Constructor for new instance of LoginAccountTypeOption.
   * @param int $id  Value to be set for id.
   * @param \svelte\core\Str $description String value to be set for description.
   */
  public function __construct(int $id, Str $description)
  {
    $this->id = $id;
    $this->description = $description;
  }

  /**
   * Get ID (enum:int|URN:Str)
   * @return mixed ID
   */
  public function get_id()
  {
    return $this->id;
  }

  /**
   * Get Description.
   * @return Str Description
   */
  public function get_description() : Str
  {
    return $this->description;
  }
}
