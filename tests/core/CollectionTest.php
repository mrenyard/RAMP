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

require_once '/usr/share/php/tests/ramp/core/ListTest.php';

require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iList;
use ramp\core\Collection;

use tests\ramp\mocks\core\AnObject;
use tests\ramp\mocks\core\BadObject;

/**
 * Collection of tests for \ramp\core\Collection.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\AnObject}
 * - {@see \tests\ramp\mocks\condition\BadObject}
 */
class CollectionTest extends \tests\ramp\core\ListTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->typeName = Str::set('tests\ramp\mocks\core\AnObject');
    $this->expectedAtNameIndex = new AnObject();
    $this->expectedAt0Index = new AnObject();
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new Collection($this->typeName); }
  #endregion
  
  /**
   * Collection of assertions for ramp\core\Collection.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \ramp\core\oList}
   * - assert is instance of {@see \ramp\core\iCollection}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert implements \IteratorAggregate
   * - assert implements \ArrayAccess
   * - assert implements \Countable
   * - assert throws InvalidAgumentException if provided Str is NOT an accessible class name
   *   - with message: *'$compositeType MUST be an accesible class name'*
   * @see \ramp\core\Collection
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\core\Collection', $this->testObject);
    $this->assertInstanceOf('ramp\core\iCollection', $this->testObject);
    try {
      $this->testObject = new Collection(Str::set('\not\a\Class'));
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame('$compositeType (\not\a\Class) MUST be an accessible class name or interface.', $expected->getMessage());
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see ramp\core\RAMPObject::__get()
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
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
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
  #[\Override]
  public function testIsCompositeType() : void
  {
    parent::testIsCompositeType();
  }

  /**
   * Collection of assertions for ramp\core\List::offsetSet().
   * - assert {@see \ramp\core\List::OffsetSet()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert value set with name key is same as retived with same name key
   * - assert value set at index same as retived at index.
   * @see \ramp\mocks\core\List::offsetSet()
   */
  #[\Override]
  public function testOffsetSet($message = 'tests\ramp\mocks\core\BadObject NOT instanceof tests\ramp\mocks\core\AnObject') : iList
  {
    return parent::testOffsetSet($message);
  }

  /**
   * Collection of assertions for ramp\core\oList::offsetUnset().
   * - assert value unset with name key is no longer retivable with same name key
   * - assert value set at index is no longer retivable at same index.
   * @param iList The test object.
   * @param string Expected child type.
   * @see \ramp\mocks\core\List::offsetUnset()
   */
  #[\Override, \PHPUnit\Framework\Attributes\Depends('testOffsetSet')]
  public function testOffsetUnset(iList $testObject, $expectedChildType = 'tests\ramp\mocks\core\AnObject') : void
  {
    parent::testOffsetUnset($testObject, $expectedChildType);
  }
  #endregion

  #region New Specialist Tests
  /**
   * Collection of assertions for ramp\core\Collection::getIterator(), add() and count.
   * - assert handle unpopulated {@see \ramp\core\Collection} iteration without fail
   * - assert {@see \ramp\core\Collection::add()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert Count equal to number of objects added.
   * - assert collection object references occupy SAME position as added
   * - assert {@see \ramp\core\Collection::offsetGet}($outOfBoundsOffset) throws \OutOfBoundsException
   *   - with message: *'Offset out of bounds'*
   * @see \ramp\core\Collection::getIterator()
   * @see \ramp\core\Collection::add()
   * @see \ramp\core\Collection::count
   */
  public function testIteratorAddCount($message = NULL, $o1 = NULL, $o2 = NULL, $o3 = NULL, $o4 = NULL) : void
  {
    $message = ($message !== NULL) ? $message : 'tests\ramp\mocks\core\BadObject NOT instanceof tests\ramp\mocks\core\AnObject';
    $o1 = ($o1 !== NULL) ? $o1 : new AnObject();
    $o2 = ($o2 !== NULL) ? $o2 : new AnObject();
    $o3 = ($o3 !== NULL) ? $o3 : new AnObject();
    $o4 = ($o4 !== NULL) ? $o4 : new AnObject();

    foreach ($this->testObject as $o)
    {
      $this->fail('Unexpected iteration of empty Collection');
    }
    $this->assertEquals(0, $this->testObject->count);
    $this->assertEquals(0, $this->testObject->count());
    try {
      $this->testObject->add(new BadObject());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame($message, $expected->getMessage());
      $i = 0;
      $this->testObject->add($o1);
      foreach ($this->testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
      }
      $this->assertSame(1, $i);
      $this->assertEquals(1, $this->testObject->count);
      $this->assertEquals(1, $this->testObject->count());
      $i = 0;
      $this->testObject->add($o2);
      foreach ($this->testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
        if ($i === 2) { $this->assertSame($o2, $o); }
      }
      $this->assertSame(2, $i);
      $this->assertSame($o2, $this->testObject[1]);
      $this->assertEquals(2, $this->testObject->count);
      $i = 0;
      $this->testObject->add($o3);
      foreach ($this->testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
        if ($i === 2) { $this->assertSame($o2, $o); }
        if ($i === 3) { $this->assertSame($o3, $o); }
      }
      $this->assertSame(3, $i);
      $this->assertEquals(3, $this->testObject->count);
      $this->assertEquals(3, $this->testObject->count());
      $i = 0;
      $this->testObject->add($o4);
      foreach ($this->testObject as $o)
      {
        $i++;
        if ($i === 1) { $this->assertSame($o1, $o); }
        if ($i === 2) { $this->assertSame($o2, $o); }
        if ($i === 3) { $this->assertSame($o3, $o); }
        if ($i === 4) { $this->assertSame($o4, $o); }
      }
      $this->assertSame(4, $i);
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
   * Collection of assertions for ramp\core\Collection::__clone().
   * - assert Shallow Cloning (default) composite collection is referenced only
   * - assert when Deep Cloning that NEW objects are formed with same values
   * @see \ramp\mocks\core\Collection::__clone()
   */
  public function testClone() : void
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
  #endregion
}
