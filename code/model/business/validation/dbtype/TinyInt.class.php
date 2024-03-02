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

/**
 * Tiny Interger database type validation rule, whole number (not decimal) from -128 to 127.
 * Runs code defined test against provided value.
 */
class TinyInt extends Integer
{
  /**
   * Default constructor for a validation rule of database type Interger between -128 and 127.
   * @param \ramp\core\Str $errorMessage Message to be displayed when tests unsuccessful
   * @param int $min Optional minimum value that is acceptable and valid.
   * @param int $max Optional maximum value that is acceptable and valid.
   * @param int $step Optional number that specifies the granularity that the value must adhere to.
   * @throws \InvalidArgumentException When $min or $max exceed limits.
   */
  public function __construct(Str $errorMessage, int $min = NULL, int $max = NULL, int $step = NULL)
  {
    if (($max !== NULL && $max > 127) || ($min !== NULL && $min < -128)) {
      throw new \InvalidArgumentException('$max has exceded 127 and or $min is less than -128');
    }
    parent::__construct($errorMessage, ($min) ? $min : -128, ($max) ? $max : 127, ($step) ? $step : 1);
  }
}
