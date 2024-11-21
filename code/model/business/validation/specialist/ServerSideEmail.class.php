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
namespace ramp\model\business\validation\specialist;

use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;

/**
 * Email address complex format validation and MX DNS domain check.
 */
class ServerSideEmail extends SpecialistValidationRule
{
  private static Str $inputType;

  /**
   * Constructor for complex email address format validation and MX DNS domain check.
   * Usually used in conjunction with validation\Regex Email Address for client side
   * pattern matching and validation\dbtype\VarChar:
   * ```php
   * $myRule = new validation\dbtype\VarChar( Str::set('e.g. jsmith@domain.com')
   *   Str::set('string with a maximun character length of '), 150,
   *   new validation\RegexEmailAddres(
   *     Str::set('validly formatted email address'),
   *     new validation\special\ServerSideEmail()
   *   )
   * );
   * ```
   */
  public function __construct()
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('email'); }
    parent::__construct(Str::_EMPTY());
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
  protected function get_hint() : Str
  {
    return Str::_EMPTY();
  }


  /**
   * Asserts that $value is format of valid email address.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if (\filter_var($value, FILTER_VALIDATE_EMAIL)) {
      if (\checkdnsrr(explode('@', $value)[1], 'MX')) { return; }
      throw new FailedValidationException('Provided email domain does NOT exist!'); // @codeCoverageIgnore
      return;
    }
    throw new FailedValidationException();
  }
}
