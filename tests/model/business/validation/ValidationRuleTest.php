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

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/SpecialistValidationRule.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/PlaceholderValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/PatternValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/LengthValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MinMaxStepValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/FailOnBadValidationRule.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;

use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\LengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\ValidationRule.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\model\MockValidationRule}
 */
class ValidationRuleTest extends \tests\ramp\core\ObjectTest
{
  protected $maxlength;
  protected $minlength;
  protected $specialAppendHint;
  protected $hint5;
  protected $hint4;
  protected $hint3;
  protected $hint2;
  protected $hint1;

  #region Setup
  protected function preSetup() : void
  {
    $this->maxlength = 4;
    $this->minlength = 3;
    $this->hint6 = Str::set('part six');
    $this->hint5 = Str::set('part five');
    $this->hint4 = Str::set('part four');
    $this->hint3 = Str::set('part three');
    $this->hint2 = Str::set('part two');
    $this->hint1 = Str::set('part one');
  }
  protected function getTestObject() : RAMPObject {
    return new MockValidationRule($this->hint6,
      new PlaceholderValidationRule($this->hint5,
        new PatternValidationRule($this->hint4,
          new LengthValidationRule($this->hint3, $this->maxlength, $this->minlength,
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
   * Collection of assertions for \ramp\model\business\validation\ValidationRule.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * @see \ramp\model\business\validation\ValidationRule
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\ValidationRule', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\core\RAMPObject::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\core\RAMPObject::__get()
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
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert returns empty string literal.
   * @see ramp\core\RAMPObject::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }
  #endregion
  
  #region New Specialist Tests
  /**
   * Collection of assertions relateing to common set of input element attribute API.
   * - assert hint equal to the component parts of each rules errorHint value concatenated with spaces between. 
   * - assert expected 'attribute value' expected defaults for data type, test scenarios, or thet provided by mock rules in that sequance.
   * @see \ramp\model\business\validation\ValidationRule::hint
   * @see \ramp\model\business\validation\ValidationRule::inputType
   * @see \ramp\model\business\validation\ValidationRule::placeholder
   * @see \ramp\model\business\validation\ValidationRule::minlength
   * @see \ramp\model\business\validation\ValidationRule::maxlength
   * @see \ramp\model\business\validation\ValidationRule::min
   * @see \ramp\model\business\validation\ValidationRule::max
   * @see \ramp\model\business\validation\ValidationRule::step
   */
  public function testExpectedAttributeValues()
  {
    $this->assertEquals(
      $this->hint1 . ' ' . $this->hint2 . ' ' . $this->hint3 . ' ' .
      $this->hint4 . ' ' . $this->hint5 . ' ' . $this->hint6,
      (string)$this->testObject->hint
    );
    $this->assertEquals('text', (string)$this->testObject->inputType);
    $this->assertEquals(MockValidationRule::PLACEHOLDER, (string)$this->testObject->placeholder);
    $this->assertSame($this->minlength, $this->testObject->minlength);
    $this->assertSame($this->maxlength, $this->testObject->maxlength);
    $this->assertEquals(MockValidationRule::PATTERN, (string)$this->testObject->pattern);
    $this->assertEquals(MockValidationRule::MIN, (string)$this->testObject->min);
    $this->assertEquals(MockValidationRule::MAX, (string)$this->testObject->max);
    $this->assertEquals(MockValidationRule::STEP, (string)$this->testObject->step);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of successful tests.
   * - assert {@see ramp\model\business\validation\FailedValidationException} bubbles up when thrown at given test (failPoint).
   * @see ramp\model\business\validation\ValidationRule::test()
   * @see ramp\model\business\validation\ValidationRule::process()
   */
  public function testProcess(
    array $badValues = ['BAD'], ?array $goodValues = ['GOOD'], int $failPoint = 5, int $ruleCount = 6,
    $failMessage = 'FailOnBadValidationRule has been given the value BAD'
  ) : void
  {
    MockValidationRule::reset();
    $f = 0;
    foreach ($badValues as $badValue) {
      MockValidationRule::reset();
      try {
        $this->testObject->process($badValue);
      } catch (FailedValidationException $expected) { $f++;
        $this->assertEquals($failMessage, $expected->getMessage());
        $this->assertSame($failPoint, MockValidationRule::$testCallCount);
      }
    }
    if ($f != count($badValues)) { $this->fail('An expected \FailedValidationException has NOT been raised.'); }
    foreach ($goodValues as $goodValue) {
      MockValidationRule::reset();
      $this->testObject->process($goodValue);
      $this->assertSame($ruleCount, MockValidationRule::$testCallCount);
    }
  }
  #endregion
}
