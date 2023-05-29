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
namespace tests\ramp\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest;

use ramp\SETTING;
use ramp\core\RAMPObject;
use ramp\model\business\BusinessModel;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\DataWriteException;
use ramp\condition\Filter;

/**
 * Defined abstract for business model managers that manage all models within systems business domain.
 *
 * RESPONSIBILITIES
 * - Create and manage business models
 * - Act as intermediary to any permanent data store
 * - Ensure only one version of any data row in system
 */
final class UniquePrimaryKeyBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  private $maxResults;
  private $permanentDataStore;
  private $recordCollection;
  private $dataObjectCollection;

  public static function reset()
  {
    self::$instance = NULL;
  }

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
    $this->maxResults = (isset(SETTING::$DATABASE_MAX_RESULTS)) ? (int)SETTING::$DATABASE_MAX_RESULTS : 100;
    $this->permanentDataStore = new \SplObjectStorage();
    $this->recordCollection = new \SplObjectStorage();
    $this->dataObjectCollection = new \SplObjectStorage();

    $stdClass = new \stdClass();
    $stdClass->uniqueKey = 'key1';
    $stdClass->name = 'smith';
    $this->permanentDataStore->attach($stdClass);
  }

  /**
   * Get instance - same instance on every request (singleton) within same http request.
   */
  public static function getInstance() : BusinessModelManager
  {
    if (!isset(self::$instance))
    {
      self::$instance = new UniquePrimaryKeyBusinessModelManager();
    }
    return self::$instance;
  }

  /**
   * Returns requested Model.
   * @param \ramp\model\business\iBusinessModelDefinition $definition Definition of requested Model
   * @param \ramp\condition\Filter $filter Optional Filter to be apply to BusinessModel
   * @param int $fromIndex Optional index of first entry in a collection
   * @return \ramp\model\business\BusinessModel Relevant requested BusinessModel
   * @throws \DomainException When {@link \ramp\model\business\BusinessModel}(s) NOT found
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel
  {
    if ((string)$definition->recordName == 'SimpleRecord')
    {
      if ((string)$definition->recordKey == 'new')
      {
        $stdClass = new \stdClass();
        $record = new SimpleRecord($stdClass);
        $this->dataObjectCollection->attach($stdClass);
        $this->recordCollection->attach($record);
        return $record;
      } else {
        foreach ($this->recordCollection as $record) {
          if ((string)$record->uniqueKey == (string)$definition->recordKey) {
            return $record;
          }
        }
        foreach ($this->permanentDataStore as $stdClass) {
          if ((string)$stdClass->uniqueKey == (string)$definition->recordKey) {
            $record = new SimpleRecord($stdClass);
            $this->dataObjectCollection->attach($stdClass);
            $this->recordCollection->attach($record);
            return $record;
          }
        }
      }
      throw new DataFetchException();
    }
    throw new \DomainException();
  }

  /**
   * Update {@link BusinessModel} to any permanent data store
   * @param \ramp\model\business\BusinessModel $model BusinessModel object to be updated
   * @throws \InvalidArgumentException when {@link \ramp\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  public function update(BusinessModel $model)
  {
    if (!$this->permanentDataStore->contains($model))
    {
      foreach ($this->permanentDataStore as $row)
      {
        if ((string)$row->uniqueKey == (string)$model->uniqueKey->value)
        {
          throw new DataWriteException();
        }
      }

      $data = $this->dataObjectCollection;
      $data->rewind();
      foreach ($this->recordCollection as $thisRecord)
      {
        if ($thisRecord === $model)
        {
          $this->permanentDataStore->attach($data->current());
          return;
        }
        $data->next();
      }
    }
  }

  /**
   * Ensure update of any out of sync Records with any permanent data store.
   * Uses the following properties of {@link \ramp\model\business\Record} for varification:
   * - {@link \ramp\model\business\Record::isValid}
   * - {@link \ramp\model\business\Record::isModified}
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  public function updateAny()
  {
    throw new \BadMethodCallException('METHOD NOT IMPLEMENTED!');
  }
}
