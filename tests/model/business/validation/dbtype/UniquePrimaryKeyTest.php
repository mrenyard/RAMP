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
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\validation\dbtype;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowerCaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/UniquePrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/DataWriteException.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';

require_once 'tests/ramp/ChromePhp.class.php';
require_once 'tests/ramp/model/business/validation/dbtype/mocks/UniquePrimaryKeyTest/SimpleRecord.class.php';
require_once 'tests/ramp/model/business/validation/dbtype/mocks/UniquePrimaryKeyTest/UniquePrimaryKeyBusinessModelManager.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\model\business\FailedValidationException;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\validation\dbtype\UniquePrimaryKey;

use tests\ramp\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\SimpleRecord;
use tests\ramp\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\UniquePrimaryKeyBusinessModelManager;

/**
 * Collection of tests for \ramp\model\business\validation\dbtype\UniquePrimaryKey.
 */
class UniquePrimaryKeyTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $recordName;
  private $associatedRecord;
  private $modelManager;

  /**
   * Setup
   */
  public function setUp() : void
  {
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\UniquePrimaryKeyBusinessModelManager';
    UniquePrimaryKeyBusinessModelManager::reset();
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->recordName = Str::set('SimpleRecord');
    $this->associatedRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, Str::set('new'))
    );
    $this->testObject = SimpleRecord::$uniquePrimaryKeyTest;
  }

  /**
   * Collection of assertions for ramp\validation\dbtype\UniquePrimaryKey::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\business\validation\ValidationRule}
   * - assert is instance of {@link \ramp\model\business\validation\UniquePrimaryKey}
   * @link ramp.model.business.validation.dbtype.UniquePrimaryKey \ramp\model\business\validation\dbtype\UniquePrimaryKey
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('ramp\model\business\validation\dbtype\UniquePrimaryKey', $this->testObject);
  }

  /**
   * Collection of assertions for ramp\model\business\validation\dbtype\UniquePrimaryKey::process().
   * - assert associatedRecord includes relevant property as child
   * - assert void returned when test successful
   * - assert data store checked and space reserved
   * - assert associatedRecord has dropped relevant property as child follwing success.
   * @link ramp.model.business.validation.dbtype.UniquePrimaryKey#method_process \ramp\model\business\validation\dbtype\UniquePrimaryKey::process()
   */
  public function testTestPass()
  {
    $this->assertEquals(1, $this->associatedRecord->count);
    $this->assertNull($this->testObject->process('key2'));
    $this->assertEquals('key2', $this->associatedRecord->uniqueKey->value);
    try {
      $this->testObject->process('key2');
    } catch (FailedValidationException $expected) {
      $this->assertEquals(0, $this->associatedRecord->count);
      return;
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }

  /**
   * Collection of assertions for ramp\model\business\validation\dbtype\UniquePrimaryKey::process().
   * - assert associatedRecord includes relevant property as child
   * - assert data store checked for avalibility
   * - assert {@link \ramp\model\business\FailedValidationException} thrown when test fails
   * - assert associatedRecord has retained relevant property as child.
   * @link ramp.model.business.validation.dbtype.UniquePrimaryKey#method_process \ramp\model\business\validation\dbtype\UniquePrimaryKey::process()
   */
  public function testTestFail()
  {
    $this->assertEquals(1, $this->associatedRecord->count);
    try {
      $this->testObject->process('key1');
    } catch (FailedValidationException $expected) {
      $this->assertEquals(1, $this->associatedRecord->count);
      return;
    }
    $this->fail('An expected \ramp\model\business\FailedValidationException has NOT been raised.');
  }
}
