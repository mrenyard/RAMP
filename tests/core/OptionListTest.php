<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace tests\svelte\core;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/OptionList.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';

require_once '/usr/share/php/tests/svelte/core/mocks/OptionListTest/NotAnOption.class.php';
require_once '/usr/share/php/tests/svelte/core/mocks/OptionListTest/ConcreteOption.class.php';

use svelte\core\Str;
use svelte\core\OptionList;
use svelte\core\Collection;

use tests\svelte\core\mocks\OptionListTest\NotAnOption;
use tests\svelte\core\mocks\OptionListTest\ConcreteOption;

/**
 * Collection of tests for \svelte\core\OptionList.
 *
 * COLLABORATORS
 * - {@link \tests\svelte\core\mocks\OptionListTest\ConcreteOption}
 * - {@link \tests\svelte\core\mocks\OptionListTest\NotAnOption}
 * - {@link \tests\svelte\core\mocks\OptionListTest\ExtendedAsEnum}
 */
class OptionlistTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $testCollection;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    $this->testCollection = new Collection(Str::set('svelte\core\SvelteObject'));
    for ($i=0; $i < 5; $i++) {
      $this->testCollection->add(new ConcreteOption($i, Str::set('VALUE' . $i)));
    }
    $this->testObject = new OptionList($this->testCollection);
  }

  /**
   * Collection of assertions for svelte\core\OptionList::__construct().
   * - assert is instance of {@link \svelte\core\OptionList}
   * - assert is instance of {@link \svelte\core\Collection}
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert implements \IteratorAggregate
   * - assert implements \Countable
   * - assert implements \ArrayAccess
   * - assert throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   *   - with message: <em>'[provided object]  NOT instanceof svelte\core\iOption'</em>
   * @link svelte.core.Collection \svelte\core\Collection
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\OptionList', $this->testObject);
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\core\iCollection', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);

    $testCollection2 = clone $this->testCollection;
    $testCollection2->add(new NotAnOption());
    try {
      $testObject2 = new OptionList($testCollection2);
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'tests\svelte\core\mocks\OptionListTest\NotAnOption NOT instanceof svelte\core\iOption',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised');
  }
}
