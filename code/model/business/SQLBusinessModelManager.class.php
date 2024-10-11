<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\SETTING;
use ramp\core\Str;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

/**
 * Manage all models within systems business domain, uses SQL for permanat storage.
 *
 * {@inheritDoc}
 * - Cache already retrieved Records
 *
 * COLLABORATORS
 * - {@see \ramp\model\business\RecordCollection}
 * - {@see \ramp\model\business\Record}
 * - {@see \ramp\model\business\field\Field}
 */
final class SQLBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  private $maxResults;
  private $recordCollection;
  private $dataObjectCollection;
  private $databaseHandle;

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
    $this->maxResults = (isset(SETTING::$DATABASE_MAX_RESULTS)) ? (int)SETTING::$DATABASE_MAX_RESULTS : 25;
    $this->recordCollection = new \SplObjectStorage();
    $this->dataObjectCollection = new \SplObjectStorage();
  }

  /**
   * Get instance - same instance on every request (singleton) within same http request.
   *
   * PRECONDITIONS
   * - Requires the following global constants to be set
   *  (depending on data storage type) (usually via ramp.ini):
   *  - \ramp\SETTING::$DATABASE_CONNECTION
   *  - \ramp\SETTING::$DATABASE_USER
   *  - \ramp\SETTING::$DATABASE_PASSWORD
   *  - \ramp\SETTING::$DATABASE_MAX_RESULTS
   *  - \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE
   * POSTCONDITIONS
   * - ensures default values set on relavant properties
   * @return \ramp\model\business\BusinessModelManager Single instance of BusinessModelManager
   */
  public static function getInstance() : BusinessModelManager
  {
    if (!isset(self::$instance))
    {
      self::$instance = new SQLBusinessModelManager();
    }
    return self::$instance;
  }

  /**
   * Connect to PDO DataStore as $this->databaseHandle.
   */
  private function connect()
  {
    $count=0;
    do {
      try {
        $this->databaseHandle = (substr(SETTING::$DATABASE_CONNECTION, 0, 7) == 'sqlite:')?
          new \PDO(SETTING::$DATABASE_CONNECTION):
          // @codeCoverageIgnoreStart
          new \PDO(SETTING::$DATABASE_CONNECTION, SETTING::$DATABASE_USER, SETTING::$DATABASE_PASSWORD); // @codeCoverageIgnoreEnd
        $this->databaseHandle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return;
        // @codeCoverageIgnoreStart
      } catch (\PDOException $pdoException) {
        $count++;
        sleep($count);
        if (DEV_MODE) { \ChromePhp::log('Database Connection FAILED - Retryed after '.$count.'second(s)'); }
      }
    } while ($count < 3);
    throw $pdoException; // @codeCoverageIgnoreEnd
  }

  /**
   * Return cached record if avalible.
   */
  private function getRecordIfCached(Str $recordName, string $primaryKey) : ?Record
  {
    $class = SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    foreach ($this->recordCollection as $record) {
      if ((get_class($record) == $class) && ($record->primaryKey->value == $primaryKey)) {
        return $record;
      }
    }
    return NULL;
  }

  /**
   * Returns requested Model.
   * @param \ramp\model\business\iBusinessModelDefinition $definition Definition of requested Model
   * @param \ramp\condition\Filter $filter Optional filter to be apply to BusinessModel
   * @param int $fromIndex Optional index of first entry in a collection
   * @return \ramp\model\business\BusinessModel Relevant requested BusinessModel
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel
  {
    if (!$definition->recordKey)
    {
      return $this->getCollection($definition->recordName, $filter, $fromIndex);
    }
    $record = $this->getRecord($definition->recordName, $definition->recordKey);
    return ($propertyName = (string)$definition->propertyName)? $record->$propertyName : $record;
  }

  /**
   * Returns requested Record.
   * @param \ramp\core\Str $name Record type to be returned
   * @param \ramp\core\Str $key Primary key of record
   * @return \ramp\model\business\Record Relevant requested Record
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  private function getRecord(Str $name, Str $key) : Record
  {
    $recordFullName = SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $name;
    $dataObject = new \stdClass();
    $record = new $recordFullName($dataObject);
    if ((string)$key == 'new')
    {
      $this->recordCollection->attach($record);
      $this->dataObjectCollection->attach($dataObject);
    }
    else
    {
      if ($o = $this->getRecordIfCached($name, $key)) { $record = $o;
      } else {
        $and = Str::set(' AND ');
        $keys = $key->explode(Str::BAR());
        $whereClause = Str::_EMPTY(); $i = 0;
        foreach ($record->primaryKey->indexes as $index) {
          $whereClause = $whereClause->append($index)->append(Str::set(' = "'))->
            append($keys[$i++])->append($and->prepend(Str::set('"')));
        }
        $whereClause = $whereClause->trimEnd($and);
        $sql = 'SELECT * FROM ' . $name . ' WHERE ' . $whereClause . ';';
        if (DEV_MODE) { \ChromePhp::log('SQL:', $sql); }
        try {
          $this->connect();
          $statementHandle = $this->databaseHandle->query($sql);
          $statementHandle->setFetchMode(\PDO::FETCH_OBJ);
          $dataObject = $statementHandle->fetch();
          if (!($dataObject instanceof \stdClass))
          {
            throw new DataFetchException('No matching Record found in data storage!');
          }
          $record = new $recordFullName($dataObject);
          $this->recordCollection->attach($record);
          $this->dataObjectCollection->attach($dataObject);
          $this->databaseHandle = \NULL;
        } catch (\PDOException $pdoException) { // @codeCoverageIgnoreStart
          $this->databaseHandle = \NULL;
          throw new DataFetchException($pdoException->getMessage(), 0, $pdoException); // @codeCoverageIgnoreEnd
        }
      }
    }
    return $record;
  }

  /**
   * Returns requested RecordCollection.
   * @param \ramp\core\Str $name Record type to be returned
   * @param \ramp\condition\Filter $filter Optional Filter critera of collection
   * @param int $fromIndex Optional index for first entry of collection
   * @return \ramp\model\business\RecordCollection Relevant requested RecordCollection
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  private function getCollection(Str $recordName, Filter $filter = null, $fromIndex = null) : RecordCollection
  {
    $recordFullName = SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    $sql = 'SELECT * FROM '. $recordName;
    if ($filter) { $sql.= ' WHERE ' . $filter(SQLEnvironment::getInstance()); }
    $limit = ($fromIndex)? $fromIndex . ', ' .($this->maxResults + $fromIndex) : '0, '.$this->maxResults;
    $sql.= ' LIMIT '. $limit . ';';
    if (DEV_MODE) { \ChromePhp::log('SQL:', $sql); }
    try {
      $this->connect();
      $statementHandle = $this->databaseHandle->query($sql);
      $statementHandle->setFetchMode(\PDO::FETCH_OBJ);
      $dataObject = $statementHandle->fetch();
      if (!($dataObject instanceof \stdClass))
      {
        throw new DataFetchException('No matching Records found in data storage!');
      }
      $collection = new RecordCollection();
      do {
        $recordFromDB = new $recordFullName($dataObject);
        $key = $recordFromDB->primaryKey->value;  
        if ($key !== NULL && $record = $this->getRecordIfCached($recordName, $key)) {
          // Empty
        } else {
          $record = $recordFromDB;
          $this->recordCollection->attach($record);
          $this->dataObjectCollection->attach($dataObject);
        }
        $collection->add($record);
      } while ($dataObject = $statementHandle->fetch());
      $this->databaseHandle = \NULL;
    } catch (\PDOException $pdoException) { // @codeCoverageIgnoreStart
      $this->databaseHandle = \NULL;
      throw new DataFetchException($pdoException->getMessage(), 0, $pdoException); // @codeCoverageIgnoreEnd
    }
    return $collection;
  }

  /**
   * Update {@see BusinessModel} to permanent data store
   * @param \ramp\model\business\BusinessModel $model BusinessModel object to be updated
   * @throws \InvalidArgumentException when {@see \ramp\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  public function update(BusinessModel $model)
  {
    if ($model instanceof \ramp\model\business\RecordCollection) {
      foreach ($model as $record) { $this->updateRecord($record); }
      return;
    }
    if ($model instanceof \ramp\model\business\field\Field) {
      $model = $model->parent;
    }
    $this->updateRecord($model);
  }

  /**
   * Returns reference for easy access to dataObject (stdClass) of provided Record.
   * @throws \InvalidArgumentException when {@see \ramp\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   */
  private function getDataObject(Record $record) : \stdClass
  {
    $data = $this->dataObjectCollection;
    $data->rewind();
    foreach ($this->recordCollection as $thisRecord) {
      if ($thisRecord === $record) {
        return $data->current();
      }
      $data->next();
    }
    throw new \InvalidArgumentException('Provided Model NOT retrieved through this BusinessModelManager');
  }

  /**
   * Update {@see Record} to permanent data store
   * @param \ramp\model\business\Record $record Record to be updated
   * @throws \InvalidArgumentException when {@see \ramp\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  private function updateRecord(Record $record)
  {
    $comma = $insertProperties = $insertPlaceholders = $and = $whereClause = '';
    foreach ($record->primaryKey->indexes as $key) {
      $insertProperties .= $comma . $key; // keyA, keyb, key3
      $insertPlaceholders .= $comma . ':' . $key; // :keyA, :keyb, :key3
      $whereClause .= $and . $key . '=:' . $key;
      $and = ' AND ';
      $comma = ', ';
    }
    $comma = $properties = $placeholders = $updateSet = '';
    $dataObject = $this->getDataObject($record);
    foreach ($dataObject as $property => $value) {
      $properties .= $comma . $property; // givenName, familyName, age 
      $placeholders .= $comma . ':' . $property; // :givenName, :familyName, :age
      $updateSet .= $comma . $property . '=:' . $property; // givenName=:givenName, familyName=:familyName, age=:age
      $comma = ', ';
    }
    $recordName = substr_replace(
      (string)$record, '', 0, strlen(SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\')
    );
    if ($record->isNew && $insertProperties != '') {
      $this->writeToDB(
        $record,
        'INSERT INTO ' . $recordName . ' (' . $insertProperties . ') VALUES (' . $insertPlaceholders . ')',
        $record->primaryKey->asArray
      );
    } 
    if ($properties != '') {
      $this->writeToDB(
        $record,
        'UPDATE ' . $recordName . ' SET ' . $updateSet . ' WHERE ' . $whereClause,
        (array)$dataObject
      );
    }
  }

  private function writeToDB(Record $record, string $preparedStatement, array $values)
  {
    if (DEV_MODE ) { 
      \ChromePhp::log('$preparedStatement:', $preparedStatement);
      \ChromePhp::log('values:', $values);
    }
    $count=0;
    do {
      try {
        if (!isset($this->databaseHandle)) { $this->connect(); }
        $statementHandle = $this->databaseHandle->prepare($preparedStatement);
        $statementHandle->execute((array)$values);
        $record->updated();
        return;
      } catch (\PDOException $pdoException) { // @codeCoverageIgnoreStart
        $count++;
        sleep($count);
        if (DEV_MODE) {
          \ChromePhp::group('Unable to write to data store '.$count);
          \ChromePhp::log('STATEMENT: '. $preparedStatement);
          \ChromePhp::log('Retryed after ' . $count . ' second(s).');
          \ChromePhp::groupEnd();
        }
      }
    } while ($count < 3);
    throw new DataWriteException($pdoException->getMessage(), 0, $pdoException); // @codeCoverageIgnoreEnd
  }

  /**
   * Ensure update of any out of sync Records.
   * Uses the following properties of {@see \ramp\model\business\Record} for varification:
   * - {@see \ramp\model\business\Record::isValid}
   * - {@see \ramp\model\business\Record::isModified}
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  public function updateAny()
  {
    foreach ($this->recordCollection as $record) {
      if ($record->isModified && $record->isValid) {
        $this->updateRecord($record);
      }
    }
    if (isset($this->databaseHandle)) { $this->databaseHandle = \NULL; }
  }

  /**
   * Prevent cloning.
   * @throws \BadMethodCallException Cloning is not allowed!
   */
  public function __clone()
  {
    throw new \BadMethodCallException('Cloning is not allowed');
  }
}
