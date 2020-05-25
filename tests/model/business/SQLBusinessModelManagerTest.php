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
namespace tests\svelte\model\business;

require_once '/usr/share/php/tests/ChromePhp.class.php';
require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/condition/Filter.class.php';
require_once '/usr/share/php/svelte/condition/FilterCondition.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/svelte/model/business/SQLBusinessModelManager.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Input.class.php';
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/UniquePrimaryKey.class.php';
require_once '/usr/share/php/svelte/model/business/validation/Alphanumeric.class.php';
require_once '/usr/share/php/svelte/model/business/validation/LowerCaseAlphanumeric.class.php';

require_once '/usr/share/php/tests/svelte/model/business/mocks/SQLBusinessModelManagerTest/BadRecord.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/SQLBusinessModelManagerTest/MockRecord.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\condition\Filter;
use svelte\condition\PostData;
use svelte\model\business\SQLBusinessModelManager;
use svelte\model\business\SimpleBusinessModelDefinition;

use tests\svelte\model\business\mocks\SQLBusinessModelManagerTest\BadRecord;
use tests\svelte\model\business\mocks\SQLBusinessModelManagerTest\MockRecord;

/**
 * Collection of tests for \svelte\model\business\SQLBusinessModelManager.
 */
class SQLBusinessModelManagerTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $recordName;
  private $recordKey;
  private $collection;
  private $primaryKeyName;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\mocks\SQLBusinessModelManagerTest';
    SETTING::$SVELTE_BUSINESS_MODEL_MANAGER = 'svelte\model\business\SQLBusinessModelManager';
    SETTING::$DATABASE_CONNECTION = 'sqlite:/usr/share/php/' . str_replace('\\', '/', SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE) . '/database.db';
    SETTING::$DATABASE_MAX_RESULTS = 4;
    $recordName = 'MockRecord';
    $recordFullName = SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    $this->primaryKeyName = $recordFullName::primaryKeyName();
    $this->recordName = Str::set($recordName);
    $this->recordKey = Str::set('key');
    $this->collection = Str::set('Collection');
    $DIR = '/usr/share/php/tests/svelte/model/business/mocks/SQLBusinessModelManagerTest';
    copy($DIR . '/database_copy.db', $DIR . '/database.db') or die("Unable to copy database.");
    $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
    $this->testObject = $MODEL_MANAGER::getInstance();
    \ChromePhp::clear();
  }

  /**
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::getInstance().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\BusinessModelManager}
   * - assert is instance of {@link \svelte\model\business\SQLBusinessModelManager}
   * - assert is same instance on every call (Singleton)
   * - assert cannot be cloned, throwing \BadMethodCallException
   *   - with message *Cloning is not allowed*
   * @link svelte.model.business.SQLBusinessModelManager svelte\model\business\SQLBusinessModelManager
   */
  public function testGetInstance()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModelManager', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\SQLBusinessModelManager', $this->testObject);
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
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordKey equals 'new'.
   * - assert no SQL statement logged with \ChromePhp (Logger) as is 'new'
   * - assert returned is instance of {@link \svelte\model\Model}
   * - assert returned is instance of {@link \svelte\model\business\BusinessModel}
   * - assert returned is instance of {@link \svelte\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert returns fresh Record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of record are instance of {@link \svelte\model\business\field\Field}
   * - assert each property's field\Field::$value returns NULL
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData)
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * @link svelte.model.business.SQLBusinessModelManager#method_getBusinessModel svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelNewRecord()
  {
    $newRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, Str::set('new'))
    );
    $this->assertFalse(isset(\ChromePhp::getMessages()[0])); // No SQL statement logged
    $this->assertInstanceOf('\svelte\model\Model', $newRecord);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $newRecord);
    $this->assertInstanceOf('\svelte\model\business\Record', $newRecord);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(Str::set('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\')),
      $newRecord
    );
    $this->assertTrue($newRecord->isNew);
    $this->assertFalse($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->property);
    $this->assertNull($newRecord->property->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->propertyA);
    $this->assertNull($newRecord->propertyA->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->propertyB);
    $this->assertNull($newRecord->propertyB->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->propertyC);
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
    $expectedLog1 = 'LOG:$preparedStatement: INSERT INTO ' . $this->recordName .
      ' (property, propertyA, propertyB, propertyC) ' .
      'VALUES (:property, :propertyA, :propertyB, :propertyC)';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: key, valueA, valueB, valueC';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertFalse($newRecord->isNew);
    $this->assertTrue($newRecord->isValid);
    $this->assertFalse($newRecord->isModified);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->property);
    $this->assertSame('key', $newRecord->property->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->propertyA);
    $this->assertSame('valueA', $newRecord->propertyA->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->propertyB);
    $this->assertSame('valueB', $newRecord->propertyB->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $newRecord->propertyC);
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
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   * where iBusinessModelDefinition::$recordKey references existing stored Record.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@link \svelte\model\Model}
   * - assert returned is instance of {@link \svelte\model\business\BusinessModel}
   * - assert returned is instance of {@link \svelte\model\business\Record}
   * - assert returned record is instance of provided argument iBusinessModelDefinition::$recordName
   * - assert state of returned Record (isNew, isValid and isModified) is as expected
   * - assert properties of Record are instance of {@link \svelte\model\business\field\Field}
   * - assert each property's field\Field::$value of stored Record returns as stored
   * - assert state of Record its properties, as well as hasErrors, isNew, isValid and
   *   isModified have changed as expected following population via Record::validate(PostData).
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert state of Record (isNew, isValid and isModified) is as expected following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName and $recordKey)
   *   returns referance to same Record without contacting data store
   * @link svelte.model.business.SQLBusinessModelManager#method_getBusinessModel svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelStoredRecord()
  {
    $recordKey = $this->recordKey->append(Str::set('1'));
    $storedRecord = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE ' .
      $this->recordName . '.' . $this->primaryKeyName . ' = "' . $recordKey . '";';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertInstanceOf('\svelte\model\Model', $storedRecord);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $storedRecord);
    $this->assertInstanceOf('\svelte\model\business\Record', $storedRecord);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(Str::set('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\')),
      $storedRecord
    );
    $this->assertFalse($storedRecord->isNew);
    $this->assertTrue($storedRecord->isValid);
    $this->assertFalse($storedRecord->isModified);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $storedRecord->property);
    $this->assertSame((string)$recordKey, $storedRecord->property->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $storedRecord->propertyA);
    $this->assertSame('valueA', $storedRecord->propertyA->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $storedRecord->propertyB);
    $this->assertSame('valueB', $storedRecord->propertyB->value);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $storedRecord->propertyC);
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
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordKey is NOT in data store.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert throws \DomainException as provided iBusinessModelDefinition::$recordKey NOT found
   *   - with message: <em>'No matching Record(s) found in data storage!'</em>
   * @link svelte.model.business.SQLBusinessModelManager#method_getBusinessModel svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelRecordNotStored()
  {
    try {
      $recordWithBadKey = $this->testObject->getBusinessModel(
        new SimpleBusinessModelDefinition($this->recordName, Str::set('badkey'))
      );
    } catch (\DomainException $expected) {
      $this->assertSame('No matching Record(s) found in data storage!', $expected->getMessage());
      $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE ' .
        $this->recordName . '.' . $this->primaryKeyName . ' = "badkey";';
      $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
      return;
    }
    $this->fail('An expected \DomainException has NOT been raised');
  }

  /**
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition has $recordName $recordKey and $propertyName thereby
   * retuning a \svelte\model\business\field\Field.
   * - assert makes successful connection with data store
   * - assert SELECT SQL statement logged with \ChromePhp (Logger) on first request as expected
   * - assert returned is instance of {@link \svelte\model\Model}
   * - assert returned is instance of {@link \svelte\model\business\BusinessModel}
   * - assert returned is instance of {@link \svelte\model\business\field\Field}
   * - assert property's field\Field::$value returns as stored
   * - assert expected UPDATE SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert duplicate requests (same iBusinessModelDefinition::$recordName, $recordKey and
   *   $propertyName) returns referance to same Field without contacting data store
   * @link svelte.model.business.SQLBusinessModelManager#method_getBusinessModel svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelProperty()
  {
    $recordKey = $this->recordKey->append(Str::set('2'));
    $propertyName = Str::set('propertyB');
    $property = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, $recordKey, $propertyName)
    );
    $expectedLog = 'LOG:SQL: SELECT * FROM ' . $this->recordName . ' WHERE ' .
      $this->recordName . '.' . $this->primaryKeyName . ' = "' . $recordKey . '";';
    $this->assertSame($expectedLog, (string)\ChromePhp::getMessages()[0]);
    $this->assertInstanceOf('\svelte\model\Model', $property);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $property);
    $this->assertInstanceOf('\svelte\model\business\field\Field', $property);
    $this->assertInstanceOf('svelte\model\business\field\Input', $property);
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
  }

  /**
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   * where provided iBusinessModelDefinition::$recordName ONLY, returning a RecordCollection.
   * - assert makes successful connection with data store
   * - assert returned is instance of {@link \svelte\core\iCollection}
   * - assert returned is instance of {@link \svelte\model\business\RecordCollection}
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
   * @depends testGetBusinessModelProperty
   * @link svelte.model.business.SQLBusinessModelManager#method_getBusinessModel svelte\model\business\SQLBusinessModelManager::getBusinessModel()
   */
  public function testGetBusinessModelCollection()
  {
    $all = $this->testObject->getBusinessModel(new SimpleBusinessModelDefinition($this->recordName));
    $this->assertInstanceOf('\svelte\core\iCollection', $all);
    $this->assertInstanceOf('\svelte\model\business\RecordCollection', $all);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(
        Str::set('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\')
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
      $this->assertInstanceOf('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $record);
      $this->assertSame('key' . $i, $record->property->value);
      $expectedValueOfA = ($i != 1)? ($i % 2 != 0)? 'valueA' : 'Avalue' : 'newValue';
      $this->assertSame($expectedValueOfA, $record->propertyA->value);
      $expectedValueOfB = ($i == 2)? 'newValueB' : 'valueB';
      $this->assertSame($expectedValueOfB, $record->propertyB->value);
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
      $this->assertInstanceOf('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $record);
      $this->assertSame('key' . ($i * 2), $record->property->value);
      $this->assertSame('Avalue', $record->propertyA->value);
      $expectedValueOfB = ($i == 1)? 'newValueB' : 'valueB';
      $this->assertSame($expectedValueOfB, $record->propertyB->value);
      $this->assertSame('valueC', $record->propertyC->value);
    }
    \ChromePhp::clear();
    $fromIndex = 1;
    $allFrom = $this->testObject->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName), null, $fromIndex
    );
    $this->assertInstanceOf('\svelte\core\iCollection', $allFrom);
    $this->assertInstanceOf('\svelte\model\business\RecordCollection', $allFrom);
    $this->assertInstanceOf(
      (string)$this->recordName->prepend(
        Str::set('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\')
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
      $this->assertInstanceOf('\\' . SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName, $record);
      $this->assertSame('key' . $i, $record->property->value);
      $expectedValueOfA = ($i % 2 != 0)? 'valueA' : 'Avalue';
      $this->assertSame($expectedValueOfA, $record->propertyA->value);
      $expectedValueOfB = ($i == 2)? 'newValueB' : 'valueB';
      $this->assertSame($expectedValueOfB, $record->propertyB->value);
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
      $expectedLogValues = 'LOG:values: ' . $record->key . ', ' .
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
      $this->assertSame('No matching Record(s) found in data storage!', $expected->getMessage());
      return;
    }
    $this->fail('An expected \DomainException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::update().
   * - assert throws \InvalidArgumentException when model unknown
   *   - with message *'Provided Model NOT retrieved through this BusinessModelManager'*
   * @link svelte.model.business.SQLBusinessModelManager#method_update svelte\model\business\SQLBusinessModelManager::update()
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
   * Collection of assertions for \svelte\model\business\SQLBusinessModelManager::update() and
   * \svelte\model\business\SQLBusinessModelManager::updateAny() with BAD BusinessModel defined in
   * system but no Table in SQL DataBase.
   *
   * **THIS EXTRA TEST HAS BEEN RENAMED TO PREVENT EXECUTION AS SLOW RUNNING
   * RENAME *EXTRADataWriteException* TO *testDataWriteException* TO RUN**
   *
   * - assert throws \PDOException when Record type NOT found in data storage
   * - assert expected INSERT SQL statements logged with \ChromePhp (Logger) following
   *   SQLBusinessModelManager::update(BusinessModel) or SQLBusinessModelManager::updateAny()
   * - assert logged error reports and delayed atempts reported in \ChromePhp (Logger)
   * @link svelte.model.business.SQLBusinessModelManager#method_update svelte\model\business\SQLBusinessModelManager::update()
   * @link svelte.model.business.SQLBusinessModelManager#method_updateAny svelte\model\business\SQLBusinessModelManager::updateAny()
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
    } catch (\PDOException $expected) {
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
