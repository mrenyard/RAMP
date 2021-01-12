<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\model\business;

use svelte\SETTING;
use svelte\core\Str;
use svelte\condition\Filter;
use svelte\condition\SqlEnvironment;

/**
 * Manage all models within systems business domain, uses SQL for permanat storage.
 *
 * {@inheritDoc}
 * - Cache already retrieved Records
 *
 * COLLABORATORS
 * - {@link \svelte\model\business\RecordCollection}
 * - {@link \svelte\model\business\Record}
 * - {@link \svelte\model\business\field\Field}
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
    $this->maxResults = (isset(SETTING::$DATABASE_MAX_RESULTS)) ? (int)SETTING::$DATABASE_MAX_RESULTS : 100;
    $this->recordCollection = new \SplObjectStorage();
    $this->dataObjectCollection = new \SplObjectStorage();
  }

  /**
   * Get instance - same instance on every request (singleton) within same http request.
   *
   * PRECONDITIONS
   * - Requires the following global constants to be set
   *  (depending on data storage type) (usually via svelte.ini):
   *  - \svelte\SETTING::$DATABASE_CONNECTION
   *  - \svelte\SETTING::$DATABASE_USER
   *  - \svelte\SETTING::$DATABASE_PASSWORD
   *  - \svelte\SETTING::$DATABASE_MAX_RESULTS
   *  - \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE
   * POSTCONDITIONS
   * - ensures default values set on relavant properties
   * @return \svelte\model\business\BusinessModelManager Single instance of BusinessModelManager
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
        \ChromePhp::log('Database Connection FAILED - Retryed after '.$count.'second(s)');
      }
    } while ($count < 3);
    throw $pdoException; // @codeCoverageIgnoreEnd
  }

  /**
   * Return cached record if avalible.
   */
  private function getRecordIfCached(Str $recordName, Str $recordPrimaryKey) : ?Record
  {
    $class = SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    $pkName = (string)$class::primaryKeyName();
    foreach ($this->recordCollection as $record)
    {
      if ((get_class($record) == $class) && ($record->$pkName->value == (string)$recordPrimaryKey))
      {
        return $record;
      }
    }
    return \NULL;
  }

  /**
   * Returns requested Model.
   * @param \svelte\model\business\iBusinessModelDefinition $definition Definition of requested Model
   * @param \svelte\condition\Filter $filter Optional filter to be apply to BusinessModel
   * @param int $fromIndex Optional index of first entry in a collection
   * @return \svelte\model\business\BusinessModel Relevant requested BusinessModel
   * @throws \DomainException When {@link \svelte\model\business\BusinessModel}(s) NOT found
   * @throws \svelte\model\business\DataFetchException When unable to fetch from data store
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
   * @param \svelte\core\Str $name Record type to be returned
   * @param \svelte\core\Str $key Primary key of record
   * @return \svelte\model\business\Record Relevant requested Record
   * @throws \DomainException When {@link \svelte\model\business\Record} of type with $key NOT found
   * @throws \svelte\model\business\DataFetchException When unable to fetch from data store
   */
  private function getRecord(Str $name, Str $key) : Record
  {
    $recordFullName = SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $name;
    $pkName = $recordFullName::primaryKeyName();
    if ((string)$key == 'new')
    {
      $dataObject = new \stdClass();
      $record = new $recordFullName($dataObject);
      $this->recordCollection->attach($record);
      $this->dataObjectCollection->attach($dataObject);
    }
    else
    {
      if ($record = $this->getRecordIfCached($name, $key))
      {
        // Empty
      }
      else
      {
        $sql = 'SELECT * FROM ' . $name;
        $sql.= ' WHERE ' . $name . '.' . $pkName . ' = "' . $key . '"' . ';';
        \ChromePhp::log('SQL:', $sql);
        try {
          $this->connect();
          $statementHandle = $this->databaseHandle->query($sql);
          $statementHandle->setFetchMode(\PDO::FETCH_OBJ);
          $dataObject = $statementHandle->fetch();
          if (!($dataObject instanceof \stdClass))
          {
            throw new \DomainException('No matching Record found in data storage!');
          }
          $record = new $recordFullName($dataObject);
          $this->recordCollection->attach($record);
          $this->dataObjectCollection->attach($dataObject);
          $this->databaseHandle = \NULL;
        } catch (\PDOException $pdoException) { // @codeCoverageIgnoreStart
          $this->databaseHandle = \NULL;
          throw new DataFetchException($pdoException->getMessage()); // @codeCoverageIgnoreEnd
        }
      }
    }
    return $record;
  }

  /**
   * Returns requested RecordCollection.
   * @param \svelte\core\Str $name Record type to be returned
   * @param \svelte\condition\Filter $filter Optional Filter critera of collection
   * @param int $fromIndex Optional index for first entry of collection
   * @return \svelte\model\business\RecordCollection Relevant requested RecordCollection
   * @throws \DomainException When {@link \svelte\model\business\RecordCollection} of type NOT found
   * @throws \svelte\model\business\DataFetchException When unable to fetch from data store
   */
  private function getCollection(Str $recordName, Filter $filter = null, $fromIndex = null) : RecordCollection
  {
    $classFullName = SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName->append(Str::set('Collection'));
    $recordFullName = SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $recordName;
    $pkName = $recordFullName::primaryKeyName();
    $sql = 'SELECT * FROM '. $recordName;
    if ($filter) { $sql.= ' WHERE ' . $filter(SQLEnvironment::getInstance()); }
    $limit = ($fromIndex)? $fromIndex . ', ' .($this->maxResults + $fromIndex) : '0, '.$this->maxResults;
    $sql.= ' LIMIT '. $limit . ';';
    \ChromePhp::log('SQL:', $sql);
    try {
      $this->connect();
      $statementHandle = $this->databaseHandle->query($sql);
      $statementHandle->setFetchMode(\PDO::FETCH_OBJ);
      $dataObject = $statementHandle->fetch();
      if (!($dataObject instanceof \stdClass))
      {
        throw new \DomainException('No matching Records found in data storage!');
      }
      $collection = new $classFullName();
      do {
        if ($record = $this->getRecordIfCached($recordName, Str::set($dataObject->$pkName)))
        {
          // Empty
        }
        else
        {
          $record = new $recordFullName($dataObject);
          $this->recordCollection->attach($record);
          $this->dataObjectCollection->attach($dataObject);
        }
        $collection->add($record);
      } while ($dataObject = $statementHandle->fetch());
      $this->databaseHandle = \NULL;
    } catch (\PDOException $pdoException) { // @codeCoverageIgnoreStart
      $this->databaseHandle = \NULL;
      throw new DataFetchException($pdoException->getMessage()); // @codeCoverageIgnoreEnd
    }
    return $collection;
  }

  /**
   * Update {@link BusinessModel} to permanent data store
   * @param \svelte\model\business\BusinessModel $model BusinessModel object to be updated
   * @throws \InvalidArgumentException when {@link \svelte\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \svelte\model\business\DataWriteException When unable to write to data store
   */
  public function update(BusinessModel $model)
  {
    if ($model instanceof \svelte\model\business\RecordCollection) {
      foreach ($model as $record) {
        $this->updateRecord($record);
      }
      return;
    }
    elseif ($model instanceof \svelte\model\business\field\Field)
    {
      $model = $model->containingRecord;
    }
    $this->updateRecord($model);
  }

  /**
   * Returns reference for easy access to dataObject (stdClass) of provided Record.
   * @throws \InvalidArgumentException when {@link \svelte\model\business\BusinessModel}
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
   * Update {@link Record} to permanent data store
   * @param \svelte\model\business\Record $record Record to be updated
   * @throws \InvalidArgumentException when {@link \svelte\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \svelte\model\business\DataWriteException When unable to write to data store
   */
  private function updateRecord(Record $record)
  {
    $dataObject = $this->getDataObject($record);
    $empty = Str::_EMPTY(); $comma = Str::set(', ');
    $colon = Str::set(':'); $eqColon = Str::set('=:');
    $properties = $empty; $placeholders = $empty; $updateSet = $empty;
    foreach ($dataObject as $propertyName => $value)
    {
      $propertyName = Str::set($propertyName);
      $properties = $properties->append($propertyName)->append($comma);
      $placeholders = $placeholders->append($colon)->append($propertyName)->append($comma);
      $updateSet = $updateSet->append($propertyName)->append($eqColon)->
        append($propertyName)->append($comma);
    }
    $properties = $properties->trimEnd($comma);
    $placeholders = $placeholders->trimEnd($comma);
    $updateSet = $updateSet->trimEnd($comma);
    if ($properties === $empty) { return; }
    $recordName = substr_replace(
      (string)$record, '', 0, strlen(SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\')
    );
    $preparedStatement = ($record->isNew)?
      'INSERT INTO '.$recordName.' ('.$properties.') VALUES ('.$placeholders.')':
      'UPDATE '.$recordName.' SET '.$updateSet.' WHERE '.$record->primaryKeyName().'=:'.$record->primaryKeyName();
    \ChromePhp::log('$preparedStatement:', $preparedStatement);
    \ChromePhp::log('values:', (array)$dataObject);
    $count=0;
    do {
      try {
        if (!isset($this->databaseHandle)) { $this->connect(); }
        $statementHandle = $this->databaseHandle->prepare($preparedStatement);
        $statementHandle->execute((array)$dataObject);
        $record->updated();
        return;
      } catch (\PDOException $pdoException) { // @codeCoverageIgnoreStart
        $count++;
        sleep($count);
        if (defined('DEV_MODE') && DEV_MODE) {
          \ChromePhp::group('Unable to write to data store '.$count);
          \ChromePhp::log('STATEMENT: '. $preparedStatement);
          \ChromePhp::log('Retryed after ' . $count . 'second(s).');
          \ChromePhp::groupEnd();
        }
      }
    } while ($count < 3);
    throw new DataWriteException($pdoException->getMessage()); // @codeCoverageIgnoreEnd
  }

  /**
   * Ensure update of any out of sync Records.
   * Uses the following properties of {@link \svelte\model\business\Record} for varification:
   * - {@link \svelte\model\business\Record::isValid}
   * - {@link \svelte\model\business\Record::isModified}
   * @throws \svelte\model\business\DataWriteException When unable to write to data store
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
