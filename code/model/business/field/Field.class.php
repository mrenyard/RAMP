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
use ramp\model\business\validation\FailedValidationException;

/**
 * Abstract field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject}).
 * - Implement property specific methods for iteration, validity checking & error reporting.
 * - Hold referance back to parent Record and restrict polymorphic composite association.
 * - Provide access to relevent value based on parent record state.
 * - Implement template method, processValidationRule to process provided ValidationRule.
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\Record Record}
 * 
 * @property-read \svetle\core\Str $label Form field label.
 * @property-read \svetle\core\Str $title Title text representing advisory information related this form control.
 * @property bool $isRequired Check for property is a required field value.
 */
abstract class Field extends RecordComponent
{
  private Str $title;
  protected StrCollection $errorCollection;

  /**
   * Base constructor for Field related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property
   * @param \ramp\core\Str $title An expanded description of expected field content.
   * @param bool $editable Optional set preference for editability.
   */
  public function __construct(Str $name, Record $parent, Str $title, bool $editable = TRUE)
  {
    $this->title = $title;
    parent::__construct($name, $parent, $editable);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_type() : Str
  {
    return BusinessModel::processType((string)$this, TRUE)->append(Str::set(' field'));
  }

  /**
   * @ignore
   */
  protected function get_label() : Str
  {
    return Str::set(ucwords(trim(preg_replace('/((?:^|[A-Z])[a-z]+)/', ' $0', $this->name))));
  }

  /**
   * ArrayAccess method offsetSet, USE DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \InvalidArgumentException Adding properties through offsetSet STRONGLY DISCOURAGED!
   */
  #[\Override]
  public function offsetSet($offset, $object) : void
  {
    if (!($object instanceof \ramp\core\iOption)) {
      throw new \InvalidArgumentException(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    }
    parent::offsetSet($offset, $object);
  }

  /**
   * @ignore
   */
  protected function get_title() : Str
  {
    return $this->title;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_isEditable() : bool
  {
    return ((!$this->parent->isValid) || parent::get_isEditable());
  }

  /**
   * @ignore
   */
  protected function get_isRequired() : bool
  {
    return ($this->parent->isRequiredField((string)$this->name));
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   * @param bool $update Default is to update on successful validation, FALSE to skip.
   */
  #[\Override]
  public function validate(PostData $postdata, bool $update = TRUE) : void
  {
    $this->errorCollection = StrCollection::set();
    foreach ($postdata as $inputdata)
    {
      if ((string)$inputdata->attributeURN == (string)$this->id)
      {
        if (!$this->isEditable) { return; }
        try {
          $this->processValidationRule($inputdata->value);
        } catch (FailedValidationException $exception) {
          $this->errorCollection->add(Str::set($exception->getMessage()));
          return;
        }
        if ($update) {
          $value = (\is_array($inputdata->value)) ? implode('|', $inputdata->value) : $inputdata->value;
          $this->parent->setPropertyValue((string)$this->name, $value);
        }
        return;
      }
    }
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_hasErrors() : bool
  {
    return (isset($this->errorCollection) && $this->errorCollection->count > 0);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_errors() : StrCollection
  {
    return (isset($this->errorCollection)) ? $this->errorCollection : StrCollection::set();
  }

  /**
   * Template method for use in validation.
   * @param mixed $value Value to be processed
   * @throws \ramp\model\business\validation\FailedValidationException When test fails.
   */
  abstract public function processValidationRule($value) : void;
}
