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
namespace tests\ramp\model\business\validation\dbtype;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DecimalPointNumber.class.php';

require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\DecimalPointNumber;

use tests\ramp\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\DecimalPointNumber.
 */
class DecimalPointNumberTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $point;
  private $errorMessage;

  /**
   * Setup
   */
  public function setUp() : void
  {
    $this->errorMessage = Str::set('My error message HERE!');
    $this->point = 2;
    $this->testObject = new DecimalPointNumber(
      $this->errorMessage,
      $this->point, 5
    );
  }

  /**
   * Collection of assertions for ramp\validation\dbtype\DecimalPointNumber.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\DecimalPointNumber}
   * @see \ramp\model\business\validation\dbtype\DecimalPointNumber
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\DbTypeValidation', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\DecimalPointNumber', $this->testObject);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\dbtype\DecimalPointNumber::process().
   * - assert void returned when test successful
   * - assert {@see \ramp\model\business\FailedValidationException} thrown when test fails
   * @see \ramp\model\business\validation\dbtype\DecimalPointNumber::process()
   */
  public function testTest()
  {
    $isFloat = floatval(10);
    $this->assertNull($this->testObject->process($isFloat));
    $this->assertNull($this->testObject->process(10.50));
    try {
      $this->testObject->process('10.55'); // string
    } catch (FailedValidationException $expected) {
      // $this->assertEquals((string)$this->errorMessage, $expected->getMessage());
      $this->assertEquals('', $expected->getMessage());
      try {
        $this->testObject->process(10.555); // more than 2 decimal points
      } catch (FailedValidationException $expected) {
        // $this->assertEquals((string)$this->errorMessage, $expected->getMessage());
        $this->assertEquals('', $expected->getMessage());
        return;
      }
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }
}
