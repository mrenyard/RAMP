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
namespace ramp\model\business\validation;

use ramp\core\RAMPObject;
use ramp\core\Str;

/**
 * Single validation rule to test against an input value before allowing a business model property
 * to be set.
 *
 * RESPONSIBILITIES
 * - Defines API for test method, where a single code defined test is executed against provided value.
 * - Act as a decorator pattern where several tests can be organised to run consecutively.
 * - Works with other ValidationRules to provide a more complex set of tests.
 *
 * COLLABORATORS
 * - {@see \ramp\validation\ValidationRule}
 * 
 * @property-read ?\ramp\core\Str $inputType HTML input type [https://www.w3.org/TR/2011/WD-html5-20110525/the-input-element.html#attr-input-type].
 * @property-read ?\ramp\core\Str $placeholder Example of the type of data that should be entered.
 * @property-read ?\ramp\core\Str $pattern Regex pattern used in this validation rule.
 * @property-read ?int $minlength The minimum allowed value length.
 * @property-read ?int $maxlength The maximum allowed value length.
 * @property-read ?\ramp\core\Str $min The minimum value that is acceptable and valid.
 * @property-read ?\ramp\core\Str $max The maxnimum value that is acceptable and valid.
 * @property-read ?\ramp\core\Str $step Number that specifies the granularity that the value must adhere to or the keyword 'any'. 
 * @property-read \ramp\core\Str $hint Format hint to be displayed on failing test.
 */
abstract class ValidationRule extends RAMPObject
{
  private static Str $defaultInputType;
  private Str $errorHint;
  private ?ValidationRule $subRule;

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
   * @param ValidationRule $subRule Addtional optional rule/s to be added to *this* test.
   */
  public function __construct(Str $errorHint, ValidationRule $subRule = NULL)
  {
    if (!isset(SELF::$defaultInputType)) { SELF::$defaultInputType = Str::set('text'); }
    $this->errorHint = $errorHint;
    $this->subRule = $subRule;
  }

  /**
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return ($this->subRule) ? $this->subRule->inputType : SELF::$defaultInputType;
  }

  /**
   * @ignore
   */
  protected function get_placeholder() : ?Str
  {
    return ($this->subRule) ? $this->subRule->placeholder : NULL;
  }

  /**
   * @ignore
   */
  protected function get_pattern() : ?Str
  {
    return ($this->subRule) ? $this->subRule->pattern : NULL;
  }

  /**
   * @ignore
   */
  protected function get_minlength() : ?int
  {
    return ($this->subRule) ? $this->subRule->minlength : NULL;
  }

  /**
   * @ignore
   */
  protected function get_maxlength() : ?int
  {
    return ($this->subRule) ? $this->subRule->maxlength : NULL;
  }

  /**
   * @ignore
   */
  protected function get_min() : ?Str
  {
    return ($this->subRule) ? $this->subRule->min : NULL;
  }

  /**
   * @ignore
   */
  protected function get_max() : ?Str
  {
    return ($this->subRule) ? $this->subRule->max : NULL;
  }

  /**
   * @ignore
   */
  protected function get_step() : ?Str
  {
    return ($this->subRule) ? $this->subRule->step : NULL;
  }

  /**
   * @ignore
   */
  protected function get_hint() : ?Str
  {
    return ($this->subRule) ? 
      $this->subRule->hint->append($this->errorHint->prepend(Str::SPACE())) :
        $this->errorHint;
  }

  /**
   * Runs code defined test against provided value.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  abstract protected function test($value) : void;

  /**
   * Process each validation test against provided value.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  public function process($value) : void
  {
    $this->test($value);
    if (isset($this->subRule)) { $this->subRule->process($value); }
  }
}
