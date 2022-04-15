<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\condition;

use ramp\core\RAMPObject;
use ramp\core\Str;

/**
 * Code defined statement that restricts, evaluates or modifies its predict.
 *  - the prerequisite on which something else is dependent within our system
 *
 * RESPONSIBILITIES
 * - Define base api for Conditions
 * - Hold values/references for attribute, operator and comparible
 *
 * COLLABORATORS
 * - {@link \ramp\condition\iEnvironment}
 * - {@link \ramp\condition\Operator}
 *
 * @property-read \ramp\core\Str $attribute Returns name of attribute to be restricted, evaluated or modified.
 * @property-read \ramp\condition\Operator $operator Returns the type of Operation to be performed.
 * @property mixed $comparable Value to be compared with attribute by operation.
 */
abstract class Condition extends RAMPObject
{
  private $attribute;
  private $operator;
  private $comparable;

  /**
   * Base constructor for Condition.
   * @param \ramp\core\Str $attribute Name of attribute to be restricted, evaluated or modified
   * @param \ramp\condition\Operator $operator Operation to perform
   * @param mixed $comparable Value to be compared with attribute by operation
   */
  public function __construct(Str $attribute, Operator $operator, $comparable = null)
  {
    $this->attribute = $attribute;
    $this->operator = $operator;
    if (isset($comparable)) { $this->set_comparable($comparable); }
  }

  /**
   * Returns name of attribute.
   * **DO NOT CALL DIRECTLY, USE this->attribute;**
   * @return \ramp\core\Str Name of attribute to be restricted, evaluated or modified
   */
  protected function get_attribute() : Str
  {
    return $this->attribute;
  }

  /**
   * Returns the type of Operation to be performed.
   * **DO NOT CALL DIRECTLY, USE this->operator;**
   * @return \ramp\condition\Operator Performing operation
   */
  final protected function get_operator() : Operator
  {
    return $this->operator;
  }

  /**
   * Returns value to be compared with attribute by operator.
   * **DO NOT CALL DIRECTLY, USE this->comparable;**
   * @return mixed Value of comparable
   */
  final protected function get_comparable()
  {
    return $this->comparable;
  }

  /**
   * Sets value of comparable (for operator to compare with attribute).
   * **DO NOT CALL DIRECTLY, USE this->comparable = $value;**
   * @param mixed $value Value to be compared with attribute by operation
   */
  protected function set_comparable($value)
  {
    $value = (is_string($value) && is_numeric($value)) ?
      ((float)$value == (int)$value) ? (int)$value :
        (float)$value :
          $value;
    $this->comparable = $value;
  }

  /**
   * Returns relevant condition statement based on target environment.
   * @param \ramp\condition\iEnvironment $targetEnvironment Environment to target.
   * @param mixed $comparable Value to be compared with attribute by operation.
   * @return string Representation of condition based on provided target environment
   */
  abstract public function __invoke(iEnvironment $targetEnvironment = null, $comparable = null) : string;
}
