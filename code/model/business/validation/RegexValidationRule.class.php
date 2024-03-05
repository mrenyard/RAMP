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
 * Regex pattern matching validation.
 */
class RegexValidationRule extends ValidationRule
{
  private $pattern;
  private $format;

   /**
   * Constructor for regex pattern matching validation.
   * Multiple ValidationRules can be wrapped within each other to form a more complex set of tests:
   * ```php
   * $myRule = new dbtype\FirstValidationRule(
   *   new RegexValidationRule(
   *     Str::set('regex format message/hint'),
   *     '[a-zA-Z]'
   *     new SpecialValidationRule(
   *       Str::set('extra format message/hint')
   *     )
   *   )
   * );
   * ```
   * @param \ramp\core\Str $errorMessage Message to be displayed on failing test
   * @param string $pattern Regex pattern to be validated against.
   * @param ValidationRule $subRule Optional addtional rule to be added to *this* test.
   * @param string $format Optional format profile based on ISO standards.
   */
  public function __construct(Str $errorMessage, string $pattern, ValidationRule $subRule = null, string $format = NULL)
  {
    $this->pattern = $pattern;
    $this->format = $format;
    parent::__construct($errorMessage, $subRule);
  }

  /**
   * @ignore 
   */
  protected function get_format() : ?string
  {
    return $this->format;
  }

  /**
   * @ignore 
   */
  protected function get_pattern() : ?Str
  {
    return Str::set($this->pattern);
  }

  /**
   * Asserts that $value is lower case and alphanumeric.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    if (preg_match('/^' . $this->pattern . '$/', $value)) { return; }
    throw new FailedValidationException('$value failed to match provided regex!');
  }
}
