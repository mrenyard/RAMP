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
use ramp\model\business\validation\FailedValidationException;

/**
 * Email address format validation and MX DNS domain check.
 */
class RegexEmailAddress extends RegexValidationRule
{
  /**
   * Constructor for regex based email address format validation (passed to client side pattern).
   * RegexEmailAddress validation usualy wrapped within validation\dbtype\VarChar and requires
   * validation\special\ServerSideEmail validation for server side checkes:
   * ```php
   * $myRule = new validation\dbtype\VarChar( Str::set('e.g. jsmith@domain.com')
   *   Str::set('string with a maximun character length of '), 150,
   *   new validation\RegexEmailAddres(
   *     Str::set('validly formatted email address'),
   *     new validation\special\ServerSideEmail()
   *   )
   * );
   * ```
   * @param Str $errorHint Format hint to be displayed on failing test.
   * @param specialist\ServerSideEmail $subRule Additional required server side email address complex format validation and MX DNS domain check.
   */
  public function __construct(Str $errorHint, specialist\ServerSideEmail $subRule)
  {
    parent::__construct($errorHint, '[a-zA-Z0-9\._%+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}', $subRule);
  }
}
