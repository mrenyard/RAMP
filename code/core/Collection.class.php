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
 * Generic collection for encapsulation or inheritance.
 *
 * RESPONSIBILITIES
 * - Provide base implementation of iCollection.
 * - Allows checking of a specified 'Type' of any added/contained {@link RAMPObject}s.
 * - Configure and manage shallow or deep cloning.
 *
 * COLLABORATORS
 * - {@link \ramp\core\RAMPObject}
 *
 * @property-read int $count Returns the number of items currently stored in this collection.
 */
class Collection extends oList implements iCollection
{
  private $deepClone;

  /**
   * Constructor for new instance of Collection.
   * POSTCONDITIONS
   * - New empty collection
   * - Defined and verified composite type
   * - Defined value for deep cloning
   * @param \ramp\core\Str $compositeType Full class name for Type of objects to be stored in this collection.
   * @param bool $deepClone Optional deep cloning (default value = FALSE).
   * @throws \InvalidArgumentException When $compositeType is NOT an accessible class name.
   */
  public function __construct(Str $compositeType = null, bool $deepClone = null)
  {
    parent::__construct($compositeType);
    $this->deepClone = ($deepClone === null)? false : $deepClone;
  }

  /**
   * Add a reference to object (of defined type), to this collection.
   * POSTCONDITIONS
   * - new object reference appended to this collection
   * @param \ramp\core\RAMPObject $object reference to be added
   * @throws \InvalidArgumentException When provided object NOT of expected type
   */
  public function add(RAMPObject $object)
  {
    parent::offsetSet(count($this->list), $object);
  }

  /**
   * Returns the number of items currently stored in this collection.
   * **DO NOT CALL DIRECTLY, USE this->count;**
   * @return int Number of items in this collection
   */
  final public function count() : int
  {
    return $this->count;
  }

  /**
   * Returns the number of items currently stored in this collection.
   * **DO NOT CALL DIRECTLY, USE this->count;**
   * @return int Number of items in this collection
   */
  final public function get_count() : int
  {
    return count($this->list);
  }

  /**
   * Ensures when in Deep Cloning Mode that composite collection is cloned or in
   * Shallow Cloning Mode (default) composite collection is referenced only.
   */
  public function __clone()
  {
    if ($this->deepClone) {
      $new = array();
      foreach ($this as $key => $value) {
        $new[$key] = clone $value;
      }
      $this->list = $new;
    }
  }
}
