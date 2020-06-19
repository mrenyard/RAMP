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
 * @author Matt Renyard (renyard.m@gmail.com)
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\validation\dbtype;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';

require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/MockDbTypeValidation.class.php';
require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\model\business\FailedValidationException;

use tests\svelte\model\business\validation\MockDbTypeValidation;
use tests\svelte\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \svelte\model\business\validation\dbtype\DbTypeValidation.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\validation\MockValidationRule}
 */
class DbTypeValidationTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $errorMessage;

  /**
   * Set-up.
   */
  public function setUp()
  {
    $this->errorMessage = Str::set('My error message HERE!');
    $this->testObject = new MockDbTypeValidation(
      new FailOnBadValidationRule(),
      $this->errorMessage
    );
    FailOnBadValidationRule::reset();
    MockDbTypeValidation::reset();
  }

  /**
   * Collection of assertions for svelte\validation\DbTypeValidation::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\validation\ValidationRule}
   * - assert is instance of {@link \svelte\validation\DbTypeValidation}
   * @link svelte.validation.DbTypeValidationTest \svelte\validation\DbTypeValidationTest
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\dbtype\DbTypeValidation', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\DbTypeValidationTest::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@link \svelte\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @link svelte.model.business.validation.DbTypeValidationTest#method_test \svelte\model\business\validation\DbTypeValidationTest::test()
   * @link svelte.model.business.validation.DbTypeValidationTest#method_process \svelte\model\business\validation\DbTypeValidationTest::process()
   */
  public function testProcess()
  {
    $this->assertNull($this->testObject->process('GOOD'));
    $this->assertSame(1, MockDbTypeValidation::$testCallCount);
    $this->assertSame(1, FailOnBadValidationRule::$testCallCount);
    try {
      $this->testObject->process('BAD');
    } catch (FailedValidationException $expected) {
      $this->assertSame((string)$this->errorMessage, $expected->getMessage());
      return;
    }
    $this->fail('An expected \FailedValidationException has NOT been raised.');
  }
}
