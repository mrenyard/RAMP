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
namespace tests\ramp\model\business\field\mocks\RelationTest;

use ramp\SETTING;
use ramp\core\Str;
use ramp\model\business\BusinessModel;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\iBusinessModelDefinition;
use ramp\condition\Filter;

use tests\ramp\model\business\field\mocks\RelationTest\MockRecord;

/**
 * Mock business model managers for testing \ramp\http\Session
 * .
 */
class MockBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  public static $updateLog;
  public static $relatedObjectOne;
  public static $relatedObjectTwo;

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
   */
  public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel
  {
    if ($definition->recordName == 'MockRecord')
    {
      if ((string)$definition->recordKey == '1')
      {
        if (!isset(self::$relatedObjectOne)) {
          $relatedDataObjectOne = new \stdClass();
          $relatedDataObjectOne->key = 1;
          $relatedDataObjectOne->property = 'A Value';
          self::$relatedObjectOne = new MockRecord($relatedDataObjectOne);
        }
        return self::$relatedObjectOne;
      }
      elseif ((string)$definition->recordKey == '2')
      {
        if (!isset(self::$relatedObjectTwo)) {
          $relatedDataObjectTwo = new \stdClass();
          $relatedDataObjectTwo->key = 2;
          $relatedDataObjectTwo->property = 'B Value';
          self::$relatedObjectTwo = new MockRecord($relatedDataObjectTwo);
        }
        return self::$relatedObjectTwo;
      }
    }
    throw new \DomainException('No matching Record(s) found in data storage!');
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
    if (isset(self::$relatedObjectTwo) && self::$relatedObjectTwo->isModified && self::$relatedObjectTwo->isValid) { $this->update(self::$relatedObjectTwo); }
  }
}