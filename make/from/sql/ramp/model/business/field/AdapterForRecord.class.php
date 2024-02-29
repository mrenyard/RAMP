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
 * @package RAMP.make
 * @version 0.0.9;
 */
namespace ramp\model\business\field;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\validation\dbtype\DbTypeValidation;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\field\Field;

/**
 * MultiPartPrimary field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Implement template method, processValidationRule to process provided ValidationRule.
 * - Hold referance back to its contining Record
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\Record}
 */
class AdapterForRecord extends Field
{
  private $record;

  /**
   * Creates an adapter for make Records (Column / Table) as Field.
   * @param \ramp\model\business\Record $record record tobe adapted.
   */
  public function __construct(Record $record)
  {
    $this->record = $record;
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
    return $this->record;
  }

  public function __get($propertyName)
  {
    if (!method_exists($this->record, ($method = 'get_'.$propertyName))) {
      throw new BadPropertyCallException(
        'Unable to locate '.$propertyName.' of \''.get_class($this->record).'\''
      );
    }
    return $this->record->$propertyName;
  }

  /**
   * Process provided validation rule.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
  }
}
