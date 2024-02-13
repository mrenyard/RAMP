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
namespace ramp\model\business\validation\dbtype;

use ramp\core\Str;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\ValidationRule;

/**
 * DecimalPointNumber database type validation rule, fractional part of number represented in tenth after a dot.
 * Runs code defined test against provided value.
 */
class DecimalPointNumber extends DbTypeValidation
{
  private $point;

  /**
   * Default constructor for a validation rule of database type DecimalPointNumber.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myValidationRule = new validation\dbtype\DecimalPointNumber(
   *   2,
   *   Str::set('My error message HERE!')
   * );
   * ```
   * @param int $point Number of places from decimal point expected
   * @param \ramp\core\Str $errorMessage Message to be displayed when tests unsuccessful
   */
  public function __construct(int $point, Str $errorMessage)
  {
    $this->point = $point;
    parent::__construct(NULL, $errorMessage);
  }

  /**
   * Asserts that $value is a float with no more than X point places.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    if (
      is_float($value) &&
      (
        (strpos((string)$value, '.') == 0) ||
        (strlen(substr(strrchr((string)$value, '.'), 1)) <= $this->point)
      )
    )
    {
      return;
    }
    throw new FailedValidationException();
  }
}
