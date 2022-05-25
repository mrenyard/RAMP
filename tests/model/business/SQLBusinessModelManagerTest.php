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
namespace tests\ramp\model\business;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/SQLBusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/MultiPartPrimary.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/DataWriteException.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Alphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowerCaseAlphanumeric.class.php';

require_once '/usr/share/php/tests/ramp/ChromePhp.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/SQLBusinessModelManagerTest/BadRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/SQLBusinessModelManagerTest/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/mocks/SQLBusinessModelManagerTest/MockRecordMultiKey.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\condition\Filter;
use ramp\condition\PostData;
use ramp\model\business\SQLBusinessModelManager;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\DataWriteException;

use tests\ramp\model\business\mocks\SQLBusinessModelManagerTest\BadRecord;
use tests\ramp\model\business\mocks\SQLBusinessModelManagerTest\MockRecord;
use tests\ramp\model\business\mocks\SQLBusinessModelManagerTest\MockRecordMultiKey;

/**
 * Collection of tests for \ramp\model\business\SQLBusinessModelManager.
 */
class SQLBusinessModelManagerTest extends \PHPUnit\Framework\TestCase
{
  // private static $LOCK = FALSE;
  private $testObject;
  private $recordName;
  private $recordKey;
  private $collection;
  private $primaryKeyName;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $DIR = '/usr/share/php/tests/ramp/model/business/mocks/SQLBusinessModelManagerTest';
    \copy($DIR . '/copy_database.db', $DIR . '/database.db');
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\mocks\SQLBusinessModelManagerTest';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'ramp\model\business\SQLBusinessModelManager';
    SETTING::$DATABASE_CONNECTION = 'sqlite:/usr/share/php/' . str_replace('\\', '/', SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE) . '/database.db';
    SETTING::$DATABASE_MAX_RESULTS = 4;
    $recordName = 'MockRecord';
    $recordFullName = SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    $this->recordName = Str::set($recordName);
    $this->recordKey = Str::set('key');
    $this->collection = Str::set('Collection');
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->testObject = $MODEL_MANAGER::getInstance();
    \ChromePhp::clear();
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getInstance().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\business\BusinessModelManager}
   * - assert is instance of {@link \ramp\model\business\SQLBusinessModelManager}
   * - assert is same instance on every call (Singleton)
   * - assert cannot be cloned, throwing \BadMethodCallException
   *   - with message *Cloning is not allowed*
   * @link ramp.model.business.SQLBusinessModelManager ramp\model\business\SQLBusinessModelManager
   */
  public function testGetInstance()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModelManager', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\SQLBusinessModelManager', $this->testObject);
    $this->assertSame(SQLBusinessModelManager::getInstance(), $this->testObject);
    try {
      $fail = clone $this->testObject;
      $this->fail('An expected \BadMethodCallException has NOT been raised.');
    } catch (\BadMethodCallException $expected) {
      $this->AssertSame('Cloning is not allowed', $expected->getMessage());
      unset($fail);
    }
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordKey equals 'new'.
   * - assert no SQL statement logged with \ChromePhp (Logger) as is 'new'
   * - assert returned is instance of {@link \ramp\model\Model}
   * - assert returned is instance of {@link \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@link \ramp\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert returns fresh Record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of record are instance of {@link \ramp\model\business\field\Field}
   * - assert each property's field\Field::$value returns NULL
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData)
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelNewRecord()
  {
    $newRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, Str::set('new'))
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertInstanceOf('\ramp\model\Model', $newRecord);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $newRecord);
    $this->assertInstanceOf('\ramp\model\business\Record', $newRecord);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(Str::set('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')),
      $newRecord
    );
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property);
    $this->assertNull($newRecord->property->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyA);
    $this->assertNull($newRecord->propertyA->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyB);
    $this->assertNull($newRecord->propertyB->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyC);
    $this->assertNull($newRecord->propertyC->value);
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $_POST = array(
      'mock-record:new:property' => 'key',
      'mock-record:new:propertyA' => 'valueA',
      'mock-record:new:propertyB' => 'valueB',
      'mock-record:new:propertyC' => 'valueC'
    );
    $newRecord->validate(PostData::build($_POST));
    $this->assertTrue($newRecord->isValid);
    $this->assertTrue($newRecord->isModified);
    $this->assertTrue($newRecord->isNew);
    \ChromePhp::clear();
    $this->testObject->update($newRecord);
    $expectedLog1 = 'LOG:$preparedStatement: INSERT INTO ' . $this->recordName .
      ' (property, propertyA, propertyB, propertyC) ' .
      'VALUES (:property, :propertyA, :propertyB, :propertyC)';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: key, valueA, valueB, valueC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertTrue($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertFalse($newRecord->isNew);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property);
    $this->assertSame('key', $newRecord->property->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyA);
    $this->assertSame('valueA', $newRecord->propertyA->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyB);
    $this->assertSame('valueB', $newRecord->propertyB->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyC);
    $this->assertSame('valueC', $newRecord->propertyC->value);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    \ChromePhp::clear();
    $this->testObject->update($newRecord);
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'property=:property, propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC ' .
      'WHERE property=:property';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: key, valueA, valueB, valueC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordKey equals 'new'.
   * - assert no SQL statement logged with \ChromePhp (Logger) as is 'new'
   * - assert returned is instance of {@link \ramp\model\Model}
   * - assert returned is instance of {@link \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@link \ramp\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert returns fresh Record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of record are instance of {@link \ramp\model\business\field\Field}
   * - assert each property's field\Field::$value returns NULL
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData)
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelNewRecordMultipartPrimaryKey()
  {
    $recordName = Str::set('MockRecordMultiKey');
    $newRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($recordName, Str::set('new'))
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertInstanceOf('\ramp\model\Model', $newRecord);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $newRecord);
    $this->assertInstanceOf('\ramp\model\business\Record', $newRecord);
    $this->assertInstanceOf(
      (string)$recordName->prepend(Str::set('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')),
      $newRecord
    );
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyA);
    $this->assertNull($newRecord->propertyA->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyB);
    $this->assertNull($newRecord->propertyB->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyC);
    $this->assertNull($newRecord->propertyC->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyD);
    $this->assertNull($newRecord->propertyD->value);
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $_POST = array(
      'mock-record-multi-key:new:property-a' => 'value1',
      'mock-record-multi-key:new:property-b' => 'value2',
      'mock-record-multi-key:new:property-c' => 'value3',
      'mock-record-multi-key:new:property-d' => 'valueABC'
    );
    $newRecord->validate(PostData::build($_POST));
    $this->assertTrue($newRecord->isModified);
    $this->assertTrue($newRecord->isValid);
    $this->assertTrue($newRecord->isNew);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $expectedLog1 = 'LOG:$preparedStatement: INSERT INTO ' . $recordName .
      ' (propertyA, propertyB, propertyC, propertyD) ' .
      'VALUES (:propertyA, :propertyB, :propertyC, :propertyD)';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: value1, value2, value3, valueABC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertTrue($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertFalse($newRecord->isNew);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyA);
    $this->assertSame('value1', $newRecord->propertyA->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyB);
    $this->assertSame('value2', $newRecord->propertyB->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyC);
    $this->assertSame('value3', $newRecord->propertyC->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->propertyD);
    $this->assertSame('valueABC', $newRecord->propertyD->value);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    \ChromePhp::clear();
    $this->testObject->update($newRecord);
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $recordName .' SET ' .
      'propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC, propertyD=:propertyD ' .
      'WHERE propertyA=:propertyA AND propertyB=:propertyB AND propertyC=:propertyC';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: value1, value2, value3, valueABC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where iBusinessModelDefinition::$recordKey references existing stored Record.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@link \ramp\model\Model}
   * - assert returned is instance of {@link \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@link \ramp\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of Record are instance of {@link \ramp\model\business\field\Field}
   * - assert each property's field\Field::$value of stored Record returns as stored
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData).
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName and $recordKey)
   *   returns referance to same Record without contacting data store
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelStoredRecord()
  {
    $recordKey = $this->recordKey->append(Str::set('1'));
    $storedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName .
      ' WHERE property' . ' = "' . $recordKey . '";';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertInstanceOf('\ramp\model\Model', $storedRecord);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $storedRecord);
    $this->assertInstanceOf('\ramp\model\business\Record', $storedRecord);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(Str::set('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')),
      $storedRecord
    );
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->property);
    $this->assertSame((string)$recordKey, $storedRecord->property->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->propertyA);
    $this->assertSame('valueA', $storedRecord->propertyA->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->propertyB);
    $this->assertSame('valueB', $storedRecord->propertyB->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->propertyC);
    $this->assertSame('valueC', $storedRecord->propertyC->value);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $_POST = array('mock-record:key1:propertyA' => 'newValue');
    $storedRecord->validate(PostData::build($_POST));
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertTrue($storedRecord->isModified);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'property=:property, propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC ' .
      'WHERE property=:property';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: key1, newValue, valueB, valueC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $this->assertSame($storedRecord->propertyA->value, 'newValue');
    $this->testObject = SQLBusinessModelManager::getInstance();
    \ChromePhp::clear();
    $cachedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey)
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertSame($storedRecord, $cachedRecord);
  }


  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where iBusinessModelDefinition::$recordKey references existing stored Record with a multipart PrimaryKey.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@link \ramp\model\Model}
   * - assert returned is instance of {@link \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@link \ramp\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of Record are instance of {@link \ramp\model\business\field\Field}
   * - assert each property's field\Field::$value of stored Record returns as stored
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData).
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName and $recordKey)
   *   returns referance to same Record without contacting data store
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelStoredRecordMultipartPrimaryKey()
  {
    $recordKey = Str::set('a|b|c');
    $recordName = Str::set('MockRecordMultiKey');
    $storedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($recordName, $recordKey)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $recordName .
      ' WHERE propertyA = "a" AND propertyB = "b" AND propertyC = "c";';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertInstanceOf('\ramp\model\Model', $storedRecord);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $storedRecord);
    $this->assertInstanceOf('\ramp\model\business\Record', $storedRecord);
    $this->assertInstanceOf(
      (string)$recordName->prepend(Str::set('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')),
      $storedRecord
    );
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->propertyA);
    $this->assertSame('a', $storedRecord->propertyA->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->propertyB);
    $this->assertSame('b', $storedRecord->propertyB->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $storedRecord->propertyC);
    $this->assertSame('c', $storedRecord->propertyC->value);
    $this->assertSame('mock-record-multi-key:a|b|c', (string)$storedRecord->id);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $_POST = array('mock-record-multi-Key:a|b|c:propertyD' => 'newValue');
    $storedRecord->validate(PostData::build($_POST));
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertTrue($storedRecord->isModified);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $recordName .' SET ' .
      'propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC, propertyD=:propertyD ' .
      'WHERE propertyA=:propertyA AND propertyB=:propertyB AND propertyC=:propertyC';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: a, b, c, newValue';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $this->assertSame($storedRecord->propertyD->value, 'newValue');
    $this->testObject = SQLBusinessModelManager::getInstance();
    \ChromePhp::clear();
    $cachedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($recordName, $recordKey)
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertSame($storedRecord, $cachedRecord);
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordKey is NOT in data store.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert throws \DomainException as provided iBusinessModelDefinition::$recordKey NOT found
   *   - with message: <em>'No matching Record(s) found in data storage!'</em>
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelRecordNotStored()
  {
    try {
      $recordWithBadKey = $this->testObject->getBusinessModel(
        new SimpleBusinessModelDefinition($this->recordName, Str::set('badkey'))
      );
    } catch (\DomainException $expected) {
      $this->assertSame('No matching Record found in data storage!', $expected->getMessage());
      $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName .
        ' WHERE property' . ' = "badkey";';
      $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
      return;
    }
    $this->fail('An expected \DomainException has NOT been raised');
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition has $recordName $recordKey and $propertyName thereby
   * retuning a \ramp\model\business\field\Field.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@link \ramp\model\Model}
   * - assert returned is instance of {@link \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@link \ramp\model\business\field\Field}
   * - assert property's field\Field::$value returns as stored
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName, $recordKey and
   *   $propertyName) returns referance to same Field without contacting data store
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelProperty()
  {
    $recordKey = $this->recordKey->append(Str::set('2'));
    $propertyName = Str::set('propertyB');
    $property = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey, $propertyName)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName .
      ' WHERE property' . ' = "' . $recordKey . '";';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertInstanceOf('\ramp\model\Model', $property);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $property);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $property);
    $this->assertInstanceOf('ramp\model\business\field\Input', $property);
    $this->assertSame('valueB', $property->value);
    \ChromePhp::clear();
    $_POST = array('mock-record:key2:propertyB' => 'newValueB');
    $property->validate(PostData::build($_POST));
    $this->testObject->update($property);
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'property=:property, propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC ' .
      'WHERE property=:property';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: key2, Avalue, newValueB, valueC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    \ChromePhp::clear();
    $propertySecondRequest = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey, $propertyName)
    );
    $this->assertSame($property, $propertySecondRequest);
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    return;
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordName ONLY, returning a RecordCollection.
   * - assert makes successful connection with data store
   * - assert returned is instance of {@link \ramp\core\iCollection}
   * - assert returned is instance of {@link \ramp\model\business\RecordCollection}
   * - assert returned is instance of provided argument $recordName appended with 'Collection'
   * - assert 'ALL' collection contains Records of correct type
   *   - (LIMITED by DATABASE_MAX_RESULTS)
   *   - populated with relavant data from data storage
   *   - logs expected SELECT SQL statement with \ChromePhp (Logger)
   * - assert 'FILTERED' collection contains Records of correct type
   *   - (LIMITED by DATABASE_MAX_RESULTS)
   *   - populated with relavant data from data storage
   *   - logs expected SELECT SQL statement with \ChromePhp (Logger)
   * - assert $fromIndex returns 'ALL' fromIndex
   *   - (LIMITED by DATABASE_MAX_RESULTS)
   *   - populated with relavant data from data storage
   *   - logs expected SELECT SQL statement with \ChromePhp (Logger)
   * - assert relavant Records are EXACT SAME OBJECT accross collections
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert throws \DomainException when query returns NO results
   *   - with message: *'No matching Record(s) found in data storage!'*
   * @link ramp.model.business.SQLBusinessModelManager#method_getBusinessModel ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelCollection()
  {
    $all = $this->testObject->getBusinessModel(new SimpleBusinessModelDefinition($this->recordName));
    $this->assertInstanceOf('\ramp\core\iCollection', $all);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $all);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(
        Str::set('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')
      )->append($this->collection),
      $all
    );
    $maxResults = ((int)SETTING::$DATABASE_MAX_RESULTS < 100) ? (int)SETTING::$DATABASE_MAX_RESULTS : 100;
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' LIMIT 0, ' . $maxResults . ';';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame($maxResults, $all->count());
    $i=0;
    foreach ($all as $record)
    {
      $i++;
      $this->assertInstanceOf('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $record);
      $this->assertSame('key' . $i, $record->property->value);
      $expectedValueOfA = ($i % 2 != 0)? 'valueA' : 'Avalue';
      $this->assertSame($expectedValueOfA, $record->propertyA->value);
      $this->assertSame('valueB', $record->propertyB->value);
      $this->assertSame('valueC', $record->propertyC->value);
    }
    \ChromePhp::clear();
    $_GET = array(
      'property-a' => 'Avalue',
      'property-b' => 'valueB',
      'property-c' => 'valueC'
    );
    $filter = Filter::build($this->recordName, $_GET);
    $filtered = $this->testObject->getBusinessModel(new SimpleBusinessModelDefinition($this->recordName), $filter);
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE ' . $filter() .
      ' LIMIT ' . '0, ' . SETTING::$DATABASE_MAX_RESULTS . ';';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame(2, $filtered->count());
    $i=0;
    foreach ($filtered as $record)
    {
      $i++;
      $this->assertInstanceOf('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $record);
      $this->assertSame('key' . ($i * 2), $record->property->value);
      $this->assertSame('Avalue', $record->propertyA->value);
      $this->assertSame('valueB', $record->propertyB->value);
      $this->assertSame('valueC', $record->propertyC->value);
    }
    \ChromePhp::clear();
    $fromIndex = 1;
    $allFrom = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName), null, $fromIndex
    );
    $this->assertInstanceOf('\ramp\core\iCollection', $allFrom);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $allFrom);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(
        Str::set('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')
      )->append($this->collection),
      $allFrom
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' LIMIT ' .
      $fromIndex . ', ' . ($maxResults + $fromIndex) . ';';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame($maxResults, $allFrom->count());
    $i=1;
    foreach ($allFrom as $record)
    {
      $i++;
      $this->assertInstanceOf('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $record);
      $this->assertSame('key' . $i, $record->property->value);
      $expectedValueOfA = ($i % 2 != 0)? 'valueA' : 'Avalue';
      $this->assertSame($expectedValueOfA, $record->propertyA->value);
      $this->assertSame('valueB', $record->propertyB->value);
      $this->assertSame('valueC', $record->propertyC->value);
    }
    $this->assertSame($filtered[0], $all[1]);
    $this->assertSame($filtered[0], $allFrom[0]);
    \ChromePhp::clear();
    $_POST = array(
      'mock-record:key2:property-c' => 'newValueC',
      'mock-record:key3:property-c' => 'newValueC',
      'mock-record:key4:property-c' => 'newValueC',
      'mock-record:key5:property-c' => 'newValueC'
    );
    $allFrom->validate(PostData::build($_POST));
    $this->testObject->update($all);
    $i = 0;
    foreach ($all as $record)
    {
      $expectedLogStatement = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
        'property=:property, propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC ' .
          'WHERE property=:property';
      $this->assertEquals($expectedLogStatement, \ChromePhp::getMessages()[$i++]);
      $c = ($i == 1) ? 'valueC' : 'newValueC';
      $expectedLogValues = 'LOG:values: ' . $record->primaryKey->value . ', ' .
        $record->propertyA->value . ', ' . $record->propertyB->value . ', ' . $c;
      $this->assertEquals($expectedLogValues, \ChromePhp::getMessages()[$i++]);
    }
    $this->assertEquals(8, $i);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $expectedLogStatement = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'property=:property, propertyA=:propertyA, propertyB=:propertyB, propertyC=:propertyC ' .
        'WHERE property=:property';
    $this->assertEquals($expectedLogStatement, \ChromePhp::getMessages()[0]);
    $expectedLogValues = 'LOG:values: key5, valueA, valueB, newValueC';
    $this->assertEquals($expectedLogValues, \ChromePhp::getMessages()[1]);
    try {
      $this->testObject->getBusinessModel(
        new SimpleBusinessModelDefinition($this->recordName),
        Filter::build($this->recordName, array('property-a' => 'valueB'))
      );
    } catch (\DomainException $expected) {
      $this->assertSame('No matching Records found in data storage!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \DomainException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::update().
   * - assert throws \InvalidArgumentException when model unknown
   *   - with message *'Provided Model NOT retrieved through this BusinessModelManager'*
   * @link ramp.model.business.SQLBusinessModelManager#method_update ramp\model\business\SQLBusinessModelManager::update()
   */
  public function testUpdateInvalidArgumentException()
  {
    $this->testObject = SQLBusinessModelManager::getInstance();
    try {
      $this->testObject->update(new MockRecord());
    } catch (\InvalidArgumentException $expected) {
      $this->assertSame(
        'Provided Model NOT retrieved through this BusinessModelManager',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\SQLBusinessModelManager::update() and
   * \ramp\model\business\SQLBusinessModelManager::updateAny() with BAD BusinessModel defined in
   * system but no Table in SQL DataBase.
   *
   * **THIS EXTRA TEST HAS BEEN RENAMED TO PREVENT EXECUTION AS SLOW RUNNING
   * RENAME *EXTRADataWriteException* TO *testDataWriteException* TO RUN**
   *
   * - assert throws DataWriteException when Record type NOT found in data storage
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert logged error reports and delayed atempts reported in \ChromePhp (Logger)
   * @link ramp.model.business.SQLBusinessModelManager#method_update ramp\model\business\SQLBusinessModelManager::update()
   * @link ramp.model.business.SQLBusinessModelManager#method_updateAny ramp\model\business\SQLBusinessModelManager::updateAny()
   */
  public function EXTRADataWriteException()
  {
    defined('DEV_MODE') || define('DEV_MODE', TRUE);
    $badRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('BadRecord'), Str::set('new'))
    );
    $_POST = array(
      'bad-record:new:property' => 'key',
      'bad-record:new:propertyA' => 'valueA',
      'bad-record:new:propertyB' => 'valueB',
      'bad-record:new:propertyC' => 'valueC'
    );
    $badRecord->validate(PostData::build($_POST));
    $this->assertTrue($badRecord->isNew);
    $this->assertTrue($badRecord->isValid);
    $this->assertTrue($badRecord->isModified);
    try {
      $this->testObject->updateAny();
    } catch (DataWriteException $expected) {
      $i = 0; $j = 0;
      $this->assertSame(
        'LOG:$preparedStatement: INSERT INTO BadRecord (property, propertyA, propertyB, propertyC)' .
          ' VALUES (:property, :propertyA, :propertyB, :propertyC)',
        \ChromePhp::getMessages()[$i++]);
      $this->assertSame(
        'LOG:values: key, valueA, valueB, valueC',
        \ChromePhp::getMessages()[$i++]
      );
      while ($j < 3)
      {
        $j++;
        $this->assertSame(
          'GROUP:Unable to write to data store ' . $j,
          \ChromePhp::getMessages()[$i++]
        );
        $this->assertSame(
          'LOG:STATEMENT: INSERT INTO BadRecord (property, propertyA, propertyB, propertyC)' .
            ' VALUES (:property, :propertyA, :propertyB, :propertyC)',
          \ChromePhp::getMessages()[$i++]
        );
        $this->assertSame('LOG:Retryed after ' . $j . 'second(s).', \ChromePhp::getMessages()[$i++]);
        $this->assertSame('GROUPEND:', \ChromePhp::getMessages()[$i++]);
      }
      \ChromePhp::clear();
      return;
    }
    $this->fail('An expected \PDOException has NOT been raised.');
  } 
}
