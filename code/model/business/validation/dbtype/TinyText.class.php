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
 * TinyText database type validation rule, a string of characters from 0 to 255.
 * Runs code defined test against provided value.
 * @property-read ?int $maxlength The maximum allowed value length.
 * @property-read \ramp\core\Str $hint Format hint to be displayed on failing test.
 */
class TinyText extends Text
{
  /**
   * Default constructor for a validation rule of database type TinyText.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myValidationRule = new validation\dbtype\TinyText(
   *   new validation\SecondValidationRule(
   *     new validation\ThirdValiationRule(
   *       new validation\FourthValidationRule()
   *     )
   *   ),
   *   Str::set('Format error message/hint')
   * );
   * ```
   * @param \ramp\core\Str $placeholder Example of the type of data that should be entered.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param ?int $maxlength Optional maximum number of characters from 0 to 255 (defaults 255).
   * @param \ramp\model\business\validation\ValidationRule $subRule Additional rule/s to be added
   */
  public function __construct(Str $placeholder, Str $errorHint, int $maxlength = NULL, ValidationRule $subRule)
  {
    $maxlength = ($maxlength !== NULL && $maxlength <= 255) ? $maxlength : 255;
    parent::__construct($placeholder, $errorHint, $maxlength, $subRule);
  }
}
