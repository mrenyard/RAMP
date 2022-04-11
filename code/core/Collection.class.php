<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package ramp
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
class Collection extends RAMPObject implements iCollection
{
  private $compositeType;
  private $deepClone;
  private $collection;

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
    $compositeType = (isset($compositeType)) ? (string)$compositeType : '\ramp\core\RAMPObject';
    if (!class_exists($compositeType) && !interface_exists($compositeType)) {
      throw new \InvalidArgumentException(
        '$compositeType (' . $compositeType . ') MUST be an accessible class name or interface.'
      );
    }
    $this->compositeType = $compositeType;
    $this->collection = array();
    $this->deepClone = ($deepClone === null)? false : $deepClone;
  }

  /**
   * Confirms this compositeType same as provided name.
   * @param \ramp\core\Str $expectedTypeName Full path name of expected type.
   * @return boolean Composite type is/not same as provided name.
   */
  public function isCompositeType(string $expectedTypeName) : bool
  {
    return ((string)$expectedTypeName === $this->compositeType);
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
    if (!($object instanceof $this->compositeType)) {
      throw new \InvalidArgumentException(
        get_class($object) . ' NOT instanceof ' . $this->compositeType
      );
    }
    $this->collection[count($this->collection)] = $object;
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
    return count($this->collection);
  }

  /**
   * Implementation of \IteratorAggregate method for use with foreach etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach.
   */
  final public function getIterator() : \Traversable
  {
    return new \ArrayIterator($this->collection);
  }

  /**
   * ArrayAccess method offsetExists.
   * @param mixed $offset Index to be checked for existence.
   * @return bool It does / not exist.
   */
  public function offsetExists($offset) : bool
  {
    return isset($this->collection[$offset]);
  }

  /**
   * ArrayAccess method offsetGet.
   * @param mixed $offset Index of requested {@link \ramp\core\RAMPObject}.
   * @return \ramp\core\RAMPObject Object located at provided index.
   * @throws \OutOfBoundsException When nothing located at provided index.
   */
  public function offsetGet($offset)
  {
    if (!isset($this->collection[$offset])) {
      throw new \OutOfBoundsException('Offset out of bounds');
    }
    return $this->collection[$offset];
  }

  /**
   * ArrayAccess method offsetSet.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \InvalidArgumentException When provided object NOT of expected type
   */
  public function offsetSet($offset, $object)
  {
    if (!($object instanceof $this->compositeType)) {
      throw new \InvalidArgumentException(
        $object . ' NOT instanceof ' . $this->compositeType
      );
    }
    $this->collection[$offset] = $object;
  }

  /**
   * ArrayAccess method offsetUnset.
   * @param mixed $offset API to match \ArrayAccess interface
   */
  public function offsetUnset($offset)
  {
    unset($this->collection[$offset]);
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
      $this->collection = $new;
    }
  }
}
