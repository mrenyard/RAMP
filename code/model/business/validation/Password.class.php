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
 * Password restricted regex pattern matching validation.
 */
class Password extends RegexValidationRule
{
  private static $type;

  /**
   * Constructor for password restricted regex pattern validation.
   * @param \ramp\core\Str $errorMessage Message to be displayed on failing test
   * @param string $pattern Regex pattern to be validated against.
   * @param ValidationRule $subRule Addtional rule to be added to *this* test.
   */
  public function __construct(Str $errorMessage, ValidationRule $subRule = null)
  {
    if (!isset(self::$type)) { self::$type = Str::set('password'); } 
    parent::__construct($errorMessage, '[a-zA-Z0-9!#$%&\(\)+,-\.:;?\[\]\^*_\{\|\}\{~@ ]{8,35}', $subRule);
  }

  /**
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return self::$type;
  }
}
