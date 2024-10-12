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
namespace tests\ramp\model\business;

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SQLBusinessModelManager.class.php';

require_once '/usr/share/php/tests/ramp/ChromePhp.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/BadRecord.class.php';

use ramp\SETTING;
use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\Filter;
use ramp\condition\PostData;
use ramp\model\business\SQLBusinessModelManager;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\DataWriteException;
use ramp\model\business\DataFetchException;

use tests\ramp\mocks\model\BadRecord;
use tests\ramp\mocks\model\MockMinRecord;

/**
 * Collection of tests for \ramp\model\business\SQLBusinessModelManager.
 */
class SQLBusinessModelManagerTest extends \tests\ramp\core\ObjectTest
{
  private static $NEW_VALUE = FALSE;
  private static $NEW_VALUE_B = FALSE;
  private $recordFullName;
  private $recordName;

  #region Setup
  protected function preSetup() : void {
    SETTING::$DEV_MODE = TRUE;
    $DIR = '/usr/share/php/tests/ramp/mocks/model';
    \copy($DIR . '/copy_database.db', $DIR . '/database.db');
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'ramp\model\business\SQLBusinessModelManager';
    SETTING::$DATABASE_CONNECTION = 'sqlite:/usr/share/php/' . str_replace('\\', '/', SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE) . '/database.db';
    SETTING::$DATABASE_MAX_RESULTS = 4;
    $recordName = 'MockMinRecord';
    $this->recordFullName = SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    $this->recordName = Str::set($recordName);
  }
  protected function getTestObject() : RAMPObject {
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    return $MODEL_MANAGER::getInstance();
  }
  protected function postSetup() : void {  \ChromePhp::clear(); }
  #endregion

  /**
   * Default base construction from \ramp\model\business\SQLBusinessModelManager::getInstance()}.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\business\BusinessModelManager}
   * - assert is instance of {@see \ramp\model\business\SQLBusinessModelManager}
   * - assert is same instance on every call (Singleton)
   * - assert cannot be cloned, throwing \BadMethodCallException
   *   - with message *Cloning is not allowed*
   * @see \ramp\model\business\SQLBusinessModelManager
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('ramp\model\business\BusinessModelManager', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\SQLBusinessModelManager', $this->testObject);
    try {
      $fail = clone $this->testObject;
      $this->fail('An expected \BadMethodCallException has NOT been raised.');
    } catch (\BadMethodCallException $expected) {
      $this->AssertSame('Cloning is not allowed', $expected->getMessage());
      unset($fail);
    }
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   *
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   *
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
   *
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert {@see \ramp\model\Model::__toString()} returns string 'class name'
   * @see \ramp\model\Model::__toString()
   *
  public function testToString() : void
  {
    parent::testToString();
  }*/
  #endregion

