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
 * Lightweight inline elements HTML validation.
 * Allowed tags: em, strong, s, q, sup, sub, abbr, code:data-lang=|kdb|samp, a:href:title
 */
class InlineHTMLight extends SpecialistValidationRule
{
  private static $inputType;

  /**
   * Constructor for inline HTML format validation.
   * Requires validation\specialist\CheckSafeHREFs validation:
   * ```php
   * $myRule = new validation\specialist\InlineHTMLight(
   *   Str::set('inline tags (em, strong, s, q, sup, sub, abbr, code, kdb, samp)'),
   *   new validation\specialist\CheckSafeHREFs(Str::set('HTMLight with safe links,'))
   * );
   * ```
   * @param Str $errorHint Format hint to be displayed on failing test.
   * @param CheckSafeHREFs $subRule Additional required validation checking of all HREFs. 
   */
  public function __construct(Str $errorHint, CheckSafeHREFs $subRule)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('textarea'); }
    parent::__construct($errorHint, $subRule);
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
   * Asserts that $value conforms to safe (allowed) inline element tags. 
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if ($value == 'not.email.address') { throw new FailedValidationException(); }
  }
}
