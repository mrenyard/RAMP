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
  private $memberAccess;
  private $assignment;
  private $equalTo;
  private $notEqualTo;
  private $lessThan;
  private $greaterThan;
  private $and;
  private $or;
  private $openingParenthesis;
  private $closingParenthesis;
  private $openingGroupingParenthesis;
  private $closingGroupingParenthesis;

  /**
   * Protected constructor for use by sub-classes only.
   */
  protected function __construct()
  {
  }

  /**
   * @ignore
   */
  public function get_memberAccess() : Str { return Str::set($this->memberAccess); }

  /**
   * @ignore
   */
  protected function setMemberAccess(string $value) : void { $this->memberAccess = $value; }

  /**
   * @ignore
   */
  public function get_assignment() : Str { return Str::set($this->assignment); }

  /**
   * @ignore
   */
  protected function setAssignment(string $value) : void { $this->assignment = $value; }

  /**
   * @ignore
   */
  public function get_equalTo() : Str { return Str::set($this->equalTo); }

  /**
   * @ignore
   */
  protected function setEqualTo(string $value) : void { $this->equalTo = $value; }

  /**
   * @ignore
   */
  public function get_notEqualTo() : Str { return Str::set($this->notEqualTo); }

  /**
   * @ignore
   */
  protected function setNotEqualTo(string $value) : void { $this->notEqualTo = $value; }

  /**
   * @ignore
   */
  public function get_lessThan() : Str { return Str::set($this->lessThan); }
  
  /**
   * @ignore
   */
  protected function setLessThan(string $value) : void { $this->lessThan = $value; }

  /**
   * @ignore
   */
  public function get_greaterThan() : Str { return Str::set($this->greaterThan); }
  
  /**
   * @ignore
   */
  protected function setGreaterThan(string $value) : void { $this->greaterThan = $value; }

  /**
   * @ignore
   */
  public function get_and() : Str { return Str::set($this->and); }
  
  /**
   * @ignore
   */
  protected function setAnd(string $value) : void { $this->and = $value; }

  /**
   * @ignore
   */
  public function get_or() : Str { return Str::set($this->or); }
  
  /**
   * @ignore
   */
  protected function setOr(string $value) : void { $this->or = $value; }

  /**
   * @ignore
   */
  public function get_openingParenthesis() : Str { return Str::set($this->openingParenthesis); }
  
  /**
   * @ignore
   */
  protected function setOpeningParenthesis(string $value) : void { $this->openingParenthesis = $value; }

  /**
   * @ignore
   */
  public function get_closingParenthesis() : Str { return Str::set($this->closingParenthesis); }
  
  /**
   * @ignore
   */
  protected function setClosingParenthesis(string $value) : void { $this->closingParenthesis = $value; }

  /**
   * @ignore
   */
  public function get_openingGroupingParenthesis() : Str { return Str::set($this->openingGroupingParenthesis); }
  
  /**
   * @ignore
   */
  protected function setOpeningGroupingParenthesis(string $value) : void { $this->openingGroupingParenthesis = $value; }

  /**
   * @ignore
   */
  public function get_closingGroupingParenthesis() : Str { return Str::set($this->closingGroupingParenthesis); }
  
  /**
   * @ignore
   */
  protected function setClosingGroupingParenthesis(string $value) : void { $this->closingGroupingParenthesis = $value; }
}
