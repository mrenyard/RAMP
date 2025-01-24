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

require_once '/usr/share/php/tests/ramp/core/CollectionTest.php';

require_once '/usr/share/php/ramp/core/StrCollection.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iList;
use ramp\core\Collection;
use ramp\core\StrCollection;

use tests\ramp\mocks\core\BadObject;

/**
 * Collection of tests for \ramp\core\StrCollection.
 */
class StrCollectionTest extends \tests\ramp\core\CollectionTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->typeName = Str::set('ramp\core\Str');
    $this->expectedAtNameIndex = Str::_EMPTY();
    $this->expectedAt0Index = Str::SPACE();
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return StrCollection::set(); }
  #endregion

  /**
   * Collection of assertions for ramp\core\StrCollection.
   * - assert is instance of {@see \ramp\core\StrCollection}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\iCollection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws InvalidAgumentException if any provided arguments are NOT string literal.
   *   - with message: *'All arguments MUST be string literals!'*
   * @see \ramp\core\StrCollection
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\core\StrCollection', $this->testObject);
    $this->assertSame(0, $this->testObject->count);
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
   * Collection of assertions for ramp\core\StrCollection::isCompositeType().
   * - assert returns TRUE when $compositeType {@see \ramp\core\Str}
   * - assert returns FALSE when $compositeType name provided is NOT {@see \ramp\core\Str}
   * @see \ramp\core\StrCollection::isCompositeType()
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
  public function testOffsetSet($message = 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\core\Str') : iList
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
  public function testOffsetUnset(iList $testObject, $expectedChildType = 'ramp\core\Str') : void
  {
    parent::testOffsetUnset($testObject, $expectedChildType);
  }

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
  #[\Override]
  public function testIteratorAddCount($message = NULL, $o1 = NULL, $o2 = NULL, $o3 = NULL, $o4 = NULL) : void
  {
    parent::testIteratorAddCount(
      ($message !== NULL) ? $message : 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\core\Str',
      ($o1 !== NULL) ? $o1 : Str::COLON(),
      ($o2 !== NULL) ? $o2 : Str::SEMICOLON(),
      ($o3 !== NULL) ? $o3 : Str::BAR(),
      ($o4 !== NULL) ? $o4 : Str::NEW()
    );
  }

  /**
   * Collection of assertions for ramp\core\OptionList::__clone().
   * - assert Cloning (default) composite collection is referenced only
   * @see \ramp\mocks\core\Collection::__clone()
   * @see \ramp\mocks\core\Collection::__clone()
   */
  #[\Override]
  public function testClone() : void
  {
    $copy = clone $this->testObject;
    $this->assertNotSame($copy, $this->testObject);
    $this->assertEquals($copy, $this->testObject);
  }
  #endregion

  #region New Specialised Tests
  /**
   * Collection of assertions for ramp\core\StrCollection::set().
   * - assert accepts a single string literal
   * - assert accepts a set of comma seperated string literals
   * - assert each string literal (comma seperated) counts as one item in the collection
   * - assert that and special (_EMPTY,SPACE,COLON,SEMICOLON) exactly same as another.
   * @see \ramp\core\StrCollection::set()
   */
  public function testSet() : void
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
   * - assert returns instance of {@see \ramp\core\Str}.
   * - assert returns expected concatenated string value of Str.
   * - assert returns expected concatenated string value including provided glue.
   * @see \ramp\core\StrCollection::implode()
   */
  public function testImplode() : void
  {
    $strArray = ['string one', 'string two', 'string tree'];
    $testObject = StrCollection::set($strArray[0], $strArray[1], $strArray[2]);
    $result = $testObject->implode();
    $this->assertInstanceOf('ramp\core\Str', $result);
    $this->assertSame("string one string two string tree", (string)$result);
    $result = $testObject->implode(Str::BAR());
    $this->assertInstanceOf('ramp\core\Str', $result);
    $this->assertSame("string one|string two|string tree", (string)$result);
  }

  /**
   * Collection of assertions for ramp\core\StrCollection::contains().
   * - assert returns bool where anyone of the containd Str values matches the value of privide Str
   * @see \ramp\core\StrCollection::contains()
   */
  public function testContains() : void
  {
    $testObject = StrCollection::set('car', 'van', 'motorbike');
    $this->assertFalse($testObject->contains(Str::set('bicycle')));
    $this->assertTrue($testObject->contains(Str::set('motorbike')));
  }
  #endregion
}
