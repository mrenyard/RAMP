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
 * Year database type validation rule, 4-digit strings in the range '1901' to '2155' in the format YYYY.
 * Runs code defined test against provided value.
 */
class Year extends Integer
{
  private static Str $inputType;
  
  /**
   * Default constructor for a validation rule of database type Year between 1901 and 2155.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param int $min Optional minimum value that is acceptable and valid.
   * @param int $max Optional maximum value that is acceptable and valid.
   * @param int $step Optional number that specifies the granularity that the value must adhere to.
   * @throws \InvalidArgumentException When $min or $max exceed limits.
   */
  public function __construct(Str $errorHint, int $min = NULL, int $max = NULL, int $step = NULL)
  {
    if (($max !== NULL && $max > 2155) || ($min !== NULL && $min < 1901) || ($max < $min)) {
      throw new \InvalidArgumentException('$max has exceded 2155 and or $min is less than 1901');
    }
    parent::__construct($errorHint, ($min) ? $min : 1901, ($max) ? $max : 2155, ($step) ? $step : 1);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_inputType() : Str
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('number year'); }
    return SELF::$inputType;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_placeholder() : ?Str { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_pattern() : ?Str { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_minlength() : ?int { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_maxlength() : ?int { return NULL; }

  /**
   * Asserts that $value is a valid date in the format YYYY.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    parent::test((int)$value);
    $format = 'Y';
    $o = \DateTime::createFromFormat($format, $value);
    if ($o && $o->format($format) == $value) { return; }
    throw new FailedValidationException(); // @codeCoverageIgnore
  }
}
