<?php
/**
 * RAMP - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package ramp
 * @version 0.0.9;
 */
namespace ramp\condition;

/**
 * SQL environment, contining specilised operator strings as defined in iEnvironment.
 *
 * RESPONSIBILITIES
 * - Define full set of SQL environment specilised operators
 */
class SQLEnvironment extends Environment
{
  private static $INSTANCE;

  /**
   * Set up and return instance of *this* with full set of operators.
   * @return \ramp\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(self::$INSTANCE)) {
      $o = new SQLEnvironment();
      $o->memberAccess = '.';
      $o->assignment = '=';
      $o->equalTo = ' = ';
      $o->notEqualTo = ' <> ';
      $o->lessThan = ' < ';
      $o->greaterThan = ' > ';
      $o->and = ' AND ';
      $o->or = ' OR ';
      $o->openingParenthesis = '"';
      $o->closingParenthesis = '"';
      $o->openingGroupingParenthesis = '(';
      $o->closingGroupingParenthesis = ')';
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
