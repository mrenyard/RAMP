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
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';

require_once '/usr/share/php/tests/ramp/core/mocks/OptionListTest/NotAnOption.class.php';
require_once '/usr/share/php/tests/ramp/core/mocks/OptionListTest/ConcreteOption.class.php';
require_once '/usr/share/php/tests/ramp/core/mocks/OptionListTest/SpecialistOption.class.php';

use ramp\core\Str;
use ramp\core\OptionList;
use ramp\core\Collection;

use tests\ramp\core\mocks\OptionListTest\NotAnOption;
use tests\ramp\core\mocks\OptionListTest\ConcreteOption;
use tests\ramp\core\mocks\OptionListTest\SpecialistOption;

/**
 * Collection of tests for \ramp\core\OptionList.
 *
 * COLLABORATORS
 * - {@see \tests\ramp\core\mocks\OptionListTest\ConcreteOption}
 * - {@see \tests\ramp\core\mocks\OptionListTest\NotAnOption}
 */
class OptionListTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $testCollection;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->testCollection = new Collection();
    for ($i=0; $i < 5; $i++) {
      $this->testCollection->add(new ConcreteOption($i, Str::set('VALUE' . $i)));
    }
  }

  /**
   * Collection of assertions for ramp\core\OptionList::__construct() no arguments.
   * - assert is instance of {@see \ramp\core\OptionList}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: *'[provided object]  NOT instanceof ramp\core\iOption'*
   * @see ramp.core.Collection \ramp\core\Collection
   */
  public function test__ConstructNoAguments()
  {
    $this->testObject = new OptionList();
    $this->assertInstanceOf('ramp\core\OptionList', $this->testObject);
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\core\iCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);

    foreach ($this->testCollection as $item) {
      $this->testObject->add($item);
    }

    $this->assertSame($this->testObject->count, $this->testCollection->count);
    
    try {
      $this->testObject->add(new NotAnOption());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\ramp\core\mocks\OptionListTest\NotAnOption NOT instanceof ramp\core\iOption',
        $expected->getMessage()
      );
      return;
    }
  }

  /**
   * Collection of assertions for ramp\core\OptionList::__construct() with provided iOption Collection.
   * - assert is instance of {@see \ramp\core\OptionList}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: *'[provided object]  NOT instanceof ramp\core\iOption'*
   * @see ramp.core.Collection \ramp\core\Collection
   */
  public function test__ConstructWithCollection()
  {
    $this->testObject = new OptionList($this->testCollection);
    $this->assertInstanceOf('ramp\core\OptionList', $this->testObject);
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
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
        'tests\ramp\core\mocks\OptionListTest\NotAnOption NOT instanceof ramp\core\iOption',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }

  
  /**
   * Collection of assertions for ramp\core\OptionList::__construct() for specialist iOption Collection.
   * - assert is instance of {@see \ramp\core\OptionList}
   * - assert is instance of {@see \ramp\core\Collection}
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: *'[provided object]  NOT instanceof ramp\core\iOption'*
   * @see ramp.core.Collection \ramp\core\Collection
   */
  public function test__ConstructSpecialist()
  {  
    $testCollection2 = new Collection();
    for ($i=0; $i < 5; $i++) {
      $testCollection2->add(new SpecialistOption($i, Str::set('VALUE' . $i)));
    }

    $this->testObject = new OptionList($testCollection2, Str::set('tests\ramp\core\mocks\OptionListTest\SpecialistOption'));
    $this->assertInstanceOf('ramp\core\OptionList', $this->testObject);
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\core\iCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);

    try {
      $this->testObject->add(new ConcreteOption($i, Str::set('VALUE' . $i)));
    } catch (\InvalidArgumentException $expected) { 
      $this->assertSame(
        'tests\ramp\core\mocks\OptionListTest\ConcreteOption NOT instanceof tests\ramp\core\mocks\OptionListTest\SpecialistOption',
        $expected->getMessage()
      ); 
      try {
        $this->testObject2 = new OptionList($this->testCollection, Str::set('tests\ramp\core\mocks\OptionListTest\SpecialistOption'));
      } catch (\InvalidArgumentException $expected) {
        $this->assertSame(
          'tests\ramp\core\mocks\OptionListTest\ConcreteOption NOT instanceof tests\ramp\core\mocks\OptionListTest\SpecialistOption',
          $expected->getMessage()
        );
        return;
      }
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }
}
