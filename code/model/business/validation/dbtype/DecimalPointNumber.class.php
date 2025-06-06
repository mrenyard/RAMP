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
 * DecimalPointNumber database type validation rule, fractional part of number represented in tenths after a dot.
 * Runs code defined test constricted by construction parameters against provided value.
 */
class DecimalPointNumber extends DbTypeValidation
{
  private static Str $inputType;
  private ?int $precision;
  private int $point;
  private string $min;
  private string $max;
  private float $step;

  /**
   * Default constructor for a validation rule of database type DecimalPointNumber.
   * ```php
   * $myValidationRule = new validation\dbtype\DecimalPointNumber(
   *   Str::set(' place decimal point number.'), 2, 5
   * );
   * ```
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param int $point Number of places from decimal point expected
   * @param int $precision Number of digits including decimal places that are storable places from decimal point expected
   */
  public function __construct(Str $errorHint, int $point, int $precision = NULL)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('number'); }
    $this->precision = ($precision) ? $precision : 65; 
    $this->point = ($point && $point < $precision) ? $point: $precision;
    $this->min = 0;
    $max = '';
    for ($i = 0, $j = $this->precision; $i < $j; $i++) {
      $max = $max . '9';
      if ($i == $point) { $max = $max . '.'; }
    }
    $this->max = $max;
    $step = '0.';
    for ($i = 0, $j = $this->point; $i < $j; $i++) {
      if (($i) == ($j-1)) {
        $step = $step . '1';
        break;
      }
      $step = $step . '0';
    }
    $this->step = (float)$step;
    parent::__construct(Str::set($point)->append(Str::SPACE())->append($errorHint), NULL);
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
  protected function get_min() : ?Str
  {
    return Str::set($this->min);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_max() : ?Str
  {
    return Str::set($this->max);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_step() : ?Str
  {
    return Str::set($this->step);
  }

  /**
   * Asserts that $value is a float with no more than X point places.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if (
      is_float($value) &&
      (
        (strpos((string)$value, '.') == 0) ||
        (strlen(substr(strrchr((string)$value, '.'), 1)) <= $this->point)
      )
    )
    {
      return;
    }
    throw new FailedValidationException();
  }
}
