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

require_once '/usr/share/php/tests/ramp/mocks/core/BadObject.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iList;
use ramp\core\oList;

use tests\ramp\mocks\core\AnObject;
use tests\ramp\mocks\core\BadObject;

/**
 * List of tests for \ramp\core\List.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\AnObject}
 * - {@see \tests\ramp\mocks\condition\BadObject}
 */
class ListTest extends \tests\ramp\core\ObjectTest
{
  protected $typeName;
  protected $expectedAtNameIndex;
  protected $expectedAt0Index;

  #region Setup
  #[\Override]
  protected function preSetup() : void
  {
    $this->typeName = Str::set('tests\ramp\mocks\core\AnObject');
    $this->expectedAtNameIndex = new AnObject();
    $this->expectedAt0Index = new AnObject();
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new oList($this->typeName); }
  #endregion

  /**
   * Collection of assertions for ramp\core\List.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \ramp\core\oList}
   * - assert implements \IteratorAggregate
   * - assert implements \ArrayAccess
   * - assert implements \Countable
   * - assert throws InvalidAgumentException if provided Str is NOT an accessible class name
   *   - with message: *'$compositeType MUST be an accesible class name'*
   * @see \ramp\core\List
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\core\iList', $this->testObject);
    $this->assertInstanceOf('ramp\core\oList', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    try {
      $this->testObject = new oList(Str::set('\not\a\Class'));
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame('$compositeType (\not\a\Class) MUST be an accessible class name or interface.', $expected->getMessage());
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__get().
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
  #endregion

  #region New Specialist Tests
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
    $this->assertTrue($this->testObject->isCompositeType($this->typeName));
    $this->assertFalse($this->testObject->isCompositeType('\not\a\Class'));
  }

  /**
   * Collection of assertions for ramp\core\List::offsetSet().
   * - assert {@see \ramp\core\List::OffsetSet()} only accepts predefined types, throws \InvalidArgumentException
   *   - with message: *'[provided object] NOT instanceof [expected type]'*
   * - assert value set with name key is same as retived with same name key
   * - assert value set at index same as retived at index.
   * @see \ramp\mocks\core\List::offsetSet()
   */
  public function testOffsetSet($message = 'tests\ramp\mocks\core\BadObject NOT instanceof tests\ramp\mocks\core\AnObject') : iList
  {
    $this->testObject['name'] = $this->expectedAtNameIndex;
    $this->testObject[0] = $this->expectedAt0Index;
    try {
      $this->testObject['name'] = new BadObject();
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame($message, $expected->getMessage());
      $this->testObject[0] = $this->expectedAt0Index;
      $this->assertSame($this->expectedAtNameIndex, $this->testObject['name']);
      $this->assertSame($this->expectedAt0Index, $this->testObject[0]);
      return $this->testObject;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for ramp\core\oList::offsetUnset().
   * - assert value unset with name key is no longer retivable with same name key
   * - assert value set at index is no longer retivable at same index.
   * @param iList The test object.
   * @param string Expected child type.
   * @see \ramp\mocks\core\List::offsetUnset()
   */
  #[\PHPUnit\Framework\Attributes\Depends('testOffsetSet')]
  public function testOffsetUnset(iList $testObject, $expectedChildType = 'tests\ramp\mocks\core\AnObject') : void
  {
    $this->assertInstanceOf($expectedChildType, $testObject['name']);
    $this->assertInstanceOf($expectedChildType, $testObject[0]);
    unset($testObject['name']);
    unset($testObject[0]);
    $this->assertFalse(isset($testObject['name']));
    $this->assertFalse(isset($testObject[0]));
  }
  #endregion
}
