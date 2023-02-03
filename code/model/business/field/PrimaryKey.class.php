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
namespace ramp\model\business\field;

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\Filter;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\validation\dbtype\DbTypeValidation;
use ramp\model\business\FailedValidationException;
use ramp\model\business\DataFetchException;

/**
 * MultiPartPrimary field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Implement template method, processValidationRule to process provided ValidationRule.
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 */
class PrimaryKey extends Field
{
  private static $strPK;
  private $dataObjectPropertyNames;

  /**
   * Creates a multiple part primary key field related to a collection of property of containing record.
   * @param \ramp\model\business\Record $containingRecord Record parent of *this* property
   */
  public function __construct(Record $containingRecord)
  {
    if (!isset(self::$strPK)) { self::$strPK = Str::set('PrimaryKey'); }
    $this->dataObjectPropertyNames = $containingRecord->primaryKeyNames();
    parent::__construct(self::$strPK, $containingRecord);
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

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetUnset($offset)
  {
    throw new \BadMethodCallException('Array access unsetting is not allowed.');
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    $value = Str::_EMPTY();
    foreach ($this->dataObjectPropertyNames as $propertyName) {
      $partValue = $this->containingRecord->getPropertyValue((string)$propertyName);
      if (!isset($partValue) || $partValue == '') { return NULL; }
      $value = $value->append(Str::set($partValue))->append(Str::BAR());
    }
    return (string)$value->trimEnd(Str::BAR())->replace(Str::SPACE(), Str::set('+'));
  }

  /**
   * Validate uniqueness of combined primary key.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   */
  public function validate(PostData $postdata) : void
  {
    $this->errorCollection = StrCollection::set();
    if ($this->containingRecord->isNew && $this->containingRecord->isValid) {    
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
    if ($value !== NULL || !$this->containingRecord->isNew) {
      throw new \BadMethodCallException('PrimaryKey::processValidationRule() SHOULD ONLY be called from within!');
    }
    $recordName = Str::camelCase($this->containingRecord->id->trimEnd(Str::set(':new')));
    $filterValues = array();
    foreach ($this->dataObjectPropertyNames as $propertyName) {
      $filterValues[(string)$propertyName] = $this->containingRecord->getPropertyValue((string)$propertyName);
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
}
