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
 * Time database type validation rule, in the format hh::mm.
 * Runs code defined test against provided value.
 */
class Time extends DbTypeValidation
{
  private static Str $inputType;

  /**
   * @ignore
   */
  #[\Override]
  protected function get_inputType() : Str
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('time'); }
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
   * Asserts that $value is a valid time in the format hh:mm:ss.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    $format = 'H:i:s';
    $value = (is_string($value ) && count(explode(':', $value)) != 3) ? $value . ':00' : $value;
    $o = \DateTime::createFromFormat($format, $value);
    if ($o && $o->format($format) === $value) { return; }
    throw new FailedValidationException();
  }
}
