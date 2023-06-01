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
namespace tests\ramp\model\business\key;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/key/Key.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/key/Primary.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';

require_once '/usr/share/php/tests/ramp/model/business/key/mocks/KeyTest/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/model/business/key/mocks/KeyTest/ConcreteValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/key/mocks/KeyTest/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/key/mocks/KeyTest/MockKey.class.php';

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\Primary;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\dbtype\VarChar;

use tests\ramp\model\business\key\mocks\KeyTest\MockBusinessModelManager;
use tests\ramp\model\business\key\mocks\KeyTest\MockRecord;
use tests\ramp\model\business\key\mocks\KeyTest\MockKey;

/**
 * Collection of tests for \ramp\model\business\key\Key.
 */
class KeyTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $mockRecord;
  private $dataObject;
  private $erroMessage;
  private $propertNames;

  /**
   * Setup - add variables
   */
  public function setUp() : void 
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\key\mocks\KeyTest';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\key\mocks\KeyTest\MockBusinessModelManager';
    $this->dataObject = new \stdClass();
    $this->mockRecord = new MockRecord($this->dataObject);
    $this->testObject = new MockKey($this->mockRecord);
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\field\Field}
   * - assert is instance of {@link \ramp\model\key\Key}
   * @link ramp.model.business.key.Key ramp\model\business\key\Key
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\key\Key', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.key.Key#method_get_id ramp\model\business\key\Key::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame('mock-record:new:mock-key', (string)$this->testObject->id);

      $this->assertNull($this->mockRecord->validate(PostData::build(array(
        'mock-record:new:a-property' => 1,
        'mock-record:new:b-property' => 2,
        'mock-record:new:c-property' => 3,
      ))));
      $this->assertTrue($this->mockRecord->isValid);
      $this->assertTrue($this->mockRecord->isModified);
      $this->assertSame('mock-record:new:mock-key', (string)$this->testObject->id);
      $this->mockRecord->updated();
      $this->assertSame('mock-record:1|2|3:mock-key', (string)$this->testObject->id);      
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.key.Key#method_get_type ramp\model\business\key\Key::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertEquals('mock-key key', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through NO objects, as there are NO children.
   * @link ramp.model.business.key.Key#method_getIterator ramp\model\business\key\Key::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    foreach ($this->testObject as $child) {
      $i++;
    }
    $this->assertSame(0, $i);
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds as NO children
   * @link ramp.model.business.key.Key#method_offsetGet ramp\model\business\key\Key::offsetGet()
   */
  public function testOffsetGet()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->testObject[0];
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::offsetExists.
   * - assert False returned on isset() NO children outside expected bounds.
   * @link ramp.model.business.key.Key#method_offsetExists ramp\model\business\key\Key::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::validate() where PostData
   * does NOT contain an PrimaryDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an PrimaryDataCondition with an attribute that
   *   matches the testObject's id, then its associated ValidationRule test() method, is NOT called.
   * @link ramp.model.business.key.Key#method_validate ramp\model\business\key\Key::validate()
   */
  public function testValidateValidationRuleTestNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
  }

  /**
   * Collection of assertions for \ramp\model\business\key\Key::count.
   * - assert return expected int value related to the number of children (NO children).
   * @link ramp.model.business.key.Key#method_count ramp\model\business\key\Key::count
   */
  public function testCount()
  {
    $this->assertSame(0 ,$this->testObject->count);
  }
}
