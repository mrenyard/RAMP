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
 * @package RAMP
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\key\mocks\KeyTest;

use ramp\SETTING;
use ramp\core\Str;
use ramp\model\business\BusinessModel;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\DataFetchException;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

use tests\ramp\model\business\key\mocks\KeyTest\MockRecord;

/**
 * Mock business model managers for testing \ramp\http\Session
 * .
 */
class MockBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  public static $callCount;
  public static $updateLog;
  public static $relatedObjectOne;
  public static $relatedDataObjectOne;

  /**
   * Constuct the instance.
   */
  private function __construct()
  {
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
      self::$callCount = 0;
      self::$instance = new MockBusinessModelManager();
    }
    return self::$instance;
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
    self::$callCount++;
    if ($definition->recordName == 'MockRecord')
    {
      if ($filter(SQLEnvironment::getInstance()) == 'MockRecord.aProperty = "1" AND MockRecord.bProperty = "2" AND MockRecord.cProperty = "3"') {
        if (!isset(self::$relatedObjectOne)) {
          self::$relatedDataObjectOne = new \stdClass();
          self::$relatedDataObjectOne->aProperty = 1;
          self::$relatedDataObjectOne->bProperty = 2;
          self::$relatedDataObjectOne->cProperty = 3;
          self::$relatedObjectOne = new MockRecord(self::$relatedDataObjectOne);
        }
        return self::$relatedObjectOne;
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
    self::$updateLog[get_class($model) . ':' . $model->primaryKey->value] = 'updated ' . date('H:i:s');
    $model->updated();
  }

  /**
   * Ensure update of any out of sync Models with any permanent data store.
   */
  public function updateAny()
  {
    if (isset(self::$relatedObjectOne) && self::$relatedObjectOne->isModified && self::$relatedObjectOne->isValid) { $this->update(self::$relatedObjectOne); }
  }
}
