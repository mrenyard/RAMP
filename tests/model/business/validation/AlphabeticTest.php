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

require_once '/usr/share/php/ramp/model/business/validation/Alphabetic.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockAlphabetic.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\Alphabetic;

use tests\ramp\mocks\model\MockAlphabetic;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\LengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;
use tests\ramp\mocks\model\MockValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\Alphabetic.
 */
class AlphabeticTest extends \tests\ramp\model\business\validation\RegexValidationRuleTest
{
  #region Setup
  #[\Override]
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
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockAlphabetic($this->hint6,
      new PlaceholderValidationRule($this->hint5,
        new PatternValidationRule($this->hint4,
          new LengthValidationRule($this->hint3, $this->maxlength, NULL,
            new FailOnBadValidationRule($this->hint2,
              new MinMaxStepValidationRule($this->hint1)
            )
          )
        )
      )
    );
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\Alphabetic.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\RegexValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\Alphabetic}
   * @see \ramp\model\business\validation\Alphabetic
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\Alphabetic', $this->testObject);
  }
  #region Inherited Tests

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
  #[\Override]
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
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Collection of assertions relateing to common set of input element attribute API.
   * - assert expected 'attribute value' expected defaults for data type, test scenarios, or thet provided by mock rules in that sequance.
   * @see \ramp\validation\ValidationRule::$inputType
   * @see \ramp\validation\ValidationRule::$placeholder
   * @see \ramp\validation\ValidationRule::$minlength
   * @see \ramp\validation\ValidationRule::$maxlength
   * @see \ramp\validation\ValidationRule::$min
   * @see \ramp\validation\ValidationRule::$max
   * @see \ramp\validation\ValidationRule::$step
   * @see \ramp\validation\ValidationRule::$hint
   */
  #[\Override]
  public function testExpectedAttributeValues()
  {
    $this->assertEquals(
      $this->hint1 . ' ' . $this->hint2 . ' ' . $this->hint3 . ' ' .
      $this->hint4 . ' ' . $this->hint5 . ' ' . $this->hint6,
      (string)$this->testObject->hint
    );
    $this->assertEquals('text', (string)$this->testObject->inputType);
    $this->assertEquals(MockValidationRule::PLACEHOLDER, (string)$this->testObject->placeholder);
    $this->assertNull($this->testObject->minlength);
    $this->assertEquals($this->maxlength, $this->testObject->maxlength);
    $this->assertEquals('[a-zA-Z_\-\.\']*', (string)$this->testObject->pattern);
    $this->assertEquals(MockValidationRule::MIN, (string)$this->testObject->min);
    $this->assertEquals(MockValidationRule::MAX, (string)$this->testObject->max);
    $this->assertEquals(MockValidationRule::STEP, (string)$this->testObject->step);
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\ValidationRule::test()
   * @see \ramp\validation\ValidationRule::process()
   */
  #[\Override]
  public function testProcess(
    array $badValues = ['bad@regex'], ?array $goodValues = ['go-od_Re.gex'], int $failPoint = 1, int $ruleCount = 6,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
