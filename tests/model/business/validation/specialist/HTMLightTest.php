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

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/WebAddressURL.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/CheckSafeHREFs.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/HTMLight.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockHTMLight.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\specialist\CheckSafeHREFs;
use ramp\model\business\validation\FailedValidationException;

use tests\ramp\mocks\model\MockHTMLight;

/**
 * Collection of tests for \ramp\model\business\validation\specialist\HTMLight.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\model\MockHTMLight}
 */
class HTMLightTest extends \tests\ramp\model\business\validation\specialist\SpecialistValidationRuleTest
{
  #region Setup
  /**
   * @todo:mrenyard: Remove SETTING::$RAMP_LOCAL_DIR once DTD public.
   */
  #[\Override]
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_LOCAL_DIR = getenv("HOME") . '/Projects/RAMP/local';
    $this->hint1 = Str::set('safe (href) links,');
    $this->hint2 = Str::set('HTMLight [https://rampapp.info/assets/htmlight.dtd]');
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockHTMLight($this->hint2, new CheckSafeHREFs($this->hint1));
  }
  #endregion

  /**
   * Collection of assertions for \ramp\model\business\validation\specialist\HTMLight.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\specialist\SpecialistValidationRule}
   * - assert is instance of {@see \ramp\model\business\validation\specialist\HTMLight}
   * @see \ramp\model\business\validation\specialist\HTMLight
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\specialist\HTMLight', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__get().
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
    $this->assertEquals($this->hint1 . ' ' . $this->hint2, $this->testObject->hint);
    $this->assertEquals('textarea html-editor', (string)$this->testObject->inputType);
    $this->assertNull($this->testObject->placeholder);
    $this->assertNull($this->testObject->minlength);
    $this->assertNull($this->testObject->maxlength);
    $this->assertNull($this->testObject->pattern);
    $this->assertNull($this->testObject->min);
    $this->assertNull($this->testObject->max);
    $this->assertNull($this->testObject->step);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\specialist\HTMLight::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of successful tests.
   * - assert {@see ramp\model\business\validation\FailedValidationException} bubbles up when thrown at given test (failPoint).
   * @see ramp\model\business\validation\specialist\HTMLight::test()
   * @see ramp\model\business\validation\specialist\HTMLight::process()
   */
  #[\Override]
  public function testProcess(
    array $badValues = [
      
      '<bad>text</bad>',

      '<p><a href="javascript:action">Click</a></p>',

      '<p><a href="https://plex.domain.com:32400/web/index.html">Plex Media</a></p>',

      '<p><a href="https://my.domain.com/$myvar">sneeky</a></p>',

      '<p><?=$myVar; ?></p>',

      '<p id="identifier">Some text</p>',

    ],
    ?array $goodValues = [
      
      '<p>simple text</p>',
      
      '<p><a href="https://www.bbc.co.uk/news" title="Something about Site.">BBC News</a> has the latest</p>',
      
      '<p><a href="#person:new:family-name" title="Jump to input field">field</a>!</p>',
      
      '<p><a href="https://my.domain.com/person/~/family-name/">My Family Name</a></p>',
      
      '<p>Search for <a href="https://www.google.com/?search=clientfirefox&amp;q=help">help</a>' .
        'and <a href="https://domain.com/person/?family-name=renyard&amp;given-name=matt#main">My Family Name</a></p>',

      '<ul>' .
      '  <li>text <a href="https://www.site.com/" title="Something about Site.">Site</a> text</li>' .
      '  <li><a href="#txt3"><strong>text3</strong></a></li>' .
      '  <li><a href="https://www.google.com/search?client=firefox&amp;q=help"><strong>Help!</strong></a></li>' .
      '  <li>' .
      '    <ol>' .
      '      <li><sub>Sub</sub> text one</li>' .
      '      <li><sub>Sub</sub> text two</li>' .
      '      <li><sub>Sub</sub> text <s>two</s>three</li>' .
      '    </ol>' .
      '  </li>' .
      '  <li><h4>Header five</h4></li>' .
      '</ul>',

      '<p>According to Mozilla\'s website, <q cite="https://www.mozilla.org/en-US/about/history/details/">Firefox 1.0 was released in 2004 and became a big success.</q></p>',
      
      '<h3>Sub Heading <em>Two</em></h3>',
      
      '<figure data-mid="img:profile:101" />',
      
      '<p><strong>More text</strong></p>',
      
      '<blockquote>text</blockquote>',
      
      '<p><kbd>Ctrl+a</kbd></p>',

      '<blockquote cite="https://www.huxley.net/bnw/four.html">' .
      '  <p>Words can be like X-rays, if you use them properly they\'ll go through anything. You read and you\'re pierced.</p>' .
      '  <footer>-Aldous Huxley, <cite>Brave New World</cite></footer>' .
      '</blockquote>',

      '<p>input: <code>print("hello " . $place);</code></p>',

      '<p>output: <samp>Hello World!</samp></p>',

      '<pre><code data-lang="php">' .
      '  print("hello " . $place);' .
      '</code></pre>',

      '<pre><kbd>' .
      '  Ctrl+A' .
      '</kbd></pre>'
      
    ],
    int $failPoint = 1, int $ruleCount = 1,
    $failMessage = '$value failed to match provided regex!'
  ) : void
  {
    set_error_handler(function() { /* suppress warnings */ }, E_WARNING);
    parent::testProcess($badValues, $goodValues, $failPoint, $ruleCount, $failMessage);
    restore_error_handler();
  }
  #endregion
}
