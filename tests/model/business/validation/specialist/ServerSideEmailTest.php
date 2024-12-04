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
namespace tests\ramp\model\business\validation\specialist;

require_once '/usr/share/php/tests/ramp/model/business/validation/specialist/SpecialistValidationRuleTest.php';

require_once '/usr/share/php/ramp/model/business/validation/specialist/ServerSideEmail.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockServerSideEmail.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;

use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\MockServerSideEmail;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\LengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\validation\ValidationRule.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\validation\MockValidationRule}
 */
class ServerSideEmailTest extends \tests\ramp\model\business\validation\specialist\SpecialistValidationRuleTest
{
  #region Setup
  protected function preSetup() : void
  {
    // $this->maxlength = 4;
    // $this->minlength = 3;
    // $this->hint6 = Str::set('part six');
    // $this->hint5 = Str::set('part five');
    // $this->hint4 = Str::set('part four');
    // $this->hint3 = Str::set('part three');
    // $this->hint2 = Str::set('part two');
    // $this->hint1 = Str::set('part one');
  }
  protected function getTestObject() : RAMPObject {
    return new MockServerSideEmail(
      // $this->hint6,
      // new PlaceholderValidationRule($this->hint5,
      //   new PatternValidationRule($this->hint4,
      //     new LengthValidationRule($this->hint3, $this->maxlength, $this->minlength,
      //       new FailOnBadValidationRule($this->hint2,
      //         new MinMaxStepValidationRule($this->hint1)
      //       )
      //     )
      //   )
      // )
    );
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\ValidationRule.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * @see \ramp\validation\ValidationRule
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\specialist\SpecialistValidationRule', $this->testObject);
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
   * - assert hint equal to the component parts of each rules errorHint value concatenated with spaces between. 
   * - assert expected 'attribute value' expected defaults for data type, test scenarios, or thet provided by mock rules in that sequance.
   * @see \ramp\validation\ValidationRule::hint
   * @see \ramp\validation\ValidationRule::inputType
   * @see \ramp\validation\ValidationRule::placeholder
   * @see \ramp\validation\ValidationRule::minlength
   * @see \ramp\validation\ValidationRule::maxlength
   * @see \ramp\validation\ValidationRule::min
   * @see \ramp\validation\ValidationRule::max
   * @see \ramp\validation\ValidationRule::step
   */
  #[\Override]
  public function testExpectedAttributeValues()
  {
    $this->assertSame(Str::_EMPTY(), $this->testObject->hint);
    $this->assertEquals('email', (string)$this->testObject->inputType);
    $this->assertNull($this->testObject->placeholder);
    $this->assertNull($this->testObject->minlength);
    $this->assertNull($this->testObject->maxlength);
    $this->assertNull($this->testObject->pattern);
    $this->assertNull($this->testObject->min);
    $this->assertNull($this->testObject->max);
    $this->assertNull($this->testObject->step);
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
  
   * - Provide API to common set of input element attributes that may relate to data types or test scenarios
   * [https://www.w3.org/TR/2011/WD-html5-20110525/the-input-element.html].

   * - assert 
   * - assert process touches each test method of each sub rule throughout any give set of successful tests.
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown at given test (failPoint).
   * @see \ramp\validation\ValidationRule::test()
   * @see \ramp\validation\ValidationRule::process()
   */
  #[\Override]
  public function testProcess(
    array $badValues = ['not.email.address'], ?array $goodValues = ['a.person@gmail.com'], int $failPoint = 1, int $ruleCount = 1,
    $failMessage = ''
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
