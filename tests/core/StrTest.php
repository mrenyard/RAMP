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
 * @package RAMP Testing
 * @version 0.0.9;
 */
namespace tests\ramp\core;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\StrCollection;

/**
 * Collection of tests for \ramp\core\Str.
 */
class StrTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for ramp\core\Str::__construct().
   * - assert is private inaccessable
   * @link ramp.core.Str \ramp\core\Str
   */
  public function test__construct()
  {
    try {
      $testRAMPObject = new Str();
    } catch (\Throwable $expected) {
      $this->assertTrue(true);
      return;
    }
    $this->fail('An expected Error: Call to private ramp\core\Str::__construct() has NOT been raised.');
  }

  /**
   * Collection of assertions for ramp\core\Str::set().
   * - assert accepts string literal
   * - assert accepts string literal concatenated
   * - assert accepts {@link \ramp\core\Str} concatenated (as string literal)
   * - assert that each is instance of {@link \ramp\core\Str}
   * - assert that each is instance of {@link \ramp\core\RAMPObject}
   * @link ramp.core.Str#method_set \ramp\core\Str::set()
   */
  public function testSet()
  {
    $o1 = Str::set('string Literal');
    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertInstanceOf('ramp\core\RAMPObject', $o1);

    $o2 = Str::set('string ' . 'Literal');
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertInstanceOf('ramp\core\RAMPObject', $o2);

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
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertInstanceOf('ramp\core\RAMPObject', $o3);
  }

  /**
   * Collection of assertions for ramp\core\Str::_EMPTY().
   * - assert returns the SAME instance on every call of {@link \ramp\core\Str::_EMPTY()}
   * - assert returns the SAME instance on calling {@link \ramp\core\Str::set()} with no param
   * - assert returns the SAME instance on calling \ramp\core\Str::set('') (param of ''(empty string))
   * - assert when cast to (string) returns (empty string literal)
   * - assert that each is instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method__EMPTY \ramp\core\Str::_EMPTY()
   */
  public function test_EMPTY()
  {
    $o1 = Str::_EMPTY();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::_EMPTY();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set();
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $o4 = Str::set('');
    $this->assertInstanceOf('ramp\core\Str', $o4);
    $this->assertSame($o1, $o4);

    $this->assertEquals($o1, $o2);
    $this->assertSame('', (string) $o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::SPACE().
   * - assert returns the SAME instance on every call of {@link \ramp\core\Str::SPACE()}
   * - assert returns the SAME instance on calling set(' ')
   * - assert when cast to (string) returns (string literal ' ')
   * - assert that each is instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_SPACE \ramp\core\Str::SPACE()
   */
  public function testSPACE()
  {
    $o1 = Str::SPACE();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::SPACE();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set(' ');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame(' ', (string) $o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::COLON().
   * - assert returns the SAME instance on every call of {@link \ramp\core\Str::COLON()}
   * - assert returns the SAME instance on calling set(':')
   * - assert when cast to (string) returns (string literal ':')
   * - assert that each is instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_COLON \ramp\core\Str::COLON()
   */
  public function testCOLON()
  {
    $o1 = Str::COLON();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::COLON();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set(':');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame(':', (string) $o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::SEMICOLON().
   * - assert returns the SAME instance on every call of {@link \ramp\core\Str::SEMICOLON()}
   * - assert returns the SAME instance on calling set(';')
   * - assert when cast to (string) returns (string literal ';')
   * - assert that each is instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_SEMICOLON \ramp\core\Str::SEMICOLON()
   */
  public function testSEMICOLON()
  {
    $o1 = Str::SEMICOLON();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::SEMICOLON();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set(';');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame(';', (string) $o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::append().
   * - assert returns string same as handed to constructor + appended param
   * - assert returned {@link \ramp\core\Str} NOT same as original
   * - assert returned {@link \ramp\core\Str} is still instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_append \ramp\core\Str::append()
   */
  public function testAppend()
  {
    $o1 = Str::set('Hello');
    $o2 = $o1->append(Str::set(' World'));

    $this->assertSame("Hello", (string) $o1);
    $this->assertSame("Hello World", (string) $o2);
    $this->assertNotSame($o2, $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);

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
   * Collection of assertions for ramp\core\Str::prepend().
   * - assert returns string same as handed to prepended param + constructor
   * - assert returned {@link \ramp\core\Str} NOT same as original
   * - assert returned {@link \ramp\core\Str} is still instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_prepend \ramp\core\Str::prepend()
   */
  public function testPrepend()
  {
    $o1 = Str::set('World');
    $o2 = $o1->prepend(Str::set('Hello '));

    $this->assertSame("World", (string) $o1);
    $this->assertSame("Hello World", (string) $o2);
    $this->assertNotSame($o2, $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);

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
   * Collection of assertions for ramp\core\Str::trimEnd().
   * - assert provided value removed from end on returned {@link \ramp\core\Str}
   * - assert original {@link \ramp\core\Str}'s state in unchanged
   * - assert only last occurrence of provided value removed from Str
   * - assert returned object is instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_trimEnd \ramp\core\Str::trimEnd()
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

    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertInstanceOf('ramp\core\Str', $o3);

    $oEmpty = Str::_EMPTY();
    $test = Str::set('test');

    $o5 = Str::_EMPTY();
    $rtn = $o5->trimEnd($test);
    $this->assertSame($rtn, $oEmpty);
    $this->assertSame($o5, $oEmpty);
    $this->assertSame('', (string) $o5);
  }

  /**
   * Collection of assertions for ramp\core\Str::trimStart().
   * - assert provided value removed from end on returned {@link \ramp\core\Str}
   * - assert original {@link \ramp\core\Str}'s state in unchanged
   * - assert only last occurrence of provided value removed from Str
   * - assert returned object is instance of {@link \ramp\core\Str}
   * @link ramp.core.Str#method_trimStart \ramp\core\Str::trimStart()
   */
  public function testTrimStart()
  {
    $s1 = 'And the beast reborn spread over the earth';
    $s2 = 'the beast reborn spread over the earth';
    $s3 = 'beast reborn spread over the earth';

    $o1 = Str::set($s1);
    $o2 = $o1->trimStart(Str::set('And '));
    $o3 = $o2->trimStart(Str::set('the '));

    $this->assertSame($s1, (string)$o1);
    $this->assertSame($s2, (string)$o2);
    $this->assertSame($s3, (string)$o3);

    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertInstanceOf('ramp\core\Str', $o3);

    $oEmpty = Str::_EMPTY();
    $test = Str::set('test');

    $o5 = Str::_EMPTY();
    $rtn = $o5->trimStart($test);
    $this->assertSame($rtn, $oEmpty);
    $this->assertSame($o5, $oEmpty);
    $this->assertSame('', (string) $o5);
  }

  /**
   * Collection of assertions for ramp\core\Str::camelCase().
   * - assert any attempt to camelCase {@link \ramp\core\Str::_EMPTY()} return _EMPTY()
   * - assert correct camelcasing of space seperated words
   * - assert correct camelcasing of space seperated words with lowercase first letter
   * - assert correct camelcasing of hyphen sepperated words
   * - assert correct camelcasing of hyphen sepperated words with lowercase first letter
   * @link ramp.core.Str#method_camelCase \ramp\core\Str::camelCase()
   */
  public function testCamelCase()
  {
    $o1 = Str::camelCase(Str::_EMPTY());
    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertSame(Str::_EMPTY(), $o1);

    $o2 = Str::camelCase(Str::set('camel case a'));
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertEquals(Str::set('CamelCaseA'), $o2);
    $this->assertSame((string)Str::set('CamelCaseA'), (string)$o2);

    $o3 = Str::camelCase(Str::set('camel case a'), TRUE);
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertEquals(Str::set('camelCaseA'), $o3);
    $this->assertSame((string)Str::set('camelCaseA'), (string)$o3);

    $o4 = Str::camelCase(Str::set('camel-case-a'));
    $this->assertInstanceOf('ramp\core\Str', $o4);
    $this->assertEquals(Str::set('CamelCaseA'), $o4);
    $this->assertSame((string)Str::set('CamelCaseA'), (string)$o4);

    $o5 = Str::camelCase(Str::set('camel-case-a'), TRUE);
    $this->assertInstanceOf('ramp\core\Str', $o5);
    $this->assertEquals(Str::set('camelCaseA'), $o5);
    $this->assertSame((string)Str::set('camelCaseA'), (string)$o5);
  }

  /**
   * Collection of assertions for ramp\core\Str::hyphenate().
   * - assert any attempt to hyphenate {@link \ramp\core\Str::_EMPTY()} returns _EMPTY()
   * - assert correct hyphenation of space seperated words
   * - assert correct hyphenation of space seperated words all converted to lowercase
   * - assert correct hyphenation of camlecased string
   * @link ramp.core.Str#method_hyphenate \ramp\core\Str::hyphenate()
   */
  public function testHyphenate()
  {
    $o1 = Str::hyphenate(Str::_EMPTY());
    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertSame(Str::_EMPTY(), $o1);

    $o2 = Str::hyphenate(Str::set('hyphe nate me'));
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertEquals(Str::set('hyphe-nate-me'), $o2);
    $this->assertSame((string)Str::set('hyphe-nate-me'), (string)$o2);

    $o3 = Str::hyphenate(Str::set('Hyphe Nate Me'));
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertEquals(Str::set('hyphe-nate-me'), $o3);
    $this->assertSame((string)Str::set('hyphe-nate-me'), (string)$o3);

    $o4 = Str::hyphenate(Str::set('HypheNateMe'));
    $this->assertInstanceOf('ramp\core\Str', $o4);
    $this->assertEquals(Str::set('hyphe-nate-me'), $o4);
    $this->assertSame((string)Str::set('hyphe-nate-me'), (string)$o4);

    $o5 = Str::hyphenate(Str::set('TestA'));
    $this->assertInstanceOf('ramp\core\Str', $o5);
    $this->assertEquals(Str::set('test-a'), $o5);
    $this->assertSame((string)Str::set('test-a'), (string)$o5);

    $o6 = Str::hyphenate(Str::set('testB'));
    $this->assertInstanceOf('ramp\core\Str', $o6);
    $this->assertEquals(Str::set('test-b'), $o6);
    $this->assertSame((string)Str::set('test-b'), (string)$o6);
  }

  /**
   * Collection of assertions for ramp\core\Str::contains().
   * - assert returns FALSE when this _EMPTY().
   * - assert returns TRUE when this contains at least one maching substring from provided {@link \ramp\core\StrCollection}
   * - assert returns FALSE when thisdoes NOT contains maching substring from provided {@link \ramp\core\StrCollection}
   * @link ramp.core.Str#method_contains \ramp\core\Str::contains()
   */
  public function testContains()
  {
    $testObject = Str::set('My name is Tom Thumb');
    $testObject2 = Str::set('Marry had a little lamb');
    $searchSubstrings = StrCollection::set('Jack', 'Georgie', 'Tom', 'Bill');
    $this->assertFalse(Str::_EMPTY()->contains($searchSubstrings));
    $this->assertTrue($testObject->contains($searchSubstrings));
    $this->assertFalse($testObject2->contains($searchSubstrings));
  }
}
