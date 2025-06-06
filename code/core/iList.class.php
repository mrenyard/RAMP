<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;,
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
 * Interface for list.
 *
 * RESPONSIBILITIES
 * - Enforce base api of \IteratorAggregate, \ArrayAccess and \Countable.
 * 
 * COLLABORATORS
 * - IteratorAggregate ({@see https://www.php.net/manual/class.iteratoraggregate.php})
 * - ArrayAccess ({@see https://www.php.net/manual/class.arrayaccess.php})
 * - Countable ({@see https://www.php.net/manual/class.countable.php})
 */
interface iList extends \IteratorAggregate, \ArrayAccess, \Countable
{
  /*
   * Returns the number of items currently referenced in 'this' list.
   * @return int Number of items in *this* list
   */
  public function get_count() : int;
}
