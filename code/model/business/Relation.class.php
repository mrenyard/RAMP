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

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\StrCollection;
use ramp\condition\PostData;
// use ramp\model\business\BusinessModel;
// use ramp\model\business\Record;
// use ramp\model\business\key\Foreign;
// use ramp\model\business\DataFetchException;
// use ramp\model\business\FailedValidationException;
// use ramp\model\business\SimpleBusinessModelDefinition;
// use ramp\model\business\field\Field;
// use ramp\model\business\validation\dbtype\DbTypeValidation;

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
 * - {@link \ramp\model\business\BusinessModelManager}
 */
class Relation extends RecordComponent
{
  private $modelManager;
  private $errorCollection;
  private $relationObjectRecordName;
  private $record;

  /**
   * Creates input field related to a single property of containing record.
   * @param \ramp\core\Str $parentPropertyName Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parentRecord Record parent of *this* property.
   * @param \ramp\core\Str $relationObjectRecordName Record name of linked Object
   * proir to allowing property value change
   */
  public function __construct(Str $parentPropertyName, Record $parentRecord, Str $relationObjectRecordName)
  {
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->relationObjectRecordName = $relationObjectRecordName;
    parent::__construct($parentPropertyName, $parentRecord);
    $this->update($this->getValue());
  }

  /**
   * Update relation base on changed key.
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store.
   */
  private function update($key = NULL)
  {
    $key = (isset($key)) ? Str::set($key) : Str::NEW();
    $this->record = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition($this->relationObjectRecordName, $key)
    );
    $children = $this->record;
    if ($key === Str::NEW()) {
      $children = new key\Foreign($this->parentPropertyName, $this->parentRecord, new key\Primary($this->record));
    }
    $this->setChildren($children);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->parentRecord->id
    )->append(
      Str::hyphenate($this->dataObjectPropertyName)
    );
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final private function getValue()
  {
    return $this->parentRecord->getPropertyValue((string)$this->parentPropertyName->prepend(Str::FK()));
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    $this->errorCollection = StrCollection::set();
    foreach ($this as $child) { $child->validate($postdata); }
    foreach ($postdata as $inputdata)
    {
      if ((string)$inputdata->attributeURN == (string)$this->id)
      {
        $values = $inputdata->value;
        $primaryKeyNames = $this->record->primaryKeyNames();
        if (is_array($values))
        {
          if (isset($values['unset']) && $values['unset'] == 'on')
          {
            $this->parentRecord->setPropertyValue(
              (string)$this->dataObjectPropertyName->prepend(Str::FK()), NULL
            );
            $this->update();
          }
          else if (count($values) === $primaryKeyNames->count)
          {
            $key = StrCollection::set();
            foreach ($primaryKeyNames as $primaryKeyName) {
              if (
                !isset($values[(string)Str::hyphenate($primaryKeyName)]) ||
                $values[(string)Str::hyphenate($primaryKeyName)] == ''
              ) { return; }
              $key->add(Str::set($values[(string)Str::hyphenate($primaryKeyName)]));
            }
            $value = (string)$key->implode(Str::BAR());
            try {
              $this->processValidationRule($value);
            } catch (FailedValidationException $e) {
              $this->errorCollection->add(Str::set($e->getMessage()));
              return;
            }
            $this->parentRecord->setPropertyValue(
              (string)$this->dataObjectPropertyName->prepend(Str::FK()), $value
            );
          }
          return;
        }
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
    try {
      $this->update($value);
    } catch (DataFetchException $e) {
      throw new FailedValidationException('Relation NOT found in data storage!');
    }
  }
}
