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
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\iOption;
use ramp\core\iList;
use ramp\core\oList;
use ramp\core\Collection;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\Model;

/**
 * Abstract Business Model.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject RAMPObject})
 * - Define generalized methods for iteration, validity checking & error reporting.
 *
 * @property-read \ramp\core\Str $id Returns unique identifier (ID) for *this* (URN).
 * @property-read \ramp\core\Str $type Returns type definition as a short list, much like we
 * might use in an HTML class tag (for CSS), we uses *this* and parent classnames to define the
 * resulting values.
 * @property-read bool $hasErrors Returns whether any errors have been recorded following validate().
 * @property-read StrCollection $errors Returns a StrCollection of recorded error messages.
 * @property-read int $count Returns the number of children currently parented by *this*.
 */
abstract class BusinessModel extends Model implements iList
{
  private $children;

  /**
   * Base constructor for Business Models.
   * @param \ramp\model\business\BusinessModel $children business models.
   */
  public function __construct(BusinessModel $children = NULL)
  {
      $this->children = ($children == NULL)?
        (stripos(get_class($this), 'Collection') === (\strlen(get_class($this)) - \strlen('Collection'))) ?
          new Collection(Str::set(preg_replace('/Collection$/', '', get_class($this)))):
          new Collection(Str::set(__NAMESPACE__ . '\BusinessModel')):
        $children;
  }

  /**
   * Get ID (URN).
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  abstract protected function get_id() : Str;

  /**
   * Returns type.
   * **DO NOT CALL DIRECTLY, USE this->type;**
   * @return \ramp\core\Str Type for *this*
   */
  protected function get_type() : Str
  {
    $type = Str::_EMPTY();
    $o = $this;
    do {
      $type = $type->append(BusinessModel::processType((string)$o, TRUE)->prepend(Str::SPACE()));
    } while (is_object($o) && ($o = get_parent_class($o)));
    return $type->trimStart();
  }

  /**
   * Returns Business model type without namespace from full class name.
   * @param string $classFullName Full class name including path/namespace
   * @param \ramp\core\Boolean $hyphenate Whether model type should be returned hyphenated
   * @return \ramp\core\Str *This* business model type (without namespace)
   */
  static protected function processType($classFullName, bool $hyphenate = null) : Str
  {
    $pathNode = explode('\\', $classFullName);
    $modelName = explode('_', array_pop($pathNode));
    $type = Str::set(array_pop($modelName));
    return ($hyphenate)? Str::hyphenate($type) : $type;
  }

  final protected function setChildren(BusinessModel $value) : void
  {
    $this->children = $value;
  }

  /**
   * Implementation of \IteratorAggregate method for use with foreach etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach etc.
   */
  public function getIterator() : \Traversable
  {
    return $this->children->getIterator();
  }

  /**
   * ArrayAccess method offsetGet.
   * @param mixed $offset Index of requested {@link \ramp\core\RAMPObject}.
   * @return \ramp\model\business\BusinessModel Object located at provided index.
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
   * @param mixed $object RAMPObject to be placed at provided index.
   */
  public function offsetSet($offset, $object)
  {
    $this->children->offsetSet($offset, $object);
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
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
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
    foreach ($this->children as $child) {
      if ($child->hasErrors) { return TRUE; }
    }
    return FALSE;
  }

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return StrCollection List of recorded errors.
   */
  protected function get_errors() : StrCollection
  {
    $errors = StrCollection::set();
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
    return $this->get_count();
  }

  /**
   * Returns the number of children currently parented.
   * **DO NOT CALL DIRECTLY, USE this->count;**
   * @return int Number of parenten by *this*
   */
  final public function get_count() : int
  {
    return count($this->children);
  }
}
