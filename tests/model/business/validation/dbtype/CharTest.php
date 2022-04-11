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
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\validation\dbtype;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Char.class.php';

require_once '/usr/share/php/tests/ramp/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use ramp\core\Str;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\dbtype\Char;

use tests\ramp\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\Char.
 */
class CharTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $maxLength;
  private $errorMessage;

  /**
   * Setup
   */
  public function setUp() : void
  {
    $this->length = 10;
    $this->errorMessage = Str::set('My error message HERE!');
    $this->testObject = new Char(
      $this->length,
      new FailOnBadValidationRule(),
      $this->errorMessage
    );
  }

  /**
   * Collection of assertions for ramp\validation\dbtype\Char::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@link \ramp\model\business\validation\Char}
   * @link ramp.model.business.validation.dbtype.Char \ramp\model\business\validation\dbtype\Char
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\DbTypeValidation', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\Char', $this->testObject);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\dbtype\Char::process().
   * - assert void returned when test successful
   * - assert {@link \ramp\model\business\FailedValidationException} thrown when test fails
   * @link ramp.model.business.validation.dbtype.Char#method_process \ramp\model\business\validation\dbtype\Char::process()
   */
  public function testTest()
  {
    $this->assertNull($this->testObject->process('exactlyten'));
    try {
      $this->testObject->process('not10');
    } catch (FailedValidationException $expected) {
      $this->assertEquals((string)$this->errorMessage, $expected->getMessage());
      return;
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }
}
