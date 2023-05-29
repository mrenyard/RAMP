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
namespace tests\ramp\model\business\field;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/key/Primary.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/ForeignKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/field/ForeignKeyPart.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';

require_once '/usr/share/php/tests/ramp/model/business/field/mocks/ForeignKeyPartTest/MockField.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/ForeignKeyPartTest/MockRelation.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/ForeignKeyPartTest/FromRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/ForeignKeyPartTest/ToRecord.class.php';
require_once '/usr/share/php/tests/ramp/model/business/field/mocks/ForeignKeyPartTest/MockBusinessModelManager.class.php';

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\condition\PostData;
use ramp\model\business\field\ForeignKeyPart;

use tests\ramp\model\business\field\mocks\ForeignKeyPartTest\ToRecord;
use tests\ramp\model\business\field\mocks\ForeignKeyPartTest\FromRecord;
use tests\ramp\model\business\field\mocks\ForeignKeyPartTest\MockRelation;

/**
 * Collection of tests for \ramp\model\business\field\ForeignKeyPart.
 */
class ForeignKeyPartTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $fromRecord;
  private $relationPropertyName;
  private $foreignKeyName;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\model\business\field\mocks\ForeignKeyPartTest';
    SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\model\business\field\mocks\ForeignKeyPartTest\MockBusinessModelManager';
    MockRelation::reset();
    $dataObject = new \stdClass();
    $dataObject->key = 'KEY';
    $this->fromRecord = new FromRecord($dataObject);
    $this->relationPropertyName = Str::set('relation');
    $this->foreignKeyName = Str::set('keyA');
    $this->testObject = new ForeignKeyPart($this->fromRecord, $this->relationPropertyName, $this->foreignKeyName);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\core\iList}
   * - assert is instance of {@link \ramp\model\Model}
   * - assert is instance of {@link \ramp\model\business\BusinessModel}
   * - assert is instance of {@link \ramp\model\business\field\Field}
   * - assert is instance of {@link \ramp\model\business\field\ForeignKeyPart}
   * @link ramp.model.business.field.ForeignKeyPart ramp\model\business\field\ForeignKeyPart
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\core\iList', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\Field', $this->testObject);
    $this->assertInstanceOf('\ramp\model\business\field\ForeignKeyPart', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::id.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned id value matches that of related {@link BusinessModel}.
   * - assert returned id value matches expected result.
   * @link ramp.model.business.field.ForeignKeyPart#method_get_id ramp\model\business\field\ForeignKeyPart::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->id);
      $this->assertSame(
        (string)$this->fromRecord->id->append(
            Str::COLON()
          )->append(
            $this->relationPropertyName
          )->append(
            Str::hyphenate($this->foreignKeyName)->prepend(
            Str::set('[')
          )->append(
            Str::set(']')
          )
        ),
        (string)$this->testObject->id
      );
      $this->assertSame('from-record:KEY:relation[key-a]', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::label.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'label'
   * - assert property 'label' is gettable.
   * - assert returned value instance of {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.field.ForeignKeyPart#method_get_label ramp\model\business\field\ForeignKeyPart::label
   */
  public function testGet_label()
  {
    try {
      $this->testObject->label = "LABEL";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->label is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->label);
      $this->assertSame('Key A', (string)$this->testObject->label);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of tests for \ramp\model\business\field\ForeignKeyPart::isEditable.
   * - assert is ALWAYS returns TRUE.
   * @link ramp.model.business.field.ForeignKeyPart#method_get_isEditable ramp\model\business\field\ForeignKeyPart::isEditable
   * @link ramp.model.business.field.ForeignKeyPart#method_set_isEditable ramp\model\business\field\ForeignKeyPart::isEditable
   */
  public function testIsEditable()
  {
    $this->testObject->isEditable = FALSE;
    $this->assertTrue($this->testObject->isEditable);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::containingRecord.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'containingRecord'
   * - assert property 'containingRecord' is gettable.
   * - assert returned Record matches Record as provided construct.
   * @link ramp.model.business.field.ForeignKeyPart#method_get_containingRecord ramp\model\business\field\ForeignKeyPart::containingRecord
   */
  public function testGet_containingRecord()
  {
    try {
      $this->testObject->parentRecord = $this->fromRecord;
    } catch (PropertyNotSetException $expected) {
      $this->assertSame($this->fromRecord, $this->testObject->parentRecord);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::type.
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \ramp\core\Str}.
   * - assert returned value matches expected result.
   * @link ramp.model.business.ForeignKeyPart.Field#method_get_type ramp\model\business\field\ForeignKeyPart::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\ramp\core\Str', $this->testObject->type);
      $this->assertEquals('foreign-key-part field', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \ramp\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop, iterates Non.
   * @link ramp.model.business.field.ForeignKeyPart#method_getIterator ramp\model\business\field\ForeignKeyPart::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    foreach ($this->testObject as $child) {
      $i++;
    }
    $this->assertSame(0, $i);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.field.ForeignKeyPart#method_offsetGet ramp\model\business\field\ForeignKeyPart::offsetGet()
   */
  public function testOffsetGet()
  {
    $this->expectException(\OutOfBoundsException::class);
    $this->expectExceptionMessage('Offset out of bounds');
    $this->testObject[0];
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::offsetExists.
   * - assert False returned on isset() as ALWAYS outside expected bounds.
   * @link ramp.model.business.field.ForeignKeyPart#method_offsetExists ramp\model\business\field\ForeignKeyPart::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertFalse(isset($this->testObject[0]));
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * @link ramp.model.business.field.ForeignKeyPart#method_offsetGet ramp\model\business\field\ForeignKeyPart::offsetGet()
   */
  public function testOffsetUnset()
  {
    $this->assertSame(0, count($this->testObject));
    unset($this->testObject[0]);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::validate() where PostData
   * does NOT contain an InputDataCondition with an attribute that matches the testObject's id.
   * - assert returns void (null) when called.
   * - assert if provided PostData does NOT contain an InputDataCondition with an attribute that
   *    matches the testObject's id, then its processValidationRule method, is NOT called.
   * - assert validate method is NOT propagated through to its children and grandchildren.
   * @link ramp.model.business.field.ForeignKeyPart#method_validate ramp\model\business\field\ForeignKeyPart::validate()
   */
  public function testProcessValidationRule()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertSame(0, MockRelation::$processValidationRuleCount);

    $this->assertNull($this->testObject->validate(PostData::build(array(
      'from-record:KEY:relation' => array('key-a' => 'GOOD')
    ))));
    $this->assertSame(0, MockRelation::$processValidationRuleCount);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::hasErrors().
   * - assert returns False ALWAYS.
   * @link ramp.model.business.field.ForeignKeyPart#method_hasErrors ramp\model\business\field\ForeignKeyPart::hasErrors()
   */
  public function testHasErrors()
  {
    $this->assertNull($this->testObject->validate(new PostData()));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertSame(0, MockRelation::$processValidationRuleCount);
  }

  /**
   * Collection of assertions for \ramp\model\business\field\ForeignKeyPart::count.
   * - assert return expected int value related to the number of child BusinessModels held.
   * @link ramp.model.business.field.ForeignKeyPart#method_count ramp\model\business\field\ForeignKeyPart::count
   */
  public function testCount()
  {
    $this->assertSame(0 ,$this->testObject->count);
  }
}
