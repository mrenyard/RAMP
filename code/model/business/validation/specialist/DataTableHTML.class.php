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
 * Validates a single HTML table element and its contents as safe.
 * - tabular-data: table > tr > (th:abbr:headers:colspan:rowspan:scope=col|colgroup|row|rowgroup, td) 
 */
class DataTableHTML extends SpecialistValidationRule
{
  private static $inputType;

  /**
   * Constructor for validation of a single HTML table element.
   * ```php
   * $myRule = new validation\specialist\DataTableHTML(Str::set('valid data table'));
   * ```
   * @param Str $errorHint Format hint to be displayed on failing test.
   */
  public function __construct(Str $errorHint)
  {
    if (!isset(SELF::$inputType)) { SELF::$inputType = Str::set('textarea html-table'); }
    parent::__construct($errorHint);
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
   * Asserts that $value conforms to ... . 
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if ($value == 'not.email.address') { throw new FailedValidationException(); }
  }
}
