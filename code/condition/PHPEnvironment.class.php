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
 * PHP environment, contining specilised operator strings as defined in iEnvironment.
 *
 * RESPONSIBILITIES
 * - Define full set of PHP environment specilised operators
 */
class PHPEnvironment extends Environment
{
  private static $INSTANCE;

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
