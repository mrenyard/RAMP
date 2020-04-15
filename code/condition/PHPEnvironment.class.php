<?php
/**
 * Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\condition;

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
   * Set up and return instance of <i>this</i> with full set of operators.
   * @return \svelte\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(self::$INSTANCE)) {
      $o = new PHPEnvironment();
      $o->memberAccess = '->';
      $o->assignment = '=';
      $o->equalTo = ' == ';
      $o->notEqualTo = ' != ';
      $o->lessThan = ' < ';
      $o->greaterThan = ' > ';
      $o->and = ' && ';
      $o->or = ' || ';
      $o->openingParenthesis = "'";
      $o->closingParenthesis = "'";
      $o->openingGroupingParenthesis = '(';
      $o->closingGroupingParenthesis = ')';
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
