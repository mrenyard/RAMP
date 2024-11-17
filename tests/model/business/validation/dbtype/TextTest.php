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

require_once '/usr/share/php/tests/ramp/model/business/validation/dbtype/DbTypeValidationTest.php';

require_once '/usr/share/php/ramp/model/business/validation/dbtype/Text.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockDbTypeText.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\Text;

use tests\ramp\mocks\model\MockDbTypeText;
use tests\ramp\mocks\model\MockValidationRule;
use tests\ramp\mocks\model\PlaceholderValidationRule;
use tests\ramp\mocks\model\MaxlengthValidationRule;
use tests\ramp\mocks\model\PatternValidationRule;
use tests\ramp\mocks\model\MinMaxStepValidationRule;
use tests\ramp\mocks\model\FailOnBadValidationRule;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\Text.
 */
class TextTest extends \tests\ramp\model\business\validation\dbtype\DbTypeValidationTest
{
  protected $longText;
  protected $shortText;

  #region Setup
  protected function preSetup() : void
  {
    $this->hint5 = Str::set('with a maximum character length of ');
    $this->hint4 = Str::set('under 4 chars');
    $this->hint3 = Str::set('hinted AAAA');
    $this->hint2 = Str::set('part two');
    $this->hint1 = Str::set('part one');
  }
  protected function getTestObject() : RAMPObject {
    return new MockDbTypeText($this->hint5, NULL,
      new PlaceholderValidationRule($this->hint4,
        new PatternValidationRule($this->hint3,
          new FailOnBadValidationRule($this->hint2,
            new MinMaxStepValidationRule($this->hint1)
          )
        )
      )
    );
  }
  protected function postSetup() : void
  {
    // Char length ~255
    $this->shortText = 'The copy warned the Little Blind Text, that where it came from it would '.
    'have been rewritten a thousand times and everything that was left from its origin would be '.
    'the word and the Little Blind Text should turn around and return to its own, safe country.';
    // Char length ~16300.
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
    'and the Little Blind Text should turn around and return to its own, safe country.
' . '
' . 'But nothing the copy said could convince her and so it didn’t take long until a few ' .
    'insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged ' .
    'her into their agency, where they abused her for their projects again and again. And if ' .
    'she hasn’t been rewritten, then they are still using her. Far far away, behind the word ' .
    'mountains, far from the countries Vokalia and Consonantia, there live the blind texts. ' .
    'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large ' .
    'language ocean. A small river named Duden flows by their place and supplies it with the ' .
    'necessary regelialia. It is a paradisematic country, in which roasted parts of sentences ' .
    'fly into your mouth. Even the all-powerful Pointing has no control about the blind texts ' .
    'it is an almost unorthographic life One day however a small line of blind text by the ' .
    'name of Lorem Ipsum decided to leave for the far World of Grammar. The Big Oxmox advised ' .
    'her not to do so, because there were thousands of bad Commas, wild Question Marks and ' .
    'devious Semikoli, but the Little Blind Text didn’t listen. She packed her seven ' .
    'versalia, put her initial into the belt and made herself on the way. When she reached ' .
    'the first hills of the Italic Mountains, she had a last view back on the skyline of her ' .
    'hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own ' .
    'road, the Line Lane. Pityful a rethoric question ran over her cheek, then she continued ' .
    'her way. On her way she met a copy.
' . '
' . 'The copy warned the Little Blind Text, that where it came from it would have been ' .
    'rewritten a thousand times and everything that was left from its origin would be the ' .
    'word "and" and the Little Blind Text should turn around and return to its own, safe ' .
    'country. But nothing the copy said could convince her and so it didn’t take long until ' .
    'a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and ' .
    'dragged her into their agency, where they abused her for their projects again and ' .
    'again. And if she hasn’t been rewritten, then they are still using her. Far far away, ' .
    'behind the word mountains, far from the countries Vokalia and Consonantia, there live ' .
    'the blind texts. Separated they live in Bookmarksgrove right at the coast of the ' .
    'Semantics, a large language ocean. A small river named Duden flows by their place and ' .
    'supplies it with the necessary regelialia. It is a paradisematic country, in which ' .
    'roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no ' .
    'control about the blind texts it is an almost unorthographic life One day however a ' .
    'small line of blind text by the name of Lorem Ipsum decided to leave for the far World ' .
    'of Grammar. The Big Oxmox advised her not to do so, because there were thousands of bad ' .
    'Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t ' .
    'listen. She packed her seven versalia, put her initial into the belt and made herself on ' .
    'the way.
' . '
' . 'When she reached the first hills of the Italic Mountains, she had a last view back on ' .
    'the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the ' .
    'subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, ' .
    'then she continued her way. On her way she met a copy. The copy warned the Little Blind ' .
    'Text, that where it came from it would have been rewritten a thousand times and ' .
    'everything that was left from its origin would be the word "and" and the Little Blind ' .
    'Text should turn around and return to its own, safe country. But nothing the copy said ' .
    'could convince her and so it didn’t take long until a few insidious Copy Writers ' .
    'ambushed her, made her drunk with Longe and Parole and dragged her into their agency, ' .
    'where they abused her for their projects again and again. And if she hasn’t been ' .
    'rewritten, then they are still using her. Far far away, behind the word mountains, far ' .
    'from the countries Vokalia and Consonantia, there live the blind texts. Separated they ' .
    'live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A ' .
    'small river named Duden flows by their place and supplies it with the necessary ' .
    'regelialia. It is a paradisematic country, in which roasted parts of sentences fly into ' .
    'your mouth. Even the all-powerful Pointing has no control about the blind texts it is ' .
    'an almost unorthographic life One day however a small line of blind text by the name of ' .
    'Lorem Ipsum decided to leave for the far World of Grammar.
' . '
' . 'The Big Oxmox advised her not to do so, because there were thousands of bad Commas, ' .
    'wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She ' .
    'packed her seven versalia, put her initial into the belt and made herself on the way. ' .
    'When she reached the first hills of the Italic Mountains, she had a last view back on ' .
    'the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the ' .
    'subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, ' .
    'then she continued her way. On her way she met a copy. The copy warned the Little Blind ' .
    'Text, that where it came from it would have been rewritten a thousand times and ' .
    'everything that was left from its origin would be the word "and" and the Little Blind ' .
    'Text should turn around and return to its own, safe country. But nothing the copy said ' .
    'could convince her and so it didn’t take long until a few insidious Copy Writers ' .
    'ambushed her, made her drunk with Longe and Parole and dragged her into their agency, ' .
    'where they abused her for their projects again and again. And if she hasn’t been ' .
    'rewritten, then they are still using her. Far far away, behind the word mountains, far ' .
    'from the countries Vokalia and Consonantia, there live the blind texts. Separated they ' .
    'live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A ' .
    'small river named Duden flows by their place and supplies it with the necessary regelialia.
' . '
' . 'It is a paradisematic country, in which roasted parts of sentences fly into your mouth. ' .
    ' Even the all-powerful Pointing has no control about the blind texts it is an almost ' .
    'unorthographic life One day however a small line of blind text by the name of Lorem ' .
    'Ipsum decided to leave for the far World of Grammar. The Big Oxmox advised her not to ' .
    'do so, because there were thousands of bad Commas, wild Question Marks and devious ' .
    'Semikoli, but the Little Blind Text didn’t listen. She packed her seven versalia, put ' .
    'her initial into the belt and made herself on the way. When she reached the first hills ' .
    'of the Italic Mountains, she had a last view back on the skyline of her hometown ' .
    'Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the ' .
    'Line Lane. Pityful a rethoric question ran over her cheek, then she continued her way. ' .
    'On her way she met a copy. The copy warned the Little Blind Text, that where it came ' .
    'from it would have been rewritten a thousand times and everything that was left from ' .
    'its origin would be the word "and" and the Little Blind Text should turn around and ' .
    'return to its own, safe country. But nothing the copy said could convince her and so ' .
    'it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk ' .
    'with Longe and Parole and dragged her into their agency, where they abused her for ' .
    'their projects again and again. And if she hasn’t been rewritten, then they are still ' .
    'using her.
' . '
' . 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, ' .
    'there live the blind texts. Separated they live in Bookmarksgrove right at the coast of ' .
    'the Semantics, a large language ocean. A small river named Duden flows by their place ' .
    'and supplies it with the necessary regelialia. It is a paradisematic country, in which ' .
    'roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no ' .
    'control about the blind texts it is an almost unorthographic life One day however a ' .
    'small line of blind text by the name of Lorem Ipsum decided to leave for the far World ' .
    'of Grammar. The Big Oxmox advised her not to do so, because there were thousands of ' .
    'bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t ' .
    'listen. She packed her seven versalia, put her initial into the belt and made herself ' .
    'on the way. When she reached the first hills of the Italic Mountains, she had a last ' .
    'view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet ' .
    'Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran ' .
    'over her cheek, then she continued her way. On her way she met a copy. The copy warned ' .
    'the Little Blind Text, that where it came from it would have been rewritten a thousand ' .
    'times and everything that was left from its origin would be the word "and" and the ' .
    'Little Blind Text should turn around and return to its own, safe country.
' . '
' . 'But nothing the copy said could convince her and so it didn’t take long until a few ' .
    'insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged ' .
    'her into their agency, where they abused her for their projects again and again. And ' .
    'if she hasn’t been rewritten, then they are still using her. Far far away, behind the ' .
    'word mountains, far from the countries Vokalia and Consonantia, there live the blind ' .
    'texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a ' .
    'large language ocean. A small river named Duden flows by their place and supplies it ' .
    'with the necessary regelialia. It is a paradisematic country, in which roasted parts ' .
    'of sentences fly into your mouth. Even the all-powerful Pointing has no control about ' .
    'the blind texts it is an almost unorthographic life One day however a small line of ' .
    'blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar. ' .
    'The Big Oxmox advised her not to do so, because there were thousands of bad Commas, ' .
    'wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She ' .
    'packed her seven versalia, put her initial into the belt and made herself on the way. ' .
    'When she reached the first hills of the Italic Mountains, she had a last view back on ' .
    'the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the ' .
    'subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, ' .
    'then she continued her way. On her way she met a copy.
' . '
' . 'The copy warned the Little Blind Text, that where it came from it would have been ' .
    'rewritten a thousand times and everything that was left from its origin would be the ' .
    'word "and" and the Little Blind Text should turn around and return to its own, safe ' .
    'country. But nothing the copy said could convince her and so it didn’t take long until ' .
    'a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and ' .
    'dragged her into their agency, where they abused her for their projects again and again. ' .
    'And if she hasn’t been rewritten, then they are still using her. Far far away, behind ' .
    'the word mountains, far from the countries Vokalia and Consonantia, there live the blind ' .
    'texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a ' .
    'large language ocean. A small river named Duden flows by their place and supplies it ' .
    'with the necessary regelialia. It is a paradisematic country, in which roasted parts of ' .
    'sentences fly into your mouth. Even the all-powerful Pointing has no control about the ' .
    'blind texts it is an almost unorthographic life One day however a small line of blind ' .
    'text by the name of Lorem Ipsum decided to leave for the far World of Grammar. The Big ' .
    'Oxmox advised her not to do so, because there were thousands of bad Commas, wild ' .
    'Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She ' .
    'packed her seven versalia, put her initial into the belt and made herself on the way. ' .
    'When she reached the first hills of the Italic Mountains, she had a last view back on ' .
    'the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the ' .
    'subline of her own road, the Line Lane.
' . '
' . 'Pityful a rethoric question ran over her cheek, then she continued her way. On her way ' .
    'she met a copy. The copy warned the Little Blind Text, that where it came from it would ' .
    'have been rewritten a thousand times and everything that was left from its origin would ' .
    'be the word "and" and the Little Blind Text should turn around and return to its own, ' .
    'safe country. But nothing the copy said could convince her and so it didn’t take long ' .
    'until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole ' .
    'and dragged her into their agency, where they abused her for their projects again and ' .
    'again. And if she hasn’t been rewritten, then they are still using her. Far far away, ' .
    'behind the word mountains, far from the countries Vokalia and Consonantia, there live ' .
    'the blind texts. Separated they live in Bookmarksgrove right at the coast of the ' .
    'Semantics, a large language ocean. A small river named Duden flows by their place and ' .
    'supplies it with the necessary regelialia. It is a paradisematic country, in which ' .
    'roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no ' .
    'control about the blind texts it is an almost unorthographic life One day however a ' .
    'small line of blind text by the name of Lorem Ipsum decided to leave for the far World ' .
    'of Grammar. The Big Oxmox advised her not to do so, because there were thousands of bad ' .
    'Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t ' .
    'listen. She packed her seven versalia, put her initial into the belt and made herself ' .
    'on the way. When she reached the first hills of the Italic Mountains, she had a last ' .
    'view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet ' .
    'Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran ' .
    'over her cheek, then she continued her way. On her way she met a copy. The copy warned ' .
    'the Little Blind Text, that where it came from it would have been rewritten a thousand ' .
    'times and everything that was left from its origin would be the word "and" and the ' .
    'Little Blind Text should turn around and return to its own, safe country. But nothing ' .
    'the copy said could convince her and so it didn’t take long until a few insidious Copy ' .
    'Writers ambushed her, made her drunk with Longe and Parole and dragged her into their ' .
    'agency, where they abused her for their projects again and again. And if she hasn’t ' .
    'been rewritten, then they are still using her. Far far away, behind the word mountains, ' .
    'far from the countries Vokalia and Consonantia, there live the blind texts. Separated ' .
    'they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. ' .
    'A small river named Duden flows by their place and supplies it with the necessary ' .
    'regelialia. It is a paradisematic country, in which roasted parts of sentences fly into ' .
    'your mouth.';
  }
  #endregion

