<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/model/business/FailedValidationException.class.php';
require_once '/usr/share/php/svelte/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/svelte/model/business/validation/dbtype/UniquePrimaryKey.class.php';

require_once 'tests/svelte/model/business/validation/dbtype/mocks/UniquePrimaryKeyTest/SimpleRecord.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\model\business\FailedValidationException;
use svelte\model\business\SimpleBusinessModelDefinition;
use svelte\model\business\validation\dbtype\UniquePrimaryKey;

use tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest\SimpleRecord;

/**
 * Collection of tests for \svelte\model\business\validation\dbtype\UniquePrimaryKey.
 */
class UniquePrimaryKeyTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $recordName;
  private $associatedRecord;

  /**
   * Setup
   */
  public function setUp()
  {
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest';
    SETTING::$SVELTE_BUSINESS_MODEL_MANAGER = 'svelte\model\business\SQLBusinessModelManager';
    SETTING::$DATABASE_CONNECTION = 'sqlite:/usr/share/php/' . str_replace('\\', '/', SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE) . '/database.db';
    $DIR = '/usr/share/php/tests/svelte/model/business/mocks/SQLBusinessModelManagerTest';
    copy($DIR . '/database_copy.db', $DIR . '/database.db') or die("Unable to copy database.");
    $MODEL_MANAGER = SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
    $modelManager = $MODEL_MANAGER::getInstance();
    $this->recordName = Str::set('SimpleRecord');
    $this->associatedRecord = $modelManager->getBusinessModel(
      new SimpleBusinessModelDefinition($this->recordName, Str::set('new'))
    );
    $this->testObject = SimpleRecord::$uniquePrimaryKeyTest;
    \ChromePhp::clear();
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
    $expectedLog1 = 'LOG:$preparedStatement: INSERT INTO ' . $this->recordName .
      ' (uniqueKey) VALUES (:uniqueKey)';
    $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
    $expectedLog2 = 'LOG:values: key2';
    $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
    $this->assertEquals(0, $this->associatedRecord->count);
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
      $expectedLog1 = 'LOG:$preparedStatement: INSERT INTO ' . $this->recordName .
        ' (uniqueKey) VALUES (:uniqueKey)';
      $this->assertSame($expectedLog1, (string)\ChromePhp::getMessages()[0]);
      $expectedLog2 = 'LOG:values: key1';
      $this->assertSame($expectedLog2, (string)\ChromePhp::getMessages()[1]);
      $this->assertEquals(1, $this->associatedRecord->count);
      return;
    }
    $this->fail('An expected \svelte\model\business\FailedValidationException has NOT been raised.');
  }
}
