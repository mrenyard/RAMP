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
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\ValidationRule;

/**
 * Char database type validation rule, a string of characters of an exact length.
 * Runs code defined test against provided value.
 */
class Char extends DbTypeValidation
{
  private $length;

  /**
   * Default constructor for a validation rule of database type Char.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myValidationRule = new validation\dbtype\Char(
   *   20,
   *   new validation\SecondValidationRule(
   *     new validation\ThirdValiationRule(
   *       new validation\ForthValidationRule()
   *     )
   *   ),
   *   Str::set('My error message HERE!')
   * );
   * ```
   * @param \ramp\core\Str $errorMessage Message to be displayed when tests unsuccessful prior to $length.
   * @param int $length Exact number of characters expected
   * @param \ramp\model\business\validation\ValidationRule $subRule Addtional rule/s to be added
   */
  public function __construct(Str $errorMessage, int $length, ValidationRule $subRule)
  {
    $this->length = $length;
    parent::__construct(Str::set($this->length)->prepend($errorMessage), $subRule);
  }

  /**
   * @ignore 
   */
  protected function get_pattern() : ?Str
  {
    return ($value = parent::get_pattern()) ? (str_ends_with((string)$value, '}') || str_ends_with((string)$value, '$')) ? $value :
    $value->prepend(Str::set('('))->append(Str::set('){' . $this->length . '}')) : NULL;
  }

  /**
   * @ignore
   */
  protected function get_maxlength() : ?int
  {
    return (parent::get_maxlength() == NULL) ? NULL : $this->length;
  }

  /**
   * Asserts that $value is a string of exactly expected number of characters.
   * @param mixed $value Value to be tested.
   * @throws \ramp\model\business\validation\FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    if (is_string($value) && strlen($value) == $this->length) { return; }
    throw new FailedValidationException();
  }
}
