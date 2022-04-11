<?php
/**
 * RAMP - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
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
 * @package ramp
 * @version 0.0.9;
 */
namespace ramp\core;

/**
 * Interface for collections.
 *
 * RESPONSIBILITIES
 * - Enforce base api of \IteratorAggregate, \Countable and \ArrayAccess.
 * - Describe base api mechanism for adding to a collection.
 *
 * COLLABORATORS
 * - {@link \ramp\core\RAMPObject}
 *
 * @property-read int $count Returns the number of items currently stored in this collection.
 */
interface iCollection extends \IteratorAggregate, \Countable, \ArrayAccess
{
  /**
   * Add a reference to object (of defined type), to this collection.
   * POSTCONDITIONS
   * - new object reference appended to this collection
   * @param \ramp\core\RAMPObject $object reference to be added
   */
  public function add(RAMPObject $object);

  /**
   * Returns the number of items currently stored in this collection.
   * **DO NOT CALL DIRECTLY, USE this->count;**
   * @return int Number of items in this collection
   */
  public function get_count() : int;
}
