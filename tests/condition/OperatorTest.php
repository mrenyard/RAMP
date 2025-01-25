<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @author mrenyard (renyard.m@gmail.com)
 * @version 0.0.9;
 * @package RAMP.test
 */
namespace tests\ramp\condition;

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/ConcreteEnvironment.class.php';

use \ramp\core\RAMPObject;
use \ramp\condition\Operator;

use tests\ramp\mocks\condition\ConcreteEnvironment;

/**
 * Collection of tests for \ramp\condition\Operator.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\ConcreteEnvironment}
 */
class OperatorTest extends \tests\ramp\core\ObjectTest
{
  private $targetEnvironment;

  #region Setup
  #[\Override]
  protected function preSetup() : void { $this->targetEnvironment = ConcreteEnvironment::getInstance(); }
  #[\Override]
  protected function getTestObject() : RAMPObject { return Operator::MEMBER_ACCESS(); }
  #endregion

  /**
   * Collection of assertions for \ramp\condition\Operator::getInstance().
   * - assert __constructor inaccessible (private).
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\condition\Operator}
   * @see \ramp\condition\Environment\getInstance()
   */
  #[\Override]
  public function testConstruct() : void
  {
    try {
      $o = new Operator();
    } catch (\Error $expected) {
      $this->assertSame(0, $expected->getCode());
      parent::testConstruct();
      $this->assertInstanceOf('\ramp\condition\Operator', $this->testObject);
      return;
    }
    $this->fail('An expected \Error has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
  #[\Override]
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Collection of assertions for \ramp\condition\Operator::MEMBER_ACCESS().
   * - assert returns expected specilized 'member access' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\MEMBER_ACCESS()
   */
  public function testMEMBER_ACCESS()
  {
    $o = $this->testObject;
    $this->assertSame(Operator::MEMBER_ACCESS(), $o);
    $this->assertSame((string)$this->targetEnvironment->memberAccess, $o($this->targetEnvironment));
    $this->assertSame('memberAccess', $o($this->targetEnvironment));
  }


  /**
   * Collection of assertions for \ramp\condition\Operator::ASSIGNMENT().
   * - assert returns expected specilized 'assignment' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\ASSIGNMENT()
   */
  public function testASSIGNMENT()
  {
    $o = Operator::ASSIGNMENT();
    $this->assertSame(Operator::ASSIGNMENT(), $o);
    $this->assertSame((string)$this->targetEnvironment->assignment, $o($this->targetEnvironment));
    $this->assertSame('assignment', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::EQUAL_TO().
   * - assert returns expected specilized 'equal to' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\EQUAL_TO()
   */
  public function testEQUAL_TO()
  {
    $o = Operator::EQUAL_TO();
    $this->assertSame(Operator::EQUAL_TO(), $o);
    $this->assertSame((string)$this->targetEnvironment->equalTo, $o($this->targetEnvironment));
    $this->assertSame(' equalTo ', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::NOT_EQUAL_TO().
   * - assert returns expected specilized 'not equal to' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\NOT_EQUAL_TO()
   */
  public function testNOT_EQUAL_TO()
  {
    $o = Operator::NOT_EQUAL_TO();
    $this->assertSame(Operator::NOT_EQUAL_TO(), $o);
    $this->assertSame((string)$this->targetEnvironment->notEqualTo, $o($this->targetEnvironment));
    $this->assertSame(' notEqualTo ', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::LESS_THAN().
   * - assert returns expected specilized 'less than' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\LESS_THAN()
   */
  public function testLESS_THAN()
  {
    $o = Operator::LESS_THAN();
    $this->assertSame(Operator::LESS_THAN(), $o);
    $this->assertSame((string)$this->targetEnvironment->lessThan, $o($this->targetEnvironment));
    $this->assertSame(' lessThan ', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::GREATER_THAN().
   * - assert returns expected specilized 'greater than' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\GREATER_THAN()
   */
  public function testGREATER_THAN()
  {
    $o = Operator::GREATER_THAN();
    $this->assertSame(Operator::GREATER_THAN(), $o);
    $this->assertSame((string)$this->targetEnvironment->greaterThan, $o($this->targetEnvironment));
    $this->assertSame(' greaterThan ', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::AND().
   * - assert returns expected specilized 'and' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\AND()
   */
  public function testAND()
  {
    $o = Operator::AND();
    $this->assertSame(Operator::AND(), $o);
    $this->assertSame((string)$this->targetEnvironment->and, $o($this->targetEnvironment));
    $this->assertSame(' and ', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::OR().
   * - assert returns expected specilized 'or' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\OR()
   */
  public function testOR()
  {
    $o = Operator::OR();
    $this->assertSame(Operator::OR(), $o);
    $this->assertSame((string)$this->targetEnvironment->or, $o($this->targetEnvironment));
    $this->assertSame(' or ', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::OPENING_PARENTHESIS().
   * - assert returns expected specilized 'opening parenthesis' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\OPENING_PARENTHESIS()
   */
  public function testOPENING_PARENTHESIS()
  {
    $o = Operator::OPENING_PARENTHESIS();
    $this->assertSame(Operator::OPENING_PARENTHESIS(), $o);
    $this->assertSame((string)$this->targetEnvironment->openingParenthesis, $o($this->targetEnvironment));
    $this->assertSame('openingParenthesis', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::CLOSING_PARENTHESIS().
   * - assert returns expected specilized 'closing parenthesis' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\CLOSING_PARENTHESIS()
   */
  public function testCLOSING_PARENTHESIS()
  {
    $o = Operator::CLOSING_PARENTHESIS();
    $this->assertSame(Operator::CLOSING_PARENTHESIS(), $o);
    $this->assertSame((string)$this->targetEnvironment->closingParenthesis, $o($this->targetEnvironment));
    $this->assertSame('closingParenthesis', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::OPENING_GROUPING_PARENTHESIS().
   * - assert returns expected specilized 'opening grouping parenthesis' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\OPENING_GROUPING_PARENTHESIS()
   */
  public function testOPENING_GROUPING_PARENTHESIS()
  {
    $o = Operator::OPENING_GROUPING_PARENTHESIS();
    $this->assertSame(Operator::OPENING_GROUPING_PARENTHESIS(), $o);
    $this->assertSame((string)$this->targetEnvironment->openingGroupingParenthesis, $o($this->targetEnvironment));
    $this->assertSame('openingGroupingParenthesis', $o($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::CLOSING_GROUPING_PARENTHESIS().
   * - assert returns expected specilized 'closing grouping parenthesis' instance of Operator.
   * - assert returns the SAME instance on every subsequent call.
   * - assert retunrs same string literal operator from target environment.
   * - assert retunrs expected string literal operator based on target environment.
   * @see \ramp\condition\Environment\CLOSING_GROUPING_PARENTHESIS()
   */
  public function testCLOSING_GROUPING_PARENTHESIS()
  {
    $o = Operator::CLOSING_GROUPING_PARENTHESIS();
    $this->assertSame(Operator::CLOSING_GROUPING_PARENTHESIS(), $o);
    $this->assertSame((string)$this->targetEnvironment->closingGroupingParenthesis, $o($this->targetEnvironment));
    $this->assertSame('closingGroupingParenthesis', $o($this->targetEnvironment));
  }
  #endregion
}
