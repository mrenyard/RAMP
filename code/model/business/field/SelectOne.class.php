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
use ramp\core\OptionList;
use ramp\model\business\Record;
use ramp\model\business\validation\FailedValidationException;

/**
 * Specialised field for selecting one from a collection of iOptions tied to a single property of
 * its parent \ramp\model\business\Record.
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
final class SelectOne extends SelectFrom
{
  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    $index = $this->parent->getPropertyValue($this->name);
    return (isset($index))? $this[$index] : $this[0];
  }

  /**
   * Validate that value is one of available options.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    foreach ($this as $option)
    {
      if ((string)$value == (string)$option->id) { return; }
    }
    throw new FailedValidationException('Selected value NOT an available option!');
  }
}
