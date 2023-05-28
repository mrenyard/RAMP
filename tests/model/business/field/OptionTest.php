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
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\field;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectMany.class.php';

require_once '/usr/share/php/tests/ramp/model/business/field/mocks/OptionTest/MockRecord.class.php';

use ramp\core\Str;
use ramp\core\OptionList;
use ramp\core\PropertyNotSetException;
use ramp\model\business\field\Option;
use ramp\model\business\field\SelectOne;
use ramp\model\business\field\SelectMany;

use tests\ramp\model\business\field\mocks\OptionTest\MockRecord;

/**
 * Collection of tests for \ramp\model\business\field\Option.
 */
class OptionTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $description;
  private $key;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->key = 2;
    $this->description = Str::set('DESCRIPTION');
    $this->testObject = new Option($this->key, $this->description);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Option::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \ramp\model\business\field\Option}
   * @link ramp.core.Option ramp\core\Option
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\core\iOption', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\Option', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Option::get_isSelected
   * - assert throws \BadMethodCallException When isSelected called without first setting <em>set_parentField</em>.
   *   - with message: <em>'Must set parentField before calling isSelected.'</em>
   * @link ramp.core.Option#method_get_isSelected ramp\core\Option::isSelected
   */
  public function testIsSelected()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage('Must set parentField before calling isSelected.');
    $this->testObject->isSelected;
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Option::get_isSelected and
   *  \ramp\model\business\field\Option::setParentField with setParentField correctly set.
   * - assert isSelected returns FALSE by default.
   * - assert isSelected returns FALSE when Field type is NOT one of {@link SelectOne} or {@link SelectMany}
   * - assert isSelected returns TRUE on SelectOne were selected
   * - assert isSelected returns FALSE on SelectOne were another is selected
   * - assert isSelected returns TRUE on SelectMany were is one of selected
   * - assert is Selected returns FALSE on SelectMany were is NOT one of selected 
   * @link ramp.core.Option#method_get_isSelected ramp\core\Option::isSelected
   * @link ramp.core.Option#method_setParentField ramp\core\Option::setParentField
   */
  public function testIsSelectedAndSetParentField()
  {
    $dataObject = new \stdClass();
    $mockRecord = new MockRecord($dataObject);

    $options = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
    $options->add(new Option(0, Str::set('Select from:')));
    $options->add(new Option(1, Str::set('First child')));
    $options->add($this->testObject);
    $options->add(new Option(3, Str::set('Third child')));

    $dataObject->aProperty = 2;
    $this->testObject->setParentField(new SelectOne(Str::set('aProperty'), $mockRecord, $options));
    $this->assertTrue($this->testObject->isSelected);

    $dataObject->aProperty = 1;
    $this->testObject->setParentField(new SelectOne(Str::set('aProperty'), $mockRecord, $options));
    $this->assertFalse($this->testObject->isSelected);

    $dataObject->aProperty = array(1,2);
    $this->testObject->setParentField(new SelectMany(Str::set('aProperty'), $mockRecord, $options));
    $this->assertTrue($this->testObject->isSelected);

    $dataObject->aProperty = array(1,3);
    $this->testObject->setParentField(new SelectMany(Str::set('aProperty'), $mockRecord, $options));
    $this->assertFalse($this->testObject->isSelected);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Option::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown
   *   when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value matches expected result.
   * @link ramp.core.Option#method_get_id ramp\core\Option::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->key = 1;
    } catch (PropertyNotSetException $expected) {
      $this->assertEquals('ramp\model\business\field\Option->key is NOT settable', $expected->getMessage());
      $this->assertSame((string)$this->key, (string)$this->testObject->id);
    }
  }

  /**
   * Collection of assertions for \ramp\model\business\field\Option::description.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown
   *   when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.core.Option#method_get_description ramp\core\Option::description
   */
  public function testGet_description()
  {
    try {
      $this->testObject->description = 1;
    } catch (PropertyNotSetException $expected) {
      $this->assertEquals('ramp\model\business\field\Option->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->description);
      $this->assertSame($this->description, $this->testObject->description);
    }
  }
}
