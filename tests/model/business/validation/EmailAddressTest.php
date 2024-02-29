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
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/EmailAddress.class.php';

use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\EmailAddress;

/**
 * Collection of tests for \ramp\model\business\validation\RegexEmail.
 */
class EmailAddressTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;

  /**
   * Setup
   */
  public function setUp() : void
  {
    $this->testObject = new EmailAddress(Str::set('string with a maximun character length of '));
  }

  /**
   * Collection of assertions for ramp\model\business\validation\RegexEmail::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\RegexEmail}
   * @see ramp.model.business.validation.RegexEmail \ramp\model\business\validation\RegexEmail
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\EmailAddress', $this->testObject);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\RegexEmail::process().
   * - assert void returned when test successful
   * - assert {@see \ramp\model\business\FailedValidationException} thrown when test fails
   * @see ramp.model.business.validation.RegexEmail#method_process \ramp\model\business\validation\RegexEmail::process()
   */
  public function testProcess()
  {
    $this->assertNull($this->testObject->process('a.person@gmail.com'));
    try {
      $this->testObject->process('not.email.address');
    } catch (FailedValidationException $expected) {
      // try {
      //   $this->testObject->process('a.person@baddomain.co.uk');
      // } catch (FailedValidationException $expected) {
        return;
      // }
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }
}
