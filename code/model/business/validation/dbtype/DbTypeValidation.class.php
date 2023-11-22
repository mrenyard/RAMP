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

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\ValidationRule;

/**
 * Database type validation rule, one of a set of rules to be the first in any decorated set
 * used to test an input value before allowing a business model property to be set.
 *
 * RESPONSIBILITIES
 * - Inherits API for test method, where a single code defined test is executed against provided value.
 * - Act as the first validation rule of a decorator pattern where several tests can be organised to run consecutively.
 * - Takes and argument of $errorMessage to bubble up as message of FailedValidationException when test fails.
 *
 * COLLABORATORS
 * - {@see \ramp\validation\ValidationRule}
 */
abstract class DbTypeValidation extends ValidationRule
{
  private $errorMessage;

  /**
   * Default constructor for a DbTypeValidation.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new MyDbTypeValidation(
   *   new SecondValidationRule(
   *     new ThirdValiationRule(
   *       new ForthValidationRule()
   *     )
   *   )
   *   Str::set('My error message HERE!')
   * );
   * ```
   * @param ValidationRule $subRule Addtional rule to be added to *this* test
   * @param \ramp\core\Str $errorMessage Message to be displayed on failing test
   */
  public function __construct(ValidationRule $subRule = NULL, Str $errorMessage)
  {
    $this->errorMessage = $errorMessage;
    parent::__construct($subRule);
  }

  /**
   * Runs code defined test against provided value.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  abstract protected function test($value);

  /**
   * Process each validation test against provided value.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  public function process($value)
  {
    try {
      parent::process($value);
    } catch (FailedValidationException $exception) {
      throw new FailedValidationException($this->errorMessage);
    }
  }
}
