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
namespace tests\svelte\model\business;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';

require_once '/usr/share/php/tests/svelte/model/business/mocks/BusinessModelTest/MockBusinessModel.class.php';

use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;

use tests\svelte\model\business\mocks\BusinessModelTest\MockBusinessModel;

/**
 * Collection of tests for \svelte\model\business\BusinessModel.
 */
class BusinessModelTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    $this->testObject = new MockBusinessModel(); //$this->childCollection);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link IteratorAggregate}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * @link svelte.model.business.BusinessModel svelte\model\business\BusinessModel
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\svelte\core\iOption', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::getId.
   * - assert method 'getId()' is accessable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.BusinessModel#method_getId svelte\model\business\BusinessModel::getId()
   */
  public function testGetId()
  {
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
    $this->assertSame('uid-1', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::getDescription.
   * - assert method 'getDescription()' is accessable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.BusinessModel#method_getDescription svelte\model\business\BusinessModel::getDescription()
   */
  public function testGetDescription()
  {
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->description);
    $this->assertSame('uid-1', (string)$this->testObject->description);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.BusinessModel#method_get_type svelte\model\business\BusinessModel::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());

      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertSame(' mock-business-model business-model', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }
}
