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

use ramp\core\Str;
use ramp\model\business\FailedValidationException;

/**
 * Validates a string value that corespondes to a single week in a give year.
 * 
 * A valid week string consists of a valid year number, followed by a hyphen character (-), then the capital letter "W",
 * followed by a two-digit week of the year value. The week of the year is a two-digit string between 01 and 53.
 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Date_and_time_formats#week_strings
 */
final class Week extends ValidationRule
{
  private static $type;

   /**
   * Constructor for a validation rule for Week formated string.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new dbtype\Char(8, new Week(Str::set('2015-W1'), Str::set('2025-W53')))
   * );
   * ```
   * @param Str $pattern Regex pattern to be validated against.
   * @param ValidationRule $subRule Addtional rule to be added to *this* test.
   */
  public function __construct(Str $min = NULL, Str $max = NULL, int $step = NULL)
  {
    if (!isset(self::$type)) { self::$type = Str::set('week'); } 
    parent::__construct();
  }

  /**
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return self::$type;
  }

  /**
   * @ignore 
   */
  protected function get_pattern() : ?Str
  {
    return NULL;
  }

  /**
   * @ignore
   */
  protected function get_maxlength() : ?int
  {
    return NULL;
  }

  /**
   * Asserts that $value is lower case and alphanumeric.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    if (preg_match('/^[0-9]{4}-W(?:0[1-9]|[1-4][0-9]|5[0-3])$/', $value)) { return; }
    throw new FailedValidationException();
  }
}
