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
 * ISO format based regex pattern matching validation.
 */
class FormatBasedValidationRule extends RegexValidationRule
{
   /**
   * Constructor ISO format based regex pattern matching validation..
   * @param \ramp\core\Str $errorMessage Message to be displayed on failing test
   * @param string $pattern Regex pattern to be validated against.
   * @param string $format Required format profile based on ISO standards.
   */
  public function __construct(Str $errorMessage, string $pattern, string $format)
  {
    parent::__construct($errorMessage,$pattern, NULL, $format);
  }
}