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
 * Strictly alphnumaric Regex pattern validation, allows Only
 * uppercase and lowercase latin characters and numbers.
 */
class AlphanumericStrict extends RegexValidationRule
{
    /**
   * Constructor for strict alphnumaric Regex pattern validation.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param specialist\SpecialistValidationRule $subRule additional optional rule/s to be added to *this* test.
   */
  public function __construct(Str $errorHint, specialist\SpecialistValidationRule $subRule = NULL)
  {
    parent::__construct($errorHint, '[a-zA-Z0-9]*', $subRule);
  }
}
