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
namespace tests\ramp\core;

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\core\PropertyNotSetException;

/**
 * Collection of tests for \ramp\core\Str.
 */
class StrTest extends \tests\ramp\core\ObjectTest
{
  #region Setup
  #[\Override]
  protected function getTestObject() : RAMPObject { return Str::set(); }
  #endregion

  /**
   * Default base constructor assertions \ramp\core\Str.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\core\Str}
   * - assert is private inaccessible
   * @see \ramp\core\Str
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\core\Str', $this->testObject);
    try {
      $testObject = new Str();
    } catch (\Throwable $expected) {
      $this->assertTrue(true);
      return;
    }
    $this->fail('An expected Error: Call to private ramp\core\Str::__construct() has NOT been raised.');
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
    $this->assertSame('', (string)$this->testObject);
  }
  #endregion
  
  #region New Specialist Tests
  /**
   * Collection of assertions Str::$lowercase.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'lowercase'
   *   - with message: *'[className]->lowercase is NOT settable'*
   * - assert allows retrieval of 'lowercase'
   * - assert retreved is an instance of {@see \ramp\core\Str}
   * - assert each subsequent is same instance as first
   * - assert retreved matches expected lowercase instance of original
   * @see \ramp\core\Str::$lowercase
   */
  public function testLowercase() : void
  {
    $o1 = Str::set('Hello');
    try {
      $o1->lowercase = Str::set('goodbye');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\core\Str->lowercase is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $o1->lowercase);
      $this->assertSame($o1->lowercase, $o1->lowercase);
      $this->assertSame('hello', (string)$o1->lowercase);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for ramp\core\Str::uppercase.
   * - assert throws {@see \ramp\core\PropertyNotSetException} trying to set 'uppercase'
   *   - with message: *'[className]->uppercase is NOT settable'*
   * - assert allows retrieval of 'uppercase'
   * - assert retreved is an instance of {@see \ramp\core\Str}
   * - assert each subsequent is same instance as first
   * - assert retreved matches expected lowercase instance of original
   * @see \ramp\core\Str::uppercase
   */
  public function testUppercase() : void
  {
    $o1 = Str::set('Hello');
    try {
      $o1->uppercase = Str::set('GOODBYE');
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(
        'ramp\core\Str->uppercase is NOT settable',
        $expected->getMessage()
      );
      $this->assertInstanceOf('\ramp\core\Str', $o1->uppercase);
      $this->assertSame($o1->uppercase, $o1->uppercase);
      $this->assertSame('HELLO', (string)$o1->uppercase);
      return;
    }
    $this->fail('An expected ramp\core\PropertyNotSetException has NOT been raised');
  }

  /**
   * Collection of assertions for ramp\core\Str::set().
   * - assert accepts string literal
   * - assert accepts string literal concatenated
   * - assert accepts {@see \ramp\core\Str} concatenated (as string literal)
   * - assert that each is instance of {@see \ramp\core\Str}
   * - assert that each is instance of {@see \ramp\core\RAMPObject}
   * @see \ramp\core\Str::set()
   */
  public function testSet() : void
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
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::_EMPTY()}
   * - assert returns the SAME instance on calling {@see \ramp\core\Str::set()} with no param
   * - assert returns the SAME instance on calling \ramp\core\Str::set('') (param of ''(empty string))
   * - assert when cast to (string) returns (empty string literal)
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::_EMPTY()
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
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::SPACE()}
   * - assert returns the SAME instance on calling set(' ')
   * - assert when cast to (string) returns (string literal ' ')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::SPACE()
   */
  public function testSPACE() : void
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
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::COLON()}
   * - assert returns the SAME instance on calling set(':')
   * - assert when cast to (string) returns (string literal ':')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::COLON()
   */
  public function testCOLON() : void
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
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::SEMICOLON()}
   * - assert returns the SAME instance on calling set(';')
   * - assert when cast to (string) returns (string literal ';')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::SEMICOLON()
   */
  public function testSEMICOLON() : void
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
   * Collection of assertions for ramp\core\Str::BAR().
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::BAR()}
   * - assert returns the SAME instance on calling set('|')
   * - assert when cast to (string) returns (string literal '|')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::BAR()
   */
  public function testBAR() : void
  {
    $o1 = Str::BAR();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::BAR();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set('|');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame('|', (string) $o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::NEW().
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::NEW()}
   * - assert returns the SAME instance on calling set('new')
   * - assert when cast to (string) returns (string literal 'new')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::NEW()
   */
  public function testNEW() : void
  {
    $o1 = Str::NEW();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::NEW();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set('new');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame('new', (string) $o1);
  }
  
  /**
   * Collection of assertions for ramp\core\Str::PLUS().
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::PLUS()}
   * - assert returns the SAME instance on calling set('+')
   * - assert when cast to (string) returns (string literal '+')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::PLUS()
   */
  public function testPLUS() : void
  {
    $o1 = Str::PLUS();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::PLUS();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set('+');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame('+', (string) $o1);
  }
  
  /**
   * Collection of assertions for ramp\core\Str::FK().
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::FK()}
   * - assert returns the SAME instance on calling set('FK_')
   * - assert when cast to (string) returns (string literal 'FK_')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::FK()
   */
  public function testFK() : void
  {
    $o1 = Str::FK();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::FK();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set('fk_');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame('fk_', (string) $o1);
  }
  
  /**
   * Collection of assertions for ramp\core\Str::UNDERLINE().
   * - assert returns the SAME instance on every call of {@see \ramp\core\Str::UNDERLINE()}
   * - assert returns the SAME instance on calling set('_')
   * - assert when cast to (string) returns (string literal '_')
   * - assert that each is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::UNDERLINE()
   */
  public function testUNDERLINE() : void
  {
    $o1 = Str::UNDERLINE();
    $this->assertInstanceOf('ramp\core\Str', $o1);

    $o2 = Str::UNDERLINE();
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertSame($o1, $o2);

    $o3 = Str::set('_');
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertSame($o1, $o3);

    $this->assertEquals($o1, $o2);
    $this->assertSame('_', (string) $o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::append().
   * - assert returns string same as handed to constructor + appended param
   * - assert returned {@see \ramp\core\Str} NOT same as original
   * - assert returned {@see \ramp\core\Str} is still instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::append()
   */
  public function testAppend() : void
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
   * - assert returned {@see \ramp\core\Str} NOT same as original
   * - assert returned {@see \ramp\core\Str} is still instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::prepend()
   */
  public function testPrepend() : void
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
   * - assert provided value removed from end on returned {@see \ramp\core\Str}
   * - assert original {@see \ramp\core\Str}'s state in unchanged
   * - assert only last occurrence of provided value removed from Str
   * - assert returned object is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::trimEnd()
   */
  public function testTrimEnd() : void
  {
    $s1 = 'And the beast reborn spread over the earth ';
    $s2 = 'And the beast reborn spread over the earth';
    $s3 = 'And the beast reborn spread over the';
    $s4 = 'And the beast reborn spread over';

    $o1 = Str::set($s1);
    $o2 = $o1->trimEnd();
    $o3 = $o2->trimEnd(Str::set(' earth'));
    $o4 = $o3->trimEnd(Str::set(' the'));

    $this->assertSame($s1, (string)$o1);
    $this->assertSame($s2, (string)$o2);
    $this->assertSame($s3, (string)$o3);
    $this->assertSame($s4, (string)$o4);

    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertInstanceOf('ramp\core\Str', $o4);

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
   * - assert provided value removed from end on returned {@see \ramp\core\Str}
   * - assert original {@see \ramp\core\Str}'s state in unchanged
   * - assert only last occurrence of provided value removed from Str
   * - assert returned object is instance of {@see \ramp\core\Str}
   * @see \ramp\core\Str::trimStart()
   */
  public function testTrimStart() : void
  {
    $s1 = ' And the beast reborn spread over the earth';
    $s2 = 'And the beast reborn spread over the earth';
    $s3 = 'the beast reborn spread over the earth';
    $s4 = 'beast reborn spread over the earth';

    $o1 = Str::set($s1);
    $o2 = $o1->trimStart();
    $o3 = $o2->trimStart(Str::set('And '));
    $o4 = $o3->trimStart(Str::set('the '));

    $this->assertSame($s1, (string)$o1);
    $this->assertSame($s2, (string)$o2);
    $this->assertSame($s3, (string)$o3);
    $this->assertSame($s4, (string)$o4);

    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertInstanceOf('ramp\core\Str', $o4);

    $oEmpty = Str::_EMPTY();
    $test = Str::set('test');

    $o5 = Str::_EMPTY();
    $rtn = $o5->trimStart($test);
    $this->assertSame($rtn, $oEmpty);
    $this->assertSame($o5, $oEmpty);
    $this->assertSame('', (string) $o5);
  }

  /**
   * 
   *
  public function testTrim()
  {
    $s1 = ' And the beast reborn spread over the earth ';
    $s2 = 'And the beast reborn spread over the earth';
    $s3 = 'And the beast reborn spread over the';
    $s4 = 'the beast reborn spread over the';
    $s5 = 'beast reborn spread over';

    $o1 = Str::set($s1);
    $o2 = $o1->trim();
    $o3 = $o2->trim(Str::set(' earth'));
    $o4 = $o3->trim(Str::set('And '));
    $o5 = $o4->trim(Str::set('the'));

    $this->assertSame($s1, (string)$o1);
    $this->assertSame($s2, (string)$o2);
    $this->assertSame($s3, (string)$o3);
    $this->assertSame($s4, (string)$o4);
    $this->assertSame($s5, (string)$o5);

    $this->assertInstanceOf('ramp\core\Str', $o1);
    $this->assertInstanceOf('ramp\core\Str', $o2);
    $this->assertInstanceOf('ramp\core\Str', $o3);
    $this->assertInstanceOf('ramp\core\Str', $o4);
    $this->assertInstanceOf('ramp\core\Str', $o5);
  }*/

  /**
   * Collection of assertions for ramp\core\Str::replace().
   * - assert correct replacement of searched sub string on all occurrences of sub string
   * - assert original value remains unchanged.
   * @see \ramp\core\Str::replace()
   */
  public function testReplace() : void
  {
    $o1 = Str::set('Hello World!');
    $o2 = $o1->replace(Str::set('Hello'), Str::set('Goodbye'));
    $o3 = $o1->replace(Str::set('o'), Str::set('0'));
    $this->assertSame('Goodbye World!', (string)$o2);
    $this->assertSame('Hell0 W0rld!', (string)$o3);
    $this->assertSame('G00dbye W0rld!', (string)$o2->replace(Str::set('o'), Str::set('0')));
    $this->assertSame('Hello World!', (string)$o1);
  }

  /**
   * Collection of assertions for ramp\core\Str::camelCase().
   * - assert any attempt to camelCase {@see \ramp\core\Str::_EMPTY()} return _EMPTY()
   * - assert correct camelcasing of space seperated words
   * - assert correct camelcasing of space seperated words with lowercase first letter
   * - assert correct camelcasing of hyphen sepperated words
   * - assert correct camelcasing of hyphen sepperated words with lowercase first letter
   * @see \ramp\core\Str::camelCase()
   */
  public function testCamelCase() : void
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
   * - assert any attempt to hyphenate {@see \ramp\core\Str::_EMPTY()} returns _EMPTY()
   * - assert correct hyphenation of space seperated words
   * - assert correct hyphenation of space seperated words all converted to lowercase
   * - assert correct hyphenation of camlecased string
   * @see \ramp\core\Str::hyphenate()
   */
  public function testHyphenate() : void
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
   * - assert returns TRUE when this contains at least one maching substring from provided {@see \ramp\core\StrCollection}
   * - assert returns FALSE when thisdoes NOT contains maching substring from provided {@see \ramp\core\StrCollection}
   * @see \ramp\core\Str::contains()
   */
  public function testContains() : void
  {
    $testObject = Str::set('My name is Tom Thumb');
    $testObject2 = Str::set('Marry had a little lamb');
    $searchSubstrings = StrCollection::set('Jack', 'Georgie', 'Tom', 'Bill');
    $this->assertFalse(Str::_EMPTY()->contains($searchSubstrings));
    $this->assertTrue($testObject->contains($searchSubstrings));
    $this->assertFalse($testObject2->contains($searchSubstrings));
  }
  #endregion
}
