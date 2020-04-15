<?php
/**
 * Svelte - Rapid web application development using best practice.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package svelte
 * @version 0.0.9;
 */
namespace tests\svelte\condition\mocks\OperatorTest;

use \svelte\condition\iEnvironment;
use \svelte\condition\Environment;

/**
 * Mock concrete environment, contining specilised operator strings as defined in iEnvironment.
 * .
 */
class ConcreteEnvironment extends Environment
{
  private static $INSTANCE;

  /**
   * Set up and return instance of <i>this</i> with full set of operators.
   * @return \svelte\condition\iEnvironment this with full set of operators
   */
  public static function getInstance() : iEnvironment
  {
    if (!isset(self::$INSTANCE)) {
      $o = new ConcreteEnvironment();
      $o->memberAccess = 'memberAccess';
      $o->assignment = 'assignment';
      $o->equalTo = ' equalTo ';
      $o->notEqualTo = ' notEqualTo ';
      $o->lessThan = ' lessThan ';
      $o->greaterThan = ' greaterThan ';
      $o->and = ' and ';
      $o->or = ' or ';
      $o->openingParenthesis = 'openingParenthesis';
      $o->closingParenthesis = 'closingParenthesis';
      self::$INSTANCE = $o;
    }
    return self::$INSTANCE;
  }
}
