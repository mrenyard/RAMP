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
 * @author mrenyard (renyard.m@gmail.com)
 * @version 0.0.9;
 * @package RAMP.test
 */
namespace tests\ramp\condition;

require_once '/usr/share/php/tests/ramp/core/CollectionTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';

require_once '/usr/share/php/tests/ramp/mocks/condition/Field.class.php';
require_once '/usr/share/php/tests/ramp/mocks/condition/Record.class.php';

use \ramp\core\RAMPObject;
use \ramp\core\iList;
use \ramp\core\Str;
use \ramp\condition\PostData;
use \ramp\condition\InputDataCondition;

/**
 * Collection of tests for \ramp\condition\PostData.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\mocks\condition\Property}
 * - {@see \tests\ramp\mocks\condition\Record}
 */
class PostDataTest extends \tests\ramp\core\CollectionTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\condition';
    $this->typeName = Str::set('ramp\condition\InputDataCondition');
    $this->record = Str::set('Record');
    $this->expectedAtNameIndex = new InputDataCondition($this->record, Str::NEW(), Str::set('propertyA'), 'ValueA');
    $this->expectedAt0Index = new InputDataCondition($this->record, Str::NEW(), Str::set('propertyB'), 'ValueA');
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new PostData(); }
  #endregion

  /**
   * Collection of assertions for \ramp\condition\PostData.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\core\iList}
   * - assert is instance of {@see \ramp\core\oList}
   * - assert is instance of {@see \ramp\core\iCollection}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\condition\Filter}
   * - assert implements \IteratorAggregate
   * - assert implements \ArrayAccess
   * - assert implements \Countable
   * - assert throws InvalidAgumentException if provided Str is NOT an accessible class name
   *   - with message: *'$compositeType MUST be an accesible class name'*
   * @see \ramp\condition\PostData
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\condition\PostData', $this->testObject);
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
  public function testOffsetSet($message = 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\condition\InputDataCondition') : iList
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
  public function testOffsetUnset(iList $testObject, $expectedChildType = 'ramp\condition\InputDataCondition') : void
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
      ($message !== NULL) ? $message : 'tests\ramp\mocks\core\BadObject NOT instanceof ramp\condition\InputDataCondition',
      ($o1 !== NULL) ? $o1 : new InputDataCondition($this->record, Str::NEW(), Str::set('propertyA'), 'ValueA'),
      ($o2 !== NULL) ? $o2 : new InputDataCondition($this->record, Str::NEW(), Str::set('propertyB'), 'ValueB'),
      ($o3 !== NULL) ? $o3 : new InputDataCondition($this->record, Str::NEW(), Str::set('propertyC'), 'ValueC'),
      ($o4 !== NULL) ? $o4 : new InputDataCondition($this->record, Str::NEW(), Str::set('propertyInt'), 'ValueInt')
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
   * Collection of assertions for \ramp\condition\PostData::build().
   * - assert throws \DomainException when any $postdata NAME is NOT in correct form
   *   - with message: *'Invalid format for name in $postdata, SHOULD be URN in the form "record:key:property"'*
   * - assert throws \DomainException when any $postdata NAME does NOT match business model
   *   - with message: *'Invalid name in $postdata does NOT match business model'*
   * - assert where valid produces like for like representation of provied array as PostData object
   * @see \ramp\condition\PostData::build()
   */
  public function testBuild() : void
  {
    $alphabet = array( 'A','B','C' );
    $badlyFormedNameArray = array();
    $badBusinessModelArray = array();
    $goodArray = array();

    for ($i=0, $j=3; $i < $j; $i++) {
      $badlyFormedNameArray['name' . $i] = 'value' . $i;
      $badBusinessModelArray['record:' . $i . ':not-property'] = 'value' . $i;
      $goodArray['record:' . $i . ':property-' . $alphabet[$i]] = 'value' . $alphabet[$i];
    }

    try {
      PostData::build($badlyFormedNameArray);
    } catch(\DomainException $expected) {
      $this->assertSame(
        'Invalid format for name in $postdata, SHOULD be URN in the form "record:key:property"',
        $expected->getMessage()
      );

      try {
        PostData::build($badBusinessModelArray);
      } catch (\DomainException $expected) {
        $this->assertSame(
          'Invalid: Record->notProperty, does NOT match business model',
          $expected->getMessage()
        );

        $testObject = PostData::build($goodArray);
        for ($i=0,$j=count($goodArray); $i<$j; $i++) {
          $this->assertSame($goodArray['record:' . $i . ':property-' . $alphabet[$i]], $testObject[$i]->value);
          $this->assertSame('value' . $alphabet[$i], $testObject[$i]->value);
          $this->assertEquals(Str::set($i), $testObject[$i]->primaryKeyValue);
          $this->assertSame('Record', (string)$testObject[$i]->record);
        }
        return;
      }
    }
    $this->fail('An expected \DomainException has NOT been raised');
  }
  #endregion
}
