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

require_once '/usr/share/php/tests/ramp/model/business/validation/dbtype/TextTest.php';

require_once '/usr/share/php/ramp/model/business/validation/dbtype/TinyText.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockDbTypeTinyText.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\TinyText;

use tests\ramp\mocks\model\MockDbTypeTinyText;
use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\LengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\TinyText.
 */
class TinyTextTest extends \tests\ramp\model\business\validation\dbtype\TextTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->inputType = 'textarea';
    $this->maxlength = 255;
    $this->hint4 = Str::set('with a maximum character length of ');
    $this->hint3 = Str::set('part three');
    $this->hint2 = Str::set('part two');
    $this->hint1 = Str::set('part one');
  }
  #[\Override]
  protected function getTestObject() : RAMPObject {
    return new MockDbTypeTinyText(
      Str::set(MockValidationRule::PLACEHOLDER),
      $this->hint4, NULL,
      new PatternValidationRule($this->hint3,
        new FailOnBadValidationRule($this->hint2,
          new MinMaxStepValidationRule($this->hint1)
        )
      )
    );
  }
  #[\Override]
  protected function postSetup() : void
  {
    // Char length ~255
    $this->shortText = 'The copy warned the Little Blind Text, that where it came from it would '.
    'have been rewritten a thousand times and everything that was left from its origin would be '.
    'the word and the Little Blind Text should turn around and return to its own, safe country.';
    // Char length > 255.
    $this->longText = 'Far far away, behind the word mountains, far from the countries Vokalia and ' .
    'Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at ' .
    'the coast of the Semantics, a large language ocean. A small river named Duden flows by ' .
    'their place and supplies it with the necessary regelialia. It is a paradisematic ' .
    'country, in which roasted parts of sentences fly into your mouth. Even the all-powerful ' .
    'Pointing has no control about the blind texts it is an almost unorthographic life One ' .
    'day however a small line of blind text by the name of Lorem Ipsum decided to leave for ' .
    'the far World of Grammar. The Big Oxmox advised her not to do so, because there were ' .
    'thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind ' .
    'Text didn’t listen. She packed her seven versalia, put her initial into the belt and ' .
    'made herself on the way. When she reached the first hills of the Italic Mountains, she ' .
    'had a last view back on the skyline of her hometown Bookmarksgrove, the headline of ' .
    'Alphabet Village and the subline of her own road, the Line Lane. Pityful a rethoric ' .
    'question ran over her cheek, then she continued her way. On her way she met a copy. The ' .
    'copy warned the Little Blind Text, that where it came from it would have been rewritten ' .
    'a thousand times and everything that was left from its origin would be the word "and" ' .
    'and the Little Blind Text should turn around and return to its own, safe country.';
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\dbtype\TinyText.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\validation\DbTypeValidation}
   * - assert is instance of {@see \ramp\model\business\validation\TinyText}
   * @see \ramp\model\business\validation\dbtype\TinyText
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\TinyText', $this->testObject);
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
    parent::testExpectedAttributeValues();
    // $this->assertEquals(
    //   $this->hint1 . ' ' . $this->hint2 . ' ' . $this->hint3 . ' ' . $this->hint4 . ' ' . $this->hint5 . '255',
    //   (string)$this->testObject->hint
    // );
    // $this->assertEquals('textarea', (string)$this->testObject->inputType);
    // $this->assertEquals(MockValidationRule::PLACEHOLDER, (string)$this->testObject->placeholder);
    // $this->assertNull($this->testObject->pattern);
    // $this->assertNull($this->testObject->minlength);
    // $this->assertEquals(255, $this->testObject->maxlength);
    // $this->assertNull($this->testObject->pattern);
    // $this->assertNull($this->testObject->min);
    // $this->assertNull($this->testObject->max);
    // $this->assertNull($this->testObject->step);
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\ValidationRule::test()
   * @see \ramp\validation\ValidationRule::process()
   */
  #[\Override]
  public function testProcess( // badValues [INT, CharLimit]
    array $badValues = NULL, ?array $goodValues = NULL, int $failPoint = 1, int $ruleCount = 4,
    $failMessage = ''
  ) : void
  {
    parent::testProcess([1, $this->longText], [$this->shortText], $failPoint, $ruleCount, $failMessage);
  }
  #endregion

  #region New Extra Tests
  /**
   * Constructor presented with a $subRule::maxlength that is greater than maximum available or set $maxlength.
   * - assert \InvalidArgumentException thrown when $subRule contains a rule with maxlength > $maxlength param on constructor.
   * @see \ramp\model\business\validation\dbtype\TinyText
   * @see \ramp\model\business\validation\ValidationRule::maxlength
   */
  #[\Override]
  public function testInsufficientSpaceMismatchException() //int $limit = 255, int $beyondLimit = 256)
  {
    // parent::testInsufficientSpaceMismatchException($limit, $beyondLimit);
    $limit = 255;
    $beyondLimit = 260;
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Possibly insufficient character space allocated for value!');
    new MockDbTypeTinyText( Str::_EMPTY(),
      $this->hint4, NULL,
      new LengthValidationRule($this->hint3, $beyondLimit)
    );
    $this->assertNull($this->testObject->minlength);
    $this->assertEquals($limit, $this->testObject->maxlength);
  }

  /**
   * Constructor presented with a $subRule::minlength that is greater than maximum available or set $maxlength.
   * - assert \InvalidArgumentException thrown when $subRule contains a rule with minlength >= $maxlength.
   * @see \ramp\model\business\validation\dbtype\TinyText
   * @see \ramp\model\business\validation\ValidationRule::maxlength
   */
  #[\Override]
  public function testSubRuleMinlengthOutsideScopeException()
  {
    parent::testSubRuleMinlengthOutsideScopeException();
    // $this->expectException(\InvalidArgumentException::class);
    // $this->expectExceptionMessage('Provided $subRule::$minlength GREATER THAN $maxlength!');
    // $o1 = new MockDbTypeTinyText( Str::_EMPTY(),
    //   $this->hint4, NULL,
    //   new LengthValidationRule($this->hint1, 200, 250)
    // );
  }

  /**
   * Collection of assertions for an additional ramp\model\business\validation\validation\ValidationRule::maxlength and minlength.
   * - assert maxlength same as default limit when NON set from dbtype constructor or subRule.
   * - assert maxlength same as provided on dbtype constructor when within limit and NON on subRule/s.
   * - assert maxlength same as default limit when that provided on dbtype constructor greater than available limit.
   * - assert maxlength same as provided on subRule when within limit.
   * - assert maxlength same as default limit when that provided on subRule greater than available limit.
   * - assert maxlength same as provided on subRule when less than that provided on dbtype constructor and within limit.
   * @see \ramp\model\business\validation\validation\ValidationRule::maxlength
   */
  #[\Override]
  public function testMaxlengthVariations() //int $limit = 255, int $withinLimit = 250, int $beyondLimit = 260)
  {
    // parent::testMaxlengthVariations($limit = 255, $withinLimit = 250, $beyondLimit = 260);
    $limit = 255;
    $withinLimit = 250;
    $beyondLimit = 260;
    $o1 = new MockDbTypeTinyText( Str::_EMPTY(),
      $this->hint4, NULL,
      new PatternValidationRule($this->hint1)
    );
    $this->assertSame($limit, $o1->maxlength);
    $this->assertSame('part one with a maximum character length of ' . $limit, (string)$o1->hint);
    
    $o2 = new MockDbTypeTinyText( Str::_EMPTY(),
      $this->hint4, $withinLimit,
      new PatternValidationRule($this->hint1)
    );
    $this->assertSame($withinLimit, $o2->maxlength);
    $this->assertSame('part one with a maximum character length of ' . $withinLimit, (string)$o2->hint);

    $o3 = new MockDbTypeTinyText( Str::_EMPTY(),
      $this->hint4, $beyondLimit,
      new PatternValidationRule($this->hint1)
    );
    $this->assertSame($limit, $o3->maxlength);
    $this->assertSame('part one with a maximum character length of ' . $limit, (string)$o3->hint);
    
    $o4 = new MockDbTypeTinyText( Str::_EMPTY(),
      $this->hint4, NULL,
      new LengthValidationRule($this->hint1, $withinLimit)
    );
    $this->assertSame($withinLimit, $o4->maxlength);
    $this->assertSame('part one with a maximum character length of ' . $withinLimit, (string)$o4->hint);

    $o5 = new MockDbTypeTinyText( Str::_EMPTY(),
      $this->hint4, $whitinLimit,
      new LengthValidationRule($this->hint1, ($withinLimit - 100))
    );
    $this->assertSame(($withinLimit - 100), $o5->maxlength);
    $this->assertSame('part one with a maximum character length of ' . ($withinLimit - 100), (string)$o5->hint);
  }
  #endregion
}
