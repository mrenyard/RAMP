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
 * Light HTML validation list of allowed tags:
 *  - block: p, h2, h3, h4, ol, ul, li
 *  - inline: strong, em, s, sup, sub
 *  - link: <a href="[safe-web-address]">text</a>).
 */
final class HTMLight extends ValidationRule
{
  private static $type;

  /**
   * Constructor for HexidecimalColorCode validation.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   */
  public function __construct(Str $errorHint, array $tags)
  {
    if (!isset(SELF::$type)) { SELF::$type = Str::set('textarea html-editor'); } 
    parent::__construct($errorHint);
  }

  /**
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return SELF::$type;
  }

  /**
   * Asserts that $value conforms to safe (allowed) html. 
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    $hrefMatches = array();
    if (preg_match('/(<a href="*">*</a>)/', $value, $hrefMatches)) {
      print_r('Textbox has ' . count($hrefMatches) . ' link/s');
    }
    if (preg_match('(?:<a href="(?:(https://[a-z0-9-_\.]|wwww.|)"<[a-z]\s*>', $value)) { return; }

    throw new FailedValidationException();
  }
}
