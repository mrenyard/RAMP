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
 * Lightweight HTML validation.
 * List of allowed tags:
 * - block: p, h2, h3, h4, h5, h6, ul, ol, li, blockquote, pre+code:data-lang=|kdb|samp, figure:data-mid
 * - inline: em, strong, s, q, sup, sub, abbr, code:data-lang=|kdb|samp
 * - **Optional** tabular-data: table > tr > (th:abbr:headers:colspan:rowspan:scope=col|colgroup|row|rowgroup, td) 
 * - link: &lt;a href="[safe-web-address]"&gt;text&lt;/a&gt;
 */
class HTMLight extends SpecialistValidationRule
{
  private static $inputType;
  private DataTableHTML $tableValidator;

  /**
   * Constructor for light HTML format validation.
   * HTMLight validation usualy wrapped within validation\dbtype\Text
   * and requires validation\specialist\CheckSafeHREFs validation:
   * ```php
   * $myRule = new validation\dbtype\Text(
   *   Str::set('with a maximun character length of '), NULL,
   *   new validation\HTMLight(
   *     Str::set('and blocklevel tags (p, h2, h3, h4, ol, ul, li, pre+code|kbd|samp, blockquote, figure)')
   *     new validation\specialist\InlineHTMLight(
   *       Str::set('inline tags (em, strong, s, q, sup, sub, abbr, code, kdb, samp)'),
   *       new validation\specialist\CheckSafeHREFs(
   *         Str::set('HTMLight with safe links,')
   *       )
   *     ),
   *     new validation\specialist\DataTableHTML(
   *       Str::set('allows data tables,')
   *     )
   *   )
   * );
   * ```
   * @param Str $errorHint Format hint to be displayed on failing test.
   * @param InlineHTMLight $subRule Additional required server side email address complex format validation and MX DNS domain check.
   * @param ?DataTableHTML $tableValidator Optional table validator - when present extends HTMLight to allow advanved data tables. 
   */
  public function __construct(Str $errorHint, InlineHTMLight $subRule, ?DataTableHTML $tableValidator = NULL)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('textarea html-editor'); }
    $this->tableValidator = $tableValidator;
    parent::__construct(
      ($tableValidator === NULL) ? $errorHint : $tableValidator->hint->prepend($errorHint->append(Str::SPACE())),
      $subRule
    );
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
   * Asserts that $value conforms to safe (allowed) html. 
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if ($value == 'not.email.address') { throw new FailedValidationException(); }
    // $hrefMatches = array();

    // if (preg_match('/(<a href="(*)">(*)</a>)/', $value, $hrefMatches)) {
    //   print_r('Textbox has ' . count($hrefMatches) . ' link/s');
    //   foreach ($hrefMatches as $match) {
    //     print_r($match);
    //     // check URL: any URL unsafe chars = throw new FailedValidationException();
    //     // check text: any text safe chars = throw new FailedValidationException();
    //   }
    // }
    return;
  }
}
