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

require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/Alphanumeric.class.php';

use svelte\model\business\FailedValidationException;
use svelte\model\business\validation\Alphanumeric;

/**
 * Collection of tests for \svelte\model\business\validation\Alphanumeric.
 */
class AlphanumericTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;

  /**
   * Setup
   */
  public function setUp()
  {
    $this->testObject = new Alphanumeric();
  }

  /**
   * Collection of assertions for svelte\validation\Alphanumeric::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\validation\ValidationRule}
   * - assert is instance of {@link \svelte\model\business\validation\Alphanumeric}
   * @link svelte.model.business.validation.Alphanumeric \svelte\model\business\validation\Alphanumeric
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\Alphanumeric', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\Alphanumeric::process().
   * - assert void returned when test successful
   * - assert {@link \svelte\model\business\FailedValidationException} thrown when test fails
   * @link svelte.model.business.validation.Alphanumeric#method_process \svelte\model\business\validation\Alphanumeric::process()
   */
  public function testTest()
  {
    $this->assertNull($this->testObject->process(
      'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ1234567890'
    ));
    try {
      $this->testObject->process('Not-Alphanumeric');
    } catch (FailedValidationException $expected) {
      return;
    }
    $this->fail('An expected \svelte\model\business\FailedValidationException has NOT been raised.');
  }
}
