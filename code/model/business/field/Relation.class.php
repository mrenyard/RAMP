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
use ramp\core\Collection;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\DataFetchException;
use ramp\model\business\FailedValidationException;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\validation\dbtype\DbTypeValidation;

/**
 * Relation field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Implement template method, processValidationRule to process provided ValidationRule.
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 * - {@link \ramp\validation\ValidationRule}
 */
class Relation extends Field
{
  private static $singleNewRecordCheck;
  private $modelManager;
  private $relationObjectRecordName;
  private $relatedObject;

  /**
   * Creates input field related to a single property of containing record.
   * @param \ramp\core\Str $dataObjectPropertyName Containing record's dataObject property name 
   * @param \ramp\model\business\Record $containingRecord Record parent of *this* property
   * @param \ramp\core\Str $relationObjectRecordName Record name of linked Object
   * proir to allowing property value change
   */
  public function __construct(Str $dataObjectPropertyName, Record $containingRecord, Str $relationObjectRecordName)
  {
    if (!isset(self::$singleNewRecordCheck)) { self::$singleNewRecordCheck = array(); }
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->relationObjectRecordName = $relationObjectRecordName;
    $this->update($containingRecord->getPropertyValue((string)$dataObjectPropertyName));
    parent::__construct($dataObjectPropertyName, $containingRecord, $this->value);
  }

  /**
   * Update relation base on changed key.
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store.
   */
  private function update($key)
  {
    $this->relatedObject = (isset($key) && $key != 'new') ?
      $this->modelManager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->relationObjectRecordName, Str::set($key))
      ):
      NULL;
  }

  /**
   * Returns related BusinessModel.
   * @return mixed Related BusinessModel (object).
   */
  protected function get_value() {
    return $this->relatedObject;
  }

  /**
   * Get ID (URN) of related BusinessModel.
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return (isset($this->relatedObject)) ? $this->relatedObject->id : parent::get_id();
  }

  /**
   * Implementation of \IteratorAggregate method for use with foreach etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach etc.
   */
  public function getIterator() : \Traversable
  {
    if (isset($this->relatedObject)) { return $this->relatedObject; }
    // On first request for a new record of type return record
    if (!isset(self::$singleNewRecordCheck[(string)$this->relationObjectRecordName])) {
      self::$singleNewRecordCheck[(string)$this->relationObjectRecordName] = 1;
      return $this->modelManager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->relationObjectRecordName, Str::NEW())
      );
    }
    // TODO:mrenyard: On all susuquent request for new record of type only return a link with relationship data.
    /* return new RelationLink(
      $this->dataObjectPropertyName,
      $this->containingRecord,
      $this->relationObjectRecordName
    );*/
  }


  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    $this->errorCollection = StrCollection::set();
    if (isset($this->relatedObject)) { $this->relatedObject->validate($postdata); }
    foreach ($postdata as $inputdata)
    {
      if ((string)$inputdata->attributeURN == (string)parent::get_id())
      {
        try {
          $this->processValidationRule($inputdata->value);
        } catch (FailedValidationException $e) {
          $this->errorCollection->add(Str::set($e->getMessage()));
          return;
        }
        $this->containingRecord->setPropertyValue(
          (string)$this->dataObjectPropertyName, $inputdata->value
        );
      }
    }
  }

  /**
   * Process provided validation rule.
   * @param mixed $value Value to be processed
   * @throws ramp\model\business\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    if (!is_int($value)) { throw new FailedValidationException('Relation Key NOT valid!'); }
    try {
      $this->update($value);
    } catch (DataFetchException $e) {
      throw new FailedValidationException('Relation NOT found in data storage!');
    }
  }
}
