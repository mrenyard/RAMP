<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
namespace tests\svelte\core;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\core\Boolean;

/**
 * Collection of tests for \svelte\core\Str.
 */
class StrTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for svelte\core\Str::__construct().
   * - assert is private inaccessable
   * @link svelte.core.Str \svelte\core\Str
   */
  public function test__construct()
  {
    try {
      $testSvelteObject = new Str();
    } catch (\Throwable $expected) {
      $this->assertTrue(true);
      return;
    }
    $this->fail('An expected Error: Call to private svelte\core\Str::__construct() has NOT been raised.');
  }

  /**
   * Collection of assertions for svelte\core\Str::set().
   * - assert accepts string literal
   * - assert accepts string literal concatenated
   * - assert accepts {@link \svelte\core\Str} concatenated (as string literal)
   * - assert that each is instance of {@link \svelte\core\Str}
   * - assert that each is instance of {@link \svelte\core\SvelteObject}
   * @link svelte.core.Str#method_set \svelte\core\Str::set()
   */
  public function testSet()
  {
    $o1 = Str::set('string Literal');
    $this->assertInstanceOf('svelte\core\Str', $o1);
    $this->assertInstanceOf('svelte\core\SvelteObject', $o1);

    $o2 = Str::set('string ' . 'Literal');
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertInstanceOf('svelte\core\SvelteObject', $o2);

    $this->assertEquals($o1, $o2);
    $this->assertNotSame($o1, $o2);
    $this->assertSame((string) $o1, (string) $o2);
    $this->assertSame('string Literal', (string) $o2);

    $stringA = Str::set('Hello');
    $stringB = Str::set('World');
    $o3 = Str::set($stringA . $stringB);
    $this->assertSame('HelloWorld', (string) $o3);
    $this->assertTrue(('HelloWorld' == $o3));
    $this->assertFalse(('HelloWorld' === $o3));
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertInstanceOf('svelte\core\SvelteObject', $o3);
  }

  /**
   * Collection of assertions for svelte\core\Str::_EMPTY().
   * - assert returns the SAME instance on every call of {@link \svelte\core\Str::_EMPTY()}
   * - assert returns the SAME instance on calling {@link \svelte\core\Str::set()} with no param
   * - assert returns the SAME instance on calling \svelte\core\Str::set('') (param of ''(empty string))
   * - assert when cast to (string) returns (empty string literal)
   * - assert that each is instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method__EMPTY \svelte\core\Str::_EMPTY()
   */
  public function test_EMPTY()
  {
    $o1 = Str::_EMPTY();
    $this->assertInstanceOf('svelte\core\Str', $o1);

    $o2 = Str::_EMPTY();
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set();
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $o4 = Str::set('');
    $this->assertInstanceOf('svelte\core\Str', $o4);
    $this->assertSame($o1, $o4);

    $this->assertEquals($o1, $o2);
    $this->assertSame('', (string) $o1);
  }

  /**
   * Collection of assertions for svelte\core\Str::SPACE().
   * - assert returns the SAME instance on every call of {@link \svelte\core\Str::SPACE()}
   * - assert returns the SAME instance on calling set(' ')
   * - assert when cast to (string) returns (string literal ' ')
   * - assert that each is instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method_SPACE \svelte\core\Str::SPACE()
   */
  public function testSPACE()
  {
    $o1 = Str::SPACE();
    $this->assertInstanceOf('svelte\core\Str', $o1);

    $o2 = Str::SPACE();
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set(' ');
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame(' ', (string) $o1);
  }

  /**
   * Collection of assertions for svelte\core\Str::COLON().
   * - assert returns the SAME instance on every call of {@link \svelte\core\Str::COLON()}
   * - assert returns the SAME instance on calling set(':')
   * - assert when cast to (string) returns (string literal ':')
   * - assert that each is instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method_COLON \svelte\core\Str::COLON()
   */
  public function testCOLON()
  {
    $o1 = Str::COLON();
    $this->assertInstanceOf('svelte\core\Str', $o1);

    $o2 = Str::COLON();
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set(':');
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame(':', (string) $o1);
  }

  /**
   * Collection of assertions for svelte\core\Str::SEMICOLON().
   * - assert returns the SAME instance on every call of {@link \svelte\core\Str::SEMICOLON()}
   * - assert returns the SAME instance on calling set(';')
   * - assert when cast to (string) returns (string literal ';')
   * - assert that each is instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method_SEMICOLON \svelte\core\Str::SEMICOLON()
   */
  public function testSEMICOLON()
  {
    $o1 = Str::SEMICOLON();
    $this->assertInstanceOf('svelte\core\Str', $o1);

    $o2 = Str::SEMICOLON();
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set(';');
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame(';', (string) $o1);
  }

  /**
   * Collection of assertions for svelte\core\Str::append().
   * - assert returns string same as handed to constructor + appended param
   * - assert returned {@link \svelte\core\Str} NOT same as original
   * - assert returned {@link \svelte\core\Str} is still instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method_append \svelte\core\Str::append()
   */
  public function testAppend()
  {
    $o1 = Str::set('Hello');
    $o2 = $o1->append(Str::set(' World'));

    $this->assertSame("Hello", (string) $o1);
    $this->assertSame("Hello World", (string) $o2);
    $this->assertNotSame($o2, $o1);
    $this->assertInstanceOf('svelte\core\Str', $o2);

    $oEmpty = Str::_EMPTY();
    $test = Str::set('test');

    $o5 = Str::_EMPTY();
    $rtn = $o5->append($test);
    $this->assertSame($rtn, $test);
    $this->assertSame($o5, $oEmpty);
    $this->assertNotSame($rtn, $oEmpty);
    $this->assertSame('', (string) $o5);
  }

  /**
   * Collection of assertions for svelte\core\Str::prepend().
   * - assert returns string same as handed to prepended param + constructor
   * - assert returned {@link \svelte\core\Str} NOT same as original
   * - assert returned {@link \svelte\core\Str} is still instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method_prepend \svelte\core\Str::prepend()
   */
  public function testPrepend()
  {
    $o1 = Str::set('World');
    $o2 = $o1->prepend(Str::set('Hello '));

    $this->assertSame("World", (string) $o1);
    $this->assertSame("Hello World", (string) $o2);
    $this->assertNotSame($o2, $o1);
    $this->assertInstanceOf('svelte\core\Str', $o2);

    $oEmpty = Str::_EMPTY();
    $test = Str::set('test');

    $o5 = Str::_EMPTY();
    $rtn = $o5->prepend($test);
    $this->assertSame($rtn, $test);
    $this->assertSame($o5, $oEmpty);
    $this->assertNotSame($rtn, $oEmpty);
    $this->assertSame('', (string) $o5);
  }

  /**
   * Collection of assertions for svelte\core\Str::append().
   * - assert provided value removed from end on returned {@link \svelte\core\Str}
   * - assert original {@link \svelte\core\Str}'s state in unchanged
   * - assert only last occurrence of provided value removed from Str
   * - assert returned object is instance of {@link \svelte\core\Str}
   * @link svelte.core.Str#method_append \svelte\core\Str::append()
   */
  public function testTrimEnd()
  {

    $s1 = 'And the beast reborn spread over the earth';
    $s2 = 'And the beast reborn spread over the';
    $s3 = 'And the beast reborn spread over';

    $o1 = Str::set($s1);
    $o2 = $o1->trimEnd(Str::set(' earth'));
    $o3 = $o2->trimEnd(Str::set(' the'));

    $this->assertSame($s1, (string)$o1);
    $this->assertSame($s2, (string)$o2);
    $this->assertSame($s3, (string)$o3);

    $this->assertInstanceOf('svelte\core\Str', $o1);
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertInstanceOf('svelte\core\Str', $o3);

    $oEmpty = Str::_EMPTY();
    $test = Str::set('test');

    $o5 = Str::_EMPTY();
    $rtn = $o5->trimEnd($test);
    $this->assertSame($rtn, $oEmpty);
    $this->assertSame($o5, $oEmpty);
    $this->assertSame('', (string) $o5);
  }

  /**
   * Collection of assertions for svelte\core\Str::camelCase().
   * - assert any attempt to camelCase {@link \svelte\core\Str::_EMPTY()} return _EMPTY()
   * - assert correct camelcasing of space seperated words
   * - assert correct camelcasing of space seperated words with lowercase first letter
   * - assert correct camelcasing of hyphen sepperated words
   * - assert correct camelcasing of hyphen sepperated words with lowercase first letter
   * @link svelte.core.Str#method_camelCase \svelte\core\Str::camelCase()
   */
  public function testCamelCase()
  {
    $o1 = Str::camelCase(Str::_EMPTY());
    $this->assertInstanceOf('svelte\core\Str', $o1);
    $this->assertSame(Str::_EMPTY(), $o1);

    $o2 = Str::camelCase(Str::set('camel case this'));
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertEquals(Str::set('CamelCaseThis'), $o2);
    $this->assertSame((string)Str::set('CamelCaseThis'), (string)$o2);

    $o3 = Str::camelCase(Str::set('camel case this'), TRUE);
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertEquals(Str::set('camelCaseThis'), $o3);
    $this->assertSame((string)Str::set('camelCaseThis'), (string)$o3);

    $o4 = Str::camelCase(Str::set('camel-case-this'));
    $this->assertInstanceOf('svelte\core\Str', $o4);
    $this->assertEquals(Str::set('CamelCaseThis'), $o4);
    $this->assertSame((string)Str::set('CamelCaseThis'), (string)$o4);

    $o5 = Str::camelCase(Str::set('camel-case-this'), TRUE);
    $this->assertInstanceOf('svelte\core\Str', $o5);
    $this->assertEquals(Str::set('camelCaseThis'), $o5);
    $this->assertSame((string)Str::set('camelCaseThis'), (string)$o5);
  }

  /**
   * Collection of assertions for svelte\core\Str::hyphenate().
   * - assert any attempt to hyphenate {@link \svelte\core\Str::_EMPTY()} returns _EMPTY()
   * - assert correct hyphenation of space seperated words
   * - assert correct hyphenation of space seperated words all converted to lowercase
   * - assert correct hyphenation of camlecased string
   * @link svelte.core.Str#method_hyphenate \svelte\core\Str::hyphenate()
   */
  public function testHyphenate()
  {
    $o1 = Str::hyphenate(Str::_EMPTY());
    $this->assertInstanceOf('svelte\core\Str', $o1);
    $this->assertSame(Str::_EMPTY(), $o1);

    $o2 = Str::hyphenate(Str::set('hyphe nate me'));
    $this->assertInstanceOf('svelte\core\Str', $o2);
    $this->assertEquals(Str::set('hyphe-nate-me'), $o2);
    $this->assertSame((string)Str::set('hyphe-nate-me'), (string)$o2);

    $o3 = Str::hyphenate(Str::set('Hyphe Nate Me'));
    $this->assertInstanceOf('svelte\core\Str', $o3);
    $this->assertEquals(Str::set('hyphe-nate-me'), $o3);
    $this->assertSame((string)Str::set('hyphe-nate-me'), (string)$o3);

    $o4 = Str::hyphenate(Str::set('HypheNateMe'));
    $this->assertInstanceOf('svelte\core\Str', $o4);
    $this->assertEquals(Str::set('hyphe-nate-me'), $o4);
    $this->assertSame((string)Str::set('hyphe-nate-me'), (string)$o4);
  }

  /**
   * Collection of assertions for svelte\core\Str::contains().
   * - assert returns {@link \svelte\core\Boolean::TRUE()} when {@link \svelte\core\Str}
   *   - contains at least one maching {@link \svelte\core\Str} within provided {@link \svelte\core\Collection}
   * - assert evaluates true when {@link \svelte\core\Boolean::get()} called on result from {@link \svelte\core\Str}
   *   - containing at least one {@link \svelte\core\Str} within provided {@link \svelte\core\Collection}
   * - assert returns {@link \svelte\core\Boolean::FALSE()} when {@link \svelte\core\Str}
   *   - does NOT contains at least one maching {@link \svelte\core\Str} within provided {@link \svelte\core\Collection}
   * - assert evaluates false when {@link \svelte\core\Boolean::get()} called on result from {@link \svelte\core\Str}
   *   - NOT containing at least one {@link \svelte\core\Str} within provided {@link \svelte\core\Collection}
   * - assert throws InvalidArgumentException When Collection NOT a composite of {@link \svelte\core\Str}s
   *   - with message: <em>'Provided Collection MUST be a composite of \svelte\core\Strs'</em>
   * @link svelte.core.Str#method_contains \svelte\core\Str::contains()
   *
  public function testContains()
  {
    $testSvelteObject = Str::set('My name is Tom Thumb');
    $testSvelteObject2 = Str::set('Marry had a little lamb');

    $stringCollection = new Collection(Str::set('svelte\core\Str'));
    $stringCollection->add(Str::set('Jack'));
    $stringCollection->add(Str::set('Georgie'));
    $stringCollection->add(Str::set('Tom'));
    $stringCollection->add(Str::set('Bill'));

    $this->assertSame(Boolean::TRUE(), $testSvelteObject->contains($stringCollection));
    $this->assertTrue($testSvelteObject->contains($stringCollection)->get());

    $this->assertSame(Boolean::FALSE(), $testSvelteObject2->contains($stringCollection));
    $this->assertFalse($testSvelteObject2->contains($stringCollection)->get());

    $mockSvelteObjectCollection = new Collection(Str::set('\tests\svelte\core\MockSvelteObject'));
    try {
      $testSvelteObject->contains($mockSvelteObjectCollection);
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'Provided Collection MUST be a composite of \svelte\core\Strs', $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }*/
}
