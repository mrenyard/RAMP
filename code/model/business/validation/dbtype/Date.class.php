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
 * Date database type validation rule, in the format YYYY-mm-dd.
 * ValidationRules can be wrapped within each other to form a more complex set of tests:
 * ```php
 * $myRule = new dbtype\Date(
 *   Str::set('Format error message/hint'),
 *   new ISODate(Str::set('Format error message/hint'), ...)
 * );
 * ```
*/
class Date extends DbTypeValidation
{
  /**
   * Asserts that $value is a valid date in the format YYYY-MM-DD.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    $format = 'Y-m-d';
    $o = \DateTime::createFromFormat($format, $value);
    if ($o && $o->format($format) === $value) { return; }
    throw new FailedValidationException();
  }
}
