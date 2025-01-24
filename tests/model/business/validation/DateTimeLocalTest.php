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

require_once '/usr/share/php/ramp/model/business/validation/DateTimeLocal.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockDateTimeLocal.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\DateTimeLocal;

use tests\ramp\mocks\model\MockDateTimeLocal;

/**
 * Collection of tests for \ramp\model\business\validation\RegexEmail.
 */
class DateTimeLocalTest extends \tests\ramp\model\business\validation\FormatBasedValidationRuleTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->inputType = 'datetime-local';
    $this->hint = Str::set('date and time in the format: ');
    $this->format = 'yyyy-mm-ddThh:mm:ss';
    $this->step = 1;
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockDateTimeLocal($this->hint);
  }
  #endregion

  /**
   * Collection of assertions for ramp\model\business\validation\DateTimeLocal.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\DateTimeLocal}
   * @see \ramp\model\business\validation\DateTimeLocal
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\DateTimeLocal', $this->testObject);
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
   * Collection of assertions relating to common set of 'input element attribute'.
   * - assert expected 'attribute value' are expected defaults for data type, test scenarios, or that provided by mock rules.
   * @see \ramp\validation\DateTimeLocal::$inputType
   * @see \ramp\validation\DateTimeLocal::$pattern
   * @see \ramp\validation\DateTimeLocal::$placeholder
   * @see \ramp\validation\DateTimeLocal::$minlength
   * @see \ramp\validation\DateTimeLocal::$maxlength
   * @see \ramp\validation\DateTimeLocal::$min
   * @see \ramp\validation\DateTimeLocal::$max
   * @see \ramp\validation\DateTimeLocal::$step
   * - assert 'hint' returns concatenated combination of all ValidationRule::$errorHint/s glued together with a space.
   * @see \ramp\validation\DateTimeLocal::$hint
   * - assert 'format' returns same string as provided at construction.
   * @see \ramp\validation\DateTimeLocal::$format
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
   * @see \ramp\validation\DateTimeLocal::test()
   * @see \ramp\validation\DateTimeLocal::process()
   */
  #[\Override]
  public function testProcess(
    array $badValues = ['2024-05-06T24:00:00', '2024/05/06T23-30-55'],
    ?array $goodValues = ['2024-05-06T23:30', '2024-05-06T23:30:55'],
    int $failPoint = 1, int $ruleCount = 1,
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
   * @see \ramp\model\business\validation\DateTimeLocal
   */
  public function testBadlyFormattedMinString()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('The provided $min value is badly formatted!');
    new MockDateTimeLocal($this->hint, Str::set('2024/05/06T23-30-55'));
  }

  /**
   * Additional constructor 'optional maximum value' ($max) format validity test.
   * - assert throws \InvalidArgumentException When constructor provided 'optional maximum value' NOT valid.
   *   - with message: *'The provided $max value is badly formatted!'*
   * @see \ramp\model\business\validation\DateTimeLocal
   */
  public function testBadlyFormattedMaxString()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('The provided $max value is badly formatted!');
    new MockDateTimeLocal($this->hint, NULL, Str::set('2024/05/06T23-30-55'));
  }

  /**
   * Additional constructor 'optional minimum and maximum values' ($min/$max) illogical test.
   * - assert throws \InvalidArgumentException When constructor provided 'optional $min $max value' NOT logical.
   *   - with message: *'Illogical $min is greater than $max!'*
   * @see \ramp\model\business\validation\DateTimeLocal
   */
  public function testMinGreaterThanMax()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Illogical $min is greater than $max!');
    new MockDateTimeLocal($this->hint, Str::set('2024-05-06T23:30:58'), Str::set('2024-05-06T23:30:57'));
  }

  /**
   * Additional max bound ramp\validation\DateTimeLocal::process() and test() assertions.
   * - assert throws \ramp\model\validaton\FailedValidationException When $value outside of maximum bounds.
   *   - with message: *'Tested $value outside of $max bounds!'*
   * @see \ramp\validation\DateTimeLocal::test()
   * @see \ramp\validation\DateTimeLocal::process()
   */
  public function testOutsideMaxBounds()
  {
    $o = new MockDateTimeLocal($this->hint, NULL, Str::set('2024-05-06T23:30:57'));
    $this->expectException(FailedValidationException::class);
    $this->expectExceptionMessage('Tested $value outside of $max bounds!');
    $o->process('2024-05-06T23:30:58');
  }

  /**
   * Additional min bound ramp\validation\DateTimeLocal::process() and test() assertions.
   * - assert throws \ramp\model\validaton\FailedValidationException When $value outside of minimum bounds.
   *   - with message: *'Tested $value outside of $max bounds!'*
   * @see \ramp\validation\DateTimeLocal::test()
   * @see \ramp\validation\DateTimeLocal::process()
   */
  public function testOutsideMinBounds()
  {
    $o = new MockDateTimeLocal($this->hint, Str::set('2024-05-06T23:30:58'));
    $this->expectException(FailedValidationException::class);
    $this->expectExceptionMessage('Tested $value outside of $min bounds!');
    $o->process('2024-05-06T23:30:57');
  }
  #endregion
}
