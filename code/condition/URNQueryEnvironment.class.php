<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP
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
      $o->setMemberAccess(':');
      $o->setAssignment('=');
      $o->setEqualTo('=');
      $o->setNotEqualTo('|not=');
      $o->setLessThan('|lt=');
      $o->setGreaterThan('|gt=');
      $o->setAnd('&');
      $o->setOr('|');
      $o->setOpeningParenthesis('');
      $o->setClosingParenthesis('');
      $o->setOpeningGroupingParenthesis('');
      $o->setClosingGroupingParenthesis('');
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
