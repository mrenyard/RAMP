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
use ramp\model\business\validation\FormatBasedValidationRule;

/**
 * Char database type validation rule, a string of characters of an exact length.
 * Runs code defined test against provided value.
 */
class Char extends DbTypeValidation
{
  private bool $isFormatRule;
  private Str $placeholder;
  private int $length;

  /**
   * Default constructor for a validation rule of database type Char.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new dbtype\Char(
   *   Str::set('Format error message/hint'), 20,
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
   * @param \ramp\core\Str $placeholder Example of the type of data that should be entered.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param int $length Exact number of characters expected.
   * @param \ramp\model\business\validation\ValidationRule $subRule Additional rule/s to be added.
   */
  public function __construct(Str $placeholder, Str $errorHint, int $length, ValidationRule $subRule)
  {
    $this->placeholder = $placeholder;
    $this->length = $length;
    $this->isFormatRule = ($subRule instanceof FormatBasedValidationRule);
    parent::__construct(Str::set($this->length)->prepend($errorHint), $subRule);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_placeholder() : ?Str
  {
    return (!$this->isFormatRule) ? $this->placeholder : NULL;
  }

  /**
   * @ignore 
   */
  #[\Override]
  protected function get_pattern() : ?Str
  {
    return ($value = parent::get_pattern()) ? (str_ends_with((string)$value, '}') || str_ends_with((string)$value, '$')) ? $value :
    $value->prepend(Str::set('('))->append(Str::set('){' . $this->length . '}')) : NULL;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_minlength() : ?int
  {
    return (!$this->isFormatRule) ? $this->length : NULL;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_maxlength() : ?int
  {
    return (!$this->isFormatRule) ? $this->length : NULL;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_min() : ?Str { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_max() : ?Str { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_step() : ?Str { return NULL; }

  /**
   * Asserts that $value is a string of exactly expected number of characters.
   * @param mixed $value Value to be tested.
   * @throws \ramp\model\business\validation\FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if (is_string($value) && strlen($value) == $this->length) { return; }
    throw new FailedValidationException('Invalid character length!');
  }
}
