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
use ramp\model\business\Record;
use ramp\model\business\validation\dbtype\DbTypeValidation;

/**
 * Input field related to a single property of its parent \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject}).
 * - Implement property specific methods for iteration, validity checking & error reporting.
 * - Hold referance back to parent Record and restrict polymorphic composite association. 
 * - Implement template method, processValidationRule to process provided ValidationRule.
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\Record}
 * - {@see \ramp\validation\ValidationRule}
 * 
 * @property-read \ramp\core\Str $inputType HTML input type [https://www.w3.org/TR/2011/WD-html5-20110525/the-input-element.html#attr-input-type].
 * @property-read \ramp\core\Str $pattern Regex pattern used in this validation rule.
 * @property-read \ramp\core\Str $maxlength Regex pattern used in this validation rule.
 */
class Input extends Field
{
  private $validationRule;

  /**
   * Creates input field related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property
   * @param \ramp\validation\dbtype\DbTypeValidation $validationRule Validation rule to test against
   * proir to allowing property value change
   */
  public function __construct(Str $name, Record $parent, DbTypeValidation $validationRule, bool $editable = TRUE)
  {
    $this->validationRule = $validationRule;
    parent::__construct($name, $parent, NULL, $editable);
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
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return $this->validationRule->inputType;
  }

  /**
   * @ignore
   */
  protected function get_pattern() : ?Str
  {
    return $this->validationRule->pattern;    
  }

  /**
   * @ignore
   */
  protected function get_maxlength() : ?int
  {
    return $this->validationRule->maxlength;
  }

  /**
   * @ignore
   */
  protected function get_min() : ?float
  {
    return $this->validationRule->min;
  }

  /**
   * @ignore
   */
  protected function get_max() : ?float
  {
    return $this->validationRule->max;
  }

  /**
   * @ignore
   */
  protected function get_step() : ?float
  {
    return $this->validationRule->step;
  }

  /**
   * Process provided validation rule.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    $this->validationRule->process($value);
  }
}
