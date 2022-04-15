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
namespace ramp\model\business\validation;

use ramp\core\RAMPObject;

/**
 * Single validation rule to test against an input value before allowing a business model property
 *  to be set.
 *
 * RESPONSIBILITIES
 * - Defines API for test method, where a single code defined test is executed against provided value.
 * - Act as a decorator pattern where several tests can be organised to run consecutively.
 * - Works with other ValidationRules to provide more complex set of tests.
 *
 * COLLABORATORS
 * - {@link \ramp\validation\ValidationRule}
 */
abstract class ValidationRule extends RAMPObject
{
  private $subRule;

  /**
   * Default constructor for a ValidationRule.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new FirstValidationRule(
   *   new SecondValidationRule(
   *     new ThirdValiationRule(
   *       new ForthValidationRule()
   *     )
   *   )
   * );
   * ```
   * @param ValidationRule $subRule Addtional rule to be added to *this* test.
   */
  public function __construct(ValidationRule $subRule = null)
  {
    $this->subRule = $subRule;
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
    $this->test($value);
    if (isset($this->subRule)) {
      $this->subRule->process($value);
    }
  }
}
