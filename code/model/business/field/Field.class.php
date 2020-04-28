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
namespace svelte\model\business\field;

use svelte\core\Str;
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\business\BusinessModel;
use svelte\model\business\Record;
use svelte\validation\FailedValidationException;

/**
 * Abstract field related to a single property of its containing \svelte\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Define template method, processValidationRule
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \svelte\model\business\Record}
 */
abstract class Field extends BusinessModel
{
  private $propertyName;
  private $containingRecord;

  /**
   * Base constructor for Field related to a single property of containing record.
   * @param \svelte\core\Str $propertyName Property name of related property of containing record
   * @param \svelte\model\business\Record $containingRecord Record parent of *this* property
   * @param \svelte\core\iCollection $children Collection of child business models.
   */
  public function __construct(Str $propertyName, Record $containingRecord, iCollection $children = null)
  {
    $this->containingRecord = $containingRecord;
    $this->propertyName = $propertyName;
    parent::__construct($children);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  final public function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->containingRecord->id
    )->append(
      Str::hyphenate($this->propertyName)
    );
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    return $this->containingRecord->getPropertyValue($this->propertyName);
  }

  /**
   * ArrayAccess method offsetSet, DO NOT USE.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   * @throws \BadMethodCallException Array access setting is not allowed.
   */
  final public function offsetSet($offset, $object)
  {
    throw new \BadMethodCallException('Array access setting is not allowed.');
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \svelte\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  final public function validate(PostData $postdata)
  {
    //parent::validate($postdata);
    $this->errorCollection = new Collection(Str::set('\svelte\core\Str'));
    foreach ($postdata as $inputdata) {
      if ($inputdata->attributeURN == $this->id) {
        try {
          $this->processValidationRule($inputdata->value);
        } catch (FailedValidationException $e) {
          $this->errorCollection->add(Str::set($e->getMessage()));
          return;
        }
        $this->containingRecord->setPropertyValue((string)$this->propertyName, $inputdata->value);
      }
    }
  }

  /**
   * Checks if any errors have been recorded following validate().
   * @return bool True if an error has been recorded
   */
  final public function hasErrors() : bool
  {
    if ($this->errorCollection->count() > 0) { return TRUE; }
    return FALSE;
  }

  /**
   * Gets collection of recorded errors.
   * @return iCollection List of recorded errors.
   */
  final public function getErrors() : iCollection
  {
    $errors = clone $this->errorCollection;
    return $errors;
  }

  /**
   * Template method for use in validation.
   * @param mixed $value Value to be processed
   * @throws \svelte\validation\FailedValidationException When test fails.
   */
  abstract protected function processValidationRule($value);
}
