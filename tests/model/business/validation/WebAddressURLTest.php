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

require_once '/usr/share/php/ramp/model/business/validation/WebAddressURL.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockWebAddressURL.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\WebAddressURL;

use tests\ramp\mocks\model\MockWebAddressURL;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\LengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;
use tests\ramp\mocks\model\MockValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\WebAddressURL.
 */
class WebAddressURLTest extends \tests\ramp\model\business\validation\RegexValidationRuleTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->hint1 = Str::set('error hint');
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockWebAddressURL($this->hint1);
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\WebAddressURL.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\RegexValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\WebAddressURL}
   * @see \ramp\model\business\validation\WebAddressURL
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\WebAddressURL', $this->testObject);
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
    $this->assertEquals($this->hint1, $this->testObject->hint);
    $this->assertEquals('url', $this->testObject->inputType);
    $this->assertNull($this->testObject->placeholder);
    $this->assertNull($this->testObject->maxlength);
    $this->assertEquals(
      '(https:\/\/[a-z0-9-\.]+)?\/([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&amp;([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?',
      $this->testObject->pattern
    );
    $this->assertNull($this->testObject->min);
    $this->assertNull($this->testObject->max);
    $this->assertNull($this->testObject->step);
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
    array $badValues = [
      'javascript:action',
      'https://plex.domain.com:32400/web/index.html',
      '#person:new:family-name',
      'https://my.domain.com/<?=$myVar; ?>',
      'https://my.domain.com/$myvar'
    ], ?array $goodValues = [
      'https://www.bbc.co.uk/',
      'https://www.bbc.co.uk/news',
      '/person/~/family-name/',
      'https://my.domain.com/person/~/family-name/',
      'https://www.google.com/search?client=firefox',
      'https://www.google.com/search?client=firefox&amp;q=help',
      'https://domain.com/person/?family-name=renyard&amp;given-name=matt#person:mrenyard'
    ],
    int $failPoint = 1, int $ruleCount = 1,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion

  #region New Specialist Tests
  /**
   * Additional assertions for allowing InpageLinks variant of ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\WebAddressURL
   * @see \ramp\validation\WebAddressURL::test()
   * @see \ramp\validation\WebAddressURL::process()
   */
  public function testAllowInpageLinksProcess(
    array $badValues = [
      'javascript:action',
      'https://plex.domain.com:32400/web/index.html',
      'https://my.domain.com/<?=$myVar; ?>',
      'https://my.domain.com/$myvar'
    ], ?array $goodValues = [
      'https://www.bbc.co.uk/',
      'https://www.bbc.co.uk/news',
      '/person/~/family-name/',
      '#person:new:family-name',
      'https://my.domain.com/person/~/family-name/',
      'https://www.google.com/search?client=firefox',
      'https://www.google.com/search?client=firefox&amp;q=help',
      'https://domain.com/person/?family-name=renyard&amp;given-name=matt#person:mrenyard'
    ],
    int $failPoint = 1, int $ruleCount = 1,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    $this->testObject = new MockWebAddressURL($this->hint1, FALSE, TRUE);
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }

  /**
   * Additional assertions for $allowPorts variant of ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\WebAddressURL
   * @see \ramp\validation\WebAddressURL::test()
   * @see \ramp\validation\WebAddressURL::process()
   */
  public function testAllowPortsProcess(
    array $badValues = [
      'javascript:action',
      '#person:new:family-name',
      'https://my.domain.com/<?=$myVar; ?>',
      'https://my.domain.com/$myvar'
    ], ?array $goodValues = [
      'https://www.bbc.co.uk/',
      'https://www.bbc.co.uk/news',
      '/person/~/family-name/',
      'https://my.domain.com/person/~/family-name/',
      'https://plex.domain.com:32400/web/index.html',
      'https://www.google.com/search?client=firefox',
      'https://www.google.com/search?client=firefox&amp;q=help',
      'https://domain.com/person/?family-name=renyard&amp;given-name=matt#person:mrenyard'
    ],
    int $failPoint = 1, int $ruleCount = 1,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    $this->testObject = new MockWebAddressURL($this->hint1, TRUE, FALSE);
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
  }
  #endregion
}
