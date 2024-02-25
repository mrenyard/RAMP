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
 * Regex pattern matching validation.
 */
class HexidecimalColorCode extends ValidationRule
{
  private static $type;

   /**
   * Abstract regex pattern matching validation.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new dbtype\Char(7, new HexidecimalColorCode())
   * );
   * ```
   * @param string $pattern Regex pattern to be validated against.
   * @param ValidationRule $subRule Addtional rule to be added to *this* test.
   */
  public function __construct()
  {
    if (!isset(self::$type)) { self::$type = Str::set('color'); } 
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
    if (preg_match('/^#[0-9A-F]{1,2}[0-9A-F]{1,2}[0-9A-F]{1,2}$/', $value)) { return; }
    throw new FailedValidationException();
  }
}
