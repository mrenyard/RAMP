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
namespace svelte\condition;

use svelte\core\SvelteObject;
use svelte\core\Str;

/**
 * Code defined statement that restricts, evaluates or modifies its predict.
 *  - the prerequisite on which something else is dependent within our system
 *
 * RESPONSIBILITIES
 * - Define base api for Conditions
 * - Hold values/references for attribute, operator and comparible
 *
 * COLLABORATORS
 * - {@link \svelte\condition\iEnvironment}
 * - {@link \svelte\condition\Operator}
 */
abstract class Condition extends SvelteObject
{
  private $attribute;
  private $operator;
  private $comparable;

  /**
   * Base constructor for Condition.
   * @param \svelte\core\Str $attribute Name of attribute to be restricted, evaluated or modified
   * @param \svelte\condition\Operator $operator Operation to perform
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
   * <b>DO NOT CALL DIRECTLY, USE this->attribute;</b>
   * @return \svelte\core\Str Name of attribute to be restricted, evaluated or modified
   */
  protected function get_attribute() : Str
  {
    return $this->attribute;
  }

  /**
   * Returns the type of Operation to be performed.
   * <b>DO NOT CALL DIRECTLY, USE this->operator;</b>
   * @return \svelte\condition\Operator Performing operation
   */
  protected function get_operator() : Operator
  {
    return $this->operator;
  }

  /**
   * Returns value to be compared with attribute by operator.
   * <b>DO NOT CALL DIRECTLY, USE this->comparable;</b>
   * @return mixed Value of comparable
   */
  protected function get_comparable()
  {
    return $this->comparable;
  }

  /**
   * Sets value of comparable (for operator to compare with attribute).
   * <b>DO NOT CALL DIRECTLY, USE this->comparable = $value;</b>
   * @param mixed $value Value to be compared with attribute by operation
   */
  protected function set_comparable($value)
  {
    $this->comparable = $value;
  }

  /**
   * Returns relevant condition statement based on target environment.
   * @param \svelte\condition\iEnvironment $targetEnvironment Environment to target.
   * @param mixed $comparable Value to be compared with attribute by operation.
   * @return string Representation of condition based on provided target environment
   */
  abstract public function __invoke(iEnvironment $targetEnvironment = null, $comparable = null) : string;
}
