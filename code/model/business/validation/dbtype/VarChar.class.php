<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace ramp\model\business\validation\dbtype;

use ramp\core\Str;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\ValidationRule;

/**
 * VarChar database type validation rule, a string of characters with defined max length.
 * Runs code defined test against provided value.
 */
class VarChar extends DbTypeValidation
{
  private $maxLength;

  /**
   * Default constructor for a validation rule of database type VarChar.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myValidationRule = new validation\dbtype\VarChar(
   *   20,
   *   new validation\SecondValidationRule(
   *     new validation\ThirdValiationRule(
   *       new validation\ForthValidationRule()
   *     )
   *   ),
   *   Str::set('My error message HERE!')
   * );
   * ```
   * @param int $maxLength Maximum number of characters from 0 to 16383
   * @param \ramp\model\business\validation\ValidationRule $subRule Addtional rule/s to be added
   * @param \ramp\core\Str $errorMessage Message to be displayed when tests unsuccessful
   */
  public function __construct(int $maxLength, ValidationRule $subRule, Str $errorMessage)
  {
    $this->maxLength = $maxLength;
    parent::__construct($subRule, $errorMessage);
  }

  /**
   * Asserts that $value is a string upto defined max length.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value)
  {
    if (is_string($value) && strlen($value) <= $this->maxLength) { return; }
    throw new FailedValidationException();
  }
}
