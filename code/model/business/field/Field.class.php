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

use ramp\core\Str;
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\core\OptionList;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;
use ramp\model\business\RecordComponent;
use ramp\model\business\Record;
use ramp\model\business\FailedValidationException;

/**
 * Abstract field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Define template method, processValidationRule
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 *
 * @property-read \ramp\core\Str $id Returns unique identifier (id) for *this* (URN).
 * @property-read mixed $value Returns value held by relevant property of containing record.
 * @property-read \ramp\model\business\Record $containingRecord Record containing property related to *this*.
 */
abstract class Field extends RecordComponent
{
  private $dataObjectPropertyName;
  private $containingRecord;
  private $editable;

  protected $errorCollection;

  /**
   * Base constructor for Field related to a single property of containing record.
   * @param \ramp\core\Str $dataObjectPropertyName Related dataObject property name of containing record
   * @param \ramp\model\business\Record $containingRecord Record parent of *this* property
   * @param \ramp\model\business\BusinessModel $children Next sub BusinessModel.
   * @throws \InvalidArgumentException When OptionList CastableType is NOT field\Option or highter.
   */
  public function __construct(Str $dataObjectPropertyName, Record $containingRecord, BusinessModel $children = NULL, bool $editable = NULL)
  {
    $this->errorCollection = StrCollection::set();
    $this->containingRecord = $containingRecord;
    $this->dataObjectPropertyName = $dataObjectPropertyName;
    $this->editable = ($editable === FALSE) ? FALSE : $editable;
    parent::__construct($children);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->containingRecord->id
    )->append(
      Str::hyphenate($this->dataObjectPropertyName)
    );
  }

  /**
   * Get Label
   * **DO NOT CALL DIRECTLY, USE this->label;**
   * @return \ramp\core\Str Label for *this*
   */
  protected function get_label() : Str
  {
    return Str::set(ucwords(trim(preg_replace('/((?:^|[A-Z])[a-z]+)/', ' $0', str_replace('KEY', '', $this->dataObjectPropertyName)))));
  }

  /**
   * Get isEditable
   * **DO NOT CALL DIRECTLY, USE this->isEditable;**
   * @return bool isEditable for *this*
   */
  protected function get_isEditable() : bool
  {
    return (
      $this->containingRecord->isNew || 
      (!$this->containingRecord->primaryKeyNames()->contains($this->dataObjectPropertyName) && $this->editable !== FALSE)
    );
  }

  /**
   * Set isEditable
   * **DO NOT CALL DIRECTLY, USE this->isEditable = $value;**
   * Use to request change of isEditable, some defaults are NOT overridable.
   * @param bool $value of requested value.
   */
  protected function set_isEditable(bool $value)
  {
    $this->editable = ($this->isEditable && $value == FALSE) ? FALSE : NULL;
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  abstract protected function get_value();

  /**
   * Get dataobject property name
   * **DO NOT CALL DIRECTLY, USE this->dataObjectPropertyName;**
   * @return \ramp\core\Str Property name for dataobject of *this* containing record
   */
  final protected function get_dataObjectPropertyName() : Str
  {
    return $this->dataObjectPropertyName;
  }

  /**
   * Get containing record
   * **DO NOT CALL DIRECTLY, USE this->containingRecord;**
   * @return \ramp\model\business\Record Containing record of *this*
   */
  final protected function get_containingRecord() : Record
  {
    return $this->containingRecord;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    foreach ($postdata as $inputdata)
    {
      if ((string)$inputdata->attributeURN == (string)$this->id)
      {
        if (!$this->isEditable) { return; }
        try {
          $this->processValidationRule($inputdata->value);
        } catch (FailedValidationException $e) {
          $this->errorCollection->add(Str::set($e->getMessage()));
          return;
        }
        $this->containingRecord->setPropertyValue(
          (string)$this->dataObjectPropertyName, $inputdata->value
        );
        return;
      }
    }
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
   * Template method for use in validation.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  abstract public function processValidationRule($value) : void;
}
