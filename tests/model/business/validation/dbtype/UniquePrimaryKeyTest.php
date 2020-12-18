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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\validation\dbtype;

require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/UniquePrimaryKey.class.php';
require_once '/usr/share/php/svelte/model/business/DataWriteException.class.php';

require_once 'tests/svelte/ChromePhp.class.php';
require_once 'tests/svelte/model/business/validation/dbtype/mocks/UniquePrimaryKeyTest/SimpleRecord.class.php';
require_once 'tests/svelte/model/business/validation/dbtype/mocks/UniquePrimaryKeyTest/UniquePrimaryKeyBusinessModelManager.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\model\business\FailedValidationException;
use svelte\model\business\SimpleBusinessModelDefinition;
use svelte\model\business\validation\dbtype\UniquePrimaryKey;

use tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\SimpleRecord;
use tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\UniquePrimaryKeyBusinessModelManager;

/**
 * Collection of tests for \svelte\model\business\validation\dbtype\UniquePrimaryKey.
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
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest';
    SETTING::$SVELTE_BUSINESS_MODEL_MANAGER = 'tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\UniquePrimaryKeyBusinessModelManager';
    UniquePrimaryKeyBusinessModelManager::reset();
    $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
    $this->modelManager = $MODEL_MANAGER::getInstance();
    $this->recordName = Str::set('SimpleRecord');
    $this->associatedRecord = $this->modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, Str::set('new'))
    );
    $this->testObject = SimpleRecord::$uniquePrimaryKeyTest;
  }

  /**
   * Collection of assertions for svelte\validation\dbtype\UniquePrimaryKey::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\business\validation\ValidationRule}
   * - assert is instance of {@link \svelte\model\business\validation\UniquePrimaryKey}
   * @link svelte.model.business.validation.dbtype.UniquePrimaryKey \svelte\model\business\validation\dbtype\UniquePrimaryKey
   */
  public function test__Construct()
  {
    $this->assertInstanceOf('svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\ValidationRule', $this->testObject);
    $this->assertInstanceOf('svelte\model\business\validation\dbtype\UniquePrimaryKey', $this->testObject);
  }

  /**
   * Collection of assertions for svelte\model\business\validation\dbtype\UniquePrimaryKey::process().
   * - assert associatedRecord includes relevant property as child
   * - assert void returned when test successful
   * - assert data store checked and space reserved
   * - assert associatedRecord has dropped relevant property as child follwing success.
   * @link svelte.model.business.validation.dbtype.UniquePrimaryKey#method_process \svelte\model\business\validation\dbtype\UniquePrimaryKey::process()
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
    $this->fail('An expected \svelte\model\business\FailedValidationException has NOT been raised.');
  }

  /**
   * Collection of assertions for svelte\model\business\validation\dbtype\UniquePrimaryKey::process().
   * - assert associatedRecord includes relevant property as child
   * - assert data store checked for avalibility
   * - assert {@link \svelte\model\business\FailedValidationException} thrown when test fails
   * - assert associatedRecord has retained relevant property as child.
   * @link svelte.model.business.validation.dbtype.UniquePrimaryKey#method_process \svelte\model\business\validation\dbtype\UniquePrimaryKey::process()
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
    $this->fail('An expected \svelte\model\business\FailedValidationException has NOT been raised.');
  }
}
