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
use ramp\model\business\RelationLookup;
use ramp\model\business\BusinessModel;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\RecordCollection;
use ramp\model\business\DataFetchException;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

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
  public $objectFive;
  public $dataObjectNew;
  public $dataObjectOne;
  public $dataObjectTwo;
  public $dataObjectThree;
  public $dataObjectFour;
  public $dataObjectFive;
  // MANY to MANY LOOKUP
  private $lookup;
  public $dataA1;
  public $dataB1;
  public $dataB2;
  public $dataB3;
  public $objectA1;
  public $objectB1;
  public $objectB2;
  public $objectB3;
  public $dataLookupA1toB1;
  public $dataLookupA1toB2;
  public $dataLookupA1toB3;
  public $objectLookupA1toB1;
  public $objectLookupA1toB2;
  public $objectLookupA1toB3;

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
    $this->lookup = FALSE;
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

  public static function reset()
  {
    $thiz = self::getInstance();
    $thiz->mockMinNew = NULL;
    $thiz->mockNew = NULL;
    $thiz->objectNew = NULL;
    $thiz->objectOne = NULL;
    $thiz->objectTwo = NULL;
    $thiz->objectThree = NULL;
    $thiz->objectFour = NULL;
    $thiz->objectFive = NULL;
    $thiz->objectA1 = NULL;
    $thiz->objectA1 = NULL;
    $thiz->objectA1 = NULL;
    $thiz->objectA1 = NULL;
    $thiz->objectLookupA1toB1 = NULL;
    $thiz->objectLookupA1toB2 = NULL;
    $thiz->objectLookupA1toB3 = NULL;
    self::$instance = NULL;
  }

  private function buildMockNew()
  {
    $this->mockNew = new MockRecord(new \stdClass());
  }
  private function buildMinNew()
  {
    $this->mockMinNew = new MockMinRecord(new \stdClass());
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
    $this->dataObjectThree->fk_relationDelta_MockRecord_keyA = 1;
    $this->dataObjectThree->fk_relationDelta_MockRecord_keyB = 1;
    $this->dataObjectThree->fk_relationDelta_MockRecord_keyC = 1;
    $this->objectThree = new MockMinRecord($this->dataObjectThree);
  }
  private function buildObjectFour() {
    $this->dataObjectFour = new \stdClass();
    $this->dataObjectFour->key1 = 'A';
    $this->dataObjectFour->key2 = 'B';
    $this->dataObjectFour->key3 = 'E';
    $this->dataObjectFour->fk_relationDelta_MockRecord_keyA = 1;
    $this->dataObjectFour->fk_relationDelta_MockRecord_keyB = 1;
    $this->dataObjectFour->fk_relationDelta_MockRecord_keyC = 1;
    $this->objectFour = new MockMinRecord($this->dataObjectFour);
  }
  private function buildObjectFive() {
    $this->dataObjectFive = new \stdClass();
    $this->dataObjectFive->key1 = 'A';
    $this->dataObjectFive->key2 = 'B';
    $this->dataObjectFive->key3 = 'F';
    $this->dataObjectFive->fk_relationDelta_MockRecord_keyA = 1;
    $this->dataObjectFive->fk_relationDelta_MockRecord_keyB = 1;
    $this->dataObjectFive->fk_relationDelta_MockRecord_keyC = 1;
    $this->objectFive = new MockMinRecord($this->dataObjectFive);
  }

  // MANY to MANY LOOKUPs
  private function buildLOOKUP()
  {
    $this->lookup = TRUE;

    $this->dataA1 = new \stdClass();
    $this->dataA1->keyA = 1;
    $this->dataA1->keyB = 1;
    $this->dataA1->keyC = 1;
    $this->objectA1 = new RecordA($this->dataA1);

    $this->dataB1 = new \stdClass();
    $this->dataB1->key1 = 1;
    $this->dataB1->key2 = 1;
    $this->objectB1 = new RecordB($this->dataB1);

    $this->dataLookupA1toB1 = new \stdClass();
    $this->dataLookupA1toB1->fk_RecordA_keyA = '1';
    $this->dataLookupA1toB1->fk_RecordA_keyB = '1';
    $this->dataLookupA1toB1->fk_RecordA_keyC = '1';
    $this->dataLookupA1toB1->fk_RecordB_key1 = '1';
    $this->dataLookupA1toB1->fk_RecordB_key2 = '1';
    $this->objectLookupA1toB1 = new Lookup($this->dataLookupA1toB1);

    $this->dataB2 = new \stdClass();
    $this->dataB2->key1 = 2;
    $this->dataB2->key2 = 2;
    $this->objectB2 = new RecordB($this->dataB2);

    $this->dataLookupA1toB2 = new \stdClass();
    $this->dataLookupA1toB2->fk_RecordA_keyA = '1';
    $this->dataLookupA1toB2->fk_RecordA_keyB = '1';
    $this->dataLookupA1toB2->fk_RecordA_keyC = '1';
    $this->dataLookupA1toB2->fk_RecordB_key1 = '2';
    $this->dataLookupA1toB2->fk_RecordB_key2 = '2';
    $this->objectLookupA1toB2 = new Lookup($this->dataLookupA1toB2);

    $this->dataB3 = new \stdClass();
    $this->dataB3->key1 = 3;
    $this->dataB3->key2 = 3;
    $this->objectB3 = new RecordB($this->dataB3);

    $this->dataLookupA1toB3 = new \stdClass();
    $this->dataLookupA1toB3->fk_RecordA_keyA = '1';
    $this->dataLookupA1toB3->fk_RecordA_keyB = '1';
    $this->dataLookupA1toB3->fk_RecordA_keyC = '1';
    $this->dataLookupA1toB3->fk_RecordB_key1 = '3';
    $this->dataLookupA1toB3->fk_RecordB_key2 = '3';
    $this->objectLookupA1toB3 = new Lookup($this->dataLookupA1toB3);

    // $this->dataA2 = new \stdClass();
    // $this->dataA2->keyA = 2;
    // $this->dataA2->keyB = 2;
    // $this->dataA2->keyC = 2;
    // $this->objectA2 = new RecordA($this->dataA2);

    // $this->dataLookupA2toB1 = new \stdClass();
    // $this->dataLookupA2toB1->fk_RecordA_keyA = '2';
    // $this->dataLookupA2toB1->fk_RecordA_keyB = '2';
    // $this->dataLookupA2toB1->fk_RecordA_keyC = '2';
    // $this->dataLookupA2toB1->fk_RecordB_key1 = '1';
    // $this->dataLookupA2toB1->fk_RecordB_key2 = '1';
    // $this->objectLookupA2toB1 = new Lookup($this->dataLookupA2toB1);

    // $this->dataA3 = new \stdClass();
    // $this->dataA3->keyA = 3;
    // $this->dataA3->keyB = 3;
    // $this->dataA3->keyC = 3;
    // $this->objectA3 = new RecordA($this->dataA3);

    // $this->dataLookupA3toB1 = new \stdClass();
    // $this->dataLookupA3toB1->fk_RecordA_keyA = '3';
    // $this->dataLookupA3toB1->fk_RecordA_keyB = '3';
    // $this->dataLookupA3toB1->fk_RecordA_keyC = '3';
    // $this->dataLookupA3toB1->fk_RecordB_key1 = '1';
    // $this->dataLookupA3toB1->fk_RecordB_key2 = '1';
    // $this->objectLookupA3toB1 = new Lookup($this->dataLookupA3toB1);
  }

  /**
   * Returns requested Model.
   * @param \ramp\model\iModelDefinition $definition  Definition of requested Model
   * @param \ramp\condition\Filter $filter Optional Filter to be apply to Model
   * @param int $fromIndex Optional index for first entry in a collection
   * @return \ramp\model\Model Relevant requested Model object
   * @throws \DomainException when {@see \ramp\model\Model}(s) NOT found
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel
  {
    $this->callCount++;
    if ((string)$definition->recordName == 'MockRecord')
    {
      if ((string)$definition->recordKey == 'new') {
        // if (!isset($this->mockNew)) { 
        $this->buildMockModel(); //}
        return $this->mockNew;
      }
      if ((string)$definition->RecordKey == '1|1|1') {
        if (!isset($this->objectNew)) { $this->buildObjectNew(); }
        return $this->objectNew;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockRecord.keyA = "1" AND MockRecord.keyB = "1" AND MockRecord.keyC = "1"') {
        if (!isset($this->objectNew)) { $this->buildObjectNew(); }
        $o = new RecordCollection();
        $o->add($this->objectNew);
        return $o;
      }
      if ((string)$definition->RecordKey == '2|2|2') {
        if (!isset($this->objectOne)) { $this->buildObjectOne(); }
        return $this->objectOne;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockRecord.keyA = "2" AND MockRecord.keyB = "2" AND MockRecord.keyC = "2"') {
        if (!isset($this->objectOne)) { $this->buildObjectOne(); }
        $o = new RecordCollection();
        $o->add($this->objectOne);
        return $o;
      }
      throw new DataFetchException('No matching Record(s) found in data storage!');
    }
    if ((string)$definition->recordName == 'MockMinRecord')
    {
      if ((string)$definition->recordKey == 'new') {
        // if (!isset($this->mockMinNew)) {
        $this->buildMinNew(); //}
        return $this->mockMinNew;
      }
      if ((string)$definition->RecordKey == 'A|B|C') {
        if (!isset($this->objectTwo)) { $this->buildObjectTwo(); }
        return $this->objectTwo;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "C"') {
        if (!isset($this->objectTwo)) { $this->buildObjectTwo(); }
        $o = new RecordCollection();
        $o->add($this->objectTwo);
        return $o;
      }
      if ((string)$definition->RecordKey == 'A|B|D') {
        if (!isset($this->objectThree)) { $this->buildObjectThree(); }
        return $this->objectThree;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "D"') {
        if (!isset($this->objectThree)) { $this->buildObjectThree(); }
        $o = new RecordCollection();
        $o->add($this->objectThree);
        return $o;
      }
      if ((string)$definition->RecordKey == 'A|B|E') {
        if (!isset($this->objectFour)) { $this->buildObjectFour(); }
        return $this->objectFour;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "E"') {
        if (!isset($this->objectFour)) { $this->buildObjectFour(); }
        $o = new RecordCollection();
        $o->add($this->objectFour);
        return $o;
      }
      if ((string)$definition->RecordKey == 'A|B|F') {
        if (!isset($this->objectFive)) { $this->buildObjectFive(); }
        return $this->objectFive;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'MockMinRecord.key1 = "A" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "F"') {
        if (!isset($this->objectFive)) { $this->buildObjectFive(); }
        $o = new RecordCollection();
        $o->add($this->objectFive);
        return $o;
      }
      if (
        isset($filter) && $filter(SQLEnvironment::getInstance()) == 
        'MockMinRecord.fk_relationAlpha_MockRecord_keyA = "2" AND ' .
        'MockMinRecord.fk_relationAlpha_MockRecord_keyB = "2" AND ' .
        'MockMinRecord.fk_relationAlpha_MockRecord_keyC = "2"'
      ) {
        // NO ENTRIES 
        if (!isset($this->objectMockNew)) { $this->buildObjectMockNew(); }
        $collection = new RecordCollection();
        $collection->add($this->objectMockNew);
        return $collection;
      }
      if (
        isset($filter) && $filter(SQLEnvironment::getInstance()) ==
        'MockMinRecord.fk_relationDelta_MockRecord_keyA = "1" AND ' .
        'MockMinRecord.fk_relationDelta_MockRecord_keyB = "1" AND ' .
        'MockMinRecord.fk_relationDelta_MockRecord_keyC = "1"'
      ) {
        if (!isset($this->objectThree)) { $this->buildObjectThree(); }
        if (!isset($this->objectFour)) { $this->buildObjectFour(); }
        if (!isset($this->objectFive)) { $this->buildObjectFive(); }
        $collection = new RecordCollection();
        $collection->add($this->objectThree);
        $collection->add($this->objectFour);
        $collection->add($this->objectFive);
        return $collection;
      }
      throw new DataFetchException('No matching Record(s) found in data storage!');
    }
    if (!$this->lookup) { $this->buildLookup(); }
    if ((string)$definition->recordName == 'Lookup')
    {
      if (
        isset($filter) && $filter(SQLEnvironment::getInstance()) ==
        'Lookup.fk_RecordA_keyA = "1" AND ' .
        'Lookup.fk_RecordA_keyB = "1" AND ' .
        'Lookup.fk_RecordA_keyC = "1"'
      ) {
        $lookupA1 = new RecordCollection();
        $lookupA1->add($this->objectLookupA1toB1);
        $lookupA1->add($this->objectLookupA1toB2);
        $lookupA1->add($this->objectLookupA1toB3);
        return $lookupA1;
      }
      if (
        isset($filter) && $filter(SQLEnvironment::getInstance()) ==
        'Lookup.fk_RecordB_key1 = "1" AND ' .
        'Lookup.fk_RecordB_key2 = "1"'
      ) {
        $lookupB1 = new RecordCollection();
        $lookupB1->add($this->objectLookupA1toB1);
        $lookupB1->add($this->objectLookupA2toB1);
        $lookupB1->add($this->objectLookupA3toB1);
        return $lookupB1;
      }
    }
    if ((string)$definition->recordName == 'RecordA')
    {
      if ((string)$definition->RecordKey == '1|1|1') { return $this->objectA1; }
      if ((string)$definition->RecordKey == '2|2|2') { return $this->objectA2; }
      if ((string)$definition->RecordKey == '3|3|3') { return $this->objectA3; }
    }
    if ((string)$definition->recordName == 'RecordB')
    {
      if ((string)$definition->RecordKey == '1|1') { return $this->objectB1; }
      if ((string)$definition->RecordKey == '2|2') { return $this->objectB2; }
      if ((string)$definition->RecordKey == '3|3') { return $this->objectB3; }
    }
    throw new \DomainException('Business Model(s) NOT found!');
  }

  /**
   * Update {@see Model} to any permanent data store.
   * @param BusinessModel Object to be updated
   * @throws \InvalidArgumentException when {@see \ramp\model\business\BusinessModel}
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
    if (isset($this->mockMinNew) && $this->mockMinNew->isModified && $this->mockMinNew->isValid) { $this->update($this->mockMinNew); }
    if (isset($this->mockNew) && $this->mockNew->isModified && $this->mockNew->isValid) { $this->update($this->mockNew); }
    if (isset($this->objectNew) && $this->objectNew->isModified && $this->objectNew->isValid) { $this->update($this->objectNew); }
    if (isset($this->objectOne) && $this->objectOne->isModified && $this->objectOne->isValid) { $this->update($this->objectOne); }
    if (isset($this->objectTwo) && $this->objectTwo->isModified && $this->objectTwo->isValid) { $this->update($this->objectTwo); }
    if (isset($this->objectThree) && $this->objectThree->isModified && $this->objectThree->isValid) { $this->update($this->objectThree); }
    if (isset($this->objectFour) && $this->objectFour->isModified && $this->objectFour->isValid) { $this->update($this->objectFour); }
    if (isset($this->objectFive) && $this->objectFive->isModified && $this->objectFive->isValid) { $this->update($this->objectFive); }
    if (isset($this->objectA1) && $this->objectA1->isModified && $this->objectA1->isValid) { $this->update($this->objectA1); }
    if (isset($this->objectA1) && $this->objectB1->isModified && $this->objectB1->isValid) { $this->update($this->objectB1); }
    if (isset($this->objectA1) && $this->objectB2->isModified && $this->objectB2->isValid) { $this->update($this->objectB2); }
    if (isset($this->objectA1) && $this->objectB3->isModified && $this->objectB3->isValid) { $this->update($this->objectB3); }
    if (isset($this->objectLookupA1toB1) && $this->objectLookupA1toB1->isModified && $this->objectLookupA1toB1->isValid) { $this->update($this->objectLookupA1toB1); }
    if (isset($this->objectLookupA1toB2) && $this->objectLookupA1toB2->isModified && $this->objectLookupA1toB2->isValid) { $this->update($this->objectLookupA1toB2); }
    if (isset($this->objectLookupA1toB3) && $this->objectLookupA1toB3->isModified && $this->objectLookupA1toB3->isValid) { $this->update($this->objectLookupA1toB3); }
  }
}
