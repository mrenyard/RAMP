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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\model\business;

use svelte\SETTING;
use svelte\core\Str;
use svelte\core\Collection;
use svelte\model\business\BusinessModel;
use svelte\model\business\BusinessModelManager;
use svelte\model\business\iBusinessModelDefinition;
use svelte\model\business\LoginAccount;
use svelte\condition\Filter;
use svelte\condition\SQLEnvironment;

use tests\svelte\http\SessionTest;

/**
 * Mock business model managers for testing \svelte\http\Session
 * .
 */
class MockBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  public static $updateLog;
  public static $loginAccountDataObject;

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
   *  (depending on data storage type) (usually via svelte.ini):
   *  - \svelte\SETTING::$DATABASE_CONNECTION
   *  - \svelte\SETTING::$DATABASE_USER
   *  - \svelte\SETTING::$DATABASE_PASSWORD
   *  - \svelte\SETTING::$DATABASE_MAX_RESULTS
   *  - \svelte\SETTING::$SVELTE_MODEL_NAMESPACE
   * POSTCONDITIONS
   * - ensures default values set on relavant properties
   * @return \svelte\model\business\BusinessModelManager Single instance of BusinessModelManager
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
   * @param \svelte\model\iModelDefinition $definition  Definition of requested Model
   * @param \svelte\condition\Filter $filter Optional Filter to be apply to Model
   * @param int $fromIndex Optional index for first entry in a collection
   * @return \svelte\model\Model Relevant requested Model object
   * @throws \DomainException when {@link \svelte\model\Model}(s) NOT found
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
        self::$loginAccountDataObject->typeID = null;
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
          $dataObject->typeID = 4;
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
    if ($definition->recordName == 'AnAuthenticatableUnit')
    {
      if ($definition->recordKey == 'new')
      {
        // new
        $dataObject = new \stdClass();
        return new AnAuthenticatableUnit($dataObject);
      }
      elseif ($definition->recordKey == 'aperson')
      {
        // valid
        $dataObject = new \stdClass();
        $dataObject->uname = 'aperson';
        $dataObject->email = SessionTest::$sessionLoginAccountEmail;
        $dataObject->givenName = 'ann';
        $dataObject->familyName = 'person';
        return new AnAuthenticatableUnit($dataObject);
      }
    }
    throw new \DomainException('No matching Record(s) found in data storage!');
  }

  /**
   * Update {@link Model} to any permanent data store.
   * @param BusinessModel Object to be updated
   * @throws \InvalidArgumentException when {@link \svelte\model\business\BusinessModel}
   *  was not initially retrieved using this BusinessModelManager
   */
  public function update(BusinessModel $model)
  {
    if (!isset(self::$updateLog)) { self::$updateLog = array(); }
    self::$updateLog[get_class($model) . ':' . $model->primaryKey] = 'updated ' . date('H:i:s');
    $model->updated();
  }

  /**
   * Ensure update of any out of sync Models with any permanent data store.
   */
  public function updateAny()
  {
  }
}
