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
namespace tests\svelte\core\mocks\OptionListTest;

use svelte\core\SvelteObject;
use svelte\core\iOption;
use svelte\core\Str;

/**
 * Concrete implementation of \svelte\core\iOption for testing against.
 * .
 */
class SpecialistOption extends SvelteObject implements iOption
{
  /**
   * Constructor for new instance of SimpleOption.
   * @param \svelte\core\Str $id String value to be set for id.
   * @param \svelte\core\Str $description String value to be set for description.
   */
  public function __construct($key, Str $description)
  {
  }

  /**
   * A test getter for SpecialistOption::get_key
   */
  public function get_key()
  {
  }

  /**
   * A test getter for SpecialistOption::get_description
   */
  public function get_description() : Str
  {
  }

  /**
   * A test getter for SpecialistOption::get_isSelected
   */
  public function get_isSelected() : bool
  {
  }
}