  /**
   * Retrieve 'new' Record from SQL data store where provided iBusinessModelDefinition::$recordKey equals 'new'.
   * - assert no SQL statement logged with \ChromePhp (Logger) as is 'new'.
   * - assert returned is instance of {@see \ramp\model\Model}.
   * - assert returned is instance of {@see \ramp\model\business\BusinessModel}.
   * - assert returned is instance of {@see \ramp\model\business\Record}.
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName.
   * - assert returns fresh Record is instance of provided argument iBusinessModelDefinition::$recordName.
   * - assert state of returned Record (isNew, isValid and isModified) is as expected.
   * - assert properties of record are instance of {@see \ramp\model\business\field\Field}.
   * - assert each property's field\Field::$value returns NULL.
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following primaryKey population via Record::validate(PostData).
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny().
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny().
   * @see \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  #[Depends('testConstruct')]
  public function testGetBusinessModelNewRecord() : void
  {
    $newRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockMinRecord'), Str::NEW())
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertInstanceOf('\ramp\model\Model', $newRecord);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $newRecord);
    $this->assertInstanceOf('\ramp\model\business\Record', $newRecord);
    $this->assertInstanceOf($this->recordFullName, $newRecord);
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property1);
    $this->assertNull($newRecord->property1->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property2);
    $this->assertNull($newRecord->property2->value);
    $this->assertFalse(isset(\ChromePhp::getMessages()[0]));
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $newRecord->reset();
    $_POST = array(
      'mock-min-record:new:property-2' => 'valueB',
      'mock-min-record:new:property-1' => 'valueA',
      'mock-min-record:new:key-3' => 'C',
      'mock-min-record:new:key-1' => 'A',
      'mock-min-record:new:key-2' => 'B'
    );
    $newRecord->validate(PostData::build($_POST));
    $i = $newRecord->primaryKey->indexes;
    $v = $newRecord->primaryKey->values;
    $expectedLog1 = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE ' .
      $this->recordName . '.' . $i[0] . ' = "' . $v[0] . '" AND ' .
      $this->recordName . '.' . $i[1] . ' = "' . $v[1] . '" AND ' . 
      $this->recordName . '.' . $i[2] . ' = "' . $v[2] . '"' .
      ' LIMIT 0, ' . SETTING::$DATABASE_MAX_RESULTS . ';';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame(1, $newRecord->validateCount);
    $this->assertSame(1, $newRecord->primaryKey[0]->validateCount);
    $this->assertSame(1, $newRecord->primaryKey[1]->validateCount);
    $this->assertSame(1, $newRecord->primaryKey[2]->validateCount);
    $this->assertSame(1, $newRecord[0]->validateCount);
    $this->assertSame(1, $newRecord[1]->validateCount);    
    $this->assertTrue($newRecord->isValid);
    $this->assertTrue($newRecord->isModified);
    $this->assertTrue($newRecord->isNew);
    \ChromePhp::clear();
    $this->testObject->update($newRecord);
    // ' (key1, key2, key3, property1, property2) VALUES (:key1, :key2, :key3, :property1, :property2)';
    $expectedLog1 = 'LOG:$preparedStatement: INSERT INTO ' . $this->recordName . 
      ' (' . $i[0] . ', ' . $i[1] . ', ' . $i[2] . ') VALUES (:' . $i[0] . ', :' . $i[1] . ', :' . $i[2] . ')';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    // $expectedLog2 = 'LOG:values: A, B, C, valueA, Avalue';
    $expectedLog2 = 'LOG:values: ' . $v[0] . ', ' . $v[1] . ', ' . $v[2] . '';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertTrue($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertFalse($newRecord->isNew);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->key1);
    $this->assertSame('A', $newRecord->key1->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->key2);
    $this->assertSame('B', $newRecord->key2->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->key3);
    $this->assertSame('C', $newRecord->key3->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property1);
    $this->assertSame('valueA', $newRecord->property1->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property2);
    $this->assertSame('valueB', $newRecord->property2->value);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $this->assertTrue($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertFalse($newRecord->isNew);
    $newRecord->reset();
    $_POST = array(
      'mock-min-record:a|b|c:property-2' => 'valueD',
      'mock-min-record:a|b|c:property-1' => 'valueC'
    );
    $newRecord->validate(PostData::build($_POST));
    $this->assertSame(1, $newRecord->validateCount);
    // $this->assertSame(1, $newRecord->primaryKey[0]->validateCount);
    // $this->assertSame(1, $newRecord->primaryKey[1]->validateCount);
    // $this->assertSame(1, $newRecord->primaryKey[2]->validateCount);
    $this->assertSame(1, $newRecord[0]->validateCount);
    $this->assertSame(1, $newRecord[1]->validateCount);
    $this->assertTrue($newRecord->isValid);
    $this->assertTrue($newRecord->isModified);
    $this->assertFalse($newRecord->isNew);
    \ChromePhp::clear();
    $this->testObject->update($newRecord);
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .
      ' SET key1=:key1, key2=:key2, key3=:key3, property1=:property1, property2=:property2 WHERE key1=:key1 AND key2=:key2 AND key3=:key3';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: A, B, C, valueC, valueD';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertTrue($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertFalse($newRecord->isNew);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->key1);
    $this->assertSame('A', $newRecord->key1->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->key2);
    $this->assertSame('B', $newRecord->key2->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->key3);
    $this->assertSame('C', $newRecord->key3->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property1);
    $this->assertSame('valueC',$newRecord->property1->value);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $newRecord->property2);
    $this->assertSame('valueD',$newRecord->property2->value);
  }

  /**
   * Retrieve Stored Record from SQL data store where provided iBusinessModelDefinition::$recordKey
   * references existing stored Record.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@see \ramp\model\Model}
   * - assert returned is instance of {@see \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@see \ramp\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of Record are instance of {@see \ramp\model\business\field\Field}
   * - assert each property's field\Field::$value of stored Record returns as stored
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData).
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName and $recordKey)
   *   returns referance to same Record without contacting data store
   * @see \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  #[Depends('testGetBusinessModelNewRecord')]
  public function testGetBusinessModelStoredRecord() : void
  {
    $recordKey = Str::set('A|A|A');
    $storedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName .
      ' WHERE key1 = "A" AND key2 = "A" AND key3 = "A";';
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
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $storedRecord->key1);
    $this->assertSame('A', (string)$storedRecord->key1->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $storedRecord->key2);
    $this->assertSame('A', (string)$storedRecord->key2->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $storedRecord->key3);
    $this->assertSame('A', (string)$storedRecord->key3->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $storedRecord->property1);
    $this->assertSame('valueA', (string)$storedRecord->property1->value);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $storedRecord->property2);
    $this->assertSame('valueB', $storedRecord->property2->value);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $_POST = array('mock-min-record:a|a|a:property1' => 'newValue');
    $storedRecord->validate(PostData::build($_POST));
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertTrue($storedRecord->isModified);
    self::$NEW_VALUE = TRUE;
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'key1=:key1, key2=:key2, key3=:key3, property1=:property1, property2=:property2 ' .
      'WHERE key1=:key1 AND key2=:key2 AND key3=:key3';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: A, A, A, newValue, valueB';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $this->assertSame($storedRecord->property1->value, 'newValue');
    $this->testObject = SQLBusinessModelManager::getInstance();
    \ChromePhp::clear();
    $cachedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey)
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertSame($storedRecord, $cachedRecord);
  }

  /**
   * Fail to retrieve Record from SQL data store where provided iBusinessModelDefinition::$recordKey
   * represents an NON existant entry. 
   * where provided iBusinessModelDefinition::$recordKey is NOT in data store.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert throws \DomainException as provided iBusinessModelDefinition::$recordKey NOT found
   *   - with message: *'No matching Record(s) found in data storage!'*
   * @see \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  #[Depends('testGetBusinessModelStoredRecord')]
  public function testGetBusinessModelRecordNotStored() : void
  {
    try {
      $recordWithBadKey = $this->testObject->getBusinessModel(
        new SimpleBusinessModelDefinition($this->recordName, Str::set('A|bad|key'))
      );
    } catch (DataFetchException $expected) {
      $this->assertSame('No matching Record found in data storage!', $expected->getMessage());
      $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName .
        ' WHERE key1 = "A" AND key2 = "bad" AND key3 = "key";';
      $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
      return;
    }
    $this->fail('An expected \DomainException has NOT been raised');
  }

  /**
   * Retrieve specific 'property' of Record from SQL data store where provided iBusinessModelDefinition
   * has $recordName $recordKey and $propertyName thereby retuning a \ramp\model\business\field\Field.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@see \ramp\model\Model}
   * - assert returned is instance of {@see \ramp\model\business\BusinessModel}
   * - assert returned is instance of {@see \ramp\model\business\field\Field}
   * - assert property's field\Field::$value returns as stored
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName, $recordKey and
   *   $propertyName) returns referance to same Field without contacting data store
   * @see \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  #[Depends('testGetBusinessModelRecordNotStored')]
  public function testGetBusinessModelProperty() : void
  {
    $recordKey = Str::set('A|A|C');
    $propertyName = Str::set('property2');
    $property = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey, $propertyName)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE key1 = "A" AND key2 = "A" AND key3 = "C";';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertInstanceOf('\ramp\model\Model', $property);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $property);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $property);
    $this->assertSame('valueB', (string)$property->value);
    \ChromePhp::clear();
    $_POST = array('mock-min-record:a|a|c:property-2' => 'newValueB');
    $property->validate(PostData::build($_POST));
    $this->assertTrue($property->parent->isModified);
    SELF::$NEW_VALUE_B = TRUE;
    $this->testObject->update($property);
    $expectedLog1 = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'key1=:key1, key2=:key2, key3=:key3, property1=:property1, property2=:property2 WHERE key1=:key1 AND key2=:key2 AND key3=:key3';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: A, A, C, valueA, newValueB';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
  }

  /**
   * Retrieve Stored Record Collection from SQL data store where provided with ONLY iBusinessModelDefinition::$recordName.
   * - assert makes successful connection with data store
   * - assert returned is instance of {@see \ramp\core\iCollection}
   * - assert returned is instance of {@see \ramp\model\business\RecordCollection}
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
   * - assert throws DataFetchException when query returns NO results
   *   - with message: *'No matching Record(s) found in data storage!'*
   * - assert update(ALL) runs update on full Record collection.
   * @see \ramp\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  #[Depends('testGetBusinessModelProperty')]
  public function testGetBusinessModelCollection() : void
  {
    $all = $this->testObject->getBusinessModel(new SimpleBusinessModelDefinition($this->recordName));
    $this->assertInstanceOf('\ramp\core\iCollection', $all);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $all);
    $maxResults = ((int)SETTING::$DATABASE_MAX_RESULTS < 100) ? (int)SETTING::$DATABASE_MAX_RESULTS : 100;
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' LIMIT 0, ' . $maxResults . ';';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame($maxResults, $all->count());
    $i=0; $keyBvalues = ['A','B','C','D','E'];
    foreach ($all as $recordAll)
    {
      $this->assertInstanceOf('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $recordAll);
      $this->assertSame('A', $recordAll->key1->value);
      $this->assertSame('A', $recordAll->key2->value);
      $this->assertSame($keyBvalues[$i++], $recordAll->key3->value);
      $expectedValueOf1 = ($i % 2 != 0)? 'valueA' : 'Avalue';
      $expectedValueOf1 = ($i == 1 && self::$NEW_VALUE === TRUE)? 'newValue' : $expectedValueOf1;
      $expectedValueOf2 = ($i == 3 && self::$NEW_VALUE_B === TRUE)? 'newValueB' : 'valueB';
      $this->assertSame($expectedValueOf1, $recordAll->property1->value);
      $this->assertSame($expectedValueOf2, $recordAll->property2->value);
    }
    $this->testObject->update($all);
    \ChromePhp::clear();
    $_GET = array('property-1' => 'Avalue', 'property-2' => 'valueB');
    $filter = Filter::build($this->recordName, $_GET);
    $filtered = $this->testObject->getBusinessModel(new SimpleBusinessModelDefinition($this->recordName), $filter);
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE ' . $filter() .
      ' LIMIT ' . '0, ' . SETTING::$DATABASE_MAX_RESULTS . ';';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame(2, $filtered->count());
    $i=0;
    foreach ($filtered as $recordFiltered)
    {
      $i++;
      $this->assertInstanceOf('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $recordFiltered);
      $this->assertSame('A', $recordFiltered->key1->value);
      $this->assertSame('A', $recordFiltered->key2->value);
      $this->assertSame($keyBvalues[$i++], $recordFiltered->key3->value);
      $this->assertSame('Avalue', $recordFiltered->property1->value);
      $this->assertSame('valueB', $recordFiltered->property2->value);
    }
    \ChromePhp::clear();
    $fromIndex = 1;
    $allFrom = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName), null, $fromIndex
    );
    $this->assertInstanceOf('\ramp\core\iCollection', $allFrom);
    $this->assertInstanceOf('\ramp\model\business\RecordCollection', $allFrom);
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' LIMIT ' .
      $fromIndex . ', ' . ($maxResults + $fromIndex) . ';';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertSame($maxResults, $allFrom->count());
    $i=1;
    foreach ($allFrom as $recordFrom)
    {
      $this->assertInstanceOf('\\' . SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $recordFrom);
      $this->assertSame('A', $recordFrom->key1->value);
      $this->assertSame('A', $recordFrom->key2->value);
      $this->assertSame($keyBvalues[$i++], $recordFrom->key3->value);
      $expectedValueOf1 = ($i % 2 != 0)? 'valueA' : 'Avalue';
      $expectedValueOf2 = ($i == 3 && self::$NEW_VALUE_B == TRUE)? 'newValueB' : 'valueB';
      $this->assertSame($expectedValueOf1, $recordFrom->property1->value);
      $this->assertSame($expectedValueOf2, $recordFrom->property2->value);
    }
    $this->assertSame($filtered[0], $all[1]);
    $this->assertSame($filtered[0], $allFrom[0]);
    \ChromePhp::clear();
    $_POST = array(
      'mock-min-record:a|a|b:property-2' => 'newValueB',
      'mock-min-record:a|a|c:property-2' => 'newValueB',
      'mock-min-record:a|a|d:property-2' => 'newValueB',
      'mock-min-record:a|a|e:property-2' => 'newValueB'
    );
    $allFrom->validate(PostData::build($_POST));
    $this->testObject->update($all);
    $j = $i = 0;
    foreach ($all as $recordTo)
    {
      $expectedLogStatement = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
        'key1=:key1, key2=:key2, key3=:key3, property1=:property1, property2=:property2 WHERE key1=:key1 AND key2=:key2 AND key3=:key3';
      $this->assertEquals($expectedLogStatement, \ChromePhp::getMessages()[$j++]);
      $expectedLogValues = 'LOG:values: A, A, ' . $keyBvalues[$i++] . ', ' . $recordTo->property1->value . ', ' . $recordTo->property2->value;
      $this->assertEquals($expectedLogValues, \ChromePhp::getMessages()[($j++)]);
    }
    $this->assertEquals(4, $i);
    \ChromePhp::clear();
    $this->testObject->updateAny();
    $expectedLogStatement = 'LOG:$preparedStatement: UPDATE ' . $this->recordName .' SET ' .
      'key1=:key1, key2=:key2, key3=:key3, property1=:property1, property2=:property2 WHERE key1=:key1 AND key2=:key2 AND key3=:key3';
    $this->assertEquals($expectedLogStatement, \ChromePhp::getMessages()[0]);
    $expectedLogValues = 'LOG:values: A, A, E, valueA, newValueB';
    $this->assertEquals($expectedLogValues, \ChromePhp::getMessages()[1]);
    try {
      $this->testObject->getBusinessModel(
        new SimpleBusinessModelDefinition($this->recordName),
        Filter::build($this->recordName, array('key1' => 'A', 'key2' => 'B', 'key3' => 'C'))
      );
    } catch (DataFetchException $expected) {
      $this->assertSame('No matching Records found in data storage!', $expected->getMessage());
      return;
    }
    $this->fail('An expected DataFetchException has NOT been raised.');
  }

  /**
   * Fail attemped update of Record NOT retrieved through this BusinessModelManager.
   * - assert throws \InvalidArgumentException when model unknown
   *   - with message *'Provided Model NOT retrieved through this BusinessModelManager'*
   * @see \ramp\model\business\SQLBusinessModelManager::update()
   */
  public function testUpdateInvalidArgumentException() : void
  {
    $this->testObject = SQLBusinessModelManager::getInstance();
    try {
      $this->testObject->update(new MockMinRecord());
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
   * Failed data write update(Record) updateAny() with BAD BusinessModel defined in
   * system and no corresponding table in SQL Data Store.
   *
   * **THIS EXTRA TEST HAS BEEN RENAMED TO PREVENT EXECUTION AS SLOW RUNNING
   * RENAME *EXTRADataWriteException* TO *testDataWriteException* TO RUN**
   *
   * - assert LOG:SQL: SELECT for key avalibility check.
   * - assert throws DataWriteException when Record type NOT found in data storage
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert logged error reports and delayed atempts reported in \ChromePhp (Logger)
   * @see \ramp\model\business\SQLBusinessModelManager::update()
   * @see \ramp\model\business\SQLBusinessModelManager::updateAny()
   */
  #[Depneds('testGetBusinessModelCollection')]
  public function EXTRADataWriteException()
  {
    $badRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('BadRecord'), Str::set('new'))
    );
    $_POST = array(
      'bad-record:new:key' => 'key',
      'bad-record:new:property1' => 'valueA',
      'bad-record:new:property2' => 'valueB'
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
        'LOG:SQL: SELECT * FROM BadRecord WHERE BadRecord.key = "key" LIMIT 0, 4;',
        \ChromePhp::getMessages()[$i++]);
      $this->assertSame(
        'LOG:$preparedStatement: INSERT INTO BadRecord (key) VALUES (:key)',
        \ChromePhp::getMessages()[$i++]);
      $this->assertSame('LOG:values: key', \ChromePhp::getMessages()[$i++]);
      while ($j < 3)
      {
        $j++;
        $this->assertSame(
          'GROUP:Unable to write to data store ' . $j,
          \ChromePhp::getMessages()[$i++]
        );
        $this->assertSame(
          'LOG:STATEMENT: INSERT INTO BadRecord (key) VALUES (:key)',
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
