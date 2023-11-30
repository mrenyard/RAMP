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
 * - Provide base implementation of {@see \ramp\core\iCollection}.
 * - Allows checking of a specified 'Type' of any added/contained {@see \ramp\core\RAMPObject}s.
 * - Configure and manage shallow or deep cloning.
 *
 * COLLABORATORS
 * - {@see \ramp\core\iCollection}
 * - {@see \ramp\core\RAMPObject}
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
  public function __construct(Str $compositeType = NULL, bool $deepClone = NULL)
  {
    parent::__construct($compositeType);
    $this->deepClone = ($deepClone === NULL)? false : $deepClone;
  }

  /**
   * Add a reference to object (of defined type), to this collection.
   * POSTCONDITIONS
   * - new object reference appended to this collection
   * @param \ramp\core\RAMPObject $object reference to be added
   * @throws \InvalidArgumentException When provided object NOT of expected type
   */
  public function add(RAMPObject $object) : void
  {
    parent::offsetSet(count($this), $object);
  }

  /**
   * Ensures when in Deep Cloning Mode that composite collection is cloned or in
   * Shallow Cloning Mode (default) composite collection is referenced only.
   */
  public function __clone() : void
  {
    if ($this->deepClone) {
      $new = array();
      foreach ($this as $key => $value) {
        $this[$key] = clone $value;
      }
    }
  }
}
