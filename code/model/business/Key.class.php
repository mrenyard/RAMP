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
use ramp\core\StrCollection;
use ramp\condition\Filter;
use ramp\condition\PostData;

/**
 * Abstract Key Record Component Business Model.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject RAMPObject})
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Hold a collection of reference back to parent Record and restrict polymorphic composite association.
 * - Define access to compound key indexes and values based on parent record state.
 * 
 * COLLABORATORS
 * - {@see \ramp\model\business\Record Record}
 *
 * @property-read \ramp\core\StrCollection $indexes Sub key indexs (names) of composite properties that make up *this* PrimaryKey.
 * @property-read ?\ramp\core\StrCollection $values Values held by each relevant property as key value composite or NULL.
 */
final class Key extends RecordComponent
{
  private $errorCollection;

  /**
   * Define a multiple part key related to its parent record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   */
  public function __construct(Str $name, Record $parent)
  {
    parent::__construct($name, $parent, NULL, FALSE);
  }

  /**
   * ArrayAccess method offsetSet, USE DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \BadMethosCallException Adding properties through offsetSet STRONGLY DISCOURAGED!
   */
  public function offsetSet($offset, $object)
  {
    if (
      (!($object instanceof field\Field))
      || $object->parent != $this->parent
      || $this->indexes->contains($object->name)
    ) {
      throw new \InvalidArgumentException(
        'Adding properties to Key through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    }
    parent::offsetSet($offset, $object);
  }

  /**
   * @ignore
   */
  protected function get_indexes() : StrCollection
  {
    $value = StrCollection::set();
    foreach($this as $subKey) {
      $value->add($subKey->name);
    }
    return $value;
  }

  /**
   * @ignore
   */
  protected function get_values() : ?StrCollection
  {
    $values = NULL;
    foreach ($this as $subKey) {
      if ($subKey->value === NULL || $subKey->value === '') { return NULL; }
      if ($values === NULL) { $values = StrCollection::set(); }
      $values->add(Str::set($subKey->value));
    }
    return $values;
  }

  /**
   * @ignore
   */
  protected function set_isEditable(bool $value)
  {
    // Changes nothing
  }

  /**
   * @ignore
   */
  final protected function get_value()
  {
    return ($this->values !== NULL) ? (string)$this->values->implode(Str::BAR()) : NULL;
  }

  /**
   * Validate uniqueness of combined key
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   * @throws \ramp\model\business\DataExistingEntryException An entry already exists with this key!
   */
  public function validate(PostData $postdata) : void
  {
    if ($this->values !== NULL) { return; } // Once set cannot be changed
    parent::validate($postdata);
    if ($this->parent->isModified && $this->parent->isNew && !$this->hasErrors &&
      ((string)$this->name == 'primaryKey') && $this->value !== NULL)
    {
      $recordName = Str::camelCase($this->parent->id->explode(Str::COLON())[0]);
      $filterValues = array();
      foreach ($this->parent->primaryKey->indexes as $propertyName) {
        $filterValues[(string)$propertyName] = $this->parent->getPropertyValue((string)$propertyName);
      }
      $filter = Filter::build($recordName, $filterValues);
      $def = new SimpleBusinessModelDefinition($recordName);
      $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
      try {
        $MODEL_MANAGER::getInstance()->getBusinessModel($def, $filter);
      } catch (DataFetchException $expected) {
        return;
      }
      $targetID = $this->parent->primaryKey->value;
      foreach ($this->parent->primaryKey->indexes as $propertyName) {
        $this->parent->setPropertyValue((string)$propertyName, NULL);
      }
      $this->parent->updated();
      throw new DataExistingEntryException($targetID, 'An entry already exists with this key!');
    }    
  }
}