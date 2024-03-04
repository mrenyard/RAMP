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

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';

require_once '/usr/share/php/tests/ramp/core/mocks/ListTest/AnObject.class.php';
require_once '/usr/share/php/tests/ramp/core/mocks/ListTest/BadObject.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iList;
use ramp\core\oList;

use tests\ramp\core\mocks\ListTest\AnObject;
use tests\ramp\core\mocks\ListTest\BadObject;

/**
 * List of tests for \ramp\core\List.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\condition\mocks\ListTest\AnObject}
 * - {@see \tests\ramp\condition\mocks\ListTest\BadObject}
 */
class ListTest extends \PHPUnit\Framework\TestCase
{
  private $typeName;
  private $expectedAtNameIndex;
  private $expectedAt0Index;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->typeName = Str::set('tests\ramp\core\mocks\ListTest\AnObject');
  }

  /**
   * Collection of assertions for ramp\core\List.
   * - assert is instance of {@see \ramp\core\List}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws InvalidAgumentException if provided Str is NOT an accessible class name
   *   - with message: *'$compositeType MUST be an accesible class name'*
   * @see \ramp\core\List
   */
  public function test__Construct()
  {
    $testObject = new oList($this->typeName);
    $this->assertInstanceOf('ramp\core\oList', $testObject);
    $this->assertInstanceOf('ramp\core\iList', $testObject);
    $this->assertInstanceOf('ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\IteratorAggregate', $testObject);
    $this->assertInstanceOf('\ArrayAccess', $testObject);
    try {
      $testObject = new oList(Str::set('\not\a\Class'));
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame('$compositeType (\not\a\Class) MUST be an accessible class name or interface.', $expected->getMessage());
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  /**
   * Collection of assertions for ramp\core\List::isCompositeType().
   * - assert returns TRUE when $compositeType name provided to constructor is
   *    same as provided {@see \ramp\core\Str}
   * - assert evaluates TRUE where $compositeType name provided to constructor is
   *    same as provided {@see \ramp\core\Str}
   * - assert returns FALSE when $compositeType name provided to constructor is
   *    NOT same as provided {@see \ramp\core\Str}
   * - assert evaluates FALSE where $compositeType name provided to constructor is
   *    NOT same as provided {@see \ramp\core\Str}
   * @see \ramp\core\List::isCompositeType()
   */
  public function testIsCompositeType() : void
  {
    $testObject = new oList($this->typeName);
    $notAClass = Str::set('\not\a\Class');
    $this->assertTrue($testObject->isCompositeType($this->typeName));
    $this->assertFalse($testObject->isCompositeType($notAClass));
  }

  /**
   * Collection of assertions for ramp\core\List::getIterator(), add() and count.
   * - assert handle unpopulated {@see \ramp\core\List} iteration without fail
   * - assert {@see \ramp\core\List::add()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert Count equal to number of objects added.
   * - assert collection object references occupy SAME position as added
   * - assert {@see \ramp\core\List::offsetGet}($outOfBoundsOffset) throws \OutOfBoundsException
   *   - with message: *'Offset out of bounds'*
   * @see \ramp\core\List::getIterator()
   * @see \ramp\core\List::add()
   * @see \ramp\core\List::count
   *
  public function testIteratorAddCount() : void
  {
    $testObject = new oList($this->typeName);
    foreach ($testObject as $o)
    {
      $this->fail('Unexpected iteration of empty List');
    }
    $this->assertEquals(0, $testObject->count);
    $this->assertEquals(0, $testObject->count());
    try {
      $testObject->add(new BadObject());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\core\mocks\ListTest\BadObject NOT instanceof tests\ramp\core\mocks\ListTest\AnObject',
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
  }*/

  /**
   * Collection of assertions for ramp\core\List::offsetSet().
   * - assert {@see \ramp\core\List::OffsetSet()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert value set with name key is same as retived with same name key
   * - assert value set at index same as retived at index.
   * @see \ramp\core\mocks\ListTest\List::offsetSet()
   */
  public function testOffsetSet() //: void
  {
    $testObject = new oList($this->typeName);
    $expectedAtNameIndex = new AnObject();
    $expectedAt0Index = new AnObject();
    $testObject['name'] = $expectedAtNameIndex;
    $testObject[0] = new AnObject();
    try {
      $testObject['name'] = new BadObject();
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\core\mocks\ListTest\BadObject NOT instanceof tests\ramp\core\mocks\ListTest\AnObject',
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
   * Collection of assertions for ramp\core\List::offsetUnset().
   * - assert value unset with name key is no longer retivable with same name key
   * - assert value set at index is no longer retivable at same index.
   * @depends testOffsetSet
   * @param List The test object.
   * @see \ramp\core\mocks\ListTest\List::offsetUnset()
   */
  public function testOffsetUnset(oList $testObject)
  {
    $this->assertInstanceOf('tests\ramp\core\mocks\ListTest\AnObject', $testObject['name']);
    $this->assertInstanceOf('tests\ramp\core\mocks\ListTest\AnObject', $testObject[0]);
    unset($testObject['name']);
    unset($testObject[0]);
    $this->assertFalse(isset($testObject['name']));
    $this->assertFalse(isset($testObject[0]));
  }

  /**
   * Collection of assertions for ramp\core\List::__clone().
   * - assert Shallow Cloning (default) composite collection is referenced only
   * - assert when Deep Cloning that NEW objects are formed with same values
   * @see \ramp\core\mocks\ListTest\List::__clone()
   *
  public function test__clone()
  {
    $o1 = new AnObject();
    $o1->property = 'value1';
    $o2 = new AnObject();
    $o2->property = 'value2';
    $o3 = new AnObject();
    $o3->property = 'value3';
    $shallowList = new oList($this->typeName);
    $shallowList->add($o1);
    $shallowList->add($o2);
    $shallowList->add($o3);
    $this->assertSame(3, $shallowList->count);
    $deepList = new oList($this->typeName, TRUE);
    $deepList->add($o1);
    $deepList->add($o2);
    $deepList->add($o3);
    $this->assertSame(3, $deepList->count);
    $shallowClone = clone $shallowList;
    $this->assertSame(3, $shallowClone->count);
    $this->assertSame($shallowClone[0], $o1);
    $this->assertSame($shallowClone[1], $o2);
    $this->assertSame($shallowClone[2], $o3);
    $deepClone = clone $deepList;
    $this->assertSame(3, $deepClone->count);
    $this->assertNotSame($deepClone[0], $o1);
    $this->assertNotSame($deepClone[1], $o2);
    $this->assertNotSame($deepClone[2], $o3);
    $this->assertEquals($deepClone[0]->property, 'value1');
    $this->assertEquals($deepClone[1]->property, 'value2');
    $this->assertEquals($deepClone[2]->property, 'value3');
  }*/
}
