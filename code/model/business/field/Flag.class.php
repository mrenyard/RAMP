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
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\validation\dbtype\Flag as Rule;

/**
 * Flag  boolean field related to a single property of its parent \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject}).
 * - Implement property specific methods for iteration, validity checking & error reporting.
 * - Hold referance back to parent Record and restrict polymorphic composite association. 
 * - Implement template method, processValidationRule to process provided ValidationRule.
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\Record}
 */
class Flag extends Field
{
  /**
   * Creates boolean field related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   */
  public function __construct(Str $name, Record $parent)
  {
    parent::__construct($name, $parent);
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
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    return ((bool)$this->parent->getPropertyValue((string)$this->name));
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    parent::validate($postdata);
    if ($this->parent->isModified) {
      $currentValue = $this->parent->getPropertyValue((string)$this->name);
      if ($currentValue === TRUE || $currentValue === FALSE) {
        $newValue = ($currentValue)? 1:0;
        $this->parent->setPropertyValue((string)$this->name, $newValue);
      }
    }
  }

  /**
   * Process provided validation rule.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    $rule = new Rule(Str::set('Flag input can only be one of True or False.'));
    $rule->process($value);
  }
}