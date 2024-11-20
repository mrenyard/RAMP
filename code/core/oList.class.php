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
 * Generic object List for encapsulation or inheritance.
 *
 * RESPONSIBILITIES
 * - Provide base implementation of {@see \ramp\core\iList iList}.
 * - Allows checking of a specified 'Type' of any added/contained {@see \ramp\core\RAMPObject}s.
 *
 * COLLABORATORS
 * - {@see \ramp\core\iList}
 * - {@see \ramp\core\RAMPObject}
 * 
 * @property-read int $count Returns the number of items currently stored in this collection.
 */
class oList extends RAMPObject implements iList
{
  private string $compositeType;
  private array $list;

  /**
   * Constructor for new instance of oList.
   * POSTCONDITIONS
   * - New empty oList
   * - Defined and verified composite type
   * @param \ramp\core\Str $compositeType Full class name for Type of objects to be stored in this list.
   * @throws \InvalidArgumentException When $compositeType is NOT an accessible class name.
   */
  public function __construct(Str $compositeType = NULL)
  {
    $this->compositeType = ($compositeType === NULL) ? '\ramp\core\RAMPObject' : (string)$compositeType;
    if (!class_exists($this->compositeType) && !interface_exists($this->compositeType)) {
      throw new \InvalidArgumentException(
        '$compositeType (' . $compositeType . ') MUST be an accessible class name or interface.'
      );
    }
    $this->list = array();
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
   * Implementation of \IteratorAggregate method for use with 'foreach' etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach.
   */
  final public function getIterator() : \Traversable
  {
    return new \ArrayIterator($this->list);
  }

  /**
   * ArrayAccess method offsetExists.
   * @param mixed $offset Index to be checked for existence.
   * @return bool It does / not exist.
   */
  public function offsetExists($offset) : bool
  {
    return isset($this->list[$offset]);
  }

  /**
   * ArrayAccess method offsetGet.
   * @param mixed $offset Index of requested {@see \ramp\core\RAMPObject}.
   * @return \ramp\core\RAMPObject Object located at provided index.
   * @throws \OutOfBoundsException When nothing located at provided index.
   */
  public function offsetGet($offset) : RAMPObject
  {
    if (!isset($this->list[$offset])) {
      throw new \OutOfBoundsException('Offset out of bounds');
    }
    return $this->list[$offset];
  }

  /**
   * ArrayAccess method offsetSet.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \InvalidArgumentException When provided object NOT of expected type
   */
  public function offsetSet($offset, $object) : void
  {
    if (!($object instanceof $this->compositeType)) {
      throw new \InvalidArgumentException(
        $object . ' NOT instanceof ' . $this->compositeType
      );
    }
    $this->list[$offset] = $object;
    ksort($this->list);
  }

  /**
   * ArrayAccess method offsetUnset.
   * @param mixed $offset API to match \ArrayAccess interface
   */
  public function offsetUnset($offset) : void
  {
    unset($this->list[$offset]);
  }

  /**
   * Returns the number of items currently stored in this collection.
   * @see https://www.php.net/manual/class.countable.php
   * @return int Number of items in this collection
   */
  final public function count() : int
  {
    return $this->count;
  }

  /**
   * @ignore
   */
  #[\Override]
  final public function get_count() : int
  {
    return count($this->list);
  }
}
