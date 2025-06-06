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

require_once '/usr/share/php/tests/ramp/model/business/validation/FormatBasedValidationRuleTest.php';

require_once '/usr/share/php/ramp/model/business/validation/ISOTime.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockISOTime.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\ISOTime;

use tests\ramp\mocks\model\MockISOTime;

/**
 * Collection of tests for \ramp\model\business\validation\RegexEmail.
 */
class ISOTimeTest extends \tests\ramp\model\business\validation\FormatBasedValidationRuleTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->inputType = 'time';
    $this->hint = Str::set('time in the format:');
    $this->format = 'hh:mm:ss';
    $this->step = 1;
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockISOTime($this->hint);
  }
  #endregion

  /**
   * Collection of assertions for ramp\model\business\validation\ISOTime.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\ISOTime}
   * @see \ramp\model\business\validation\ISOTime
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\ISOTime', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\model\Model::__get().
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
   * Collection of assertions relating to common set of 'input element attribute'.
   * - assert expected 'attribute value' are expected defaults for data type, test scenarios, or that provided by mock rules.
   * @see \ramp\validation\ISOTime::$inputType
   * @see \ramp\validation\ISOTime::$pattern
   * @see \ramp\validation\ISOTime::$placeholder
   * @see \ramp\validation\ISOTime::$minlength
   * @see \ramp\validation\ISOTime::$maxlength
   * @see \ramp\validation\ISOTime::$min
   * @see \ramp\validation\ISOTime::$max
   * @see \ramp\validation\ISOTime::$step
   * - assert 'hint' returns concatenated combination of all ValidationRule::$errorHint/s glued together with a space.
   * @see \ramp\validation\ISOTime::$hint
   * - assert 'format' returns same string as provided at construction.
   * @see \ramp\validation\ISOTime::$format
   */
  #[\Override]
  public function testExpectedAttributeValues()
  {
    parent::testExpectedAttributeValues();
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\ISOTime::test()
   * @see \ramp\validation\ISOTime::process()
   */
  #[\Override]
  public function testProcess(
    array $badValues = ['24:45', '23-45-59'], ?array $goodValues = ['23:30', '23:45:59'], int $failPoint = 1, int $ruleCount = 1,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion

  #region New Specialist Tests
  /**
   * Additional constructor 'optional minimum value' ($min) format validity test.
   * - assert throws \InvalidArgumentException When constructor provided 'optional minimum value' NOT valid.
   *   - with message: *'The provided $min value is badly formatted!'*
   * @see \ramp\model\business\validation\ISOTime
   */
  public function testBadlyFormattedMinString()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('The provided $min value is badly formatted!');
    new MockISOTime($this->hint, Str::set('23-30'));
  }

  /**
   * Additional constructor 'optional maximum value' ($max) format validity test.
   * - assert throws \InvalidArgumentException When constructor provided 'optional maximum value' NOT valid.
   *   - with message: *'The provided $max value is badly formatted!'*
   * @see \ramp\model\business\validation\ISOTime
   */
  public function testBadlyFormattedMaxString()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('The provided $max value is badly formatted!');
    new MockISOTime($this->hint, NULL, Str::set('23-30'));
  }

  /**
   * Additional constructor 'optional minimum and maximum values' ($min/$max) illogical test.
   * - assert throws \InvalidArgumentException When constructor provided 'optional $min $max value' NOT logical.
   *   - with message: *'Illogical $min is greater than $max!'*
   * @see \ramp\model\business\validation\ISOTime
   */
  public function testMinGreaterThanMax()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Illogical $min is greater than $max!');
    new MockISOTime($this->hint, Str::set('23:30:58'), Str::set('23:30:57'));
  }

  /**
   * Additional max bound ramp\validation\ISOTime::process() and test() assertions.
   * - assert throws \ramp\model\validaton\FailedValidationException When $value outside of maximum bounds.
   *   - with message: *'Tested $value outside of $max bounds!'*
   * @see \ramp\validation\ISOTime::test()
   * @see \ramp\validation\ISOTime::process()
   */
  public function testOutsideMaxBounds()
  {
    $o = new MockISOTime($this->hint, NULL, Str::set('23:30:57'));
    $this->expectException(FailedValidationException::class);
    $this->expectExceptionMessage('Tested $value outside of $max bounds!');
    $o->process('23:30:58');
  }

  /**
   * Additional min bound ramp\validation\ISOTime::process() and test() assertions.
   * - assert throws \ramp\model\validaton\FailedValidationException When $value outside of minimum bounds.
   *   - with message: *'Tested $value outside of $max bounds!'*
   * @see \ramp\validation\ISOTime::test()
   * @see \ramp\validation\ISOTime::process()
   */
  public function testOutsideMinBounds()
  {
    $o = new MockISOTime($this->hint, Str::set('23:30:58'));
    $this->expectException(FailedValidationException::class);
    $this->expectExceptionMessage('Tested $value outside of $min bounds!');
    $o->process('23:30:57');
  }
  #endregion
}
