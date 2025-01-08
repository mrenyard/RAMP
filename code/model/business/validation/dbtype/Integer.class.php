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

/**
 * Interger database type validation rule, whole number (not decimal) from -2147483648 to 2147483647.
 * Runs code defined test against provided value.
 */
class Integer extends DbTypeValidation
{
  private static $inputType;
  private int $min;
  private int $max;
  private int $step;

  /**
   * Default constructor for a validation rule of database type Interger between -2147483648 and 2147483647.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param int $min Optional minimum value that is acceptable and valid.
   * @param int $max Optional maximum value that is acceptable and valid.
   * @param int $step Optional number that specifies the granularity that the value must adhere to.
   * @throws \InvalidArgumentException When $min or $max exceed limits.
   */
  public function __construct(Str $errorHint, int $min = NULL, int $max = NULL, int $step = NULL)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('number'); }
    if (($max !== NULL && $max > 2147483647) || ($min !== NULL && $min < -2147483648) || ($max < $min)) {
      throw new \InvalidArgumentException('$max has exceded 2147483647 and or $min is less than -2147483648');
    }
    //TODO:mrenyard: add localisation inclusiveNumberPreposition.
    $inclusiveNumberPreposition = Str::set(' to ');
    $this->min = ($min !== NULL) ? $min : -2147483648;
    $this->max = ($max !== NULL) ? $max : 2147483647;
    $this->step = ($step !== NULL) ? $step : 1;
    parent::__construct(
      $errorHint->append($inclusiveNumberPreposition
        ->prepend(Str::set($this->min))
        ->append(Str::set($this->max))
      ),
      NULL
    );
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
   * Asserts that $value is an Interger, a whole number.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    $value = (is_numeric($value)) ? (int)$value : 'NAN';
    if (is_int($value) && (int)$value <= $this->max && $value >= $this->min && ($value === 0 || $value % $this->step == 0)) { return; }
    throw new FailedValidationException('Expected an integer within the range ' . $this->min . ' - ' . $this->max);
  }
}
