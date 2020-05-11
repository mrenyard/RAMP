<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\validation;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';

require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/MockValidationRule.class.php';
require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/FirstValidationRule.class.php';
require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/SecondValidationRule.class.php';
require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/ThirdValidationRule.class.php';
require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use svelte\core\SvelteObject;
use svelte\model\business\validation\FailedValidationException;

use tests\svelte\model\business\validation\MockValidationRule;
use tests\svelte\model\business\validation\FirstValidationRule;
use tests\svelte\model\business\validation\SecondValidationRule;
use tests\svelte\model\business\validation\ThirdValidationRule;
use tests\svelte\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \svelte\validation\ValidationRule.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\validation\MockValidationRule}
 */
class ValidationRuleTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for svelte\validation\ValidationRule::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\validation\ValidationRule}
   * @link svelte.validation.ValidationRule \svelte\validation\ValidationRule
   */
  public function test__Construct()
  {
    $testObject = new MockValidationRule();
    $this->assertInstanceOf('svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $testObject);
  }

  /**
   * Collection of assertions for svelte\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@link \svelte\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @link svelte.validation.ValidationRule#method_test \svelte\validation\ValidationRule::test()
   * @link svelte.validation.ValidationRule#method_process \svelte\validation\ValidationRule::process()
   */
  public function testProcess()
  {
    MockValidationRule::reset();
    $testObject = new MockValidationRule();
    $testObject->process('GOOD');
    $this->assertSame(1, MockValidationRule::$testCallCount);

    MockValidationRule::reset();
    $testObject = new MockValidationRule(
      new MockValidationRule(
        new MockValidationRule(
          new MockValidationRule(
            new MockValidationRule()
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
      new SecondValidationRule(
        new ThirdValidationRule()
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
      new SecondValidationRule(
        new ThirdValidationRule(
          new FailOnBadValidationRule()
        )
      )
    );
    try {
      $testObject->process('BAD');
    } catch (FailedValidationException $expected) {
      $this->assertSame(
        'FailOnBadValidationRule has been given the value BAD', $expected->getMessage()
      );
      $this->assertSame(1, FirstValidationRule::$testCallCount);
      $this->assertSame(1, SecondValidationRule::$testCallCount);
      $this->assertSame(1, ThirdValidationRule::$testCallCount);
      $this->assertSame(1, FailOnBadValidationRule::$testCallCount);

      FirstValidationRule::reset();
      SecondValidationRule::reset();
      ThirdValidationRule::reset();
      FailOnBadValidationRule::reset();
      $testObject = new FirstValidationRule(
        new SecondValidationRule(
          new ThirdValidationRule(
            new FailOnBadValidationRule()
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
