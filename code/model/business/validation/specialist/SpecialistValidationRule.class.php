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
namespace ramp\model\business\validation\specialist;

use ramp\core\Str;
use ramp\model\business\validation\ValidationRule;

/**
 * Specialist validation rule used to test an input value before allowing a business model property to be set.
 *
 * RESPONSIBILITIES
 * - Inherits API for test method, where a single code defined test is executed against provided value.
 * - Act as the first validation rule of a decorator pattern where several tests can be organised to run consecutively.
 * - Takes and argument of $errorMessage to bubble up as message of FailedValidationException when test fails.
 *
 * COLLABORATORS
 * - {@see \ramp\validation\ValidationRule}
 */
abstract class SpecialistValidationRule extends ValidationRule
{
  /**
   * Default constructor for a ValidationRule.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new dbtype\FirstValidationRule(
   *   Str::set('Format error message/hint'),
   *   new SecondValidationRule(
   *     Str::set('Format error message/hint'),
   *     new ThirdValiationRule(
   *       Str::set('Format error message/hint'),
   *       new ForthValidationRule(
   *         Str::set('Format error message/hint')
   *       )
   *     )
   *   )
   * );
   * ```
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param ValidationRule $subRule additional optional rule/s to be added to *this* test.
   */
  public function __construct(Str $errorHint, SpecialistValidationRule $subRule = NULL)
  {
    parent::__construct($errorHint, $subRule);
  }
}
