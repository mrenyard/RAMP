<?php
/**
 * Svelte - Rapid web application development using best practice.
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
namespace svelte\model\business\validation;

/**
 * Is string validation.
 * Runs code defined test against provided value.
 */
class VarChar extends ValidationRule
{
  private $maxLength;

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
   * @param int $maxLength Maximum number of characters
   * @param ValidationRule $subRule Addtional rule to be added to *this* test.
   */
  public function __construct(int $maxLength, ValidationRule $subRule = null)
  {
    $this->maxLength = $maxLength;
    parent::__construct($subRule);
  }

  /**
   * Asserts that $value is a string.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value)
  {
    if (is_string($value) && strlen($value) <= $this->maxLength) { return; }
    throw new FailedValidationException(
      'Please make sure input value is a string less than ' . $this->maxLength . ' characters!'
    );
  }
}
