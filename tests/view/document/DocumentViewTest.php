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
namespace tests\ramp\view\document;

require_once '/usr/share/php/tests/ramp/view/ComplexViewTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/SQLEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/model/Document.class.php';
require_once '/usr/share/php/ramp/model/business/DataFetchException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/SimpleBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Flag.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectMany.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Relation.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToMany.class.php';
require_once '/usr/share/php/ramp/model/business/RelationToOne.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/view/document/DocumentView.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/MockSqlBusinessModelManager.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockFlag.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockValidationRule.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockOption.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockSelectFrom.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockInput.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToMany.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRelationToOne.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockMinRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/view/MockDocumentView.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\view\RootView;

use tests\ramp\mocks\model\MockRecord;
use tests\ramp\mocks\model\MockBusinessModel;
use tests\ramp\mocks\view\MockDocumentView;

/**
 * Collection of tests for \ramp\view\ComplexView.
 */
class DocumentViewTest extends \tests\ramp\view\ComplexViewTest
{
  #region Setup
  #[\Override]
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\model';
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER = 'tests\ramp\mocks\model\MockSqlBusinessModelManager';
    RootView::reset(); 
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new MockDocumentView(RootView::getInstance()); }
  #endregion

  /**
   * Default base constructor assertions \ramp\view\View::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\ChildView}
   * - assert is instance of {@see \ramp\view\ComplexView}
   * - assert is instance of {@see \ramp\view\document\DocumentView}
   * @see \ramp\view\document\DocumentView
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\document\DocumentView', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessible on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }

  /**
   * Addition of sub views. 
   * - assert each child view added sequentially.
   * - assert View->children output maintains sequance and format.
   * @see \ramp\view\View::add()
   * @see \ramp\view\View::children
   */
  #[\Override]
  public function testSubViewAddition(string $parentRender = 'tests\ramp\mocks\view\MockDocumentView ') : void
  {
    parent::postSetup();
    $this->testObject->viewOnlyTesting = TRUE;
    parent::testSubViewAddition($parentRender);
  }

  /**
   * Cloning copies sub views.
   * - assert cloned View without associated model is equal to the original
   * - assert cloned View with associated model NOT equal as Model association removed
   * - assert cloned View with model re associated is equal to the original 
   * @see \ramp\view\View::__clone()
   */
  #[\Override]
  public function testClone() : void
  {
    parent::testClone();
  }

  /**
   * Check BadMethodCallException thrown when model already set.
   * - assert throws BadMethodCallException when model already set.
   *   - with message: *'model already set violation'*
   * @see \ramp\view\ComplexView::setModel()
   */
  #[\Override]
  public function testModelAlreadySetException() : void
  {
    parent::testModelAlreadySetException();
  }

  /**
   * Check read access to associated Model's properties.
   * - assert that property calls are passes to its component (contained) {@see \ramp\model\Model}.
   */
  #[\Override]
  public function testPassthroughProperties() : void
  {
    parent::testPassthroughProperties();
  }

  /**
   * Manage complex hierarchical and ordered model to view interlacing, as appropriate from *this* View definition.
   * - assert prior to model set hasModel returns FALSE and post set TRUE.
   * - assert when Sequentially model provided (x2 examples):
   *   - each view added sequentially as expected.
   *   - output from View->render() maintains ecpected sequance.
   * - assert when Hierarchy model provided (x2 examples):
   *   - each view added sequentially and hieratically as expected.
   *   - output from View->render() maintains sequance and hieratically format.
   */
  #[\Override]
  public function testComplexModelCascading(string $parentViewType = 'tests\ramp\mocks\view\MockDocumentView', $templateName = NULL, $templateType = NULL) : void
  {
    parent::testComplexModelCascading($parentViewType, $templateName, $templateType);
  }
  #endregion

  #region New Specialist Tests
  /**
   * 'class' attribute managment and retrieval.
   * - assert 'style' setting adds to classlist
   * - assert attribute('class') returns in expected format.
   * - assert model definition forms part of classlist as expected. 
   */
  public function testClassProperyReturnValue()
  {
    $this->testObject->style = Str::set('default');
    $this->assertSame('default', (string)$this->testObject->class);
    $this->assertSame(' class="default"', (string)$this->testObject->attribute('class'));
    $record = new MockRecord();
    $this->testObject->setModel($record->aProperty);
    $this->assertSame('mock-field field default', (string)$this->testObject->class);
    $this->assertSame(' class="mock-field field default"', (string)$this->testObject->attribute('class'));
  }

  /**
   * heading/label element value managment and retrieval.
   * - assert default value '[Heading/Lable]'
   * - assert access setting through $this->label.
   * - assert retrieval throught either throught 'heading' or 'lable'
   * - assert when associated with a field, returns the field 'lable' in expected format. 
   */
  public function testLabelHeadingProperyReturnValue()
  {
    $this->assertSame('[LABEL]', (string)$this->testObject->label); // DEFAULT
    $record = new MockRecord();
    $this->testObject->setModel($record->aProperty);
    $this->assertSame('A Property', (string)$this->testObject->label); // from Field name
    $this->testObject->label = Str::set('My Heading');
    $this->assertSame('My Heading', (string)$this->testObject->heading);
    $this->assertSame($this->testObject->label, $this->testObject->heading);
  }

  /**
   * 'id' attribute managment and retrieval.
   * - assert default when not set or related to data field returns unique uid[number].
   * - assert with data returns expected URN [record]:[key]:[property]
   */
  public function testIdPropertyReturenValue()
  {
    $this->assertMatchesRegularExpression('#^uid[0-9]*$#', (string)$this->testObject->id);
    $data = new \stdClass();
    $data->keyA = 1; $data->keyB = 1; $data->keyC = 1;
    $record = new MockRecord($data);
    $this->testObject->setModel($record->aProperty);
    $this->assertSame('mock-record:1|1|1:a-property', (string)$this->testObject->id);
  }

  public function testDocumentModelCopiedOnClone()
  {
    $title = Str::set('Person');
    $label =  Str::set('First Name');
    $placeholder = Str::set('e.g. John');
    $style = Str::set('compact');
    $this->testObject->title = $title;
    $this->testObject->label = $label;
    $this->testObject->placeHolder = $placeholder;
    $this->testObject->style = $style;
    $o2 = clone $this->testObject;
    $this->assertSame($title, $o2->title);
    $this->assertSame($this->testObject->title, $o2->title);
    $this->assertSame($label, $o2->label);
    $this->assertSame($this->testObject->label, $o2->label);
    $this->assertSame($placeholder, $o2->placeholder);
    $this->assertSame($this->testObject->placeholder, $o2->placeholder);
    $this->assertSame($style, $o2->style);
    $this->assertSame($this->testObject->style, $o2->style);
    $this->testObject->title = Str::set('Indervidual');
    $this->assertSame('Indervidual', (string)$o2->title);
  }
  #endregion
}
