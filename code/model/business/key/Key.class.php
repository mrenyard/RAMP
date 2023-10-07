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
namespace ramp\model\business\key;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;
use ramp\model\business\FailedValidationException;

/**
 * Abstract Key Record Component Business Model.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject RAMPObject})
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Hold a collection of reference back to parent Record and restrict polymorphic composite association.
 * - Define access to compound key indexes and values based on parent record state.
 * 
 * COLLABORATORS
 * - {@link \ramp\model\business\Record Record}
 *
 * @property-read \ramp\core\StrCollection $indexes Related parent record associated property name.
 * @property-read \ramp\core\StrCollection $values Related parent Record associated with this component.
 */
abstract class Key extends RecordComponent
{
  private $errorCollection;

  /**
   * Define a multiple part key related to its parent record.
   * @param \ramp\core\Str $propertyName Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $record Record parent of *this* property.
   */
  public function __construct(Str $propertyName, Record $record)
  {
    parent::__construct($propertyName, $record);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->record->id
    )->append(Str::hyphenate($this->propertyName));
  }

  /**
   * ArrayAccess method offsetSet, USE DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \BadMethosCallException Adding properties through offsetSet STRONGLY DISCOURAGED!
   */
  public function offsetSet($offset, $object)
  {
    if (!($object instanceof \ramp\model\business\field\Field)
    || $object->record != $this->record
    || $this->indexes->contains($object->propertyName)) {
      throw new \InvalidArgumentException(
        'Adding properties to Key through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    }
    parent::offsetSet($offset, $object);
  }

  /**
   * Returns indexes for key.
   * **DO NOT CALL DIRECTLY, USE this->indexes;**
   * @return \ramp\core\StrCollection Indexes related to data fields for this key.
   */
  protected function get_indexes() : StrCollection
  {
    $value = StrCollection::set();
    foreach($this as $key) {
      $value->add($key->propertyName);
    }
    return $value;
  }

  /**
   * Returns primarykey values held by relevant properties of parent record.
   * **DO NOT CALL DIRECTLY, USE this->values;**
   * @return ?\ramp\core\StrCollection Values held by relevant property of parent record or NULL
   */
  protected function get_values() : ?StrCollection
  {
    $values = NULL;
    foreach ($this as $key) {
      if ($key->value === NULL) { return NULL; }
      if ($values === NULL) { $values = StrCollection::set(); }
      $values->add(Str::set($key->value));
    }
    return $values;
  }

  /**
   * Returns value held by relevant property of associated record.
   * @return mixed Value held by relevant property of associated record
   */
  protected function get_value()
  {
    return ($this->values !== NULL) ? (string)$this->values->implode(Str::BAR()) : NULL;
  }

  /**
   * Checks if any errors have been recorded following validate().
   * **DO NOT CALL DIRECTLY, USE this->hasErrors;**
   * @return bool True if an error has been recorded
   */
  protected function get_hasErrors() : bool
  {
    return (isset($this->errorCollection) && $this->errorCollection->count > 0);
  }

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return StrCollection List of recorded errors.
   */
  protected function get_errors() : StrCollection
  {
    return (isset($this->errorCollection)) ? $this->errorCollection : StrCollection::set();
  }

  /**
   * Validate uniqueness of combined primary key.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   */
  public function validate(PostData $postdata) : void
  {
    $this->errorCollection = StrCollection::set();
    foreach ($postdata as $inputdata)
    {
      if (
        ((string)$inputdata->attributeURN == (string)$this->id) &&
        (\is_array($inputdata->value))
      )
      {
        foreach ($inputdata->value as $subFieldKey => $subFieldValue) {
          if (!$this[$subFieldKey]->isEditable) { return; }
          try {
            $this[$subFieldKey]->processValidationRule($subFieldValue);
          } catch (FailedValidationException $e) {
            $this->errorCollection->add(Str::set($e->getMessage()));
          }
        }
      }
    }
  }
}