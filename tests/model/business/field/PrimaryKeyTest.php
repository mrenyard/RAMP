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
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';

require_once '/usr/share/php/tests/ramp/model/business/field/mocks/PrimaryKeyTest/MockBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/PrimaryKeyTest/ConcreteValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/PrimaryKeyTest/MockRecord.class.php';

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\field\PrimaryKey;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\dbtype\VarChar;

use tests\ramp\model\business\field\mocks\PrimaryKeyTest\MockBusinessModelManager;
use tests\ramp\model\business\field\mocks\PrimaryKeyTest\MockRecord;

/**
 * Collection of tests for \ramp\model\business\field\PrimaryKey.
 */
class PrimaryKeyTest extends \PHPUnit\Framework\TestCase
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
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\field\mocks\PrimaryKeyTest';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\field\mocks\PrimaryKeyTest\MockBusinessModelManager';
    $this->dataObject = new \stdClass();
    $this->mockRecord = new MockRecord($this->dataObject);
    $this->propertyNames = StrCollection::set('aProperty', 'bProperty', 'cProperty');
    $this->testObject = new PrimaryKey($this->propertyNames, $this->mockRecord);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\field\Field}
   * - assert is instance of {@link \ramp\model\field\PrimaryKey}
   * @link ramp.model.business.field.PrimaryKey ramp\model\business\field\PrimaryKey
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\PrimaryKey', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.PrimaryKey#method_get_id ramp\model\business\field\PrimaryKey::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame('mock-record:new:primary-key', (string)$this->testObject->id);

      $this->assertNull($this->mockRecord->validate(PostData::build(array(
        'mock-record:new:a-property' => 1,
        'mock-record:new:b-property' => 2,
        'mock-record:new:c-property' => 3,
      ))));
      $this->assertTrue($this->mockRecord->isValid);
      $this->assertTrue($this->mockRecord->isModified);
      $this->assertSame('mock-record:new:primary-key', (string)$this->testObject->id);
      $this->mockRecord->updated();
      $this->assertSame('mock-record:1|2|3:primary-key', (string)$this->testObject->id);      
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::value.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'value' is gettable.
   * - assert returned same as provided records getPropertyValue() method.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.PrimaryKey#method_get_value ramp\model\business\field\PrimaryKey::value
   */
  public function testGet_value()
  {
    try {
      $this->testObject->value = 'VALUE';
    } catch (PropertyNotSetException $expected) {
      $this->dataObject->aProperty = 'A';
      $this->assertNull($this->testObject->value);
      $this->dataObject->aProperty = 'A';
      $this->dataObject->bProperty = 'B';
      $this->assertNull($this->testObject->value);
      $this->dataObject->aProperty = 'A';
      $this->dataObject->bProperty = 'B';
      $this->dataObject->cProperty = 'C';
      $this->assertSame('A|B|C', $this->testObject->value);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.PrimaryKey#method_get_type ramp\model\business\field\PrimaryKey::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertEquals('primary-key field', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through NO objects, as there are NO children.
   * @link ramp.model.business.field.PrimaryKey#method_getIterator ramp\model\business\field\PrimaryKey::getIterator()
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
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds as NO children
   * @link ramp.model.business.field.PrimaryKey#method_offsetGet ramp\model\business\field\PrimaryKey::offsetGet()
   */
  public function testOffsetGet()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->testObject[0];
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::offsetExists.
   * - assert False returned on isset() NO children outside expected bounds.
   * @link ramp.model.business.field.PrimaryKey#method_offsetExists ramp\model\business\field\PrimaryKey::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Collection of assertions for ramp\model\business\field\PrimaryKey::offsetSet().
   * - assert throws BadMethodCallException as this method should be inaccessible
   *   - with message: <em>'Array access setting is not allowed, please use add.'</em>
   * @link ramp.model.business.field.PrimaryKey#method_offsetSet \ramp\model\business\field\PrimaryKey::offsetSet()
   */
  public function testOffsetSet()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access setting is not allowed.';
    $this->testObject[0] = 'VALUE';
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::offsetUnset.
   * - assert throws BadMethodCallException whenever offsetUnset is called
   *  - with message *Array access unsetting is not allowed.*
   * @link ramp.model.business.field.PrimaryKey#method_offsetUnset ramp\model\business\field\PrimaryKey::offsetUnset()
   */
  public function testOffsetUnset()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage = 'Array access unsetting is not allowed.';
    unset($this->testObject[0]);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::validate() where PostData
   * does NOT contain an PrimaryKeyDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an PrimaryKeyDataCondition with an attribute that
   *   matches the testObject's id, then its associated ValidationRule test() method, is NOT called.
   * @link ramp.model.business.field.PrimaryKey#method_validate ramp\model\business\field\PrimaryKey::validate()
   */
  public function testValidateValidationRuleTestNotCalled()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::processValidationRule()
   * coresponding PrimaryKey combined properties have been pre populated.
   * - assert returns void (null) when called.
   * - assert ValidationRule is called and executes test against data storage.
   * - assert passes test when combined primaryKey unique (NOT already in data storage).
   * - assert FailedValidationException thrown when combined primaryKey NOT unique (already in data storage).
   * - assert throws BadMethodCallException when called following update (!= isNew)
   *  - with message *PrimaryKey::processValidationRule() SHOULD ONLY be called from within!*
   * @link ramp.model.business.field.PrimaryKey#method_processValidationRule ramp\model\business\field\PrimaryKey::processValidationRule()
   */
  public function testProcessValidationRule()
  {
    $getBusinessModelCount = MockBusinessModelManager::$callCount;
    $this->dataObject->aProperty = 1;
    $this->dataObject->bProperty = 2;
    $this->dataObject->cProperty = 3;
    $this->assertTrue($this->mockRecord->isNew);
    $this->assertTrue($this->mockRecord->isValid);
    try {
      $this->testObject->processValidationRule(NULL);
    } catch (FailedValidationException $expected) {
      $this->assertSame(++$getBusinessModelCount, MockBusinessModelManager::$callCount);

      $this->dataObject->aProperty = 4;
      $this->dataObject->bProperty = 5;
      $this->dataObject->cProperty = 6;
      $this->assertTrue($this->mockRecord->isNew);
      $this->assertTrue($this->mockRecord->isValid);
      $this->testObject->processValidationRule(NULL);
      $this->assertSame(++$getBusinessModelCount, MockBusinessModelManager::$callCount);
      $this->mockRecord->updated();
      $this->assertSame('mock-record:4|5|6:primary-key', (string)$this->testObject->id);

      $this->expectException(\BadMethodCallException::class);
      $this->expectExceptionMessage = 'PrimaryKey::processValidationRule() SHOULD ONLY be called from within!';
      $this->testObject->processValidationRule('4|5|6');
      return;
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }

    /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::validate() where PostData
   * contains all PrimaryKeyDataConditions and is a new entry.
   * - assert returns void (null) when called.
   * - assert validate() passed to ValidationRule and executes test against data storage.
   * - assert hasErrors() reports as expected.
   * - assert error returns a StrCollection of one error message apon failing test. 
   * - assert passes test when combined primaryKey unique (NOT already in data storage).
   * @link ramp.model.business.field.PrimaryKey#method_validate ramp\model\business\field\PrimaryKey::validate()
   */
  public function testValidateValidationRuleExecuted()
  {
    $getBusinessModelCount = MockBusinessModelManager::$callCount;
    $this->dataObject->aProperty = 1;
    $this->dataObject->bProperty = 2;
    $this->dataObject->cProperty = 3;
    $this->assertTrue($this->mockRecord->isNew);
    $this->assertTrue($this->mockRecord->isValid);
    $this->testObject->Validate(new PostData());
    $this->assertSame(++$getBusinessModelCount, MockBusinessModelManager::$callCount);
    $this->assertTrue($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(1, $errors->count);
    $this->assertSame('An entry already exists for this record!', (string)$errors[0]);

    $getBusinessModelCount = MockBusinessModelManager::$callCount;
    $this->dataObject->aProperty = 4;
    $this->dataObject->bProperty = 5;
    $this->dataObject->cProperty = 6;
    $this->assertTrue($this->mockRecord->isNew);
    $this->assertTrue($this->mockRecord->isValid);
    $this->testObject->Validate(new PostData());
    $this->assertSame(++$getBusinessModelCount, MockBusinessModelManager::$callCount);
    $this->assertFalse($this->testObject->hasErrors);
    $errors = $this->testObject->errors;
    $this->assertSame(0, $errors->count);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\PrimaryKey::count.
   * - assert return expected int value related to the number of children (NO children).
   * @link ramp.model.business.field.PrimaryKey#method_count ramp\model\business\field\PrimaryKey::count
   */
  public function testCount()
  {
    $this->assertSame(0 ,$this->testObject->count);
  }
}
