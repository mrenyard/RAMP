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

require_once '/usr/share/php/tests/ramp/model/business/validation/ValidationRuleTest.php';

require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockDbTypeValidation.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;

use tests\ramp\mocks\model\MockDbTypeValidation;
use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\LengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\DbTypeValidation.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\validation\MockDbValidationRule}
 */
class DbTypeValidationTest extends \tests\ramp\model\business\validation\ValidationRuleTest
{
  #region Setup
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockDbTypeValidation($this->hint6,
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
   * Collection of assertions for ramp\validation\DbTypeValidation::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\dbtype\DbTypeValidation}
   * - assert throws InvalidAgumentException if provided ValidationRule is instance of DbTypeValidationRule
   *   - with message: *'DbTypeValidationRules CANNOT take DbTypeValidationRule as $subRule!'*
   * @see \ramp\validation\DbTypeValidationRule
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\DbTypeValidation', $this->testObject);
    try {
      new MockDbTypeValidation(Str::NEW(),
        new MockDbTypeValidation(Str::NEW())
      );
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame('DbTypeValidationRules CANNOT take DbTypeValidationRule as $subRule!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
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
    parent::testExpectedAttributeValues();
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
    array $badValues = ['BAD'], ?array $goodValues = ['GOOD'], int $failPoint = 5, int $ruleCount = 6,
    $failMessage = 'FailOnBadValidationRule has been given the value BAD'
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
