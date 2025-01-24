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
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\Model;

/**
 * Abstract Business Model.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject RAMPObject})
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
  private iList $children;

  /**
   * Base constructor for Business Models.
   */
  public function __construct()
  {
      $this->children = (stripos(get_class($this), 'Collection') === (\strlen(get_class($this)) - \strlen('Collection'))) ?
        new Collection(Str::set(preg_replace('/Collection$/', '', get_class($this)))):
        new Collection(Str::set(__NAMESPACE__ . '\BusinessModel'));
  }

  /**
   * @ignore
   */
  abstract protected function get_id() : Str;

  /**
   * @ignore
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
  static protected function processType($classFullName, bool $hyphenate = NULL) : Str
  {
    $pathNode = explode('\\', $classFullName);
    $modelName = explode('_', array_pop($pathNode));
    $type = Str::set(array_pop($modelName));
    return ($hyphenate)? Str::hyphenate($type) : $type;
  }

  /**
   * Set or change iteratable composite parts of *this*.
   * @param ramp\model\busniness\BusinessModel $value New children to set.
   */
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
   * @param mixed $offset Index of requested {@see \ramp\core\RAMPObject}.
   * @return \ramp\model\business\BusinessModel Object located at provided index.
   * @throws \OutOfBoundsException When nothing located at provided index.
   */
  final public function offsetGet($offset) : BusinessModel
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
  public function offsetSet($offset, $object) : void
  {
    $this->children->offsetSet($offset, $object);
  }

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetUnset($offset) : void
  {
    unset($this->children[$offset]);
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   * @param bool $update Default is to update on succesful validation, FALSE to skip.
   */
  public function validate(PostData $postdata, bool $update = TRUE) : void
  {
    foreach ($this->children as $child) {
      $child->validate($postdata);
    }
  }

  /**
   * @ignore
   */
  protected function get_hasErrors() : bool
  {
    foreach ($this->children as $child) {
      if ($child->hasErrors) { return TRUE; }
    }
    return FALSE;
  }

  /**
   * @ignore
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
   * **DO NOT CALL DIRECTLY, USE**
   * ```php
   * $this->count;
   * ```
   * @return int Number of parenten by *this*
   */
  final public function count() : int
  {
    return $this->get_count();
  }

  /**
   * @ignore
   */
  public function get_count() : int
  {
    return count($this->children);
  }
}
