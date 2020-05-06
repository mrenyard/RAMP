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
 *
 * @property-read \svelte\core\Str $id Returns unique identifier (ID) for *this* (URN).
 * @property-read \svelte\core\Str $description Returns description.
 * @property-read \svelte\core\Str $type Returns type definition as a short list, much like we
 * might use in an HTML class tag (for CSS), we uses *this* and parent classnames to define the
 * resulting values.
 * @property-read bool $hasErrors Returns whether any errors have been recorded following validate().
 * @property-read iCollection $errors Returns a collection of recorded error messages.
 * @property-read int $count Returns the number of children currently parented by *this*.
 *
 */
abstract class BusinessModel extends Model implements \IteratorAggregate, \Countable, \ArrayAccess
{
  private $children;

  /**
   * Collection of error messages ( \svelte\core\Str ).
   * @var \svelte\core\iCollection
   */
  protected $errorCollection;

  /**
   * Base constructor for Business Models.
   * @param \svelte\core\iCollection $children Collection of child business models.
   */
  public function __construct(iCollection $children = null)
  {
    $this->children = ($children != null) ? $children :
      new Collection(Str::set(__NAMESPACE__ . '\BusinessModel'));
  }

  /**
   * Get ID (URN).
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  abstract protected function get_id() : Str;

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
  final protected function processType($classFullName, bool $hyphenate = null) : Str
  {
    $pathNode = explode('\\', $classFullName);
    $modelName = explode('_', array_pop($pathNode));
    $type = Str::set(array_pop($modelName));
    return ($hyphenate)? Str::hyphenate($type) : $type;
  }

  /**
   * Implementation of \IteratorAggregate method for use with foreach etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach etc.
   */
  final public function getIterator() : \Traversable
  {
    return $this->children->getIterator();
  }

  /**
   * ArrayAccess method offsetGet.
   * @param mixed $offset Index of requested {@link \svelte\core\SvelteObject}.
   * @return \svelte\model\business\BusinessModel Object located at provided index.
   * @throws \OutOfBoundsException When nothing located at provided index.
   */
  final public function offsetGet($offset)
  {
    return $this->children[$offset];
  }

  /**
   * ArrayAccess method offsetExists.
   * @param mixed $offset Index to be checked for existence.
   * @return bool It does / not exist.
   */
  final public function offsetExists($offset) : bool
  {
    return $this->children->offsetExists($offset);
  }

  /**
   * ArrayAccess method offsetSet.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   */
  public function offsetSet($offset, $object)
  {
    $this->children[$offset] = $object;
  }

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetUnset($offset)
  {
    unset($this->children[$offset]);
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \svelte\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata)
  {
    $this->errorCollection = new Collection(Str::set('svelte\core\Str'));
    foreach ($this->children as $child) {
      $child->validate($postdata);
    }
  }

  /**
   * Checks if any errors have been recorded following validate().
   * **DO NOT CALL DIRECTLY, USE this->hasErrors;**
   * @return bool True if an error has been recorded
   */
  protected function get_hasErrors() : bool
  {
    if (isset($this->errorCollection) && $this->errorCollection->count > 0) { return TRUE; }
    foreach ($this->children as $child) {
      if ($child->hasErrors) { return TRUE; }
    }
    return FALSE;
  }

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return iCollection List of recorded errors.
   */
  protected function get_errors() : iCollection
  {
    $errors = clone $this->errorCollection;
    foreach ($this->children as $child) {
      foreach ($child->errors as $error) {
        $errors->add($error);
      }
    }
    return $errors;
  }

  /**
   * Returns the number of children currently parented.
   * **DO NOT CALL DIRECTLY, USE this->count;**
   * @return int Number of parenten by *this*
   */
  final public function count() : int
  {
    return $this->count;
  }

  /**
   * Returns the number of children currently parented.
   * **DO NOT CALL DIRECTLY, USE this->count;**
   * @return int Number of parenten by *this*
   */
  final public function get_count() : int
  {
    return $this->children->count;
  }
}
