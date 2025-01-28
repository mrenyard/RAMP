<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\mocks\view;

use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\DbTypeValidation;

/**
 * Concreate implementation of \ramp\model\business\validation\DbTypeValidation for testing against.
 * .
 */
class MockDbTypeValidation extends DbTypeValidation
{
  public static Str $inputType;
  public static ?Str $placeholder;
  public static ?Str $pattern;
  public static ?int $minlength;
  public static ?int $maxlength;
  public static ?Str $min;
  public static ?Str $max;
  public static ?Str $step;
  public static Str $hint;

  public function __construct(Str $errorHint)
  {
    if (!isset(SELF::$intpuType)) { SELF::$inputType = Str::set('text'); }
    if (!isset(SELF::$placeholder)) { SELF::$placeholder = Str::set('e.g PLACEHOLDER'); }
    if (!isset(SELF::$pattern)) { SELF::$pattern = Str::set('PATTERN'); }
    if (!isset(SELF::$minlength)) { SELF::$minlength = 10; }
    if (!isset(SELF::$maxlength)) { SELF::$maxlength = 15; }
    if (!isset(SELF::$min)) { SELF::$min = Str::set('10'); }
    if (!isset(SELF::$max)) { SELF::$max = Str::set('15'); }
    if (!isset(SELF::$step)) { SELF::$step = Str::set('1'); }
    parent::__construct($errorHint);
  }

  #[\Override]
  public function get_inputType() : Str
  {
    return SELF::$inputType;
  }

  #[\Override]
  protected function get_placeholder() : ?Str
  {
    return SELF::$placeholder;
  }

  #[\Override]
  protected function get_pattern() : ?Str
  {
    return SELF::$pattern;
  }

  #[\Override]
  protected function get_minlength() : ?int
  {
    return SELF::$minlength;
  }

  #[\Override]
  protected function get_maxlength() : ?int
  {
    return SELF::$maxlength;
  }

  #[\Override]
  protected function get_min() : ?Str
  {
    return SELF::$min;
  }

  #[\Override]
  protected function get_max() : ?Str
  {
    return SELF::$max;
  }

  #[\Override]
  protected function get_step() : ?Str
  {
    return SELF::$step;
  }

  /**
   * Runs code defined test against provided value.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if ($value === 'BadValue') {
      throw new FailedValidationException('MockDbValidationRule has been given the value BadValue');
    }
  }
}