  #region Sub process template
  protected function doAttributeValueConfirmation()
  {
    $this->assertEquals(
      $this->hint1 . ' ' . $this->hint2 . ' ' . $this->hint3 . ' ' . $this->hint4 . ' ' . $this->hint5 . '16383',
      (string)$this->testObject->hint
    );
    $this->assertSame(MockValidationRule::$inputTypeValue, $this->testObject->inputType);
    $this->assertSame(MockValidationRule::$placeholderValue, $this->testObject->placeholder);
    $this->assertEquals(16383, $this->testObject->maxlength);
    $this->assertSame(MockValidationRule::$patternValue, $this->testObject->pattern);
    $this->assertSame(MockValidationRule::$minValue, $this->testObject->min);
    $this->assertSame(MockValidationRule::$maxValue, $this->testObject->max);
    $this->assertSame(MockValidationRule::$stepValue, $this->testObject->step);
  }
  #endregion

  /**
   * Collection of assertions for ramp\validation\dbtype\Text.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@see \ramp\validation\DbTypeValidation}
   * - assert is instance of {@see \ramp\model\business\validation\Text}
   * @see \ramp\model\business\validation\dbtype\Text
   */
  public function testConstruct() : void
  {
    parent::testConstruct();    
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\Text', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
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
   * Correct return of ramp\model\Model::__toString().
   * - assert returns empty string literal.
   * @see \ramp\model\Model::__toString()
   */
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Collection of assertions for ramp\validation\ValidationRule::process() and test().
   * - assert process touches each test method of each sub rule throughout any give set of tests
   * - assert {@see \ramp\validation\FailedValidationException} bubbles up when thrown in any given test.
   * @see \ramp\validation\ValidationRule::test()
   * @see \ramp\validation\ValidationRule::process()
   */
  public function testProcess( // badValues [INT, CharLimit] 
    array $badValues = NULL, ?array $goodValues = NULL, int $failPoint = 1, int $ruleCount = 5,
    $failMessage = ''
  ) : void
  {
    parent::testProcess([1, $this->longText . $this->shortText], [$this->longText], $failPoint, $ruleCount, $failMessage);
  }
  #endregion

  #region New Extra Tests
  /**
   * Collection of assertions for an additional ramp\model\business\validation\validation\ValidationRule::maxlength.
   * - assert maxlength same as default limit when NON set from dbtype constructor or subRule.
   * - assert maxlength same as provided on dbtype constructor when within limit and NON on subRule/s.
   * - assert maxlength same as default limit when that provided on dbtype constructor greater than avalible limit.
   * - assert maxlength same as provided on subRule when within limit.
   * - assert maxlength same as default limit when that provided on subRule greater than avalible limit.
   * - assert maxlength same as provided on subRule when less than that provided on dbtype constructor and within limit.
   * @see \ramp\model\business\validation\validation\ValidationRule::maxlength
   */
   public function testMaxlengthVariations()
  {
    $limit = 16383;
    $withinLimit = 16380;
    $beyondLimit = 17000;
    $o1 = new MockDbTypeText($this->hint5, NULL,
      new PatternValidationRule($this->hint3)
    );
    $this->assertSame($limit, $o1->maxlength);
    $this->assertSame('hinted AAAA with a maximum character length of ' . $limit, (string)$o1->hint);
    
    $o2 = new MockDbTypeText($this->hint5, $withinLimit,
      new PatternValidationRule($this->hint3)
    );
    $this->assertSame($withinLimit, $o2->maxlength);
    $this->assertSame('hinted AAAA with a maximum character length of ' . $withinLimit, (string)$o2->hint);

    $o3 = new MockDbTypeText($this->hint5, $beyondLimit,
      new PatternValidationRule($this->hint3)
    );
    $this->assertSame($limit, $o3->maxlength);
    $this->assertSame('hinted AAAA with a maximum character length of ' . $limit, (string)$o3->hint);
    
    $o4 = new MockDbTypeText($this->hint5, NULL,
      new MaxlengthValidationRule($this->hint3, $withinLimit)
    );
    $this->assertSame($withinLimit, $o4->maxlength);
    $this->assertSame('hinted AAAA with a maximum character length of ' . $withinLimit, (string)$o4->hint);

    $o5 = new MockDbTypeText($this->hint5, NULL,
      new MaxlengthValidationRule($this->hint3, $beyondLimit)
    );
    $this->assertSame($limit, $o5->maxlength);
    $this->assertSame('hinted AAAA with a maximum character length of ' . $limit, (string)$o5->hint);

    $o6 = new MockDbTypeText($this->hint5, $whitinLimit,
      new MaxlengthValidationRule($this->hint3, ($withinLimit - 100))
    );
    $this->assertSame(($withinLimit - 100), $o6->maxlength);
    $this->assertSame('hinted AAAA with a maximum character length of ' . ($withinLimit - 100), (string)$o6->hint);
  }
  #endregion
}