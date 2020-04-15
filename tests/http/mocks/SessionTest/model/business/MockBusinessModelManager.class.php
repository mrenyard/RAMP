<?php
/**
 * Svelte - Rapid web application development using best practice.
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

/**
 * Mock business model managers for testing \svelte\http\Session
 * .
 */
class MockBusinessModelManager extends BusinessModelManager
{
  private static $instance;
  private static $loginAccount;
  private static $authenticatableUnit;

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
    if ($definition->getRecordName() == 'LoginAccount')
    {
      if ($definition->getRecordKey() == 'new')
      {
        return new LoginAccount();
      }
      else if (($definition->getRecordKey() == null) && (isset($filter)))
      {
        $collection = new LoginAccountCollection();
        if ($filter(SQLEnvironment::getInstance()) == 'LoginAccount.email = "a.person@domain.com"')
        {
          // collection of one valid LoginAccount
          $dataObject = new \stdClass();
          $dataObject->id = 'login-account:aperson';
          $dataObject->email = 'a.person@domain.com';
          $dataObject->encryptedPassword = crypt('P@assw0rd!', SETTING::$SECURITY_PASSWORD_SALT);
          $dataObject->typeID = 4;
          $dataObject->auPK = 'aperson';
          $collection->add(new LoginAccount($dataObject));
        }
        return $collection;
      }
      else if ($definition->getRecordKey() == 'aperson')
      {
        // valid LoginAccount
        $dataObject = new \stdClass();
        $dataObject->id = 'login-account:aperson';
        $dataObject->email = 'a.person@domain.com';
        $dataObject->encryptedPassword = crypt('P@assw0rd!', SETTING::$SECURITY_PASSWORD_SALT);
        $dataObject->typeID = 4;
        $dataObject->auPK = 'aperson';
        return new LoginAccount($dataObject);
      }
    }
    if ($definition->getRecordName() == 'Person')
    {
      if ($definition->getRecordKey() == 'new')
      {
        // new Person
        return new Person();
      }
      else if ($definition->getRecordKey() == 'aperson')
      {
        // valid Person
        $dataObject = new \stdClass();
        $dataObject->id = 'person:aperson';
        $dataObject->email = 'a.person@domain.com';
        return new Person($dataObject);
      }
    }
    throw new \DomainException('No matching Record(s) found in data storage!');
  }

  /**
   * Update {@link Model} to any permanent data store
   *
   * @param BusinessModel Object to be updated
   * @throws \InvalidArgumentException when {@link \svelte\model\business\BusinessModel}
   *  was not initially retrieved using this BusinessModelManager
   */
  public function update(BusinessModel $model)
  {
  }

  /**
   * Ensure update of any out of sync Models with any permanent data store.
   */
  public function updateAny()
  {
  }
}
