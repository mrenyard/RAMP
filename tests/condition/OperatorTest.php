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
 */
namespace tests\ramp\condition;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/OperatorTest/ConcreteEnvironment.class.php';

use \ramp\condition\Operator;

use tests\ramp\condition\mocks\OperatorTest\ConcreteEnvironment;

/**
 * Collection of tests for \ramp\condition\Operator.
 *
 * COLLABORATORS
 * - {@link \tests\ramp\condition\mocks\OperatorTest\ConcreteEnvironment}
 */
class OperatorTest extends \PHPUnit\Framework\TestCase
{
  private $targetEnvironment;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->targetEnvironment = ConcreteEnvironment::getInstance();
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::getInstance().
   * - assert __constructor inaccessible (private).
   * @link ramp.condition.Environment#method_getInstance ramp\condition\Environment\getInstance()
   */
  public function testGetInstance()
  {
    try {
      $testObject = new Operator();
    } catch (\Error $expected) {
      $this->assertSame(0, $expected->getCode());
      return;
    }
    $this->fail('An expected \Error has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::MEMBER_ACCESS().
   * - assert returns expected specilized 'member access' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_MEMBER_ACCESS ramp\condition\Environment\MEMBER_ACCESS()
   */
  public function testMEMBER_ACCESS()
  {
    $testInstance = Operator::MEMBER_ACCESS();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::MEMBER_ACCESS(), $testInstance);

    $this->assertSame('memberAccess', $testInstance($this->targetEnvironment));
  }


  /**
   * Collection of assertions for \ramp\condition\Operator::ASSIGNMENT().
   * - assert returns expected specilized 'assignment' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_ASSIGNMENT ramp\condition\Environment\ASSIGNMENT()
   */
  public function testASSIGNMENT()
  {
    $testInstance = Operator::ASSIGNMENT();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::ASSIGNMENT(), $testInstance);

    $this->assertSame('assignment', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::EQUAL_TO().
   * - assert returns expected specilized 'equal to' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_EQUAL_TO ramp\condition\Environment\EQUAL_TO()
   */
  public function testEQUAL_TO()
  {
    $testInstance = Operator::EQUAL_TO();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::EQUAL_TO(), $testInstance);

    $this->assertSame(' equalTo ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::NOT_EQUAL_TO().
   * - assert returns expected specilized 'not equal to' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_NOT_EQUAL_TO ramp\condition\Environment\NOT_EQUAL_TO()
   */
  public function testNOT_EQUAL_TO()
  {
    $testInstance = Operator::NOT_EQUAL_TO();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::NOT_EQUAL_TO(), $testInstance);

    $this->assertSame(' notEqualTo ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::LESS_THAN().
   * - assert returns expected specilized 'less than' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_LESS_THAN ramp\condition\Environment\LESS_THAN()
   */
  public function testLESS_THAN()
  {
    $testInstance = Operator::LESS_THAN();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::LESS_THAN(), $testInstance);

    $this->assertSame(' lessThan ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::GREATER_THAN().
   * - assert returns expected specilized 'greater than' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_GREATER_THAN ramp\condition\Environment\GREATER_THAN()
   */
  public function testGREATER_THAN()
  {
    $testInstance = Operator::GREATER_THAN();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::GREATER_THAN(), $testInstance);

    $this->assertSame(' greaterThan ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::AND().
   * - assert returns expected specilized 'and' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_AND ramp\condition\Environment\AND()
   */
  public function testAND()
  {
    $testInstance = Operator::AND();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::AND(), $testInstance);

    $this->assertSame(' and ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::OR().
   * - assert returns expected specilized 'or' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_OR ramp\condition\Environment\OR()
   */
  public function testOR()
  {
    $testInstance = Operator::OR();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::OR(), $testInstance);

    $this->assertSame(' or ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::OPENING_PARENTHESIS().
   * - assert returns expected specilized 'opening parenthesis' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_OPENING_PARENTHESIS ramp\condition\Environment\OPENING_PARENTHESIS()
   */
  public function testOPENING_PARENTHESIS()
  {
    $testInstance = Operator::OPENING_PARENTHESIS();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::OPENING_PARENTHESIS(), $testInstance);

    $this->assertSame('openingParenthesis', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \ramp\condition\Operator::CLOSING_PARENTHESIS().
   * - assert returns expected specilized 'closing parenthesis' instance of Operator.
   * - assert is instance of {@link \ramp\condition\Operator}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link ramp.condition.Environment#method_CLOSING_PARENTHESIS ramp\condition\Environment\CLOSING_PARENTHESIS()
   */
  public function testCLOSING_PARENTHESIS()
  {
    $testInstance = Operator::CLOSING_PARENTHESIS();
    $this->assertInstanceOf('\ramp\condition\Operator', $testInstance);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testInstance);
    $this->assertSame(Operator::CLOSING_PARENTHESIS(), $testInstance);

    $this->assertSame('closingParenthesis', $testInstance($this->targetEnvironment));
  }
}
