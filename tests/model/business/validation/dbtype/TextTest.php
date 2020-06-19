<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\validation\dbtype;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/Text.class.php';

require_once '/usr/share/php/tests/svelte/model/business/validation/mocks/ValidationRuleTest/FailOnBadValidationRule.class.php';

use svelte\core\Str;
use svelte\model\business\FailedValidationException;
use svelte\model\business\validation\dbtype\Text;

use tests\svelte\model\business\validation\FailOnBadValidationRule;

/**
 * Collection of tests for \svelte\model\business\validation\dbtype\Text.
 */
class TextTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $errorMessage;

  /**
   * Setup
   */
  public function setUp()
  {
    $this->errorMessage = Str::set('My error message HERE!');
    $this->testObject = new Text(
      NULL,
      new FailOnBadValidationRule(),
      $this->errorMessage
    );
  }

  /**
   * Collection of assertions for svelte\validation\dbtype\Text::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\validation\ValidationRule}
   * - assert is instance of {@link \svelte\model\business\validation\Text}
   * @link svelte.model.business.validation.dbtype.Text \svelte\model\business\validation\dbtype\Text
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\dbtype\DbTypeValidation', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\dbtype\Text', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\dbtype\Text::process().
   * - assert void returned when test successful
   * - assert {@link \svelte\model\business\FailedValidationException} thrown when test fails
   * @link svelte.model.business.validation.dbtype.Text#method_process \svelte\model\business\validation\dbtype\Text::process()
   */
  public function testTest()
  {
    $this->assertNull($this->testObject->process(
      'Far far away, behind the word mountains, far from the countries Vokalia and ' .
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
' .
      'But nothing the copy said could convince her and so it didn’t take long until a few ' .
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
' .
      'The copy warned the Little Blind Text, that where it came from it would have been ' .
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
' .
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
      'small river named Duden flows by their place and supplies it with the necessary ' .
      'regelialia. It is a paradisematic country, in which roasted parts of sentences fly into ' .
      'your mouth. Even the all-powerful Pointing has no control about the blind texts it is ' .
      'an almost unorthographic life One day however a small line of blind text by the name of ' .
      'Lorem Ipsum decided to leave for the far World of Grammar.
' .
      'The Big Oxmox advised her not to do so, because there were thousands of bad Commas, ' .
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
' .
      'It is a paradisematic country, in which roasted parts of sentences fly into your mouth. ' .
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
' .
      'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, ' .
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
' .
      'But nothing the copy said could convince her and so it didn’t take long until a few ' .
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
' .
      'The copy warned the Little Blind Text, that where it came from it would have been ' .
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
' .
      'Pityful a rethoric question ran over her cheek, then she continued her way. On her way ' .
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
      'your mouth.'
    ));
    try {
      $this->testObject->process(1);
    } catch (FailedValidationException $expected) {
      $this->assertEquals((string)$this->errorMessage, $expected->getMessage());
      return;
    }
    $this->fail('An expected \svelte\model\business\FailedValidationException has NOT been raised.');
  }
}
