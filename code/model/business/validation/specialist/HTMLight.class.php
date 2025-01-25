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
 * - tabular-data: table > tr > (th:abbr:headers:colspan:rowspan:scope=col|colgroup|row|rowgroup, td) 
 * - link: &lt;a href="[safe-web-address]"&gt;text&lt;/a&gt;
 */
class HTMLight extends SpecialistValidationRule
{
  private static $inputType;
  private static $root;
  private static $dtd;

  /**
   * Constructor for light HTML format validation.
   * HTMLight validation usually wrapped within validation\dbtype\Text
   * and requires validation\specialist\CheckSafeHREFs validation:
   * ```php
   * $myRule = new validation\dbtype\Text(
   *   Str::set('with a maximum character length of '), NULL,
   *   new validation\HTMLight(
   *     Str::set('HTMLight [https://rampapp.info/assets/htmlight.dtd]'),
   *     new validation\specialist\CheckSafeHREFs(Str::set('safe (href) links,'))
   *   )
   * );
   * ```
   * @param Str $errorHint Format hint to be displayed on failing test.
   * @param CheckSafeHREFs $subRule Additional required validation checking of all HREFs. 
   */
  public function __construct(Str $errorHint, CheckSafeHREFs $subRule)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('textarea html-editor'); }
    if (!isset(SELF::$root)) { SELF::$root = 'htmlight'; }
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
   * Asserts that $value conforms to safe (allowed) html. 
   * @todo:mrenyard: replace DTD location once public accessible.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if (!isset(SELF::$dtd)) { SELF::$dtd = file_get_contents(str_replace('local', '', \ramp\SETTING::$RAMP_LOCAL_DIR) . 'www/assets/htmlight.dtd'); }
    try {
      $providedXML = new \DOMDocument;
      $providedXML->loadXML(
      '<?xml version="1.0"?>
      <!DOCTYPE HTMLight PUBLIC "-//RAMP//DTD HTMLight//EN"
        "https://ramp.matt-laptop.lan/assets/htmlight.dtd">
      <htmlight>' . $value . '</htmlight>'
      );
      $creator = new \DOMImplementation();
      $tester = $creator->createDocument(
        NULL, '',
        $creator->createDocumentType(
          SELF::$root, '', 'data://text/plain;base64,'.base64_encode(SELF::$dtd)
        )
      );
      $tester->encoding = "utf-8";
      $providedXMLNode = $providedXML->getElementsByTagName(SELF::$root)->item(0);
      $testerNode = $tester->importNode($providedXMLNode, true);
      $tester->appendChild($testerNode);
      if ($tester->validate()) { return; }
    } catch (\Error $e) { }
    throw new FailedValidationException();
  }
}
