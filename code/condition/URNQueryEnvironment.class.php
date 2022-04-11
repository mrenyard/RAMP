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
 * URN Query environment, contining specilised operator strings as defined in iEnvironment.
 *
 * RESPONSIBILITIES
 * - Define full set of URN Query environment specilised operators
 */
class URNQueryEnvironment extends Environment
{
  private static $INSTANCE;

  /**
   * Set up and return instance of *this* with full set of operators.
   * @return \ramp\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(self::$INSTANCE)) {
      $o = new URNQueryEnvironment();
      $o->memberAccess = ':';
      $o->assignment = '=';
      $o->equalTo = '=';
      $o->notEqualTo = '|not=';
      $o->lessThan = '|lt=';
      $o->greaterThan = '|gt=';
      $o->and = '&';
      $o->or = '|';
      $o->openingParenthesis = '';
      $o->closingParenthesis = '';
      $o->openingGroupingParenthesis = '';
      $o->closingGroupingParenthesis = '';
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
