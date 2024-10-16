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
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\validation;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';

require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/MockValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/FirstValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/SecondValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/ThirdValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use ramp\core\Str;
use ramp\core\RAMPObject;
use ramp\model\business\validation\FailedValidationException;

use tests\ramp\model\business\validation\MockValidationRule;
use tests\ramp\model\business\validation\FirstValidationRule;
use tests\ramp\model\business\validation\SecondValidationRule;
use tests\ramp\model\business\validation\ThirdValidationRule;
use tests\ramp\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\validation\ValidationRule.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\validation\MockValidationRule}
 */
class ValidationRuleTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for ramp\validation\ValidationRule.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\validation\ValidationRule}
   * @see \ramp\validation\ValidationRule
   */
  public function test__Construct()
  {
    $testObject = new MockValidationRule(Str::set('formate error message/hint'));
    $this->assertInstanceOf('ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('ramp\model\business\validation\ValidationRule', $testObject);
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\ValidationRule::test()
   * @see \ramp\validation\ValidationRule::process()
   */
  public function testProcess()
  {
    MockValidationRule::reset();
    $testObject = new MockValidationRule(Str::set('formate error message/hint'));
    $testObject->process('GOOD');
    $this->assertSame(1, MockValidationRule::$testCallCount);
    MockValidationRule::reset();
    $testObject = new MockValidationRule(
      Str::set('formate error message/hint'),
      new MockValidationRule(
        Str::set('formate error message/hint'),
        new MockValidationRule(
          Str::set('formate error message/hint'),
          new MockValidationRule(
            Str::set('formate error message/hint'),
            new MockValidationRule(
              Str::set('formate error message/hint')
            )
          )
        )
      )
    );
    $testObject->process('GOOD');
    $this->assertSame(5, MockValidationRule::$testCallCount);
    FirstValidationRule::reset();
    SecondValidationRule::reset();
    ThirdValidationRule::reset();
    $testObject = new FirstValidationRule(
      Str::set('formate error message/hint'),
      new SecondValidationRule(
        Str::set('formate error message/hint'),
        new ThirdValidationRule(
          Str::set('formate error message/hint')
        )
      )
    );
    $testObject->process('GOOD');
    $this->assertSame(1, FirstValidationRule::$testCallCount);
    $this->assertSame(1, SecondValidationRule::$testCallCount);
    $this->assertSame(1, ThirdValidationRule::$testCallCount);
    FirstValidationRule::reset();
    SecondValidationRule::reset();
    ThirdValidationRule::reset();
    FailOnBadValidationRule::reset();
    $testObject = new FirstValidationRule(
      Str::set('formate error message/hint'),
      new SecondValidationRule(
        Str::set('formate error message/hint'),
        new ThirdValidationRule(
          Str::set('formate error message/hint'),
          new FailOnBadValidationRule(
            Str::set('formate error message/hint')
          )
        )
      )
    );
    try {
      $testObject->process('BAD');
    } catch (FailedValidationException $expected) {
      // $this->assertSame(
      //   'formate error message/hint', $expected->getMessage()
      // );
      $this->assertEquals('', $expected->getMessage());
      $this->assertSame(1, FirstValidationRule::$testCallCount);
      $this->assertSame(1, SecondValidationRule::$testCallCount);
      $this->assertSame(1, ThirdValidationRule::$testCallCount);
      $this->assertSame(1, FailOnBadValidationRule::$testCallCount);
      FirstValidationRule::reset();
      SecondValidationRule::reset();
      ThirdValidationRule::reset();
      FailOnBadValidationRule::reset();
      $testObject = new FirstValidationRule(
        Str::set('formate error message/hint'),
        new SecondValidationRule(
          Str::set('formate error message/hint'),
          new ThirdValidationRule(
            Str::set('formate error message/hint'),
            new FailOnBadValidationRule(
              Str::set('formate error message/hint')
            )
          )
        )
      );
      $testObject->process('GOOD');
      $this->assertSame(1, FirstValidationRule::$testCallCount);
      $this->assertSame(1, SecondValidationRule::$testCallCount);
      $this->assertSame(1, ThirdValidationRule::$testCallCount);
      $this->assertSame(1, FailOnBadValidationRule::$testCallCount);
      return;
    }
    $this->fail('An expected \FailedValidationException has NOT been raised.');
  }
}
