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

/**
 * Regex pattern matching validation.
 */
class TelephoneNumber extends RegexValidationRule
{
  private static $type;
  private static $maxlength;

   /**
   * Regex pattern matching validation.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new dbtype\FirstValidationRule(
   *   new RegexValidationRule('[a-zA-Z]'
   *     new SpecialValidationRule()
   *   )
   * );
   * ```
   * @param string $pattern Regex pattern to be validated against.
   * @param ValidationRule $subRule Addtional rule to be added to *this* test.
   */
  public function __construct(ValidationRule $subRule = null)
  {
    if (!isset(self::$type)) { self::$type = Str::set('tel'); } 
    if (!isset(self::$maxlength)) { self::$maxlength = Str::set('12'); } 
    parent::__construct('^(\+[1-9]{1,3} \(0\)|0)[0-9\- ]{8,12}$', $subRule);
  }

  /**
   * @ignore
   */
  protected function get_inputType() : ?Str
  {
    return self::$type;
  }

  /**
   * @ignore
   */
  protected function get_maxlength() : ?Str
  {
    return self::$maxlength;
  }
}
