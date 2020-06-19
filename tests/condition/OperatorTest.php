<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
namespace tests\svelte\condition;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/condition/iEnvironment.class.php';
require_once '/usr/share/php/svelte/condition/Environment.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';

require_once '/usr/share/php/tests/svelte/condition/mocks/OperatorTest/ConcreteEnvironment.class.php';

use \svelte\condition\Operator;

use tests\svelte\condition\mocks\OperatorTest\ConcreteEnvironment;

/**
 * Collection of tests for \svelte\condition\Operator.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\condition\mocks\OperatorTest\ConcreteEnvironment}
 */
class OperatorTest extends \PHPUnit\Framework\TestCase
{
  private $targetEnvironment;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    $this->targetEnvironment = ConcreteEnvironment::getInstance();
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::getInstance().
   * - assert __constructor inaccessible (private).
   * @link svelte.condition.Environment#method_getInstance svelte\condition\Environment\getInstance()
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
   * Collection of assertions for \svelte\condition\Operator::MEMBER_ACCESS().
   * - assert returns expected specilized 'member access' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_MEMBER_ACCESS svelte\condition\Environment\MEMBER_ACCESS()
   */
  public function testMEMBER_ACCESS()
  {
    $testInstance = Operator::MEMBER_ACCESS();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::MEMBER_ACCESS(), $testInstance);

    $this->assertSame('memberAccess', $testInstance($this->targetEnvironment));
  }


  /**
   * Collection of assertions for \svelte\condition\Operator::ASSIGNMENT().
   * - assert returns expected specilized 'assignment' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_ASSIGNMENT svelte\condition\Environment\ASSIGNMENT()
   */
  public function testASSIGNMENT()
  {
    $testInstance = Operator::ASSIGNMENT();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::ASSIGNMENT(), $testInstance);

    $this->assertSame('assignment', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::EQUAL_TO().
   * - assert returns expected specilized 'equal to' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_EQUAL_TO svelte\condition\Environment\EQUAL_TO()
   */
  public function testEQUAL_TO()
  {
    $testInstance = Operator::EQUAL_TO();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::EQUAL_TO(), $testInstance);

    $this->assertSame(' equalTo ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::NOT_EQUAL_TO().
   * - assert returns expected specilized 'not equal to' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_NOT_EQUAL_TO svelte\condition\Environment\NOT_EQUAL_TO()
   */
  public function testNOT_EQUAL_TO()
  {
    $testInstance = Operator::NOT_EQUAL_TO();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::NOT_EQUAL_TO(), $testInstance);

    $this->assertSame(' notEqualTo ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::LESS_THAN().
   * - assert returns expected specilized 'less than' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_LESS_THAN svelte\condition\Environment\LESS_THAN()
   */
  public function testLESS_THAN()
  {
    $testInstance = Operator::LESS_THAN();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::LESS_THAN(), $testInstance);

    $this->assertSame(' lessThan ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::GREATER_THAN().
   * - assert returns expected specilized 'greater than' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_GREATER_THAN svelte\condition\Environment\GREATER_THAN()
   */
  public function testGREATER_THAN()
  {
    $testInstance = Operator::GREATER_THAN();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::GREATER_THAN(), $testInstance);

    $this->assertSame(' greaterThan ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::AND().
   * - assert returns expected specilized 'and' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_AND svelte\condition\Environment\AND()
   */
  public function testAND()
  {
    $testInstance = Operator::AND();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::AND(), $testInstance);

    $this->assertSame(' and ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::OR().
   * - assert returns expected specilized 'or' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_OR svelte\condition\Environment\OR()
   */
  public function testOR()
  {
    $testInstance = Operator::OR();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::OR(), $testInstance);

    $this->assertSame(' or ', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::OPENING_PARENTHESIS().
   * - assert returns expected specilized 'opening parenthesis' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_OPENING_PARENTHESIS svelte\condition\Environment\OPENING_PARENTHESIS()
   */
  public function testOPENING_PARENTHESIS()
  {
    $testInstance = Operator::OPENING_PARENTHESIS();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::OPENING_PARENTHESIS(), $testInstance);

    $this->assertSame('openingParenthesis', $testInstance($this->targetEnvironment));
  }

  /**
   * Collection of assertions for \svelte\condition\Operator::CLOSING_PARENTHESIS().
   * - assert returns expected specilized 'closing parenthesis' instance of Operator.
   * - assert is instance of {@link \svelte\condition\Operator}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert returns the SAME instance on every subsequent call.
   * @link svelte.condition.Environment#method_CLOSING_PARENTHESIS svelte\condition\Environment\CLOSING_PARENTHESIS()
   */
  public function testCLOSING_PARENTHESIS()
  {
    $testInstance = Operator::CLOSING_PARENTHESIS();
    $this->assertInstanceOf('\svelte\condition\Operator', $testInstance);
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testInstance);
    $this->assertSame(Operator::CLOSING_PARENTHESIS(), $testInstance);

    $this->assertSame('closingParenthesis', $testInstance($this->targetEnvironment));
  }
}
