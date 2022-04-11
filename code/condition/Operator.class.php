<?php
/**
 * RAMP - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
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
 * @package ramp
 * @version 0.0.9;
 */
namespace ramp\condition;

use ramp\core\RAMPObject;

/**
 * An operation to be performed against a Conditions predict.
 *
 * RESPONSIBILITIES
 * - Each variant of Operator is resposible for holding its type as a string.
 * - Act as a factory for a range of operation types.
 * - Work with iEnvironment in providing string literal representations of relavant operation.
 *
 * COLLABORATORS
 * - {@link \ramp\condition\iEnvironment}
 */
class Operator extends RAMPObject
{
  private $type;

  private static $MEMBER_ACCESS;
  private static $ASSIGNMENT;
  private static $EQUAL_TO;
  private static $NOT_EQUAL_TO;
  private static $LESS_THAN;
  private static $GREATER_THAN;
  private static $AND;
  private static $OR;
  private static $OPENING_PARENTHESIS;
  private static $CLOSING_PARENTHESIS;
  private static $OPENING_GROUPING_PARENTHESIS;
  private static $CLOSING_GROUPING_PARENTHESIS;

  private function __construct(string $type)
  {
    $this->type = $type;
  }

  /**
   * Returns the Operator object representing 'member access' operator syntax.
   * @return \ramp\condition\Operator Representing member access operator syntax
   */
  public static function MEMBER_ACCESS() : Operator
  {
    if (!isset(self::$MEMBER_ACCESS)) {
      self::$MEMBER_ACCESS = new Operator('memberAccess');
    }
    return self::$MEMBER_ACCESS;
  }

  /**
   * Returns the Operator object representing 'assignment operator' syntax.
   * @return \ramp\condition\Operator Representing assignment operator syntax
   */
  public static function ASSIGNMENT() : Operator
  {
    if (!isset(self::$ASSIGNMENT)) {
      self::$ASSIGNMENT = new Operator('assignment');
    }
    return self::$ASSIGNMENT;
  }

  /**
   * Returns the Operator object representing 'equal to' operator syntax.
   * @return \ramp\condition\Operator Representing 'equal to' operator syntax
   */
  public static function EQUAL_TO() : Operator
  {
    if (!isset(self::$EQUAL_TO)) {
      self::$EQUAL_TO = new Operator('equalTo');
    }
    return self::$EQUAL_TO;
  }

  /**
   * Returns the Operator object representing 'not equal to' operator syntax.
   * @return \ramp\condition\Operator Representing 'not equal to' operator syntax
   */
  public static function NOT_EQUAL_TO() : Operator
  {
    if (!isset(self::$NOT_EQUAL_TO)) {
      self::$NOT_EQUAL_TO = new Operator('notEqualTo');
    }
    return self::$NOT_EQUAL_TO;
  }

  /**
   * Returns the Operator object representing 'less than' operator syntax.
   * @return \ramp\condition\Operator Representing 'less than' operator syntax
   */
  public static function LESS_THAN() : Operator
  {
    if (!isset(self::$LESS_THAN)) {
      self::$LESS_THAN = new Operator('lessThan');
    }
    return self::$LESS_THAN;
  }

  /**
   * Returns the Operator object representing 'greater than' operator syntax.
   * @return \ramp\condition\Operator Representing 'greater than' operator syntax
   */
  public static function GREATER_THAN() : Operator
  {
    if (!isset(self::$GREATER_THAN)) {
      self::$GREATER_THAN = new Operator('greaterThan');
    }
    return self::$GREATER_THAN;
  }

  /**
   * Returns the Operator object representing 'and' operator syntax.
   * @return \ramp\condition\Operator Representing 'and' operator syntax
   */
  public static function AND() : Operator
  {
    if (!isset(self::$AND)) {
      self::$AND = new Operator('and');
    }
    return self::$AND;
  }

  /**
   * Returns the Operator object representing 'or' operator syntax.
   * @return \ramp\condition\Operator Representing 'or' operator syntax
   */
  public static function OR() : Operator
  {
    if (!isset(self::$OR)) {
      self::$OR = new Operator('or');
    }
    return self::$OR;
  }

  /**
   * Returns the Operator object representing 'opening parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'opening parenthesis' operator syntax
   */
  public static function OPENING_PARENTHESIS() : Operator
  {
    if (!isset(self::$OPENING_PARENTHESIS)) {
      self::$OPENING_PARENTHESIS = new Operator('openingParenthesis');
    }
    return self::$OPENING_PARENTHESIS;
  }

  /**
   * Returns the Operator object representing 'closing parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'closing parenthesis' operator syntax
   */
  public static function CLOSING_PARENTHESIS() : Operator
  {
    if (!isset(self::$CLOSING_PARENTHESIS)) {
      self::$CLOSING_PARENTHESIS = new Operator('closingParenthesis');
    }
    return self::$CLOSING_PARENTHESIS;
  }

  /**
   * Returns the Operator object representing 'opening grouping parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'opening grouping parenthesis' operator syntax
   */
  public static function OPENING_GROUPING_PARENTHESIS() : Operator
  {
    if (!isset(self::$OPENING_GROUPING_PARENTHESIS)) {
      self::$OPENING_GROUPING_PARENTHESIS = new Operator('openingGroupingParenthesis');
    }
    return self::$OPENING_GROUPING_PARENTHESIS;
  }

  /**
   * Returns the Operator object representing 'closing grouping parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'closing grouping parenthesis' operator syntax
   */
  public static function CLOSING_GROUPING_PARENTHESIS() : Operator
  {
    if (!isset(self::$CLOSING_GROUPING_PARENTHESIS)) {
      self::$CLOSING_GROUPING_PARENTHESIS = new Operator('closingGroupingParenthesis');
    }
    return self::$CLOSING_GROUPING_PARENTHESIS;
  }

  /**
   * Returns relevant string literal operator based on target environment.
   * @param \ramp\condition\iEnvironment $targetEnvironment Environment to target.
   * @return string Representation of operator based on provided target environment
   */
  public function __invoke(iEnvironment $targetEnvironment = null) : string
  {
    $type = $this->type;
    return $targetEnvironment->$type;
  }
}
