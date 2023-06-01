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

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\Filter;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\DataFetchException;
use ramp\model\business\FailedValidationException;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\validation\dbtype\DbTypeValidation;

/**
 * MultiPartPrimary field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject})
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Hold reference back to parent Record and restrict polymorphic composite association. 
 * - Provide access to compound key indexes and values based on parent record state.
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record Record}
 */
class Primary extends Key
{
  private static $propertyName;
  private $errorCollection;

  /**
   * Creates a multiple part primary key field related to a collection of property of parent record.
   * @param \ramp\model\business\Record $parentRecord Record parent of *this* property.
   */
  public function __construct(Record $parentRecord)
  {
    if (!isset(self::$propertyName)) { self::$propertyName = Str::set('PrimaryKey'); }
    $this->errorCollection = StrCollection::set();
    parent::__construct(self::$propertyName, $parentRecord);
  }

  /**
   * Returns indexes for key.
   * @return \ramp\core\StrCollection Indexes related to data fields for this key.
   */
  final protected function get_indexes() : StrCollection
  {
    return $this->parentRecord->primaryKeyNames();
  }

  /**
   * Returns primarykey values held by relevant properties of parent record.
   * @return \ramp\core\StrCollection Values held by relevant property of parent record
   */
  final protected function get_values() : ?StrCollection
  {
    $value = StrCollection::set();
    foreach ($this->parentRecord->primaryKeyNames() as $propertyName) {
      $partValue = $this->parentRecord->getPropertyValue((string)$propertyName);
      if (!isset($partValue) || $partValue == '') { return NULL; }
      $value->add(Str::set($partValue)->replace(Str::SPACE(), Str::PLUS()));
    }
    return $value;
  }

  /**
   * Returns primarykey bar seperated concatenated values held by relevant properties of parent record.
   * @return \ramp\core\StrCollection Value Bar seperated concatenated key values.
   */
  final protected function get_value() : ?Str
  {
    return ($this->values === NULL) ? NULL : $this->values->implode(Str::BAR())->replace(Str::SPACE(), Str::PLUS());
  }

  /**
   * Checks if any errors have been recorded following validate().
   * **DO NOT CALL DIRECTLY, USE this->hasErrors;**
   * @return bool True if an error has been recorded
   */
  protected function get_hasErrors() : bool
  {
    return ($this->errorCollection->count > 0);
  }

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return StrCollection List of recorded errors.
   */
  protected function get_errors() : StrCollection
  {
    return $this->errorCollection;
  }

  /**
   * Validate uniqueness of combined primary key.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   */
  public function validate(PostData $postdata) : void
  {
    $this->errorCollection = StrCollection::set();
    if ($this->parentRecord->isNew && $this->parentRecord->isValid) {    
      try {
        $this->processValidationRule(NULL);
      } catch (FailedValidationException $e) {
        $this->errorCollection->add(Str::set($e->getMessage()));
        return;
      }
    }
  }

  /**
   * Validate that combined primaryKey unique, NOT already in data storage.
   * @param mixed $value Value should be NULL
   * @throws \ramp\validation\FailedValidationException When test fails.
   * @throws \BadMethodCallException If NOT called under valid constraints as set within Record::validate().
   */
  public function processValidationRule($value) : void
  {
    if ($value !== NULL || !$this->parentRecord->isNew) {
      throw new \BadMethodCallException('Primary::processValidationRule() SHOULD ONLY be called from within!');
    }
    $recordName = Str::camelCase($this->parentRecord->id->trimEnd(Str::set(':new')));
    $filterValues = array();
    foreach ($this->parentRecord->primaryKeyNames() as $propertyName) {
      $filterValues[(string)$propertyName] = $this->parentRecord->getPropertyValue((string)$propertyName);
    }
    $filter = Filter::build($recordName, $filterValues);
    $def = new SimpleBusinessModelDefinition($recordName);
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    try {
      $MODEL_MANAGER::getInstance()->getBusinessModel($def, $filter);
    } catch (DataFetchException $expected) {
      return;
    }
    throw new FailedValidationException('An entry already exists for this record!');
  }
  
  /**
   * ArrayAccess method offsetSet, DO NOT USE.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetSet($offset, $object)
  {
    throw new \BadMethodCallException('Array access setting is not allowed.');
  }
}
