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
namespace tests\ramp\mocks\model;

use ramp\SETTING;
use ramp\core\Str;
use ramp\model\business\BusinessModel;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\RecordCollection;
use ramp\model\business\DataFetchException;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

// use tests\ramp\mocks\model\MockRecord;

/**
 * Mock business model managers for testing \ramp\http\Session
 * .
 */
class MockBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  public $callCount;
  public $updateLog;
  public $mockMinNew;
  public $mockNew;
  public $objectNew;
  public $objectOne;
  public $objectTwo;
  public $objectThree;
  public $objectFour;
  public $dataObjectNew;
  public $dataObjectOne;
  public $dataObjectTwo;
  public $dataObjectThree;
  public $dataObjectFour;

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
    $this->callCount = 0;
  }

  /**
   * Get instance - same instance on every request (singlton)
   *
   * PRECONDITIONS
   * - Requires the following global constants to be set
   *  (depending on data storage type) (usually via ramp.ini):
   *  - \ramp\SETTING::$DATABASE_CONNECTION
   *  - \ramp\SETTING::$DATABASE_USER
   *  - \ramp\SETTING::$DATABASE_PASSWORD
   *  - \ramp\SETTING::$DATABASE_MAX_RESULTS
   *  - \ramp\SETTING::$RAMP_MODEL_NAMESPACE
   * POSTCONDITIONS
   * - ensures default values set on relavant properties
   * @return \ramp\model\business\BusinessModelManager Single instance of BusinessModelManager
   */
  public static function getInstance() : BusinessModelManager
  {
    if (!isset(self::$instance)) {
      self::$instance = new MockBusinessModelManager();
    }
    return self::$instance;
  }

  private function buildObjectNew()
  {
    $this->dataObjectNew = new \stdClass();
    $this->dataObjectNew->keyA = 1;
    $this->dataObjectNew->keyB = 1;
    $this->dataObjectNew->keyC = 1;
    $this->objectNew = new MockRecord($this->dataObjectNew);
  }
  private function buildObjectOne()
  {
    $this->dataObjectOne = new \stdClass();
    $this->dataObjectOne->keyA = 2;
    $this->dataObjectOne->keyB = 2;
    $this->dataObjectOne->keyC = 2;
    $this->dataObjectOne->fk_relationBeta_MockMinRecord_key1 = 'A';
    $this->dataObjectOne->fk_relationBeta_MockMinRecord_key2 = 'B';
    $this->dataObjectOne->fk_relationBeta_MockMinRecord_key3 = 'C';
    $this->objectOne = new MockRecord($this->dataObjectOne);
  }
  private function buildObjectTwo() {
    $this->dataObjectTwo = new \stdClass();
    $this->dataObjectTwo->key1 = 'A';
    $this->dataObjectTwo->key2 = 'B';
    $this->dataObjectTwo->key3 = 'C';
    $this->objectTwo = new MockMinRecord($this->dataObjectTwo);          
  }
  private function buildObjectThree() {
    $this->dataObjectThree = new \stdClass();
    $this->dataObjectThree->key1 = 'A';
    $this->dataObjectThree->key2 = 'B';
    $this->dataObjectThree->key3 = 'D';
    $this->dataObjectThree->fk_relationAlpha_MockRecord_keyA = 1;
    $this->dataObjectThree->fk_relationAlpha_MockRecord_keyB = 1;
    $this->dataObjectThree->fk_relationAlpha_MockRecord_keyC = 1;
    $this->objectThree = new MockMinRecord($this->dataObjectThree);
  }
  private function buildObjectFour() {
    $this->dataObjectFour = new \stdClass();
    $this->dataObjectFour->key1 = 'A';
    $this->dataObjectFour->key2 = 'B';
    $this->dataObjectFour->key3 = 'E';
    $this->dataObjectFour->fk_relationAlpha_MockRecord_keyA = 1;
    $this->dataObjectFour->fk_relationAlpha_MockRecord_keyB = 1;
    $this->dataObjectFour->fk_relationAlpha_MockRecord_keyC = 1;
    $this->objectFour = new MockMinRecord($this->dataObjectFour);
  }

  /**
   * Returns requested Model.
   * @param \ramp\model\iModelDefinition $definition  Definition of requested Model
   * @param \ramp\condition\Filter $filter Optional Filter to be apply to Model
   * @param int $fromIndex Optional index for first entry in a collection
   * @return \ramp\model\Model Relevant requested Model object
   * @throws \DomainException when {@link \ramp\model\Model}(s) NOT found
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel
  {
    $this->callCount++;
    if ($definition->recordName == 'MockRecord')
    {
      if ($definition->recordKey == 'new') {
        if (!isset($this->mockNew)) { $this->mockNew = new MockRecord(new \stdClass()); }
        return $this->mockNew;
      }
      if (
        $definition->RecordKey == '1|1|1' ||
        (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockRecord.keyA = "1" AND MockRecord.keyB = "1" AND MockRecord.keyC = "1"')
      ) {
        if (!isset($this->objectNew)) { $this->buildObjectNew(); }
        return $this->objectNew;
      }
      if (
        $definition->RecordKey == '2|2|2' ||
        (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockRecord.keyA = "2" AND MockRecord.keyB = "2" AND MockRecord.keyC = "2"')
      ) {
        if (!isset($this->objectOne)) { $this->buildObjectOne(); }
        return $this->objectOne;
      }
      throw new DataFetchException('No matching Record(s) found in data storage!');
    }
    if ($definition->recordName == 'MockMinRecord')
    {
      if ($definition->recordKey == 'new') {
        if (!isset($this->mockMinNew)) { $this->mockMinNew = new MockMinRecord(new \stdClass()); }
        return $this->mockMinNew;
      }
      if (
        $definition->RecordKey == 'A|B|C' ||
        (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "C"')
      ) {
        if (!isset($this->objectTwo)) { $this->buildObjectTwo(); }
        return $this->objectTwo;
      }
      if (
        $definition->RecordKey == 'A|B|D' ||
        (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "D"')
      ) {
        if (!isset($this->objectThree)) { $this->buildObjectThree(); }
        return $this->objectThree;
      }
      if (
        $definition->RecordKey == 'A|B|E' ||
        (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "E"')
      ) {
        if (!isset($this->objectFour)) { $this->buildObjectFour(); }
        return $this->objectFour;
      }
      if (
        isset($filter) && $filter(SQLEnvironment::getInstance()) == 
        'MockMinRecord.fk_relationAlpha_MockRecord_keyA = "2" AND ' .
        'MockMinRecord.fk_relationAlpha_MockRecord_keyB = "2" AND ' .
        'MockMinRecord.fk_relationAlpha_MockRecord_keyC = "2"'
      ) {
        if (!isset($this->objectNew)) { $this->buildObjectNew(); }
        if (!isset($this->objectOne)) { $this->buildObjectOne(); }
        if (!isset($this->objectTwo)) { $this->buildObjectTwo(); }
        if (!isset($this->objectThree)) { $this->buildObjectThree(); }
        if (!isset($this->objectFour)) { $this->buildObjectFour(); }
        $collection = new RecordCollection();
        $collection->add($this->objectTwo);
        $collection->add($this->objectThree);
        $collection->add($this->objectFour);
        $collection->add(new MockMinRecord());
        return $collection;
      }
      if (
        isset($filter) && $filter(SQLEnvironment::getInstance()) ==
        'MockMinRecord.fk_relationAlpha_MockRecord_keyA = "1" AND ' .
        'MockMinRecord.fk_relationAlpha_MockRecord_keyB = "1" AND ' .
        'MockMinRecord.fk_relationAlpha_MockRecord_keyC = "1"'
      ) {
        $collection = new RecordCollection();
        return $collection;
      }
      throw new DataFetchException('No matching Record(s) found in data storage!');
    }
    throw new \DomainException('Business Model(s) NOT found!');
  }

  /**
   * Update {@link Model} to any permanent data store.
   * @param BusinessModel Object to be updated
   * @throws \InvalidArgumentException when {@link \ramp\model\business\BusinessModel}
   *  was not initially retrieved using this BusinessModelManager
   */
  public function update(BusinessModel $model)
  {
    $this->updateLog[get_class($model) . ':' . $model->primaryKey->value] = 'updated ' . date('H:i:s');
    $model->updated();
  }

  /**
   * Ensure update of any out of sync Models with any permanent data store.
   */
  public function updateAny()
  {
    if (isset($this->objectOne) && $this->objectOne->isModified && $this->objectOne->isValid) { $this->update($this->objectOne); }
    if (isset($this->objectTwo) && $this->objectTwo->isModified && $this->objectTwo->isValid) { $this->update($this->objectTwo); }
    if (isset($this->objectThree) && $this->objectThree->isModified && $this->objectThree->isValid) { $this->update($this->objectThree); }
    if (isset($this->objectFour) && $this->objectFour->isModified && $this->objectFour->isValid) { $this->update($this->objectFour); }
  }
}
