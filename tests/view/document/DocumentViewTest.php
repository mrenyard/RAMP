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
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/model/Document.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/view/document/DocumentView.class.php';

require_once '/usr/share/php/tests/ramp/mocks/view/MockDbTypeValidation.class.php';
require_once '/usr/share/php/tests/ramp/mocks/view/MockRecord.class.php';
require_once '/usr/share/php/tests/ramp/mocks/view/MockDocumentView.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\condition\PostData;
use ramp\view\RootView;

use tests\ramp\mocks\view\MockRecord;
use tests\ramp\mocks\view\MockBusinessModel;
use tests\ramp\mocks\view\MockDocumentView;
use tests\ramp\mocks\view\MockDbTypeValidation;

/**
 * Collection of tests for \ramp\view\ComplexView.
 */
class DocumentViewTest extends \tests\ramp\view\ComplexViewTest
{
  protected $data;
  protected $record;

  #region Setup
  #[\Override]
  protected function preSetup() : void {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\view';
    RootView::reset();
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new MockDocumentView(RootView::getInstance()); }
  #[\Override]
  protected function postSetup() : void {
    $this->data = new \stdClass();
    $this->record = new MockRecord($this->data);
  }
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
   * - assert cloned View without associated model (minus encpsulated Document::id) is equal to the original
   * - assert cloned View with associated model NOT equal as Model association removed
   * - assert cloned View with model re associated is equal to the original
   * Check cloning includes copy of encapsulated model\Document.
   * - assert initial values (title, label, placeholder, style) same on cloned copy as original.
   * - assert changes made to original do NOT affect new cloned version.
   * - assert changes on cloned version do NOT affect original.
   * @see \ramp\view\View::__clone()
   */
  #[\Override]
  public function testClone() : void
  {
    $title = Str::set('Person');
    $label =  Str::set('First Name');
    $placeholder = Str::set('e.g. John');
    $style = Str::set('compact');
    $this->testObject->title = $title;
    $this->testObject->label = $label;
    $this->testObject->placeHolder = $placeholder;
    $this->testObject->style = $style;

    $clone = clone $this->testObject;
    $this->assertNotSame($this->testObject, $clone);

    $this->assertNotEquals($this->testObject->id, $clone->id);

    $this->assertEquals($this->testObject->title, $clone->title);
    $this->assertEquals($this->testObject->label, $clone->label);
    $this->assertEquals($this->testObject->placeholder, $clone->placeholder);
    $this->assertEquals($this->testObject->style, $clone->style);

    $this->testObject->setModel(new MockBusinessModel());
    $clone = clone $this->testObject; 
    $this->assertNotSame($this->testObject, $clone);
    $this->assertNotEquals($this->testObject, $clone);
    $this->assertTrue($this->testObject->hasModel);
    $this->assertFalse($clone->hasModel);
        
    $this->testObject->title = Str::set('Indervidual');
    $this->assertSame($title, $clone->title);

    $copyTitle = Str::set('human');
    $clone->title = $copyTitle;
    $this->assertNotSame($copyTitle, $testObject->title);

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
   * 'id' property managment and retrieval.
   * - assert default when not set or related to data field returns unique uid[number].
   * - assert with data returns expected URN [record]:[key]:[property]
   */
  public function testIdPropertyReturenValue()
  {
    $this->assertMatchesRegularExpression('#^uid[0-9]*$#', $this->testObject->id);
    $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
    $this->testObject->setModel($this->record->aProperty);
    $this->assertEquals('mock-record:1|1|1:a-property', $this->testObject->id);
  }

  /**
   * 'class/style' property managment and retrieval.
   * - assert 'style' setting adds to classlist
   * - assert model definition forms part of classlist as expected. 
   */
  public function testClassStyleProperyReturnValue(Str $setStyle = NULL)
  {
    if ($setStyle === NULL) {
      $setStyle = Str::set('default');
      $this->testObject->style = $setStyle;
    } 
    $this->assertEquals($setStyle, $this->testObject->style);
    $this->assertEquals($setStyle, $this->testObject->class);
    $this->testObject->setModel($this->record->aProperty);
    $this->assertEquals($setStyle, $this->testObject->style);
    $this->assertEquals('input field ' . $setStyle, $this->testObject->class);
  }

  /**
   * 'title' property value managment and retrieval.
   * - assert default value '[TITLE]'
   * - assert same as set on model once model set.
   * - assert setting through $this->title = $value overides that of model.
   * - assert retrieval throught '$this->title' as expected.
   */
  public function testTitlePropertyReturnValue(string $value = NULL)
  {
    $value = ($value === NULL) ? 'Full context descriptive title' : $value;
    $this->assertEquals('[TITLE]', $this->testObject->title); // DEFAULT
    $this->testObject->setModel($this->record->aProperty);
    $this->assertEquals($this->record->title, $this->testObject->title);
    $this->testObject->title = Str::set($value);
    $this->assertEquals($value, $this->testObject->title);
  }

  /**
   * 'heading/label' property value managment and retrieval.
   * - assert default value '[HEADING/LABEL]'
   * - assert when associated with a field, returns the field 'label' in expected format. 
   * - assert access setting through $this->label and $this->heading.
   * - assert retrieval throught either 'heading' or 'lable'
   */
  public function testHeadingLabelProperyReturnValue()
  {
    $this->assertEquals('[HEADING]', $this->testObject->heading); // DEFAULT
    $this->assertEquals('[LABEL]', $this->testObject->label); // DEFAULT
    $this->testObject->setModel($this->record->aProperty);
    $this->assertEquals('A Property', $this->testObject->label); // from Field name
    $this->testObject->label = Str::set('My Label');
    $this->assertEquals('My Label', $this->testObject->heading);
    $this->assertSame($this->testObject->label, $this->testObject->heading);
    $this->testObject->heading = Str::set('My Heading');
    $this->assertEquals('My Heading', $this->testObject->heading);
    $this->assertSame($this->testObject->heading, $this->testObject->label);
  }

  /**
   * 'summary/placeholder' property value managment and retrieval.
   * - assert default value '[SUMMARY/PLACEHOLDER]'
   * - assert access setting through $this->summary and $this->placeholder.
   * - assert retrieval throught either 'summary' or 'placeholder'
   */
  public function testSummaryPlaceholderProperyReturnValue()
  {
    $this->assertEquals('[SUMMARY]', $this->testObject->summary); // DEFAULT
    $this->assertEquals('[PLACEHOLDER]', $this->testObject->placeholder); // DEFAULT
    $this->testObject->summary = Str::set('My Summary');
    $this->assertEquals('My Summary', $this->testObject->placeholder);
    $this->assertSame($this->testObject->placeholder, $this->testObject->summary);
    $this->testObject->placeholder = Str::set('My Placeholder');
    $this->assertEquals('My Placeholder', $this->testObject->summary);
    $this->assertSame($this->testObject->summary, $this->testObject->placeholder);
  }

  /**
   * 'extendedSummary' property value managment and retrieval.
   * - assert default value NULL.
   * - assert access setting through $this->extendedSummary.
   * - assert retrieval throught either throught 'extendedSummary'.
   */
  public function testExtendedSummaryProperyReturnValue()
  {
    $this->assertNull($this->testObject->extendedSummary);
    $this->testObject->extendedSummary = 'My extended summary with lots of extra text.';
    $this->assertEquals('My extended summary with lots of extra text.', $this->testObject->extendedSummary);
  }

  /**
   * 'extendedContent' property value managment and retrieval.
   * - assert default value NULL.
   * - assert access setting through $this->extendedContent.
   * - assert retrieval throught either throught 'extendedContent'.
   */
  public function testExtendedContentProperyReturnValue()
  {
    $this->assertNull($this->testObject->extendedContent);
    $this->testObject->extendedContent = '<h2>My extended Content<h2> <p>With lots of extra text.</p>';
    $this->assertEquals('<h2>My extended Content<h2> <p>With lots of extra text.</p>', $this->testObject->extendedContent);
  }

  /**
   * 'footnote' property value managment and retrieval.
   * - assert default value NULL.
   * - assert access setting through $this->footnote.
   * - assert retrieval throught either throught 'footnote'.
   */
  public function testFootnoteProperyReturnValue()
  {
    $this->assertNull($this->testObject->footnote);
    $this->testObject->footnote = '<h2>My Footnote<h2> <p>With lots of extra text.</p>';
    $this->assertEquals('<h2>My Footnote<h2> <p>With lots of extra text.</p>', $this->testObject->footnote);
  }

  /**
   * 'type' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'type' prior to setting modal.
   * - assert hasModel prior setting 'setModel' returns FALSE.
   * - assert hasModel post setting 'setModel' returns TRUE.
   * - assert 'type' successfully return as expected based on associated model.
   */
  public function testHasModelTypePropertyReturnValue()
  {
    try {
      $this->testObject->type;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->assertFalse($this->testObject->hasModel);
      $this->testObject->setModel($this->record->aProperty);
      $this->assertTrue($this->testObject->hasModel);
      $this->assertEquals('input field', $this->testObject->type);  
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'errors' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'hasErrors' prior to setting modal.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'errors' prior to setting modal.
   * - assert hesErrors successfully reports FALSE pre POST.
   * - assert hasErrors reports TRUE following BAD data POST.
   * - assert 'errors' returns itterator and successfuly cycles corret number of errors in foreach.
   */
  public function testHasErrorsForeachErrorsProperyReturnValue()
  {
    try {
      $this->testObject->hasErros;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      try {
        $this->testObject->errors;
      } catch (\ramp\core\BadPropertyCallException $expected) {
        $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
        $this->data->aProperty = 'GOOD';
        $this->testObject->setModel($this->record->aProperty);
        $this->assertTrue($this->testObject->hasModel);
        $this->assertFalse($this->testObject->hasErrors);
        $this->record->validate(PostData::build(array('mock-record:1|1|1:a-property' => 'BadValue')));
        $this->assertTrue($this->testObject->hasErrors);
        $i = 0;
        foreach ($this->testObject->errors as $error) { $i++; }
        $this->assertSame(1, $i);
        return;
      }
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'isEditable' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'isEditable' prior to setting modal.
   * - assert 'isEditable' successfully return as expected.
   */
  public function testIsEditableProperyReturnValue()
  {
    try {
      $this->testObject->isEditable;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->testObject->setModel($this->record->aProperty);
      $this->assertTrue($this->testObject->hasModel);
      $this->assertTrue($this->testObject->isEditable);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'value' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `value` prior to setting modal.
   * - assert 'value' successfully return as expected.
   */
  public function testValueProperyReturnValue()
  {
    try {
      $this->testObject->value;
    } catch (\ramp\core\BadPropertyCallException $expencted) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertNull($this->testObject->value);
      $this->data->aProperty = 'GOOD';
      $this->record->updated();
      $this->assertEquals('GOOD', $this->testObject->value);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'isRequired' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `isRequired` prior to setting modal.
   * - assert `isRequired` successfully return as expected.
   */
  public function testIsRequieredReturnValue()
  {
    try {
      $this->testObject->isRequired;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->requiredProperty);
      $this->assertTrue($this->testObject->isRequired);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'inputType' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `inputType` prior to setting modal.
   * - assert `inputType` successfully return as expected for each of the following variants:
   *   - text
   *   - search
   *   - tel
   *   - url
   *   - email
   *   - password
   *   - date
   *   - month
   *   - week
   *   - time
   *   - datetime-local
   *   - number
   *   - range
   *   - color
   */
  public function testInputTypeReturnValue()
  {
    try {
      $this->testObject->inputType;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertEquals('text', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('search');
      $this->assertEquals('search', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('tel');
      $this->assertEquals('tel', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('url');
      $this->assertEquals('url', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('email');
      $this->assertEquals('email', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('password');
      $this->assertEquals('password', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('date');
      $this->assertEquals('date', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('month');
      $this->assertEquals('month', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('week');
      $this->assertEquals('week', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('time');
      $this->assertEquals('time', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('datetime-local');
      $this->assertEquals('datetime-local', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('number');
      $this->assertEquals('number', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('range');
      $this->assertEquals('range', $this->testObject->inputType);
      MockDbTypeValidation::$inputType = Str::set('color');
      $this->assertEquals('color', $this->testObject->inputType);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'pattern' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `pattern` prior to setting modal.
   * - assert return as set on Sub validation rule if set.
   * - assert returns NULL if unset.
   */
  public function testPatternReturnValue()
  {
    try {
      $this->testObject->pattern;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame(MockDbtypevalidation::$pattern, $this->testObject->pattern);
      MockDbtypevalidation::$pattern = NULL;
      $this->assertNull($this->testObject->pattern);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'minlength' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `minlength` prior to setting modal.
   * - assert return as set on Sub validation rule if set.
   * - assert returns NULL if unset.
   */
  public function testMinlengthReturnValue()
  {
    try {
      $this->testObject->minlength;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame(MockDbtypevalidation::$minlength, $this->testObject->minlength);
      MockDbtypevalidation::$minlength = NULL;
      $this->assertNull($this->testObject->minlength);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'maxlength' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `maxlength` prior to setting modal.
   * - assert return as set on Sub validation rule if set.
   * - assert returns NULL if unset.
   */
  public function testMaxlengthReturnValue()
  {
    try {
      $this->testObject->maxlength;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame(MockDbtypevalidation::$maxlength, $this->testObject->maxlength);
      MockDbtypevalidation::$maxlength = NULL;
      $this->assertNull($this->testObject->maxlength);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'min' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `min` prior to setting modal.
   * - assert return as set on Sub validation rule if set.
   * - assert returns NULL if unset.
   */
  public function testMinReturnValue()
  {
    try {
      $this->testObject->min;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame(MockDbtypevalidation::$min, $this->testObject->min);
      MockDbtypevalidation::$min = NULL;
      $this->assertNull($this->testObject->min);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'max' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `max` prior to setting modal.
   * - assert return as set on Sub validation rule if set.
   * - assert returns NULL if unset.
   */
  public function testMaxReturnValue()
  {
    try {
      $this->testObject->max;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame(MockDbtypevalidation::$max, $this->testObject->max);
      MockDbtypevalidation::$max = NULL;
      $this->assertNull($this->testObject->max);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'step' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `step` prior to setting modal.
   * - assert return as set on Sub validation rule if set.
   * - assert returns NULL if unset.
   */
  public function testStepReturnValue()
  {
    try {
      $this->testObject->step;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame(MockDbtypevalidation::$step, $this->testObject->step);
      MockDbtypevalidation::$step = NULL;
      $this->assertNull($this->testObject->step);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }

  /**
   * 'hint' property value mamagment and retrieval.
   * - assert throws \ramp\core\BadPropertyCallException when calling `hint` prior to setting modal.
   * - assert return as set on Sub validation rule.
   */
  public function testHintReturnValue()
  {
    try {
      $this->testObject->hint;
    } catch (\ramp\core\BadPropertyCallException $expected) {
      $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
      $this->record->updated();
      $this->testObject->setModel($this->record->aProperty);
      $this->assertSame($this->record->hint, $this->testObject->hint);
      return;
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }
  #endregion
}
