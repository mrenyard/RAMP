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

require_once '/usr/share/php/tests/ramp/core/mocks/CollectionTest/BadObject.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\StrCollection;

use tests\ramp\core\mocks\CollectionTest\BadObject;

/**
 * Collection of tests for \ramp\core\StrCollection.
 */
class StrCollectionTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->testObject = StrCollection::set();
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::__construct().
   * - assert is instance of {@link \ramp\core\StrCollection}
   * - assert is instance of {@link \ramp\core\Collection}
   * - assert is instance of {@link \ramp\core\iCollection}
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws InvalidAgumentException if any provided arguments are NOT string literal.
   *   - with message: <em>'All arguments MUST be string literals!'</em>
   * @link ramp.core.StrCollection \ramp\core\StrCollection
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('ramp\core\StrCollection', $this->testObject);
    $this->assertInstanceOf('ramp\core\Collection', $this->testObject);
    $this->assertInstanceOf('ramp\core\iCollection', $this->testObject);
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertSame(0, $this->testObject->count);
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::set().
   * - assert accepts a single string literal
   * - assert accepts a set of comma seperated string literals
   * - assert each string literal (comma seperated) counts as one item in the collection
   * - assert that and special (_EMPTY,SPACE,COLON,SEMICOLON) exactly same as another.
   * @link ramp.core.StrCollection#method_set \ramp\core\StrCollection::set()
   */
  public function testSet()
  {
    $strArray = ['string one', 'string two', 'string tree'];
    $oneStringCollection = StrCollection::set($strArray[0]);
    $this->assertSame(1, $oneStringCollection->count);
    $twoStringCollection = StrCollection::set($strArray[0], $strArray[1]);
    $this->assertSame(2, $twoStringCollection->count);
    $threeStringCollection = StrCollection::set($strArray[0], $strArray[1], $strArray[2]);
    $this->assertSame(3, $threeStringCollection->count);
    $i = 0;
    foreach ($threeStringCollection as $strValue) {
      $this->assertInstanceof('ramp\core\Str', $strValue);
      $this->assertSame((string)$strValue, $strArray[$i]);
      $i++;
    }
    $testObject = StrCollection::set('',' ',':',';','|');
    $this->assertSame($testObject[0], Str::_EMPTY());
    $this->assertSame($testObject[1], Str::SPACE());
    $this->assertSame($testObject[2], Str::COLON());
    $this->assertSame($testObject[3], Str::SEMICOLON());
    $this->assertSame($testObject[4], Str::BAR());
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::implode().
   * - assert returns instance of {@link \ramp\core\Str}.
   * - assert returns expected concatenated string value of Str.
   * - assert returns expected concatenated string value including provided glue.
   * @link ramp.core.StrCollection#method_implode \ramp\core\StrCollection::implode()
   */
  public function testImplode()
  {
    $strArray = ['string one', 'string two', 'string tree'];
    $testObject = StrCollection::set($strArray[0], $strArray[1], $strArray[2]);
    $result = $testObject->implode();
    $this->assertInstanceOf('ramp\core\Str', $result);
    $this->assertSame("string onestring twostring tree", (string)$result);
    $result = $testObject->implode(Str::set(' | '));
    $this->assertInstanceOf('ramp\core\Str', $result);
    $this->assertSame("string one | string two | string tree", (string)$result);
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::contains().
   * - assert returns bool where anyone of the containd Str values matches the value of privide Str
   * @link ramp.core.StrCollection#method_contains \ramp\core\StrCollection::contains()
   */
  public function testContains()
  {
    $testObject = StrCollection::set('car', 'van', 'motorbike');
    $this->assertFalse($testObject->contains(Str::set('bicycle')));
    $this->assertTrue($testObject->contains(Str::set('motorbike')));
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::isCompositeType().
   * - assert returns TRUE when $compositeType {@link \ramp\core\Str}
   * - assert returns FALSE when $compositeType name provided is NOT {@link \ramp\core\Str}
   * @link ramp.core.StrCollection#method_isCompositeType \ramp\core\StrCollection::isCompositeType()
   */
  public function testIsCompositeType()
  {
    $this->assertTrue($this->testObject->isCompositeType(Str::set('ramp\core\Str')));
    $this->assertFalse($this->testObject->isCompositeType(Str::set('\not\Str')));
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::getIterator(), add() and count.
   * - assert handle unpopulated {@link \ramp\core\StrCollection} iteration without fail
   * - assert {@link \ramp\core\StrCollection::add()} only accepts {@link \ramp\core\Str}
   * - assert Count equal to number of objects added.
   * - assert collection object references occupy SAME position as added
   * - assert {@link \ramp\core\Collection::offsetGet}($outOfBoundsOffset) throws \OutOfBoundsException
   *   - with message: <em>'Offset out of bounds'</em>
   * @link ramp.core.StrCollection#method_getIterator \ramp\core\StrCollection::getIterator()
   * @link ramp.core.StrCollection#method_add \ramp\core\StrCollection::add()
   * @link ramp.core.StrCollection#method_count \ramp\core\StrCollection::count
   */
  public function testIteratorAddCount()
  {
    foreach ($this->testObject as $o)
    {
      $this->fail('Unexpected iteration of empty Collection');
    }
    $this->assertEquals(0, $this->testObject->count);
    $this->assertEquals(0, $this->testObject->count());
    try {
      $this->testObject->add(new BadObject());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\core\mocks\CollectionTest\BadObject NOT instanceof ramp\core\Str',
        $expected->getMessage()
      );
      $i = 0; $j = 0;
      $o1 = Str::set('item' . $i++);
      $this->testObject->add($o1);
      foreach ($this->testObject as $o)
      {
        $j++;
        if ($j === 1) { $this->assertSame($o1, $o); }
      }
      $this->assertSame(1, $j);
      $this->assertEquals(1, $this->testObject->count);
      $this->assertEquals(1, $this->testObject->count());
      $o2 = Str::set('item' . $i++);
      $this->testObject->add($o2);
      $j = 0;
      foreach ($this->testObject as $o)
      {
        $j++;
        if ($j === 1) { $this->assertSame($o1, $o); }
        if ($j === 2) { $this->assertSame($o2, $o); }
      }
      $this->assertSame(2, $j);
      $this->assertSame($o2, $this->testObject[1]);
      $this->assertEquals(2, $this->testObject->count);
      $j = 0;
      $o3 = Str::set('item' . $i++);
      $this->testObject->add($o3);
      foreach ($this->testObject as $o)
      {
        $j++;
        if ($j === 1) { $this->assertSame($o1, $o); }
        if ($j === 2) { $this->assertSame($o2, $o); }
        if ($j === 3) { $this->assertSame($o3, $o); }
      }
      $this->assertSame(3, $j);
      $this->assertEquals(3, $this->testObject->count);
      $this->assertEquals(3, $this->testObject->count());
      $o4 = Str::set('item' . $i++);
      $this->testObject->add($o4);
      $j = 0;
      foreach ($this->testObject as $o)
      {
        $j++;
        if ($j === 1) { $this->assertSame($o1, $o); }
        if ($j === 2) { $this->assertSame($o2, $o); }
        if ($j === 3) { $this->assertSame($o3, $o); }
        if ($j === 4) { $this->assertSame($o4, $o); }
      }
      $this->assertSame(4, $j);
      $this->assertEquals(4, $this->testObject->count);
      $this->assertEquals(4, $this->testObject->count());
      $this->assertFalse(isset($this->testObject[4]));
      $this->assertTrue(isset($this->testObject[3]));
      $this->assertSame($o4, $this->testObject[3]);
      $this->assertTrue(isset($this->testObject[2]));
      $this->assertSame($o3, $this->testObject[2]);
      $this->assertTrue(isset($this->testObject[1]));
      $this->assertSame($o2, $this->testObject[1]);
      $this->assertTrue(isset($this->testObject[0]));
      $this->assertSame($o1, $this->testObject[0]);
      try {
        $this->testObject[4];
      } catch (\OutOfBoundsException $expected) {
        $this->assertSame('Offset out of bounds', $expected->getMessage());
        return;
      }
      $this->fail('An expected \OutOfBoundsException has NOT been raised.');
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::offsetSet().
   * - assert {@link \ramp\core\StrCollection::OffsetSet()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: <em>'[provided object] NOT instanceof [expected type]'</em>
   * - assert value set with name key is same as retived with same name key
   * - assert value set at index same as retived at index.
   * @link ramp.core.StrCollection#method_offsetSet \ramp\core\StrCollection::offsetSet()
   */
  public function testOffsetSet()
  {
    $expectedAtNameIndex = Str::set('named');
    $expectedAt0Index = Str::set('indexed');
    $this->testObject['name'] = $expectedAtNameIndex;
    $this->testObject[0] = $expectedAt0Index;
    try {
      $this->testObject['name'] = new BadObject();
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\core\mocks\CollectionTest\BadObject NOT instanceof ramp\core\Str',
        $expected->getMessage()
      );
      $this->assertSame($expectedAtNameIndex, $this->testObject['name']);
      $this->assertSame($expectedAt0Index, $this->testObject[0]);
      return $this->testObject;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::offsetUnset().
   * - assert value unset with name key is no longer retivable with same name key
   * - assert value set at index is no longer retivable at same index.
   * @depends testOffsetSet
   * @param Collection The test object.
   * @link ramp.core.StrCollection#method_offsetUnset \ramp\core\mocks\CollectionTest\StrCollection::offsetUnset()
   */
  public function testOffsetUnset(StrCollection $testObject)
  {
    $this->assertInstanceOf('ramp\core\Str', $testObject['name']);
    $this->assertInstanceOf('ramp\core\Str', $testObject[0]);
    unset($testObject['name']);
    unset($testObject[0]);
    $this->assertFalse(isset($testObject['name']));
    $this->assertFalse(isset($testObject[0]));
  }
}
