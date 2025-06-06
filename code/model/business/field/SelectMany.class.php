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
use ramp\core\iOption;
use ramp\core\OptionList;
use ramp\model\business\Record;
use ramp\model\business\validation\FailedValidationException;

/**
 * Specilised field for selecting zero or many from a collection of iOptions tied to a single
 * property of its parent \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject}).
 * - Implement property specific methods for iteration, validity checking & error reporting.
 * - Hold reference back to parent Record and restrict polymorphic composite association. 
 * - Implement template method, processValidationRule to validate against available iOptions.
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\Record}
 * - {@see \ramp\core\OptionList}
 */
final class SelectMany extends SelectFrom
{
  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value() : OptionList|iOption|string|int|float|bool|NULL
  {
    $selection = new OptionList();
    $value = $this->parent->getPropertyValue($this->name);
    $value = ($value !== NULL) ? explode('|', $value) : array(0);
    foreach ($value as $key) {
      foreach ($this as $option) {
        if ($option->key == (int)$key) { $selection->add($option); }
      }
    }
    return $selection;
  }

  /**
   * Validate that value is an array contain zero or many of available options.
   * @param mixed $value Value to be processed
   * @throws \BadMethodCallException When $value parameter is NOT an array.
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    if (!is_array($value)) {
      throw new \BadMethodCallException('$value parameter must be an array');
    }
    foreach ($value as $selected) {
      $valid = FALSE;
      foreach ($this as $option) {
        if ((string)$selected == (string)$option->key) {
          $valid = TRUE;
          continue;
        }
      }
      if (!$valid) {
        throw new FailedValidationException(
          'At least one selected value is NOT an available option!'
        );
      }
    }
  }
}
