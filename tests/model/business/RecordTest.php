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
namespace tests\svelte\model\business;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/OptionList.class.php';
require_once '/usr/share/php/svelte/core/Option.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/field/Field.class.php';
require_once '/usr/share/php/svelte/model/business/field/Input.class.php';
require_once '/usr/share/php/svelte/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/svelte/model/business/field/SelectMany.class.php';
require_once '/usr/share/php/svelte/validation/ValidationRule.class.php';
require_once '/usr/share/php/svelte/validation/FailedValidationException.class.php';

require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordTest/ConcreteRecord.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordTest/ConcreteValidationRule.class.php';
require_once '/usr/share/php/tests/svelte/model/business/mocks/RecordTest/ConcreteOptionList.class.php';

use svelte\SETTING;
use svelte\core\Str;
use svelte\core\Collection;
use svelte\core\PropertyNotSetException;
use svelte\condition\PostData;
use svelte\model\business\field\Input;
use svelte\model\business\field\SelectOne;
use svelte\model\business\field\SelectMany;

use tests\svelte\model\business\mocks\RecordTest\ConcreteRecord;
use tests\svelte\model\business\mocks\RecordTest\ConcreteValidationRule;

/**
 * Collection of tests for \svelte\model\business\Record.
 */
class RecordTest extends \PHPUnit\Framework\TestCase
{
  private $testObjectPropertyCount;
  private $dataObject;
  private $testObject;
  private $testObjectProperties;

