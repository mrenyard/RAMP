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
namespace ramp\model\business;

use ramp\SETTING;
use ramp\core\Str;
use ramp\model\business\RelationLookup;
use ramp\model\business\BusinessModel;
use ramp\model\business\BusinessModelManager;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\RecordCollection;
use ramp\model\business\DataFetchException;
use ramp\model\business\LoginAccount;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

use tests\ramp\http\SessionTest;
use ramp\model\business\AnAuthenticatableUnit;

/**
 * Mock business model managers for testing \ramp\http\Session
 */
class HttpBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  public $callCount;
  public $updateLog;
  public $newLogin;
  public $existingLogin;
  public $anAuthenticatableUnit;
  public $newLoginData;
  public $existingLoginData;
  public $anAuthenticatableUnitData;

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
      self::$instance = new HttpBusinessModelManager();
    }
    return self::$instance;
  }

  public static function reset()
  {
    $o = self::getInstance();
    $o->existingLogin = NULL;
    $o->anAuthenticatableUnit = NULL;
    self::$instance = NULL;
  }

  private function buildExistingPerson()
  {
    $this->anAuthenticatableUnitData = new \stdClass();
    $this->anAuthenticatableUnitData->uname = 'existing';
    $this->anAuthenticatableUnitData->email = 'existing.person@domain.com';
    $this->anAuthenticatableUnitData->familyName = 'Person';
    $this->anAuthenticatableUnitData->givenName = 'Exist';
    $this->anAuthenticatableUnit = new AnAuthenticatableUnit($this->anAuthenticatableUnitData);
  }
  private function buildExistingLoginAccount()
  {
    $this->existingLoginData = new \stdClass();
    $this->existingLoginData->auPK = 'existing';
    $this->existingLoginData->email = SessionTest::$sessionLoginAccountEmail;
    $this->existingLoginData->encryptedPassword = crypt(SessionTest::$unencryptedPassword, SETTING::$SECURITY_PASSWORD_SALT);
    $this->existingLoginData->loginAccountType = LoginAccountType::ADMINISTRATOR;
    $this->existingLogin = new LoginAccount($this->existingLoginData);
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
    if ((string)$definition->recordName == 'AnAuthenticatableUnit')
    {
      if ((string)$definition->recordKey == 'new') {
        // $this->newLoginData = new \stdClass();
        // $this->newLogin = new AnAuthenticatableUnit($this->newLoginData);
        // return $this->newLogin;
        return new AnAuthenticatableUnit($this->newLoginData);
      }
      if ((string)$definition->recordKey == 'existing') {
        if (!isset($this->anAuthenticatableUnit)) { $this->buildExistingPerson(); }
        return $this->anAuthenticatableUnit;
      }
      if (isset($filter) && $filter(SQLEnvironment::getInstance()) == 'AnAuthenticatableUnit.email = "existing.person@domain.com"') {
        if (!isset($this->anAuthenticatableUnit)) { $this->buildExistingPerson(); }
        return $this->anAuthenticatableUnit;
      }
      throw new DataFetchException('No matching Record(s) found in data storage!');
    }
    if ($definition->recordName == 'LoginAccount')
    {
      if ($definition->recordKey == 'new') {
        $this->newLoginData = new \stdClass();
        $this->newLogin = new LoginAccount($this->newLoginData);
        return $this->newLogin;
      }
      if ($definition->recordKey == 'existing') {
        if (!isset($this->existingLogin)) { $this->buildExistingLoginAccount(); }
        return $this->existingLogin;
      }
      elseif (($definition->recordKey == NULL) && (isset($filter)))
      {
        $collection = new RecordCollection();
        if ($filter(SQLEnvironment::getInstance()) == 'LoginAccount.email = "existing.person@domain.com"')
        {
          if (!isset($this->existingLogin)) { $this->buildExistingLoginAccount(); }
          $collection = new RecordCollection();
          $collection->add($this->existingLogin);
          return $collection;
        }
        return $collection;
      }
      throw new DataFetchException('No matching Record(s) found in data storage!');
    }
    throw new \DomainException('Business Model(s) NOT found!');
  }

  /**
   * Update {@see Model} to any permanent data store.
   * @param BusinessModel Object to be updated
   * @throws \InvalidArgumentException when {@see \ramp\model\business\BusinessModel}
   *  was not initially retrieved using this BusinessModelManager
   */
  public function update(BusinessModel $model) : void
  {
    $this->updateLog[get_class($model) . ':' . $model->primaryKey->value] = 'updated ' . date('H:i:s');
    $model->updated();
  }

  /**
   * Ensure update of any out of sync Models with any permanent data store.
   */
  public function updateAny() : void
  {
    if (isset($this->existingLogin) && $this->existingLogin->isModified && $this->existingLogin->isValid) { $this->update($this->existingLogin); }
  }
}
