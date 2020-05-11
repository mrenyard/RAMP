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
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';

require_once '/usr/share/php/tests/svelte/core/mocks/CollectionTest/AnObject.class.php';
require_once '/usr/share/php/tests/svelte/core/mocks/CollectionTest/BadObject.class.php';

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\core\Collection;

use tests\svelte\core\mocks\CollectionTest\AnObject;
use tests\svelte\core\mocks\CollectionTest\BadObject;

/**
 * Collection of tests for \svelte\core\Collection.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\condition\mocks\CollectionTest\AnObject}
 * - {@link \tests\svelte\condition\mocks\CollectionTest\BadObject}
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
  private $typeName;
  private $expectedAtNameIndex;
  private $expectedAt0Index;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    $this->typeName = Str::set('tests\svelte\core\mocks\CollectionTest\AnObject');
  }

  /**
   * Collection of assertions for svelte\core\Collection::__construct().
   * - assert is instance of {@link \svelte\core\Collection}
   * - assert is instance of {@link \svelte\core\iCollection}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws InvalidAgumentException if provided Str is NOT an accessible class name
   *   - with message: <em>'$compositeType MUST be an accesible class name'</em>
   * @link svelte.core.Collection \svelte\core\Collection
   */
  public function test__Construct()
  {
    $testObject = new Collection($this->typeName);
    $this->assertInstanceOf('svelte\core\Collection', $testObject);
    $this->assertInstanceOf('svelte\core\iCollection', $testObject);
    $this->assertInstanceOf('svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\IteratorAggregate', $testObject);
    $this->assertInstanceOf('\Countable', $testObject);
    $this->assertInstanceOf('\ArrayAccess', $testObject);
    try {
      $testObject = new Collection(Str::set('\not\a\Class'));
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame('$compositeType MUST be an accessible class name or interface.', $expected->getMessage());
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  /**
   * Collection of assertions for svelte\core\Collection::isCompositeType().
   * - assert returns TRUE when $compositeType name provided to constructor is
   *    same as provided {@link \svelte\core\Str}
   * - assert evaluates TRUE where $compositeType name provided to constructor is
   *    same as provided {@link \svelte\core\Str}
   * - assert returns FALSE when $compositeType name provided to constructor is
   *    NOT same as provided {@link \svelte\core\Str}
   * - assert evaluates FALSE where $compositeType name provided to constructor is
   *    NOT same as provided {@link \svelte\core\Str}
   * @link svelte.core.Collection#method_isCompositeType \svelte\core\Collection::isCompositeType()
   */
  public function testIsCompositeType()
  {
    $testObject = new Collection($this->typeName);
    $notAClass = Str::set('\not\a\Class');
    $this->assertTrue($testObject->isCompositeType($this->typeName));
    $this->assertFalse($testObject->isCompositeType($notAClass));
  }

  /**
   * Collection of assertions for svelte\core\Collection::getIterator(), add() and count.
   * - assert handle unpopulated {@link \svelte\core\Collection} iteration without fail
   * - assert {@link \svelte\core\Collection::add()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: <em>'[provided object] NOT instanceof [expected type]'</em>
   * - assert Count equal to number of objects added.
   * - assert collection object references occupy SAME position as added
   * - assert {@link \svelte\core\Collection::offsetGet}($outOfBoundsOffset) throws \OutOfBoundsException
   *   - with message: <em>'Offset out of bounds'</em>
   * @link svelte.core.Collection#method_getIterator \svelte\core\Collection::getIterator()
   * @link svelte.core.Collection#method_add \svelte\core\Collection::add()
   * @link svelte.core.Collection#method_count \svelte\core\Collection::count
   */
  public function testIteratorAddCount()
  {
    $testObject = new Collection($this->typeName);
    foreach ($testObject as $o)
    {
      $this->fail('Unexpected iteration of empty Collection');
    }
    $this->assertEquals(0, $testObject->count);
    $this->assertEquals(0, $testObject->count());
    try {
      $testObject->add(new BadObject());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\svelte\core\mocks\CollectionTest\BadObject NOT instanceof tests\svelte\core\mocks\CollectionTest\AnObject',
        $expected->getMessage()
      );
      $i = 0;
      $o1 = new AnObject();
      $testObject->add($o1);
      foreach ($testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
      }
      $this->assertSame(1, $i);
      $this->assertEquals(1, $testObject->count);
      $this->assertEquals(1, $testObject->count());
      $i = 0;
      $o2 = new AnObject();
      $testObject->add($o2);
      foreach ($testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
        if ($i === 2) { $this->assertSame($o2, $o); }
      }
      $this->assertSame(2, $i);
      $this->assertSame($o2, $testObject[1]);
      $this->assertEquals(2, $testObject->count);
      $i = 0;
      $o3 = new AnObject();
      $testObject->add($o3);
      foreach ($testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
        if ($i === 2) { $this->assertSame($o2, $o); }
        if ($i === 3) { $this->assertSame($o3, $o); }
      }
      $this->assertSame(3, $i);
      $this->assertEquals(3, $testObject->count);
      $this->assertEquals(3, $testObject->count());
      $i = 0;
      $o4 = new AnObject();
      $testObject->add($o4);
      foreach ($testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
        if ($i === 2) { $this->assertSame($o2, $o); }
        if ($i === 3) { $this->assertSame($o3, $o); }
        if ($i === 4) { $this->assertSame($o4, $o); }
      }
      $this->assertSame(4, $i);
      $this->assertEquals(4, $testObject->count);
      $this->assertEquals(4, $testObject->count());
      $this->assertFalse(isset($testObject[4]));
      $this->assertTrue(isset($testObject[3]));
      $this->assertSame($o4, $testObject[3]);
      $this->assertTrue(isset($testObject[2]));
      $this->assertSame($o3, $testObject[2]);
      $this->assertTrue(isset($testObject[1]));
      $this->assertSame($o2, $testObject[1]);
      $this->assertTrue(isset($testObject[0]));
      $this->assertSame($o1, $testObject[0]);
      try {
        $testObject[4];
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
   * Collection of assertions for svelte\core\Collection::offsetSet().
   * - assert {@link \svelte\core\Collection::OffsetSet()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: <em>'[provided object] NOT instanceof [expected type]'</em>
   * - assert value set with name key is same as retived with same name key
   * - assert value set at index same as retived at index.
   * @link svelte.core.Collection#method_offsetSet \svelte\core\mocks\CollectionTest\Collection::offsetSet()
   */
  public function testOffsetSet()
  {
    $testObject = new Collection($this->typeName);
    $expectedAtNameIndex = new AnObject();
    $expectedAt0Index = new AnObject();
    $testObject['name'] = $expectedAtNameIndex;
    $testObject[0] = new AnObject();
    try {
      $testObject['name'] = new BadObject();
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\svelte\core\mocks\CollectionTest\BadObject NOT instanceof tests\svelte\core\mocks\CollectionTest\AnObject',
        $expected->getMessage()
      );
      $testObject[0] = $expectedAt0Index;
      $this->assertSame($expectedAtNameIndex, $testObject['name']);
      $this->assertSame($expectedAt0Index, $testObject[0]);
      return $testObject;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for svelte\core\Collection::offsetUnset().
   * - assert value unset with name key is no longer retivable with same name key
   * - assert value set at index is no longer retivable at same index.
   * @depends testOffsetSet
   * @param Collection The test object.
   * @link svelte.core.Collection#method_offsetUnset \svelte\core\mocks\CollectionTest\Collection::offsetUnset()
   */
  public function testOffsetUnset(Collection $testObject)
  {
    $this->assertInstanceOf('tests\svelte\core\mocks\CollectionTest\AnObject', $testObject['name']);
    $this->assertInstanceOf('tests\svelte\core\mocks\CollectionTest\AnObject', $testObject[0]);
    unset($testObject['name']);
    unset($testObject[0]);
    $this->assertFalse(isset($testObject['name']));
    $this->assertFalse(isset($testObject[0]));
  }

  /**
   * Collection of assertions for svelte\core\Collection::__clone().
   * - assert Shallow Cloning (default) composite collection is referenced only
   * - assert when Deep Cloning that NEW objects are formed with same values
   * @link svelte.core.Collection#method__clone \svelte\core\mocks\CollectionTest\Collection::__clone()
   */
  public function test__clone()
  {
    $o1 = new AnObject();
    $o1->property = 'value1';
    $o2 = new AnObject();
    $o2->property = 'value2';
    $o3 = new AnObject();
    $o3->property = 'value3';
    $shallowCollection = new Collection($this->typeName);
    $shallowCollection->add($o1);
    $shallowCollection->add($o2);
    $shallowCollection->add($o3);
    $this->assertSame(3, $shallowCollection->count);
    $deepCollection = new Collection($this->typeName, TRUE);
    $deepCollection->add($o1);
    $deepCollection->add($o2);
    $deepCollection->add($o3);
    $this->assertSame(3, $deepCollection->count);
    $shallowClone = clone $shallowCollection;
    $this->assertSame(3, $shallowClone->count);
    $this->assertSame($shallowClone[0], $o1);
    $this->assertSame($shallowClone[1], $o2);
    $this->assertSame($shallowClone[2], $o3);
    $deepClone = clone $deepCollection;
    $this->assertSame(3, $deepClone->count);
    $this->assertNotSame($deepClone[0], $o1);
    $this->assertNotSame($deepClone[1], $o2);
    $this->assertNotSame($deepClone[2], $o3);
    $this->assertEquals($deepClone[0]->property, 'value1');
    $this->assertEquals($deepClone[1]->property, 'value2');
    $this->assertEquals($deepClone[2]->property, 'value3');
  }
}
