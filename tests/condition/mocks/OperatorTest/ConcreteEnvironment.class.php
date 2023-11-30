<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP
 * @version 0.0.9;
 */
namespace tests\ramp\condition\mocks\OperatorTest;

use \ramp\condition\iEnvironment;
use \ramp\condition\Environment;

/**
 * Mock concrete environment, contining specilised operator strings as defined in iEnvironment.
 * .
 */
class ConcreteEnvironment extends Environment
{
  private static $INSTANCE;

  /**
   * Set up and return instance of <i>this</i> with full set of operators.
   * @return \ramp\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(self::$INSTANCE)) {
      $o = new ConcreteEnvironment();
      $o->setMemberAccess('memberAccess');
      $o->setAssignment('assignment');
      $o->setEqualTo(' equalTo ');
      $o->setNotEqualTo(' notEqualTo ');
      $o->setLessThan(' lessThan ');
      $o->setGreaterThan(' greaterThan ');
      $o->setAnd(' and ');
      $o->setOr(' or ');
      $o->setOpeningParenthesis('openingParenthesis');
      $o->setClosingParenthesis('closingParenthesis');
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
