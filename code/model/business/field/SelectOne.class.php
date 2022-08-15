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
use ramp\model\business\FailedValidationException;

/**
 * Specilised field for selecting one from a collection of iOptions tied to a single property of
 * its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Implement template method, processValidationRule to validate against avalible iOptions.
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 * - {@link \ramp\core\OptionList}
 */
final class SelectOne extends SelectFrom
{
  /**
   * Creates select one field type, tied to a single property of containing record.
   * @param \ramp\core\Str $dataObjectPropertyName Related dataObject property name of containing record
   * @param \ramp\model\business\Record $containingRecord Record parent of *this* property
   * @param \ramp\core\OptionList $options Collection of available field\Options
   *
  public function __construct(Str $dataObjectPropertyName, Record $containingRecord, OptionList $options)
  {
    parent::__construct($dataObjectPropertyName, $containingRecord, $options);  
  }*/

  /**
   * ArrayAccess method offsetSet, DO NOT USE.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   *
  public function offsetSet($offset, $object)
  {
    throw new \BadMethodCallException('Array access setting is not allowed.');
  }*/

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   *
  public function offsetUnset($offset)
  {
    throw new \BadMethodCallException('Array access unsetting is not allowed.');
  }*/

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    $index = $this->containingRecord->getPropertyValue($this->dataObjectPropertyName);
    return (isset($index))? $this->options[$index] : $this->options[0];
  }

  /**
   * Validate that value is one of avalible options.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value)
  {
    foreach ($this->options as $option)
    {
      if ((string)$value == (string)$option->id) { return; }
    }
    throw new FailedValidationException('Selected value NOT an avalible option!');
  }
}
