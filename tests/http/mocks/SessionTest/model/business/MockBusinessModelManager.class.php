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
namespace tests\ramp\http\mocks\SessionTest\model\business;

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\model\business\BusinessModel;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\LoginAccount;
use ramp\model\business\LoginAccountCollection;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

use tests\ramp\http\SessionTest;
use ramp\model\business\AnAuthenticatableUnit;
use ramp\model\business\AnAuthenticatableUnitCollection;

/**
 * Mock business model managers for testing \ramp\http\Session
 * .
 */
class MockBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  public static $updateLog;
  public static $loginAccountDataObject;
  public static $anAuthenticatableUnit;

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
    if ($definition->recordName == 'LoginAccount')
    {
      if ($definition->recordKey == 'new')
      {
        self::$loginAccountDataObject = new \stdClass();
        self::$loginAccountDataObject->auPK = null;
        self::$loginAccountDataObject->email = null;
        self::$loginAccountDataObject->encryptedPassword = null;
        self::$loginAccountDataObject->accountType = null;
        return new LoginAccount(self::$loginAccountDataObject);
      }
      elseif (($definition->recordKey == null) && (isset($filter)))
      {
        $collection = new LoginAccountCollection();
        if ($filter(SQLEnvironment::getInstance()) == 'LoginAccount.email = "a.person@domain.com"')
        {
          // collection of one valid LoginAccount
          $dataObject = new \stdClass();
          $dataObject->auPK = 'aperson';
          $dataObject->email = SessionTest::$sessionLoginAccountEmail;
          $dataObject->encryptedPassword = crypt(SessionTest::$unencryptedPassword, SETTING::$SECURITY_PASSWORD_SALT);
          $dataObject->accountType = 4;
          $collection->add(new LoginAccount($dataObject));
        }
        return $collection;
      }
      elseif ($definition->recordKey == 'aperson')
      {
        // valid LoginAccount
        $dataObject = new \stdClass();
        $dataObject->auPK = 'aperson';
        $dataObject->email = SessionTest::$sessionLoginAccountEmail;
        $dataObject->encryptedPassword = crypt(SessionTest::$unencryptedPassword, SETTING::$SECURITY_PASSWORD_SALT);
        $dataObject->accountType = 4;
        return new LoginAccount($dataObject);
      }
    }
    if ((string)$definition->recordName == 'AnAuthenticatableUnit')
    {
      if ((string)$definition->recordKey == 'new')
      {
        // new
        self::$anAuthenticatableUnit = new AnAuthenticatableUnit(new \stdClass());
        return self::$anAuthenticatableUnit;
      }
      if ($definition->recordKey == 'aperson')
      {
        $dataObject = new \stdClass();
        $dataObject->uname = 'aperson';
        $dataObject->email = 'aperson@domain.com';
        $dataObject->givenName = 'Ann';
        $dataObject->familyName = 'Person';
        return new AnAuthenticatableUnit($dataObject);
      }
      if (isset($filter) && $filter[0](SQLEnvironment::getInstance()) == 'AnAuthenticatableUnit.email = "existing.person@domain.com"') {
        $dataObject = new \stdClass();
        $o = new AnAuthenticatableUnit($dataObject);
        $collection = new AnAuthenticatableUnitCollection();
        $dataObject->uname = 'existing';
        $dataObject->email = 'existing.person@domain.com';
        $dataObject->givenName = 'Exist';
        $dataObject->familyName = 'Person';
        $collection->add($o);
        return $collection;
      }
      if ($definition->recordKey == 'existing')
      {
        $dataObject = new \stdClass();
        $dataObject->uname = 'existing';
        $dataObject->email = 'existing.person@domain.com';
        $dataObject->givenName = 'Exist';
        $dataObject->familyName = 'Person';
        return new AnAuthenticatableUnit($dataObject);
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
    // STUB
  }
}
