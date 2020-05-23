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

require_once '/usr/share/php/svelte/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/Alphanumeric.class.php';

use svelte\model\business\validation\FailedValidationException;
use svelte\model\business\validation\VarChar;

/**
 * Collection of tests for \svelte\model\business\validation\Alphanumeric.
 */
class VarCharTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $maxLength;

  /**
   * Setup
   */
  public function setUp()
  {
    $this->maxLength = 10;
    $this->testObject = new VarChar($this->maxLength);
  }

  /**
   * Collection of assertions for svelte\validation\VarChar::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\validation\ValidationRule}
   * - assert is instance of {@link \svelte\model\business\validation\VarChar}
   * @link svelte.model.business.validation.VarChar \svelte\model\business\validation\VarChar
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\VarChar', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\VarChar::process().
   * - assert void returned when test successful
   * - assert {@link \svelte\model\business\validation\FailedValidationException} thrown when test fails
   * @link svelte.model.business.validation.VarChar#method_process \svelte\model\business\validation\VarChar::process()
   */
  public function testTest()
  {
    $this->assertNull($this->testObject->process('aEoOp245&%'));
    try {
      $this->testObject->process('Not-Alphanumeric');
    } catch (FailedValidationException $expected) {
      $this->assertEquals(
        'Please make sure input value is a string less than ' . $this->maxLength . ' characters!',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \svelte\model\business\validation\FailedValidationException has NOT been raised.');
  }
}