  /**
   * Setup - add variables
   */
  public function setUp()
  {
    SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE = 'tests\svelte\model\business\mocks\RecordTest';
    $this->testObjectPropertyCount = 3;
    $this->dataObject = new \stdClass();
    $this->testObject = new ConcreteRecord($this->dataObject);
    $this->testObjectProperties = new Collection(Str::set('svelte\model\business\field\Field'));
    $className = __NAMESPACE__ . '\mocks\RecordTest\ConcreteRecord';
    foreach (get_class_methods($className) as $methodName)
    {
      if (strpos($methodName, 'get_') === 0)
      {
        foreach (get_class_methods('\svelte\model\business\Record') as $parentMethod)
        {
          if ($methodName == $parentMethod) { continue 2; }
        }
        $propertyName = str_replace('get_', '', $methodName);
        $this->testObjectProperties[$propertyName] = $this->testObject->$propertyName;
      }
    }
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\business\BusinessModel}
   * - assert is instance of {@link \svelte\model\business\Record}
   * - assert is instance of {@link \svelte\core\iOption}
   * - assert is instance of {@link \IteratorAggregate}
   * - assert is instance of {@link \Countable}
   * - assert is instance of {@link \ArrayAccess}
   * - assert dataObject provided at construction is now populated with NULL valued properties
   *   that match the testObjects (Record) get_ methods.
   * @link svelte.model.business.Record svelte\model\business\Record
   */
  public function test__construction()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\BusinessModel', $this->testObject);
    $this->assertInstanceOf('\svelte\model\business\Record', $this->testObject);
    $this->assertInstanceOf('\svelte\core\iOption', $this->testObject);
    $this->assertInstanceOf('\IteratorAggregate', $this->testObject);
    $this->assertInstanceOf('\Countable', $this->testObject);
    $this->assertInstanceOf('\ArrayAccess', $this->testObject);
    $i = 0;
    $properties = get_object_vars($this->dataObject);
    foreach ($properties as $name => $value)
    {
      $this->assertEquals('property' . ++$i, $name);
      $this->assertNull($value);
    }
    $this->assertEquals($i, count($properties));
    $this->assertEquals($this->testObjectPropertyCount, $i);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::id.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'id'
   * - assert property 'id' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected result, in the format:
   *   - lowercase and hypenated colon seperated [class-name]:[key].
   * @link svelte.model.business.Record#method_get_id svelte\model\business\Record::id
   */
  public function testGet_id()
  {
    try {
      $this->testObject->id = "ID";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->id is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->id);
      $this->assertSame('concrete-record:new', (string)$this->testObject->id);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::description.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'description'
   * - assert property 'description' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned same as 'id'.
   * - assert returned value matches expected result.
   * @link svelte.model.business.Record#method_get_description svelte\model\business\Record::description
   */
  public function testGet_description()
  {
    try {
      $this->testObject->description = "DESCRIPTION";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->description is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->description);
      $this->assertEquals($this->testObject->id, $this->testObject->description);
      $this->assertEquals('concrete-record:new', (string)$this->testObject->description);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::value.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'value'
   * - assert property 'vlaue' is gettable.
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned same as 'id'.
   * - assert returned value matches expected result.
   * @link svelte.model.business.Record#method_get_value svelte\model\business\Record::value
   */
  public function testGet_value()
  {
    try {
      $this->testObject->value = "VALUE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->value is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->value);
      $this->assertEquals($this->testObject->id, $this->testObject->value);
      $this->assertEquals('concrete-record:new', (string)$this->testObject->value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::type.
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when trying to set property 'type'
   * - assert property 'type' is gettable.
   * - assert returned value is of type {@link \svelte\core\Str}.
   * - assert returned value matches expected result.
   * @link svelte.model.business.Record#method_get_type svelte\model\business\Record::type
   */
  public function testGet_type()
  {
    try {
      $this->testObject->type = "TYPE";
    } catch (PropertyNotSetException $expected) {
      $this->assertSame(get_class($this->testObject) . '->type is NOT settable', $expected->getMessage());
      $this->assertInstanceOf('\svelte\core\Str', $this->testObject->type);
      $this->assertSame(' concrete-record record', (string)$this->testObject->type);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::getIterator().
   * - assert returns object that is an instance of {@link \Traversable}
   * - assert foreach loop iterates through, and on each iteration, returns a relivant
   *   {@link field\Field} as defined by the relevant property of the testObject (Record).
   * @link svelte.model.business.Record#method_getIterator svelte\model\business\Record::getIterator()
   */
  public function testGetIterator()
  {
    $this->assertInstanceOf('\Traversable', $this->testObject->getIterator());
    $i = 0;
    $iterator = $this->testObjectProperties->getIterator();
    $iterator->rewind();
    foreach ($this->testObject as $child)
    {
      $this->assertSame($child, $iterator->current());
      $this->assertEquals('concrete-record:new:property-' . ++$i, (string)$child->id);
      $this->assertInstanceOf('\svelte\model\business\field\Field', $child);
      $iterator->next();
    }
    $this->assertEquals($this->testObjectProperties->count, $i);
    $properties = get_object_vars($this->dataObject);
    $this->assertEquals($i, count($properties));
    $this->assertEquals($this->testObjectPropertyCount, $i);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::offsetGet.
   * - assert {@link \OutOfBoundsException} thrown when offset index beyond bounds of its children
   * - assert expected object returned at its expected index [(propertyname)].
   * @link svelte.model.business.Record#method_offsetGet svelte\model\business\Record::offsetGet()
   */
  public function testOffsetGet()
  {
    try {
      $this->testObject[4];
    } catch (\OutOfBoundsException $expected) {
      for ($i = 0; $i < $this->testObjectProperties->count; $i++)
      {
        $this->assertInstanceOf(
          '\svelte\model\business\field\Field', $this->testObject['property' . ($i + 1)]
        );
        $this->assertSame(
          $this->testObjectProperties['property' . ($i + 1)],
          $this->testObject['property' . ($i + 1)]
        );
      }
      return;
    }
    $this->fail('An expected \OutOfBoundsException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::offsetExists.
   * - assert True returned on isset() when indexed with valid index.
   * - assert False returned on isset() when indexed with invalid index.
   * @link svelte.model.business.Record#method_offsetExists svelte\model\business\Record::offsetExists()
   */
  public function testOffsetExists()
  {
    $this->assertTrue(isset($this->testObject['property1']));
    $this->assertTrue(isset($this->testObject['property2']));
    $this->assertTrue(isset($this->testObject['property3']));
    $this->assertFalse(isset($this->testObject['property4']));
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::offsetSet and
   * for \svelte\model\business\Record::offsetUnset.
   * - assert adding anything other that property fields through offsetSet fails, and even then
   *   they MUST meet strict criteria for propertyName index and object type or it throws a
   *   BadMethodCallException with the message:
   *   - *Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!*
   * - assert unsetting properties already populated with a valid value throws a
   *   BadMethodCallException with the message:
   *   - *Unsetting properties already populated with a valid value NOT alowed!*
   * - assert successful use of offsetSet
   * - assert returned object is the same object at same index (offset) as was set.
   * - assert successful use of offsetUnset
   * - assert isset return FALSE at the same index once unset has been used.
   * @link svelte.model.business.Record#method_offsetSet svelte\model\business\Record::offsetSet()
   * @link svelte.model.business.Record#method_offsetSet svelte\model\business\Record::offsetSet()
   */
  public function testOffsetSetUnset()
  {
    $object = new Input(Str::set('property4'), $this->testObject, new ConcreteValidationRule());
    $this->testObject['property4'] = $object;
    $this->assertSame($object, $this->testObject['property4']);
    unset($this->testObject['property4']);
    $this->assertFalse(isset($this->testObject['property4']));
    try {
      $this->testObject[0] = new Input(
        Str::set('property5'), $this->testObject, new ConcreteValidationRule()
      );
      } catch (\BadMethodCallException $expected) {
      $this->assertEquals(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!',
        $expected->getMessage()
      );
      try {
      $this->testObject['property5'] = new ConcreteValidationRule();
      } catch (\BadMethodCallException $expected) {
        $this->assertEquals(
          'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!',
          $expected->getMessage()
        );
        $property5 = $this->testObject['property5'] = new Input(
          Str::set('property5'), $this->testObject, new ConcreteValidationRule()
        );
        $this->testObject->setPropertyValueFromField('property5', 'GOOD');
        $this->assertEquals('GOOD', $this->dataObject->property5);
        try {
          unset($this->testObject['property5']);
        } catch (\BadMethodCallException $expected) {
          $this->assertEquals(
            'Unsetting properties already populated with a valid value NOT alowed!',
            $expected->getMessage()
          );
          return;
        }
      }
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::validate(),
   * \svelte\model\business\Record::hasErrors() and \svelte\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link svelte.model.business.Record#method_validate svelte\model\business\Record::validate()
   * @link svelte.model.business.Record#method_hasErrors svelte\model\business\Record::hasErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::getErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::isModified()
   */
  public function testValidateHasGetErrorsNewAllGood()
  {
    $_POST1 = array(
      'concrete-record:new:property-1' => 'KEY',
      'concrete-record:new:property-2' => '3',
      'concrete-record:new:property-3' => array('1','4','6')
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST1)));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertEquals(0, $this->testObject->errors->count);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $this->assertEquals(0, $this->testObject->property3->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertEquals('KEY', $dataObjectProperties['property1']);
    $this->assertSame($dataObjectProperties['property1'], $this->testObject->property1->value);
    $this->assertEquals('3', $dataObjectProperties['property2']);
    $this->assertSame($dataObjectProperties['property2'], $this->testObject->property2->value);
    $this->assertEquals(array('1','4','6'), $dataObjectProperties['property3']);
    $this->assertSame($dataObjectProperties['property3'], $this->testObject->property3->value);
    $this->assertTrue($this->testObject->isModified);
    $this->assertNull($this->testObject->validate(PostData::build($_POST1)));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertEquals(0, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::validate(),
   * \svelte\model\business\Record::hasErrors() and \svelte\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link svelte.model.business.Record#method_validate svelte\model\business\Record::validate()
   * @link svelte.model.business.Record#method_hasErrors svelte\model\business\Record::hasErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewP1Bad()
  {
    $_POST2 = array(
      'concrete-record:new:property-1' => 'BAD',
      'concrete-record:new:property-2' => '4',
      'concrete-record:new:property-3' => array('1','2','6')
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST2)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(1, $errorMessages->count);
    $this->assertEquals('$value of "BAD" does NOT evaluate to KEY', $errorMessages[0]);
    $this->assertEquals(1, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $this->assertEquals(0, $this->testObject->property3->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertNull($dataObjectProperties['property1']);
    $this->assertSame($dataObjectProperties['property1'], $this->testObject->property1->value);
    $this->assertEquals('4', $dataObjectProperties['property2']);
    $this->assertSame($dataObjectProperties['property2'], $this->testObject->property2->value);
    $this->assertEquals(array('1','2','6'), $dataObjectProperties['property3']);
    $this->assertSame($dataObjectProperties['property3'], $this->testObject->property3->value);
    $this->assertNull($this->testObject->validate(PostData::build($_POST2)));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertEquals(1, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::validate(),
   * \svelte\model\business\Record::hasErrors() and \svelte\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link svelte.model.business.Record#method_validate svelte\model\business\Record::validate()
   * @link svelte.model.business.Record#method_hasErrors svelte\model\business\Record::hasErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewP2Bad()
  {
    $_POST3 = array(
      'concrete-record:new:property-1' => 'KEY',
      'concrete-record:new:property-2' => '7', // BAD - Beyond index
      'concrete-record:new:property-3' => array('1','2','6')
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST3)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(1, $errorMessages->count);
    $this->assertEquals('Selected value NOT an avalible option!', $errorMessages[0]);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(1, $this->testObject->property2->errors->count);
    $this->assertEquals(0, $this->testObject->property3->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertEquals('KEY', $dataObjectProperties['property1']);
    $this->assertSame($dataObjectProperties['property1'], $this->testObject->property1->value);
    $this->assertNull($dataObjectProperties['property2']);
    $this->assertSame($dataObjectProperties['property2'], $this->testObject->property2->value);
    $this->assertEquals(array('1','2','6'), $dataObjectProperties['property3']);
    $this->assertSame($dataObjectProperties['property3'], $this->testObject->property3->value);
    $_POST4 = array(
      'concrete-record:key:property-2' => '7', // BAD - Beyond index
      'concrete-record:key:property-3' => array('1','2','6')
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST4)));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertEquals(1, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::validate(),
   * \svelte\model\business\Record::hasErrors() and \svelte\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link svelte.model.business.Record#method_validate svelte\model\business\Record::validate()
   * @link svelte.model.business.Record#method_hasErrors svelte\model\business\Record::hasErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewP3Bad()
  {
    $_POST5 = array(
      'concrete-record:new:property-1' => 'KEY',
      'concrete-record:new:property-2' => '5',
      'concrete-record:new:property-3' => array('1','8','6') // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST5)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(1, $errorMessages->count);
    $this->assertEquals(
      'At least one selected value is NOT an available option!',
      $errorMessages[0]
    );
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $this->assertEquals(1, $this->testObject->property3->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertEquals('KEY', $dataObjectProperties['property1']);
    $this->assertSame($dataObjectProperties['property1'], $this->testObject->property1->value);
    $this->assertEquals('5', $dataObjectProperties['property2']);
    $this->assertSame($dataObjectProperties['property2'], $this->testObject->property2->value);
    $this->assertNull($dataObjectProperties['property3']);
    $this->assertSame($dataObjectProperties['property3'], $this->testObject->property3->value);
    $_POST6 = array(
      'concrete-record:key:property-2' => '5',
      'concrete-record:key:property-3' => array('1','8','6') // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST6)));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertEquals(1, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::validate(),
   * \svelte\model\business\Record::hasErrors() and \svelte\model\business\Record::getErrors().
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link svelte.model.business.Record#method_validate svelte\model\business\Record::validate()
   * @link svelte.model.business.Record#method_hasErrors svelte\model\business\Record::hasErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsNewAllBad()
  {
    $_POST7 = array(
      'concrete-record:new:property-1' => 'BAD',
      'concrete-record:new:property-2' => '7', // BAD - Beyond index
      'concrete-record:new:property-3' => array('1','8','6') // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST7)));
    $this->assertTrue($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(3, $errorMessages->count);
    $this->assertEquals(
      '$value of "BAD" does NOT evaluate to KEY',
      $errorMessages[0]
    );
    $this->assertEquals(
      'Selected value NOT an avalible option!',
      $errorMessages[1]
    );
    $this->assertEquals(
      'At least one selected value is NOT an available option!',
      $errorMessages[2]
    );
    $this->assertEquals(1, $this->testObject->property1->errors->count);
    $this->assertEquals(1, $this->testObject->property2->errors->count);
    $this->assertEquals(1, $this->testObject->property3->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    $this->assertNull($dataObjectProperties['property1']);
    $this->assertSame($dataObjectProperties['property1'], $this->testObject->property1->value);
    $this->assertNull($dataObjectProperties['property2']);
    $this->assertSame($dataObjectProperties['property2'], $this->testObject->property2->value);
    $this->assertNull($dataObjectProperties['property3']);
    $this->assertSame($dataObjectProperties['property3'], $this->testObject->property3->value);
    $_POST8 = array(
      'concrete-record:new:property-1' => 'BAD',
      'concrete-record:new:property-2' => '7', // BAD - Beyond index
      'concrete-record:new:property-3' => array('1','8','6') // BAD - Second argument beyond index
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST8)));
    $this->assertTrue($this->testObject->hasErrors);
    $this->assertEquals(3, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::validate(),
   * \svelte\model\business\Record::hasErrors() and \svelte\model\business\Record::getErrors().
   * - assert PrimaryKey is NOT updated.
   * - assert returns void (null) when called.
   * - assert validate method is propagated through each property of testsObject
   * - assert returns True when any property has recorded errors.
   * - assert propagates through properties until reaches one that has recorded errors.
   * - assert following validate(), the iCollection of error messages returned from getErrors() are as expected.
   * - assert any following call to hasErrors returns the same collection of messages as previously.
   * - assert a single collection containing all errors returned when called on testObject.
   * - assert a single collection containing relevent sub errors returned when called on sub Records
   * - assert successfully validated properties are updated in the record.
   * @link svelte.model.business.Record#method_validate svelte\model\business\Record::validate()
   * @link svelte.model.business.Record#method_hasErrors svelte\model\business\Record::hasErrors()
   * @link svelte.model.business.Record#method_getErrors svelte\model\business\Record::getErrors()
   */
  public function testValidateHasGetErrorsExistingAllGood()
  {
    $this->dataObject->property1 = 'pkey';
    $this->dataObject->property2 = '2';
    $this->dataObject->property3 = array('1','2','6');
    $this->assertNull($this->testObject->updated());
    $this->assertFalse($this->testObject->isNew);
    $this->assertTrue($this->testObject->isValid);

    $_POST9 = array(
      'concrete-record:pkey:property-1' => 'KEY',
      'concrete-record:pkey:property-2' => '5',
      'concrete-record:pkey:property-3' => array('3','4','5')
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST9)));
    $this->assertFalse($this->testObject->hasErrors);
    $errorMessages = $this->testObject->errors;
    $this->assertEquals(0, $errorMessages->count);
    $this->assertEquals(0, $this->testObject->property1->errors->count);
    $this->assertEquals(0, $this->testObject->property2->errors->count);
    $this->assertEquals(0, $this->testObject->property3->errors->count);
    $i = 1;
    $dataObjectProperties = get_object_vars($this->dataObject);
    // NOTE the primaryKey is NOT updated
    $this->assertEquals('pkey', $dataObjectProperties['property1']);
    $this->assertSame($dataObjectProperties['property1'], $this->testObject->property1->value);
    $this->assertEquals('5', $dataObjectProperties['property2']);
    $this->assertSame($dataObjectProperties['property2'], $this->testObject->property2->value);
    $this->assertEquals(array('3','4','5'), $dataObjectProperties['property3']);
    $this->assertSame($dataObjectProperties['property3'], $this->testObject->property3->value);
    $_POST10 = array(
      'concrete-record:pkey:property-2' => '5',
      'concrete-record:pkey:property-3' => array('3','4','5')
    );
    $this->assertNull($this->testObject->validate(PostData::build($_POST10)));
    $this->assertFalse($this->testObject->hasErrors);
    $this->assertEquals(0, $this->testObject->errors->count);
  }

  /**
   * Collection of assertions for \svelte\model\business\Record::__set(),
   * - assert {@link \svelte\core\PropertyNotSetException} thrown when unable to set undefined or
   *   inaccessible property
   * - assert get <i>Record['aProperty']</i> returns same as set <i>Record->aProperty = $value</i>
   * @param \svelte\core\SvelteObject $testSvelteObject Instance of MockSvelteObject for testing
   * @link svelte.model.business.Record#method__set svelte\model\business\Record::__set()
   */
  public function test__set()
  {
    try {
      $this->testObject->noProperty = 'VALUE';
    } catch (PropertyNotSetException $expected) {
      $this->testObject->property2 = '1';
      $this->assertEquals($this->testObject['property2'], $this->testObject->property2);
      $this->assertEquals('1', $this->testObject->property2->value);
      return;
    }
    $this->fail('An expected \svelte\core\PropertyNotSetException has NOT been raised.');
  }

   /**
   * Collection of assertions for \svelte\model\business\Record::count.
   * - assert return expected int value related to the number of child Records held.
   * @link svelte.model.business.Record#method_count svelte\model\business\Record::count
   */
  public function testCount()
  {
    $this->assertSame(3 ,$this->testObject->count);
  }
}
