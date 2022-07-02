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

use ramp\core\RAMPObject;
use ramp\core\Str;

/**
 * Base class abstract environment as defined in iEnvironment.
 *
 * RESPONSIBILITIES
 * - Provide base implementation of iEnvironment.
 * - Hold full set of properties and there accessors.
 * - Simplify code base of inherited specalized environment classes.
 * @example ./PHPEnvironment.class.php Example specilized implemetation for the PHP environment.
 *
 * @property-read \ramp\core\Str $memberAccess Returns Str repesentation of environment specific 'member access' operator.
 * @property-read \ramp\core\Str $assignment   Returns Str repesentation of environment specific 'assignment' operator.
 * @property-read \ramp\core\Str $equalTo Returns Str repesentation of environment specific 'equal to' operator.
 * @property-read \ramp\core\Str $notEqualTo Returns Str repesentation of environment specific 'not equal to' operator.
 * @property-read \ramp\core\Str $lessThan Returns Str repesentation of environment specific 'less than' operator.
 * @property-read \ramp\core\Str $greaterThan Returns Str repesentation of environment specific 'greater than' operator.
 * @property-read \ramp\core\Str $and Returns Str repesentation of environment specific 'and' operator.
 * @property-read \ramp\core\Str $or Returns Str repesentation of environment specific 'or' operator.
 * @property-read \ramp\core\Str $openingParentheses Returns Str repesentation of environment specific 'openingParentheses' operator.
 * @property-read \ramp\core\Str $closingParentheses Returns Str repesentation of environment specific 'closingParentheses' operator.
 * @property-read \ramp\core\Str $openingGroupingParentheses Returns Str repesentation of environment specific 'openingGroupingParentheses' operator.
 * @property-read \ramp\core\Str $closingGroupingParentheses Returns Str repesentation of environment specific 'closingGroupingParentheses' operator.
 */
abstract class Environment extends RAMPObject implements iEnvironment
{
  /**
   * Environment specific 'member access' operator.
   * @var string
   */
  protected $memberAccess;

  /**
   * Environment specific 'assignment operator'.
   * @var string
   */
  protected $assignment;

  /**
   * Environment specific 'equal to' operator.
   * @var string
   */
  protected $equalTo;

  /**
   * Environment specific 'not equal to' operator.
   * @var string
   */
  protected $notEqualTo;

  /**
   * Environment specific 'less than' operator.
   * @var string
   */
  protected $lessThan;

  /**
   * Environment specific 'greater then' operator.
   * @var string
   */
  protected $greaterThan;

  /**
   * Environment specific 'and' operator.
   * @var string
   */
  protected $and;

  /**
   * Environment specific 'or' operator.
   * @var string
   */
  protected $or;

  /**
   * Environment specific 'opening parenthesis' operator.
   * @var string
   */
  protected $openingParenthesis;

  /**
   * Environment specific 'closing parenthesis' operator.
   * @var string
   */
  protected $closingParenthesis;

  /**
   * Environment specific 'opening grouping parenthesis' operator.
   * @var string
   */
  protected $openingGroupingParenthesis;

  /**
   * Environment specific 'closing grouping parenthesis' operator.
   * @var string
   */
  protected $closingGroupingParenthesis;

  /**
   * Protected constructor for use by sub-classes only.
   */
  protected function __construct()
  {
  }

  /**
   * Returns Str repesentation of environment specific 'member access' operator.
   * @return \ramp\core\Str Str object composed environment specific 'member access' operator
   */
  public function get_memberAccess() : Str
  {
    return Str::set($this->memberAccess);
  }

  /**
   * Returns string repesentation of environment specific 'assignment' operator.
   * @return \ramp\core\Str Str object composed environment specific 'assignment' operator
   */
  public function get_assignment() : Str
  {
    return Str::set($this->assignment);
  }

  /**
   * Returns string repesentation of environment specific 'equal to' operator.
   * @return \ramp\core\Str Str object composed environment specific 'equal to' operator
   */
  public function get_equalTo() : Str
  {
    return Str::set($this->equalTo);
  }

  /**
   * Returns string repesentation of environment specific 'not equal to' operator.
   * @return \ramp\core\Str Str object composed environment specific 'not equal to' operator
   */
  public function get_notEqualTo() : Str
  {
    return Str::set($this->notEqualTo);
  }

  /**
   * Returns string repesentation of environment specific 'less than' operator.
   * @return \ramp\core\Str Str object composed environment specific 'less than' operator
   */
  public function get_lessThan() : Str
  {
    return Str::set($this->lessThan);
  }

  /**
   * Returns string repesentation of environment specific 'greater than' operator.
   * @return \ramp\core\Str Str object composed environment specific 'greater than' operator
   */
  public function get_greaterThan() : Str
  {
    return Str::set($this->greaterThan);
  }

  /**
   * Returns string repesentation of environment specific 'and' operator.
   * @return \ramp\core\Str Str object composed environment specific 'and' operator
   */
  public function get_and() : Str
  {
    return Str::set($this->and);
  }

  /**
   * Returns string repesentation of environment specific 'or' operator.
   * @return \ramp\core\Str Str object composed environment specific 'or' operator
   */
  public function get_or() : Str
  {
    return Str::set($this->or);
  }

  /**
   * Returns string repesentation of environment specific 'opening parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'opening parenthesis' operator
   */
  public function get_openingParenthesis() : Str
  {
    return Str::set($this->openingParenthesis);
  }

  /**
   * Returns string repesentation of environment specific 'closing parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'closopening parenthesis' operator
   */
  public function get_closingParenthesis() : Str
  {
    return Str::set($this->closingParenthesis);
  }

  /**
   * Returns string repesentation of environment specific 'opening grouping parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'opening grouping parenthesis' operator
   */
  public function get_openingGroupingParenthesis() : Str
  {
    return Str::set($this->openingGroupingParenthesis);
  }

  /**
   * Returns string repesentation of environment specific 'closing grouping parenthesis' operator.
   * @return \ramp\core\Str Str object composed environment specific 'closing grouping parenthesis' operator
   */
  public function get_closingGroupingParenthesis() : Str
  {
    return Str::set($this->closingGroupingParenthesis);
  }
}
