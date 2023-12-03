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
namespace tests\ramp\model\business;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/SQLBusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/DataWriteException.class.php';

require_once '/usr/share/php/tests/ramp/ChromePhp.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\DataFetchException;
use ramp\model\business\DataWriteException;
use ramp\model\business\SimpleBusinessModelDefinition;

/**
 * Specialist testing of bad PDO connection in \ramp\model\business\SQLBusinessModelManager.
 * Run independatly as special case due to intentianal performace lag and the use of constants
 */
class BadPDOConnectionTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Failed PDO connection simulation.
   * - assert unsuccessful re-attempt 2 other times at gradually prolonged intervals.
   * - assert reports detail of failed attemps.
   * @see \ramp\BusinessModelManager::getBusinessModel()
   */
  public function testBadConnection() : void
  {
    \ChromePhp::clear();
    define('DEV_MODE', \TRUE);
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'ramp\model\business\SQLBusinessModelManager';
    SETTING::$DATABASE_CONNECTION = 'mysql:host=localhost;dbname=notDB';
    SETTING::$DATABASE_PASSWORD = 'notpassword';
    SETTING::$DATABASE_USER = 'notroot';
    SETTING::$DATABASE_MAX_RESULTS = 4;
    $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $testObject = $MODEL_MANAGER::getInstance();
    $record = $testObject->getBusinessModel(
      new SimpleBusinessModelDefinition(Str::set('MockMinRecord'), Str::NEW())
    );
    $record->validate(PostData::build(array(
      'mock-min-record:new:key-1' => 'B',
      'mock-min-record:new:key-2' => 'B',
      'mock-min-record:new:key-3' => 'C'
    )));
    try {
      $testObject->update($record);
    } catch (DataWriteException $expected) {
      $i = 0; $j = 0;
      foreach (\ChromePhp::getMessages() as $message)
      {
        $i++;
        if ($i === 1) {
          $this->AssertSame( // Query to check uniqness of primary key. 
            'LOG:SQL: SELECT * FROM MockMinRecord WHERE MockMinRecord.key1 = "B" AND MockMinRecord.key2 = "B" AND MockMinRecord.key3 = "C" LIMIT 0, 4;',
            $message
          );
          continue;
        }
        if ($i === 2) {
          $this->AssertSame( // PreparedStatement ready for update to datastore.
            'LOG:$preparedStatement: INSERT INTO MockMinRecord (key1, key2, key3) VALUES (:key1, :key2, :key3)',
            $message
          );
          continue;
        }
        if ($i === 3) { // Values for update to datastore.
          $this->AssertSame('LOG:values: B, B, C', $message);
          continue;
        }
        if ($i === 4 || $i === 8 || $i == 12) { // Start of GROUP Unable to write to data store
          $this->AssertSame('GROUP:Unable to write to data store ' . ++$j, $message);
          continue;
        }
        if ($i === 5 || $i === 9 || $i == 13) { // LOG:STATEMENT: INSERT
            $this->AssertSame('LOG:STATEMENT: INSERT INTO MockMinRecord (key1, key2, key3) VALUES (:key1, :key2, :key3)',
            $message
          );
          continue;
        }
        if ($i === 6 || $i === 10 || $i == 14) { // LOG:Retryed delay
          $this->AssertSame('LOG:Retryed after ' . $j . ' second(s).', $message);
          continue;
        }
        if ($i === 7 || $i === 11 || $i == 15) {
          $this->AssertSame('GROUPEND:', $message);
          continue;
        }
      }
      $this->assertEquals(3, $j);
      \ChromePhp::clear();
      return;
    }
    $this->fail('An expected \PDOException has NOT been raised.');
  }
}
