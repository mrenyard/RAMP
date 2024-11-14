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

/**
 * PHP environment, containing specialised operator strings as defined in iEnvironment.
 *
 * RESPONSIBILITIES
 * - Define full set of PHP environment specialised operators
 */
class PHPEnvironment extends Environment
{
  private static iEnvironment $INSTANCE;

  /**
   * Set up and return instance of *this* with full set of operators.
   * @return \ramp\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(SELF::$INSTANCE)) {
      $o = new PHPEnvironment();
      $o->setMemberAccess('->');
      $o->setAssignment('=');
      $o->setEqualTo(' == ');
      $o->setNotEqualTo(' != ');
      $o->setLessThan(' < ');
      $o->setGreaterThan(' > ');
      $o->setAnd(' && ');
      $o->setOr(' || ');
      $o->setOpeningParenthesis("'");
      $o->setClosingParenthesis("'");
      $o->setOpeningGroupingParenthesis('(');
      $o->setClosingGroupingParenthesis(')');
      SELF::$INSTANCE = $o;
    }
    return SELF::$INSTANCE;
  }
}
