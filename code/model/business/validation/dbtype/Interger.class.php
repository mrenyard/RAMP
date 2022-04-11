<?php
/**
 * RAMP - Rapid web application development using best practice.
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
 * Interger database type validation rule, a number which is not a decimal a whole number.
 * Runs code defined test against provided value.
 */
class Interger extends DbTypeValidation
{
  /**
   * Default constructor for a validation rule of database type Interger.
   * @param \ramp\core\Str $errorMessage Message to be displayed when tests unsuccessful
   */
  public function __construct(Str $errorMessage)
  {
    parent::__construct(NULL, $errorMessage);
  }

  /**
   * Asserts that $value is an Interger, a whole number.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value)
  {
    if (is_int($value)) { return; }
    throw new FailedValidationException();
  }
}
