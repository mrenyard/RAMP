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

require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';

require_once '/usr/share/php/tests/ramp/mocks/core/NotAnOption.class.php';
require_once '/usr/share/php/tests/ramp/mocks/core/ConcreteOption.class.php';
require_once '/usr/share/php/tests/ramp/mocks/core/SpecialistOption.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iList;
use ramp\core\OptionList;
use ramp\core\Collection;

use tests\ramp\mocks\core\NotAnOption;
use tests\ramp\mocks\core\ConcreteOption;
use tests\ramp\mocks\core\SpecialistOption;

/**
 * Collection of tests for \ramp\core\OptionList.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\core\mocks\OptionListTest\ConcreteOption}
 * - {@see \tests\ramp\core\mocks\OptionListTest\NotAnOption}
 */
class OptionListTest extends \tests\ramp\core\CollectionTest
{
  protected $testCollection;

  #region Setup
  #[\Override]
  protected function preSetup() : void 
  { 
    $this->typeName = Str::set('ramp\core\iOption');
    $this->expectedAtNameIndex = new ConcreteOption(5, Str::set('VALUE5'));
    $this->expectedAt0Index = new ConcreteOption(6, Str::set('VALUE6'));
    $this->testCollection = new Collection();
    for ($i=0; $i < 5; $i++) {
      $this->testCollection->add(new ConcreteOption($i, Str::set('VALUE' . $i)));
    }
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new OptionList(); }
  #endregion

  /**
   * Collection of assertions for ramp\core\OptionList no arguments.
   * - assert is instance of {@see \ramp\core\OptionList}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: *'[provided object]  NOT instanceof ramp\core\iOption'*
   * @see \ramp\core\Collection
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\core\OptionList', $this->testObject);

    foreach ($this->testCollection as $item) {
      $this->testObject->add($item);
    }

    $this->assertSame($this->testObject->count, $this->testCollection->count);
    
    try {
      $this->testObject->add(new NotAnOption());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\mocks\core\NotAnOption NOT instanceof ramp\core\iOption',
        $expected->getMessage()
      );
      return;
    }
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
  public function testOffsetSet($message = 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\core\iOption') : iList
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
  public function testOffsetUnset(iList $testObject, $expectedChildType = 'ramp\core\iOption') : void
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
      ($message !== NULL) ? $message : 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\core\iOption',
      ($o1 !== NULL) ? $o1 : new ConcreteOption(1, Str::set('VALUE1')),
      ($o2 !== NULL) ? $o2 : new ConcreteOption(2, Str::set('VALUE2')),
      ($o3 !== NULL) ? $o3 : new ConcreteOption(3, Str::set('VALUE3')),
      ($o4 !== NULL) ? $o4 : new ConcreteOption(4, Str::set('VALUE4'))
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

  #region New Specialist Tests
  /**
   * Collection of assertions for ramp\core\OptionList with provided iOption Collection.
   * - assert is instance of {@see \ramp\core\OptionList}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: *'[provided object]  NOT instanceof ramp\core\iOption'*
   * @see \ramp\core\Collection
   */
  public function testConstructWithCollection()
  {
    $this->testObject = new OptionList($this->testCollection);
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\core\OptionList', $this->testObject);
    $this->assertInstanceOf('ramp\core\iCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);

    $testCollection2 = clone $this->testCollection;
    $testCollection2->add(new NotAnOption());
    try {
      $testObject2 = new OptionList($testCollection2);
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\mocks\core\NotAnOption NOT instanceof ramp\core\iOption',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  
  /**
   * Collection of assertions for ramp\core\OptionList for specialist iOption Collection.
   * - assert is instance of {@see \ramp\core\OptionList}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: *'[provided object]  NOT instanceof ramp\core\iOption'*
   * @see \ramp\core\Collection
   */
  public function testConstructSpecialist()
  {  
    $testCollection2 = new Collection();
    for ($i=0; $i < 5; $i++) {
      $testCollection2->add(new SpecialistOption($i, Str::set('VALUE' . $i)));
    }

    $this->testObject = new OptionList($testCollection2, Str::set('tests\ramp\mocks\core\SpecialistOption'));
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\core\OptionList', $this->testObject);
    $this->assertInstanceOf('ramp\core\iCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);

    try {
      $this->testObject->add(new ConcreteOption($i, Str::set('VALUE' . $i)));
    } catch (\InvalidArgumentException $expected) { 
      $this->assertSame(
        'tests\ramp\mocks\core\ConcreteOption NOT instanceof tests\ramp\mocks\core\SpecialistOption',
        $expected->getMessage()
      ); 
      try {
        $this->testObject2 = new OptionList($this->testCollection, Str::set('tests\ramp\mocks\core\SpecialistOption'));
      } catch (\InvalidArgumentException $expected) {
        $this->assertSame(
          'tests\ramp\mocks\core\ConcreteOption NOT instanceof tests\ramp\mocks\core\SpecialistOption',
          $expected->getMessage()
        );
        return;
      }
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }
  #endregion
}
