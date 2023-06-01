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
namespace tests\ramp\model\business\key;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/key/Key.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/key/Foreign.class.php';
require_once '/usr/share/php/ramp/model/business/key/Primary.class.php';

require_once '/usr/share/php/tests/ramp/model/business/key/mocks/ForeignTest/MockField.class.php';
require_once '/usr/share/php/tests/ramp/model/business/key/mocks/ForeignTest/ToRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/key/mocks/ForeignTest/FromRecord.class.php';

use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\model\business\key\Foreign;

use tests\ramp\model\business\key\mocks\ForeignTest\MockField;
use tests\ramp\model\business\key\mocks\ForeignTest\ToRecord;
use tests\ramp\model\business\key\mocks\ForeignTest\FromRecord;

/**
 * Collection of tests for \ramp\model\business\ForeignKey.
 */
class ForeignTest extends \PHPUnit\Framework\TestCase
{
  private $dataObject;
  private $parentPropertyName;
  private $parentRecord;
  private $target;
  private $targetPrimaryKey;
  private $testObject;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->dataObject = new \stdClass();
    $this->dataObject->key = 3;
    $this->parentPropertyName = Str::set('relation-alpha');
    $this->parentRecord = new FromRecord($this->dataObject);
    $this->target = new ToRecord();
    $this->targetPrimaryKey = $this->target->primaryKey;
    $this->testObject = new Foreign($this->parentPropertyName, $this->parentRecord, $this->targetPrimaryKey);
  }

  /**
    * Collection of assertions for \ramp\model\business\ForeignKey::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\RecordComponent}
   * - assert is instance of {@link \ramp\model\business\ForeignKey}
   * @link ramp.model.business.ForeignKey ramp\model\business\field\ForeignKey
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\RecordComponent', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\key\Foreign', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\ForeignKey::id.
   * - assert {@link \ramp\core\PropertyNotSetException PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str Str}.
   * - assert returned id value matches that of related {@link \ramp\core\model\business\BusinessModel BusinessModel}.
   * - assert returned id value matches expected result.
   * @link ramp.model.business.ForeignKey#method_get_id ramp\model\business\ForeignKey::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());      
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertEquals(
        (string)$this->parentRecord->id->append($this->parentPropertyName->prepend(Str::COLON()))->append(Str::set('[foreign-key]')),
        (string)$this->testObject->id
      );
      $this->assertEquals('from-record:3:relation-alpha[foreign-key]', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\ForeignKey::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates through expected fields of primaryKey fields as ForeignKeyPart.
   * @link ramp.model.business.ForeignKey#method_getIterator ramp\model\business\ForeignKey::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    foreach ($this->testObject as $foreignKeyPart) {
      $this->assertInstanceOf('\ramp\model\business\RecordComponent', $foreignKeyPart);
      $this->assertInstanceOf('\ramp\model\business\field\Field', $foreignKeyPart);
      $this->assertSame('from-record:3:relation-alpha[' . Str::hyphenate($this->target->primaryKeyNames()[$i]) . ']', (string)$foreignKeyPart->id);
      $this->assertSame('foreign-key-part field', (string)$foreignKeyPart->type);
      $this->assertSame(0, $foreignKeyPart->errors->count);
      $this->assertFalse($foreignKeyPart->hasErrors);
      $this->assertSame($this->parentPropertyName, $foreignKeyPart->parentPropertyName);
      $this->assertSame($this->parentRecord, $foreignKeyPart->parentRecord);
      $this->assertTrue($foreignKeyPart->isEditable);
      $this->assertNull($foreignKeyPart->value);
      $this->assertSame(0, $foreignKeyPart->count);
      $i++;
    }
  }
}
