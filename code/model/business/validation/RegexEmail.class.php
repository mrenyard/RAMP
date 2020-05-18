<?php
/**
 * Svelte - Rapid web application development using best practice.
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
 * @version 0.0.9;
 */
namespace svelte\model\business\validation;

/**
 * Email address format validation.
 * Runs code defined test against provided value.
 */
class RegexEmail extends ValidationRule
{
  /**
   * Asserts that $value is format of valid email address.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value)
  {
    if (filter_var($value, FILTER_VALIDATE_EMAIL)) { return; }
    throw new FailedValidationException(
      'Please make sure input value is a valid email address'
    );
  }
}
