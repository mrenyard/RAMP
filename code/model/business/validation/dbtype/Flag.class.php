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
 * Flag database type validation rule, TRUE or FALSE.
 * Runs code defined test against provided value.
 * @property-read ?\ramp\core\Str $inputType HTML input type [https://www.w3.org/TR/2011/WD-html5-20110525/the-input-element.html#attr-input-type].
 */
class Flag extends DbTypeValidation
{
  /**
   * Default constructor for a validation rule of database type Flag.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   */
  public function __construct(Str $errorHint)
  {
    parent::__construct($errorHint);
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_inputType() : Str { return Str::_EMPTY(); }

  /**
   * Asserts that $value is a boolean, TRUE or FALSE.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    if ($value === 'on') { return; }
    throw new FailedValidationException('Flag input can only be one of True or False.');
  }
}
