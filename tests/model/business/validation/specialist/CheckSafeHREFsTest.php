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

require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/WebAddressURL.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/CheckSafeHREFs.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockCheckSafeHREFs.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;

use tests\ramp\mocks\model\MockCheckSafeHREFs;

/**
 * Collection of tests for \ramp\model\business\validation\specialist\CheckSafeHREFs.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\model\MockCheckSafeHREFs}
 */
class CheckSafeHREFsTest extends \tests\ramp\model\business\validation\specialist\SpecialistValidationRuleTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void { 
    $this->hint1 = Str::set('error hint');
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockCheckSafeHREFs($this->hint1);
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\validation\specialist\CheckSafeHREFs.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\specialist\SpecialistValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\specialist\CheckSafeHREFs}
   * @see \ramp\model\business\validation\specialist\CheckSafeHREFs
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\specialist\CheckSafeHREFs', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\core\RAMPObject::__set()
   */
  #[\Override]
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
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of \ramp\core\RAMPObject::__toString().
   * - assert returns empty string literal.
   * @see \ramp\core\RAMPObject::__toString()
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
   * @see \ramp\model\business\validation\ValidationRule::hint
   * @see \ramp\model\business\validation\ValidationRule::inputType
   * @see \ramp\model\business\validation\ValidationRule::placeholder
   * @see \ramp\model\business\validation\ValidationRule::minlength
   * @see \ramp\model\business\validation\ValidationRule::maxlength
   * @see \ramp\model\business\validation\ValidationRule::min
   * @see \ramp\model\business\validation\ValidationRule::max
   * @see \ramp\model\business\validation\ValidationRule::step
   */
  #[\Override]
  public function testExpectedAttributeValues()
  {
    $this->assertEquals($this->hint1, $this->testObject->hint);
    $this->assertEquals('text', (string)$this->testObject->inputType);
    $this->assertNull($this->testObject->placeholder);
    $this->assertNull($this->testObject->minlength);
    $this->assertNull($this->testObject->maxlength);
    $this->assertNull($this->testObject->pattern);
    $this->assertNull($this->testObject->min);
    $this->assertNull($this->testObject->max);
    $this->assertNull($this->testObject->step);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\specialist\CheckSafeHREFs::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of successful tests.
   * - assert {@see ramp\model\business\validation\FailedValidationException} bubbles up when thrown at given test (failPoint).
   * @see ramp\model\business\validation\specialist\CheckSafeHREFs::test()
   * @see ramp\model\business\validation\specialist\CheckSafeHREFs::process()
   */
  #[\Override]
  public function testProcess(
    array $badValues = [
      'click <a href="javascript:action">here</a>! ',
      'Bad <a href="<?=$myVar; >">injected php</a>'
    ],
    ?array $goodValues = [
      '<a href="https://www.bbc.co.uk/news">BBC News</a> has the latest. ' .
      '<a href="#person:new:family-name" title="Jump to input field">field</a>! ' .
      '<a href="https://my.domain.com/person/~/family-name/">My Family Name</a> ' .
      'Search for <a href="https://www.google.com/search?client=firefox&q=help">help</a> ' .
      'and <a href="https://domain.com/person/?family-name=renyard&given-name=matt#main">My Family Name</a>'
    ],
    int $failPoint = 1, int $ruleCount = 1, $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
