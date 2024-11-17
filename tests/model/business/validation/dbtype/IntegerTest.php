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

require_once '/usr/share/php/tests/ramp/model/business/validation/dbtype/DbTypeValidationTest.php';

require_once '/usr/share/php/ramp/model/business/validation/dbtype/Integer.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockDbTypeInteger.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\Integer;

use tests\ramp\mocks\model\MockDbTypeInteger;
use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\MaxlengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\Integer.
 */
class IntegerTest extends \tests\ramp\model\business\validation\dbtype\DbTypeValidationTest
{
  #region Setup
  protected function preSetup() : void
  {
    $this->specialAppendHint = '-2147483648 to 2147483647';
    $this->hint1 = Str::set('a number from ');
  }
  protected function getTestObject() : RAMPObject { return new MockDbTypeInteger($this->hint1); }
  #endregion
  
  #region Sub process template
  protected function doAttributeValueConfirmation()
  {
    $this->assertEquals($this->hint1 . $this->specialAppendHint, (string)$this->testObject->hint);
    $this->assertSame('number', (string)$this->testObject->inputType);
    $this->assertNull($this->testObject->placeholder);
    $this->assertNull($this->testObject->maxlength);
    $this->assertNull($this->testObject->pattern);
    $this->assertEquals('-2147483648', (string)$this->testObject->min);
    $this->assertEquals('2147483647', (string)$this->testObject->max);
    $this->assertEquals('1', (string)$this->testObject->step);
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\dbtype\Integer.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\dbtype\DbTypeValidation}
   * - assert is instance of {@see \ramp\model\business\validation\dbtype\Integer}
   * - assert throws \InvalidArgumentException when supplied argument 'max' exceeds 2147483647.
   * - assert throws \InvalidArgumentException when supplied argument 'min' below -2147483648.
   * - assert throws \InvalidArgumentException when supplied arguments 'min' 'max' are out of alignment.
   *   - with message: *'$max has exceded 32767 and or $min is less than -32768'*
   * @see \ramp\model\business\validation\dbtype\Integer
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\Integer', $this->testObject);
    try {
      new MockDbTypeInteger($this->hint1, 0, 2147483648);
    } catch (\InvalidArgumentException $expected) {
      $this->assertsame('$max has exceded 2147483647 and or $min is less than -2147483648', $expected->getMessage());
      try {
        new MockDbTypeInteger($this->hint1, -2147483649, 0);
      } catch (\InvalidArgumentException $expected) {
        $this->assertsame('$max has exceded 2147483647 and or $min is less than -2147483648', $expected->getMessage());
        try {
          new MockDbTypeInteger($this->hint1, 1, 0);
        } catch (\InvalidArgumentException $expected) {
          $this->assertsame('$max has exceded 2147483647 and or $min is less than -2147483648', $expected->getMessage());
          return;
        }
      }
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\ValidationRule::test()
   * @see \ramp\validation\ValidationRule::process()
   */
  public function testProcess( // upper/lower limits.
    array $badValues = [2147483648, -2147483649], ?array $goodValues = [2147483647, -2147483648], int $failPoint = 1, int $ruleCount = 1,
    $failMessage = ''
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
