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

/**
 * An operation to be performed against a Conditions predict.
 *
 * RESPONSIBILITIES
 * - Each variant of Operator is responsible for holding its type as a string.
 * - Act as a factory for a range of operation types.
 * - Work with iEnvironment in providing string literal representations of relevant operation.
 *
 * COLLABORATORS
 * - {@see \ramp\condition\iEnvironment}
 */
class Operator extends RAMPObject
{
  private string $type;

  private static Operator $MEMBER_ACCESS;
  private static Operator $ASSIGNMENT;
  private static Operator $EQUAL_TO;
  private static Operator $NOT_EQUAL_TO;
  private static Operator $LESS_THAN;
  private static Operator $GREATER_THAN;
  private static Operator $AND;
  private static Operator $OR;
  private static Operator $OPENING_PARENTHESIS;
  private static Operator $CLOSING_PARENTHESIS;
  private static Operator $OPENING_GROUPING_PARENTHESIS;
  private static Operator $CLOSING_GROUPING_PARENTHESIS;

  private function __construct(string $type)
  {
    $this->type = $type;
  }

  /**
   * Returns the Operator object representing 'member access' operator syntax.
   * @return \ramp\condition\Operator Representing 'member access' operator syntax
   */
  public static function MEMBER_ACCESS() : Operator
  {
    if (!isset(SELF::$MEMBER_ACCESS)) {
      SELF::$MEMBER_ACCESS = new Operator('memberAccess');
    }
    return SELF::$MEMBER_ACCESS;
  }

  /**
   * Returns the Operator object representing 'assignment operator' syntax.
   * @return \ramp\condition\Operator Representing 'assignment' operator syntax
   */
  public static function ASSIGNMENT() : Operator
  {
    if (!isset(SELF::$ASSIGNMENT)) {
      SELF::$ASSIGNMENT = new Operator('assignment');
    }
    return SELF::$ASSIGNMENT;
  }

  /**
   * Returns the Operator object representing 'equal to' operator syntax.
   * @return \ramp\condition\Operator Representing 'equal to' operator syntax
   */
  public static function EQUAL_TO() : Operator
  {
    if (!isset(SELF::$EQUAL_TO)) {
      SELF::$EQUAL_TO = new Operator('equalTo');
    }
    return SELF::$EQUAL_TO;
  }

  /**
   * Returns the Operator object representing 'not equal to' operator syntax.
   * @return \ramp\condition\Operator Representing 'not equal to' operator syntax
   */
  public static function NOT_EQUAL_TO() : Operator
  {
    if (!isset(SELF::$NOT_EQUAL_TO)) {
      SELF::$NOT_EQUAL_TO = new Operator('notEqualTo');
    }
    return SELF::$NOT_EQUAL_TO;
  }

  /**
   * Returns the Operator object representing 'less than' operator syntax.
   * @return \ramp\condition\Operator Representing 'less than' operator syntax
   */
  public static function LESS_THAN() : Operator
  {
    if (!isset(SELF::$LESS_THAN)) {
      SELF::$LESS_THAN = new Operator('lessThan');
    }
    return SELF::$LESS_THAN;
  }

  /**
   * Returns the Operator object representing 'greater than' operator syntax.
   * @return \ramp\condition\Operator Representing 'greater than' operator syntax
   */
  public static function GREATER_THAN() : Operator
  {
    if (!isset(SELF::$GREATER_THAN)) {
      SELF::$GREATER_THAN = new Operator('greaterThan');
    }
    return SELF::$GREATER_THAN;
  }

  /**
   * Returns the Operator object representing 'and' operator syntax.
   * @return \ramp\condition\Operator Representing 'and' operator syntax
   */
  public static function AND() : Operator
  {
    if (!isset(SELF::$AND)) {
      SELF::$AND = new Operator('and');
    }
    return SELF::$AND;
  }

  /**
   * Returns the Operator object representing 'or' operator syntax.
   * @return \ramp\condition\Operator Representing 'or' operator syntax
   */
  public static function OR() : Operator
  {
    if (!isset(SELF::$OR)) {
      SELF::$OR = new Operator('or');
    }
    return SELF::$OR;
  }

  /**
   * Returns the Operator object representing 'opening parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'opening parenthesis' operator syntax
   */
  public static function OPENING_PARENTHESIS() : Operator
  {
    if (!isset(SELF::$OPENING_PARENTHESIS)) {
      SELF::$OPENING_PARENTHESIS = new Operator('openingParenthesis');
    }
    return SELF::$OPENING_PARENTHESIS;
  }

  /**
   * Returns the Operator object representing 'closing parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'closing parenthesis' operator syntax
   */
  public static function CLOSING_PARENTHESIS() : Operator
  {
    if (!isset(SELF::$CLOSING_PARENTHESIS)) {
      SELF::$CLOSING_PARENTHESIS = new Operator('closingParenthesis');
    }
    return SELF::$CLOSING_PARENTHESIS;
  }

  /**
   * Returns the Operator object representing 'opening grouping parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'opening grouping parenthesis' operator syntax
   */
  public static function OPENING_GROUPING_PARENTHESIS() : Operator
  {
    if (!isset(SELF::$OPENING_GROUPING_PARENTHESIS)) {
      SELF::$OPENING_GROUPING_PARENTHESIS = new Operator('openingGroupingParenthesis');
    }
    return SELF::$OPENING_GROUPING_PARENTHESIS;
  }

  /**
   * Returns the Operator object representing 'closing grouping parenthesis' operator syntax.
   * @return \ramp\condition\Operator Representing 'closing grouping parenthesis' operator syntax
   */
  public static function CLOSING_GROUPING_PARENTHESIS() : Operator
  {
    if (!isset(SELF::$CLOSING_GROUPING_PARENTHESIS)) {
      SELF::$CLOSING_GROUPING_PARENTHESIS = new Operator('closingGroupingParenthesis');
    }
    return SELF::$CLOSING_GROUPING_PARENTHESIS;
  }

  /**
   * Returns relevant string literal operator based on target environment.
   * @param \ramp\condition\iEnvironment $targetEnvironment Environment to target.
   * @return string Representation of operator based on provided target environment
   */
  public function __invoke(iEnvironment $targetEnvironment = NULL) : string
  {
    $type = $this->type;
    return $targetEnvironment->$type;
  }
}
