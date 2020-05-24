<?php
/**
 * Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\model\business\field;

use svelte\core\Str;
use svelte\model\business\Record;
use svelte\model\business\validation\ValidationRule;

/**
 * Input field related to a single property of its containing \svelte\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Implement template method, processValidationRule to process provided ValidationRule.
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@link \svelte\model\business\Record}
 * - {@link \svelte\validation\ValidationRule}
 */
class Input extends Field
{
  private $validationRule;

  /**
   * Creates input field related to a single property of containing record.
   * @param \svelte\core\Str $dataObjectPropertyName Related dataObject property name of containing record
   * @param \svelte\model\business\Record $containingRecord Record parent of *this* property
   * @param \svelte\validation\ValidationRule $validationRule Validation rule to test against
   * proir to allowing property value change
   */
  public function __construct(Str $dataObjectPropertyName, Record $containingRecord, ValidationRule $validationRule)
  {
    $this->validationRule = $validationRule;
    parent::__construct($dataObjectPropertyName, $containingRecord);
  }

  /**
   * ArrayAccess method offsetSet, DO NOT USE.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetSet($offset, $object)
  {
    throw new \BadMethodCallException('Array access setting is not allowed.');
  }

  /**
   * ArrayAccess method offsetUnset, DO NOT USE.
   * @param mixed $offset API to match \ArrayAccess interface
   * @throws \BadMethodCallException Array access unsetting is not allowed.
   */
  public function offsetUnset($offset)
  {
    throw new \BadMethodCallException('Array access unsetting is not allowed.');
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    return $this->containingRecord->getPropertyValue((string)$this->dataObjectPropertyName);
  }

  /**
   * Process provided validation rule.
   * @param mixed $value Value to be processed
   * @throws \svelte\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value)
  {
    $this->validationRule->process($value);
  }
}
