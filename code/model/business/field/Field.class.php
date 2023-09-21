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
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject}).
 * - Implement property specific methods for iteration, validity checking & error reporting.
 * - Hold referance back to parent Record and restrict polymorphic composite association.
 * - Provide access to relevent value based on parent record state.
 * - Implement template method, processValidationRule to process provided ValidationRule.
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record Record}
 * 
 * @property bool $isEditable Flag for setting or getting access to modify Field value.
 * @property-read mixed $value Returns value held by relevant property of containing record.
 */
abstract class Field extends RecordComponent
{
  protected $errorCollection;
  private $editable;

  /**
   * Base constructor for Field related to a single property of containing record.
   * @param \ramp\core\Str $parentPropertyName Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parentRecord Record parent of *this* property
   * @param \ramp\model\business\BusinessModel $children Next sub BusinessModel.
   * @param bool $editable 
   * @throws \InvalidArgumentException When OptionList CastableType is NOT field\Option or highter.
   */
  public function __construct(Str $parentPropertyName, Record $parentRecord, BusinessModel $children = NULL, bool $editable = NULL)
  {
    $this->errorCollection = StrCollection::set();
    $this->editable = ($editable === FALSE) ? FALSE : $editable;
    parent::__construct($parentPropertyName, $parentRecord, $children);
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
      Str::hyphenate($this->parentPropertyName)
    );
  }

  /**
   * Get Label
   * **DO NOT CALL DIRECTLY, USE this->label;**
   * @return \ramp\core\Str Label for *this*
   */
  protected function get_label() : Str
  {
    return Str::set(ucwords(trim(preg_replace('/((?:^|[A-Z])[a-z]+)/', ' $0', str_replace('KEY', '', $this->parentPropertyName)))));
  }

  /**
   * Get isEditable
   * **DO NOT CALL DIRECTLY, USE this->isEditable;**
   * @return bool isEditable for *this*
   */
  protected function get_isEditable() : bool
  {
    return (
      $this->parentRecord->isNew || 
      (!$this->parentRecord->primaryKeyNames()->contains($this->parentPropertyName) && $this->editable !== FALSE)
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
        $this->parentRecord->setPropertyValue(
          (string)$this->parentPropertyName, $inputdata->value
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
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  abstract protected function get_value();

  /**
   * Template method for use in validation.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  abstract public function processValidationRule($value) : void;
}
