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
namespace ramp\condition;

use ramp\core\Str;

/**
 * Interface for environment specialised operators.
 */
interface iEnvironment
{
  /**
   * Return relevant environment specific iEnvironment instance.
   * @return \ramp\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment;

  /**
   * Return string representation of environment specific 'member access' operator.
   * @return \ramp\core\Str Str object composed environment specific 'member access' operator
   */
  public function get_memberAccess() : Str;

  /**
   * Return string representation of environment specific 'assignment operator'.
   * @return \ramp\core\Str Str object composed environment specific 'assignment operator'
   */
  public function get_assignment() : Str;

  /**
   * Return string representation of environment specific 'equal to' operator.
   * @return \ramp\core\Str Str object composed environment specific 'equal to' operator
   */
  public function get_equalTo() : Str;

  /**
   * Return string representation of environment specific 'not equal to' operator.
   * @return \ramp\core\Str Str object composed environment specific 'not equal to' operator
   */
  public function get_notEqualTo() : Str;

  /**
   * Return string representation of environment specific 'less than' operator.
   * @return \ramp\core\Str Str object composed environment specific 'less than' operator
   */
  public function get_lessThan() : Str;

  /**
   * Return string representation of environment specific 'greater than' operator.
   * @return \ramp\core\Str Str object composed environment specific 'greater than' operator
   */
  public function get_greaterThan() : Str;

  /**
   * Return string representation of environment specific 'and' operator.
   * @return \ramp\core\Str Str object composed environment specific 'and' operator
   */
  public function get_and() : Str;

  /**
   * Return string representation of environment specific 'or' operator.
   * @return \ramp\core\Str Str object composed environment specific 'or' operator
   */
  public function get_or() : Str;

  /**
   * Return string representation of environment specific 'opening parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'opening parenthesis' operator
   */
  public function get_openingParenthesis() : Str;

  /**
   * Return string representation of environment specific 'closing parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'closing parenthesis' operator
   */
  public function get_closingParenthesis() : Str;

  /**
   * Return string representation of environment specific 'opening grouping parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'opening grouping parenthesis' operator
   */
  public function get_openingGroupingParenthesis() : Str;

  /**
   * Return string representation of environment specific 'closing grouping parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'closing grouping parenthesis' operator
   */
  public function get_closingGroupingParenthesis() : Str;
}
