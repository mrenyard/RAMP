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
namespace svelte\model\business;

use svelte\core\Str;
use svelte\core\iOption;
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\Model;

/**
 * Abstract Business Model.
 *
 * RESPONSIBILITIES
 * - Define generalized methods for iteration, validity checking & error reporting.
 */
abstract class BusinessModel extends Model implements iOption, \IteratorAggregate, \Countable, \ArrayAccess
{
  private $children;

  /**
   * Base constructor for Business Models.
   * @param \svelte\core\iCollection $children Collection of child elements.
   */
  public function __construct(iCollection $children = null)
  {
    $this->children = (isset($children)) ? $children :
      new Collection(Str::set('\svelte\model\business\BusinessModel'));
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  abstract public function get_id() : Str;

  /**
   * Get value.
   * @return mixed Value
   */
  public function get_description() : Str
  {
    return $this->id;
  }

  /**
   * Returns type.
   * **DO NOT CALL DIRECTLY, USE this->type;**
   * @return \svelte\core\Str Type for *this*
   */
  final protected function get_type() : Str
  {
    $type = Str::_EMPTY();
    $o = $this;
    do {
      $type = $type->append($this->processType((string)$o, TRUE)->prepend(Str::set(' ')));
    } while (is_object($o) && ($o = get_parent_class($o)));
    return $type;
  }

  /**
   * Returns this Business model type without namespace.
   * @param string $classFullName Full class name including path/namespace
   * @param \svelte\core\Boolean $hyphenate Whether model type should be returned hyphenated
   * @return \svelte\core\Str *This* business model type (without namespace)
   */
  final private function processType($classFullName, bool $hyphenate = null) : Str
  {
    $pathNode = explode('\\', $classFullName);
    $modelName = explode('_', array_pop($pathNode));
    $type = Str::set(array_pop($modelName));
    return ($hyphenate)? Str::hyphenate($type) : $type;
  }
  /**
   * Implementation of \IteratorAggregate method for use with foreach etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach.
   */
  final public function getIterator() : \Traversable
  {
    $this->children->getIterator();
  }

  /**
   * ArrayAccess method offsetGet.
   * @param mixed $offset Index of requested {@link \svelte\core\SvelteObject}.
   * @return \svelte\core\SvelteObject Object located at provided index.
   * @throws \OutOfBoundsException When nothing located at provided index.
   */
  public function offsetGet($offset)
  {
    return $this->children[$offset];
  }

  /**
   * ArrayAccess method offsetExists.
   * @param mixed $offset Index to be checked for existence.
   * @return bool It does / not exist.
   */
  public function offsetExists($offset) : bool
  {
    return $this->children->offsetExists($offset);
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \svelte\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata)
  {
  }

  /**
   * Checks if any errors have been recorded following validate().
   * @return bool True if error have been recorded
   */
  public function hasErrors() : bool
  {
  }

  /**
   * Gets collection of recorded errors.
   * @return iCollection List if recorded errors.
   */
  public function getErrors() : iCollection
  {
  }
  /**
   * Returns the number of items currently stored in this collection.
   * @return int Number of items in this collection
   */
  final public function count() : int
  {
    return $this->children->count();
  }

  /**
   * ArrayAccess method offsetSet.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   * @throws \InvalidArgumentException When provided object NOT of expected type
   */
  public function offsetSet($offset, $object)
  {
    $this->children[$offset] = $object;
  }

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   */
  public function offsetUnset($offset)
  {
    throw new \BadMethodCallException('Array access unsetting is not allowed.');
  }

  /**
   */
  public function isValid() : bool
  {
    foreach ($this->children as $child) {
      if (!$child->isValid()) { return FALSE; }
    }
    return TRUE;
  }
}
