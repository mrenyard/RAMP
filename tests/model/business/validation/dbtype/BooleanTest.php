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
namespace tests\svelte\model\business\dbtype\validation;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/Boolean.class.php';

require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use svelte\core\Str;
use svelte\model\business\FailedValidationException;
use svelte\model\business\validation\dbtype\Boolean;

use tests\svelte\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \svelte\model\business\validation\dbtype\Boolean.
 */
class BooleanTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $maxLength;
  private $errorMessage;

  /**
   * Setup
   */
  public function setUp()
  {
    $this->errorMessage = Str::set('My error message HERE!');
    $this->testObject = new Boolean($this->errorMessage);
  }

  /**
   * Collection of assertions for svelte\validation\dbtype\Boolean::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\validation\ValidationRule}
   * - assert is instance of {@link \svelte\model\business\validation\Boolean}
   * @link svelte.model.business.validation.dbtype.Boolean \svelte\model\business\validation\dbtype\Boolean
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\dbtype\DbTypeValidation', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\dbtype\Boolean', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\dbtype\Boolean::process().
   * - assert void returned when test successful
   * - assert {@link \svelte\model\business\FailedValidationException} thrown when test fails
   * @link svelte.model.business.validation.dbtype.Boolean#method_process \svelte\model\business\validation\dbtype\Boolean::process()
   */
  public function testTest()
  {
    $this->assertNull($this->testObject->process(FALSE));
    $this->assertNull($this->testObject->process(TRUE));
    try {
      $this->testObject->process(0);
    } catch (FailedValidationException $expected) {
      $this->assertEquals((string)$this->errorMessage, $expected->getMessage());
      try {
        $this->testObject->process('TRUE');
      } catch (FailedValidationException $expected) {
        $this->assertEquals((string)$this->errorMessage, $expected->getMessage());
        return;
      }
    }
    $this->fail('An expected \svelte\model\business\FailedValidationException has NOT been raised.');
  }
}