<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP
 * @version 0.0.9;
 */
namespace tests\ramp\condition\mocks\FilterConditionTest;

use \ramp\condition\iEnvironment;
use \ramp\condition\Environment;

/**
 * Mock environment, contining specilised operator strings as defined in iEnvironment.
 * .
 */
class MockEnvironment extends Environment
{
  private static $INSTANCE;

  /**
   * Set up and return instance of <i>this</i> with full set of operators.
   * @return \ramp\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(self::$INSTANCE)) {
      $o = new MockEnvironment();
      $o->setmemberAccess('memberAccess');
      $o->setassignment('assignment');
      $o->setequalTo(' equalTo ');
      $o->setnotEqualTo(' notEqualTo ');
      $o->setand(' and ');
      $o->setopeningParenthesis('openingParenthesis');
      $o->setclosingParenthesis('closingParenthesis');
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
