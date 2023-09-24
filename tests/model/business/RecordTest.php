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

require_once '/usr/share/php/tests/ramp/model/business/RelatableTest.php';

require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/key/Key.class.php';
require_once '/usr/share/php/ramp/model/business/key/Primary.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecordComponent.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\business\BusinessModel;
use ramp\model\business\field\Input;
use ramp\model\business\validation\dbtype\VarChar;

use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockRecordComponent;

/**
 * Collection of tests for \ramp\model\business\Record.
 */
class RecordTest extends \tests\ramp\model\business\RelatableTest
{
  private $dataObject;

  /**
   * Template method inc. factory for TestObject instance.
   */
  protected function preSetup() : void { $this->dataObject = new \StdClass(); }
  protected function getTestObject() : RAMPObject { return new MockRecord($this->dataObject); }
  protected function postSetup() : void { $this->expectedChildCount = $this->testObject->count; }

  /**
   * Default base constructor assertions \ramp\model\business\Relatable::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert is instance of {@link \ramp\model\business\Relatable}
   * @link ramp.model.business.Relatable ramp\model\business\Relatable
   */
  public function testConstruct()
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\business\Relatable', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\Record', $this->testObject);
  }

  /**
   * Index editing of children through \ramp\model\business\BusinessModel::offsetSet and
   * for \ramp\model\business\BusinessModel::offsetUnset.
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - asser successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link ramp.model.business.BusinessModel#method_offsetSet ramp\model\business\BusinessModel::offsetSet()
   * @link ramp.model.business.BusinessModel#method_offsetUnset ramp\model\business\BusinessModel::offsetUnset()
   */
  public function testOffsetSetOffsetUnset(BusinessModel $o = NULL) {
    parent::testOffsetSetOffsetUnset(new MockRecordComponent(Str::set('aProperty'), new MockRecord()));
  }

  /**
   * Ensure children index editing restricted to BusinessModels of type 'RecordComponent's
   */
  public function testOffSetSetBadMethodCallException()
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage('Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!');
    $this->testObject[0] = new MockRecord();
  }

  /**
   * Minimumal Record initial 'new' state.
   * - assert data state as expected:
   *   - assert isModifed returns FALSE.
   *   - assert isValid returns FALSE.
   *   - assert isNew returns TRUE.
   * - assert property 'id' is gettable:
   *   - assert returned value instance of {@link \ramp\core\Str}.
   *   - assert returned value matches expected result, in the format:
   *     - lowercase and hypenated colon seperated [class-name]:[key].
   * - assert property 'primarykey' is gettable:
   *   - assert returned value instance of {@link \ramp\core\Str}.
   *   - assert returned value matches expected result value of 'new' when new.
   * - assert contained dataObject properties match requirements.
   *   - assert 'keyA' property NULL. 
   * @link ramp.model.business.BusinessModel#method_get_isNew ramp\model\business\BusinessModel::isNew
   * @link ramp.model.business.BusinessModel#method_get_isValid ramp\model\business\BusinessModel::isValid
   * @link ramp.model.business.BusinessModel#method_get_isModified ramp\model\business\BusinessModel::isModified
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::id
   * @link ramp.model.business.Record#method_get_id ramp\model\business\Record::primarykey
   */
  public function testMinRecordNewState()
  {
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isValid);
    $this->assertFalse($this->testObject->isModified);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);
    $this->assertNull($this->dataObject->keyA);
  }

  /**
   * Validate process for primaryKey sub KEY inputs to achive valid record state.
   * - assert initial 'new' Record state:
   *   - assert isNew, isModified, isValid flags report expected (TRUE|FALSE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * - assert post simulated FIRST valid KEY input: 
   *   = assert dataObject updated with valid value for FIRST KEY 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * - assert post simulated SECOND valid KEY input: 
   *   = assert dataObject updated with valid values for FIRST and SECOND KEY 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|FALSE).
   *   - assert id matches expected result, in the format [class-name]:new.
   * 
   * - assert post simulated FINAL valid KEY input:
   *   = assert dataObject updated with valid values for ALL KEYs 
   *   - assert isNew, isModified, isValid flags report expected (TRUE|TRUE|TRUE).
   *   - assert id matches expected result, in the format [class-name]:[keyA]|[keyB]|[keyC].
   * - assert post simulated updated() called from BusinessModelManager:
   *   - assert isNew, isModified, isValid flags report expected (TRUE|FALSE|TRUE).
   */
  public function testNewRecordPrimaryKeyInput()
  {
    $this->assertTrue($this->testObject->isNew);
    $this->assertFalse($this->testObject->isValid);
    $this->assertFalse($this->testObject->isModified);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);

    $keyAValue = 'A1'; $keyBValue = 'B1'; $keyCValue = 'C1';

    // Simulate getPropertyValue() called from relevant RecordComponent.
    $this->testObject->setPropertyValue('keyB', $keyBValue);
    $this->assertSame($keyBValue, $this->dataObject->keyB);
    $this->assertSame($keyBValue, $this->testObject->keyB->value);
    $this->assertSame($this->dataObject->keyA, $this->testObject->keyA->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertTrue($this->testObject->isModified);
    $this->assertFalse($this->testObject->isValid);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);

    // Simulate getPropertyValue() called from relevant RecordComponent.
    $this->testObject->setPropertyValue('keyA', $keyAValue);
    $this->assertSame($keyBValue, $this->dataObject->keyB);
    $this->assertSame($keyAValue, $this->dataObject->keyA);
    $this->assertSame($keyAValue, $this->testObject->keyA->value);
    $this->assertSame($this->dataObject->keyA, $this->testObject->keyA->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertTrue($this->testObject->isModified);
    $this->assertFalse($this->testObject->isValid);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':new', (string)$this->testObject->id);

    // Simulate getPropertyValue() called from relevant RecordComponent.
    $this->testObject->setPropertyValue('keyC', $keyCValue);
    $this->assertSame($keyBValue, $this->dataObject->keyB);
    $this->assertSame($keyAValue, $this->dataObject->keyA);
    $this->assertSame($keyCValue, $this->dataObject->keyC);
    $this->assertSame($keyCValue, $this->testObject->keyC->value);
    $this->assertSame($this->dataObject->keyC, $this->testObject->keyC->value);
    $this->assertTrue($this->testObject->isNew);
    $this->assertTrue($this->testObject->isModified);
    // TODO:mrenyard: Restart from HERE once key/Primary finished.
    // $this->assertTrue($this->testObject->isValid);
    // $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
    // $this->assertSame($this->processType(get_class($this->testObject), TRUE) . ':a1|b1|c1', (string)$this->testObject->id);

    // // Simulate updated() called from BusinessModelManager
    // $this->testObject->updated();
    // $this->assertFalse($this->testObject->isNew);
    // $this->assertTrue($this->testObject->isValid);
    // $this->assertFalse($this->testObject->isModified);
  }
}