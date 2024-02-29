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
 * Small Interger database type validation rule, whole number (not decimal) from -32423 to 65534.
 * Runs code defined test against provided value.
 */
class SmallInt extends DbTypeValidation
{
  private static $inputType;
  private $min;
  private $max;
  private $step;

  /**
   * Default constructor for a validation rule of database type Interger.
   * @param \ramp\core\Str $errorMessage Message to be displayed when tests unsuccessful
   */
  public function __construct(Str $errorMessage, int $min = NULL, int $max = NULL, int $step = NULL)
  {
    if (!isset(self::$inputType)) { self::$inputType = Str::set('number'); }
    $this->min = ($min) ? $min : -32423;
    $this->max = ($max) ? $max : 65534;
    $this->step = ($step) ? $step : 1;
    parent::__construct($errorMessage, NULL);
  }

  /**
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return self::$inputType;
  }

  /**
   * @ignore
   */
  protected function get_min() : ?Str
  {
    return Str::set($this->min);
  }

  /**
   * @ignore
   */
  protected function get_max() : ?Str
  {
    return Str::set($this->max);
  }

  /**
   * @ignore
   */
  protected function get_step() : ?Str
  {
    return Str::set($this->step);
  }

  /**
   * Asserts that $value is an Interger, a whole number.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    if (is_int($value) && $value <= $this->max && $value >= $this->min && ($value === 0 || $value % $this->step == 0)) { return; }
    throw new FailedValidationException();
  }
}
