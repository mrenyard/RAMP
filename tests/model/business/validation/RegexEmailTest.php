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
require_once '/usr/share/php/svelte/model/business/validation/RegexEmail.class.php';

use svelte\model\business\validation\FailedValidationException;
use svelte\model\business\validation\RegexEmail;

/**
 * Collection of tests for \svelte\model\business\validation\RegexEmail.
 */
class RegexEmailTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;

  /**
   * Setup
   */
  public function setUp()
  {
    $this->testObject = new RegexEmail();
  }

  /**
   * Collection of assertions for svelte\model\business\validation\RegexEmail::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\validation\ValidationRule}
   * - assert is instance of {@link \svelte\model\business\validation\RegexEmail}
   * @link svelte.model.business.validation.RegexEmail \svelte\model\business\validation\RegexEmail
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\RegexEmail', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\RegexEmail::process().
   * - assert void returned when test successful
   * - assert {@link \svelte\model\business\validation\FailedValidationException} thrown when test fails
   * @link svelte.model.business.validation.RegexEmail#method_process \svelte\model\business\validation\RegexEmail::process()
   */
  public function testProcess()
  {
    $this->assertNull($this->testObject->process('a.person@domain.co.uk'));
    try {
      $this->testObject->process('not.email.address');
    } catch (FailedValidationException $expected) {
      /*$this->assertEquals(
        'Please make sure input value is a valid email address', $expected->getMessage()
      );*/
      return;
    }
    $this->fail('An expected \svelte\model\business\validation\FailedValidationException has NOT been raised.');
  }
}
