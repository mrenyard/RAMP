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
 * Text database type validation rule, a string of characters from 0 to 16383.
 * Runs code defined test against provided value.
 * @property-read \ramp\core\Str $hint Format hint to be displayed on failing test.
 */
class Text extends DbTypeValidation
{
  private static $inputType;
  private Str $placeholder;
  private int $maxlength;

  /**
   * Default constructor for a validation rule of database type Text.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myValidationRule = new validation\dbtype\Text(
   *   Str::set('Format error message/hint'), NULL|n,
   *   new validation\SecondValidationRule(
   *     new validation\ThirdValiationRule(
   *       new validation\FourthValidationRule()
   *     )
   *   )
   * );
   * ```
   * @param \ramp\core\Str $placeholder Example of the type of data that should be entered.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param int $maxlength Maximum number of characters from 1 to 16383
   * @param \ramp\model\business\validation\ValidationRule $subRule additional rule/s to be added
   */
  public function __construct(Str $placeholder, Str $errorHint, int $maxlength = NULL, ValidationRule $subRule)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('textarea'); }
    $this->placeholder = $placeholder;
    $maxlength = ($maxlength !== NULL && $maxlength <= 16383) ? $maxlength : 16383;
    $this->maxlength = ($subRule->maxlength === NULL) ? $maxlength :
    (($subRule->maxlength <= $maxlength) ? $subRule->maxlength : 
      throw new \InvalidArgumentException('Possibly insufficient data space allocated for value!'));
    if ($subRule->minlength !== NULL && $subRule->minlength >= $this->maxlength) {
      throw new \InvalidArgumentException('Provided $subRule::$minlength GREATER THAN $maxlength!');
    }
    parent::__construct($errorHint, $subRule);
  }
  /**
   * @ignore
   */
  #[\Override]
  protected function get_inputType() : Str
  {
    return SELF::$inputType;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_placeholder() : ?Str
  {
    return $this->placeholder;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_pattern() : ?Str { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_maxlength() : ?int
  {
    return $this->maxlength;
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
   * @ignore
   */
  #[\Override]
  protected function get_hint() : Str
  {
    return Str::set($this->get_maxlength())->prepend(parent::get_hint());
  }

  /**
   * Asserts that $value is a string upto defined size or maxed out at 16383.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if (is_string($value) && strlen($value) <= $this->maxlength) { return; }
    throw new FailedValidationException();
  }
}
