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

require_once '/usr/share/php/tests/ramp/model/business/validation/RegexValidationRuleTest.php';

require_once '/usr/share/php/ramp/model/business/validation/AlphabeticStrict.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockAlphabeticStrict.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\AlphabeticStrict;

use tests\ramp\mocks\model\MockAlphabeticStrict;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\MaxlengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;
use tests\ramp\mocks\model\MockValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\AlphabeticStrict.
 */
class AlphabeticStrictTest extends \tests\ramp\model\business\validation\RegexValidationRuleTest
{
  #region Setup
  protected function preSetup() : void
  {
    $this->maxlength = 10;
    $this->hint6 = Str::set('part six');
    $this->hint5 = Str::set('part five');
    $this->hint4 = Str::set('part four');
    $this->hint3 = Str::set('part three');
    $this->hint2 = Str::set('part two');
    $this->hint1 = Str::set('part one');
  }
  protected function getTestObject() : RAMPObject {
    return new MockAlphabeticStrict($this->hint6,
      new PlaceholderValidationRule($this->hint5,
        new PatternValidationRule($this->hint4,
          new MaxlengthValidationRule($this->hint3, $this->maxlength,
            new FailOnBadValidationRule($this->hint2,
              new MinMaxStepValidationRule($this->hint1)
            )
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
      $this->hint4 . ' ' . $this->hint5 . ' ' . $this->hint6,
      (string)$this->testObject->hint
    );
    $this->assertSame(MockValidationRule::$inputTypeValue, $this->testObject->inputType);
    $this->assertSame(MockValidationRule::$placeholderValue, $this->testObject->placeholder);
    $this->assertSame($this->maxlength, $this->testObject->maxlength);
    $this->assertSame('[a-zA-Z]*', (string)$this->testObject->pattern);
    $this->assertSame(MockValidationRule::$minValue, $this->testObject->min);
    $this->assertSame(MockValidationRule::$maxValue, $this->testObject->max);
    $this->assertSame(MockValidationRule::$stepValue, $this->testObject->step);
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\AlphabeticStrict.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\RegexValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\AlphabeticStrict}
   * @see \ramp\model\business\validation\AlphabeticStrict
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\AlphabeticStrict', $this->testObject);
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
  public function testProcess(
    array $badValues = ['bad@regex'], ?array $goodValues = ['goodRegex'], int $failPoint = 1, int $ruleCount = 6,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
