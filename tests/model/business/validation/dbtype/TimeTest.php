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

require_once '/usr/share/php/ramp/model/business/validation/dbtype/Time.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockDbTypeTime.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\Time;

use tests\ramp\mocks\model\MockDbTypeTime;
use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\MaxlengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\Time.
 */
class TimeTest extends \tests\ramp\model\business\validation\dbtype\DbTypeValidationTest
{
  #region Setup
  protected function preSetup() : void
  {
    $this->hint5 = Str::set('anything NOT BadValue');
    $this->hint4 = Str::set('under 4 chars');
    $this->hint3 = Str::set('hinted AAAA');
    $this->hint2 = Str::set('not BAD');
    $this->hint1 = Str::set('within min, max and step');
  }
  protected function getTestObject() : RAMPObject {
    return new MockDbTypeTime($this->hint5,
      new PlaceholderValidationRule($this->hint4,
        new PatternValidationRule($this->hint3,
          new FailOnBadValidationRule($this->hint2,
            new MinMaxStepValidationRule($this->hint1)
          )
        )
      )
    );
  }
  #endregion

  #region Sub process template
  protected function doAttributeValueConfirmation()
  {
    $this->assertEquals(
      $this->hint1 . ' ' . $this->hint2 . ' ' . $this->hint3 . ' ' . 
      $this->hint4 . ' ' . $this->hint5,
      (string)$this->testObject->hint
    );
    $this->assertSame(MockValidationRule::$inputTypeValue, $this->testObject->inputType);
    $this->assertSame(MockValidationRule::$placeholderValue, $this->testObject->placeholder);
    $this->assertNull($this->testObject->maxlength);
    $this->assertSame(MockValidationRule::$patternValue, $this->testObject->pattern);
    $this->assertSame(MockValidationRule::$minValue, $this->testObject->min);
    $this->assertSame(MockValidationRule::$maxValue, $this->testObject->max);
    $this->assertSame(MockValidationRule::$stepValue, $this->testObject->step);
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\dbtype\Time.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\validation\DbTypeValidation}
   * - assert is instance of {@see \ramp\model\business\validation\Time}
   * @see \ramp\model\business\validation\dbtype\Time
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\Time', $this->testObject);
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
  public function testProcess( // badValue (shoud be 00:00:00).
    $badValue = '24:00', $goodValue = '23:29', $failPoint = 1, $ruleCount = 5,
    $failMessage = ''
  ) : void
  {
    parent::testProcess($badValue, $goodValue, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
