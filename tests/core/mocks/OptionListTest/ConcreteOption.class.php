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
 * @version 0.0.9;
 */
namespace tests\svelte\core\mocks\OptionListTest;

use svelte\core\SvelteObject;
use svelte\core\iOption;
use svelte\core\Str;

/**
 * Concrete implementation of \svelte\core\iOption for testing against.
 * .
 */
class ConcreteOption extends SvelteObject implements iOption
{
  private $id;
  private $description;

  /**
   * Constructor for new instance of SimpleOption.
   * @param \svelte\core\Str $id String value to be set for id.
   * @param \svelte\core\Str $description String value to be set for description.
   */
  public function __construct($id, Str $description)
  {
    $this->id = $id;
    $this->description = $description;
  }

  /**
   * A test getter for ConcreteOption::getId
   */
  public function get_id()
  {
    return $this->id;
  }

  /**
   * A test getter for ConcreteOption::getDescription
   */
  public function get_description() : Str
  {
    return $this->description;
  }
}
