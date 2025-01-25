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

require_once '/usr/share/php/tests/ramp/view/document/DocumentViewTest.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/view/document/Templated.class.php';

use ramp\SETTING;
use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\view\RootView;
use ramp\view\document\Templated;

use tests\ramp\mocks\view\MockRecord;
use tests\ramp\mocks\view\MockViewA;
use tests\ramp\mocks\view\MockViewB;
use tests\ramp\mocks\view\MockViewC;

/**
 * Collection of tests for \ramp\view\ComplexView.
 */
class TemplatedTest extends \tests\ramp\view\document\DocumentViewTest
{
  private $templareName;
  private $templateType;

  #region Setup
  #[\Override]
  protected function preSetup() : void {
    SETTING::$DEV_MODE = TRUE;
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'tests\ramp\mocks\view';
    SETTING::$RAMP_LOCAL_DIR = getenv("HOME") . '/Projects/RAMP/local';
    if (!\str_contains(get_include_path(), SETTING::$RAMP_LOCAL_DIR)) {
      \set_include_path( "'" . SETTING::$RAMP_LOCAL_DIR . "'" . PATH_SEPARATOR . get_include_path());
    }  
    $this->templateName = Str::set('path');
    $this->templateType = Str::set('test');
    RootView::reset();
  }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new Templated(RootView::getInstance(), $this->templateName, $this->templateType); }
  #endregion

  /**
   * Default base constructor assertions \ramp\view\View::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\ChildView}
   * - assert is instance of {@see \ramp\view\ComplexView}
   * - assert is instance of {@see \ramp\view\document\DocumentView}
   * - assert is instance of {@see \ramp\view\document\Templated}
   * @see \ramp\view\document\Templated
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\document\Templated', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
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
  public function testSubViewAddition(string $parentRender = 'ramp\view\document\Templated') : void
  {
    $subCollection = new \SplObjectStorage();
    $subCollection->attach(new MockViewA());
    $subCollection->attach(new MockViewB());
    $subCollection->attach(new MockViewC());
    $i = 0;
    foreach ($subCollection as $subView) {
      $this->testObject->add($subView);
      $i++;
      ob_start();
      $this->testObject->render();
      $output = ob_get_clean();
      if ($i === 1)
      {
        $this->assertEquals(
          $parentRender .
          'tests\ramp\mocks\view\MockViewA ',
          $output
        );
      }
      if ($i === 2)
      {
        $this->assertEquals(
          $parentRender.
          'tests\ramp\mocks\view\MockViewA '.
          'tests\ramp\mocks\view\MockViewB ',
          $output
        );
      }
      if ($i === 3)
      {
        $this->assertEquals(
          $parentRender.
          'tests\ramp\mocks\view\MockViewA '.
          'tests\ramp\mocks\view\MockViewB '.
          'tests\ramp\mocks\view\MockViewC ',
          $output
        );
      }
    }
    $this->assertSame(3, $i);
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
  public function testComplexModelCascading(string $parentViewType = 'ramp\view\document\Templated', $templateName = NULL, $templateType = NULL) : void
  {
    $this->assertSame(
      SETTING::$RAMP_LOCAL_DIR . '/ramp/view/document/template/'. $this->templateType .'/'. $this->templateName . '.tpl.php',
      $this->testObject->template
    );
    parent::testComplexModelCascading($parentViewType, $this->templateName, $this->templateType);
  }

  /**
   * 'id' attribute managment and retrieval.
   * - assert default when not set or related to data field returns unique uid[number].
   * - assert with data returns expected URN [record]:[key]:[property]
   * - assert attribute('id') returns in expected format.
   */
  #[\Override]
  public function testIdPropertyReturenValue()
  {
    parent::testIdPropertyReturenValue();
    $this->assertEquals(' id="mock-record:1|1|1:a-property"', $this->testObject->attribute('id'));
  }

  /**
   * 'class' attribute managment and retrieval.
   * - assert 'style' setting adds to classlist
   * - assert model definition forms part of classlist as expected. 
   * - assert attribute('class') returns in expected format.
   */
  #[\Override]
  public function testClassStyleProperyReturnValue(Str $setStyle = NULL)
  {
    $setStyle = Str::set('default');
    $this->testObject->style = $setStyle;
    $this->assertEquals(' class="' . $setStyle . '"', $this->testObject->attribute('class'));

    parent::testClassStyleProperyReturnValue($setStyle);
    $this->assertEquals(' class="mock-input input ' . $setStyle . '"', $this->testObject->attribute('class'));
  }

  /**
   * title element value managment and retrieval.
   * - assert default value '[TITLE]'
   * - assert access setting through $this->title = $value.
   * - assert retrieval throught '$this->title' as expected.
   * - assert attribute('title') returns in expected format.
   */
  #[\Override]
  public function testTitlePropertyReturnValue(string $value = NULL)
  {
    $value = ($value === NULL) ? 'Full context descriptive title' : $value;
    parent::testTitlePropertyReturnValue($value);
    $this->assertEquals(' title="' . $value . '"', $this->testObject->attribute('title'));
  }

  /**
   * heading/label element value managment and retrieval.
   * - assert default value '[HEADING/LABEL]'
   * - assert when associated with a field, returns the field 'label' in expected format. 
   * - assert access setting through $this->label and $this->heading.
   * - assert retrieval throught either 'heading' or 'lable'
   * - assert attribute('heading/label') returns in expected format.
   */
  #[\Override]
  public function testHeadingLabelProperyReturnValue()
  {
    parent::testHeadingLabelProperyReturnValue();
    $this->assertEquals(' heading="My Heading"', $this->testObject->attribute('heading'));
    $this->assertEquals(' label="My Heading"', $this->testObject->attribute('label'));
  }

  /**
   * summary/placeholder element value managment and retrieval.
   * - assert default value '[SUMMARY/PLACEHOLDER]'
   * - assert access setting through $this->summary and $this->placeholder.
   * - assert retrieval throught either 'summary' or 'placeholder'
   * - assert attribute('summary/placeholder') returns in expected format.
   * @todo:mrenyard: check for NULL return on inputType != text|search|url|tel|email|password
   */
  #[\Override]
  public function testSummaryPlaceholderProperyReturnValue()
  {
    parent::testSummaryPlaceholderProperyReturnValue();
    $this->assertEquals(' summary="My Placeholder"', $this->testObject->attribute('summary'));
    $this->assertEquals(' placeholder="My Placeholder"', $this->testObject->attribute('placeholder'));
  }

  /**
   * extendedSummary element value managment and retrieval.
   * - assert default value NULL.
   * - assert access setting through $this->extendedSummary.
   * - assert retrieval throught either throught 'extendedSummary'.
   * - assert attribute('extendedSummary') throws \BadMethodCallException 
   *   - with message: *'extendedSummary is NOT avalible as an HTML attribute!'*.
   */
  #[\Override]
  public function testExtendedSummaryProperyReturnValue()
  {
    parent::testExtendedSummaryProperyReturnValue();
    try {
      $this->testObject->attribute('extendedSummary');
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals(
        'extendedSummary is NOT avalible in attribute format!',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * extendedContent element value managment and retrieval.
   * - assert default value NULL.
   * - assert access setting through $this->extendedContent.
   * - assert retrieval throught either throught 'extendedContent'.
   * - assert attribute('extendedContent') throws \BadMethodCallException 
   *   - with message: *'extendedContent is NOT avalible as an HTML attribute!'*.
   */
  #[\Override]
  public function testExtendedContentProperyReturnValue()
  {
    parent::testExtendedContentProperyReturnValue();
    try {
      $this->testObject->attribute('extendedContent');
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals(
        'extendedContent is NOT avalible in attribute format!',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * footnote element value managment and retrieval.
   * - assert default value NULL.
   * - assert access setting through $this->footnote.
   * - assert retrieval throught either throught 'footnote'.
   * - assert attribute('footnote') throws \BadMethodCallException 
   *   - with message: *'footnote is NOT avalible as an HTML attribute!'*.
   */
  #[\Override]
  public function testFootnoteProperyReturnValue()
  {
    parent::testFootnoteProperyReturnValue();
    try {
      $this->testObject->attribute('footnote');
    } catch (\BadMethodCallException $expected) {
      $this->assertEquals(
        'footnote is NOT avalible in attribute format!',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * 'type' property value mamagment and retrieval.
   * - assert throws \BadMethodCallException when calling attribute('type') prior to setting modal.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'type' prior to setting modal.
   * - assert hasModel prior setting 'setModel' returns FALSE.
   * - assert hasModel post setting 'setModel' returns TRUE.
   * - assert 'type' successfuly return as expected based on associated model.
   * - assert attribute('type') returns in expected format.
   */
  #[\Override]
  public function testHasModelTypePropertyReturnValue()
  {
    try {
      $this->testObject->attribute('type');
    } catch(\BadMethodCallException $expected) {
      parent::testHasModelTypePropertyReturnValue();
      $this->assertEquals(' type="mock-input input"', $this->testObject->attribute('type'));
      return;
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * 'errors' property value mamagment and retrieval.
   * - assert throws \BadMethodCallException when calling attribute('errors') both prior and post setting modal.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'hasErrors' prior to setting modal.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'errors' prior to setting modal.
   * - assert hesErrors successfully reports FALSE pre POST.
   * - assert hasErrors reports TRUE following BAD data POST.
   * - assert 'errors' returns itterator and successfuly cycles corret number of errors in foreach.
   */
  #[\Override]
  public function testHasErrorsForeachError()
  {
    try {
      $this->testObject->attribute('errors');
    } catch(\BadMethodCallException $expected) {
      parent::testHasErrorsForeachError();
      try {
        $this->testObject->attribute('errors');
      } catch (\BadMethodCallException $expected) {
        return;
      }
    }
    $this->fail('An expected \BadMethodCallException has NOT been raised');
  }

  /**
   * 'isEditable' property value mamagment and retrieval.
   * - assert throws \BadMethodCallException when calling attribute('isEditable') both prior and post setting modal.
   * - assert throws \ramp\core\BadPropertyCallException when calling 'isEditable' prior to setting modal.
   * - assert 'isEditable' successfully return as expected.
   */
  #[\Override]
  public function testIsEditable()
  {
    try {
      $this->testObject->attribute('isEditable');
    } catch (\BadMethodCallException $expected) {
      parent::testIsEditable();
      try {
        $this->testObject->attribute('isEditable');
      } catch (\BadMethodCallException $expected) {
        return;
      }
    }
    $this->fail('An expected \ramp\core\BadPropertyCallException has NOT been raised');
  }
  #endregion

  #region New Specialist Tests
  /**
   * 
   */
  public function testAttributeRequiredOutput()
  {
    $this->testObject->setModel($this->record->aProperty);
    $this->assertFalse($this->testObject->isRequired);
    $this->assertNull($this->testObject->attribute('required'));

    $recordWithRequiredFields = new MockRecord(NULL, TRUE);
    $testObject2 = new Templated(RootView::getInstance(), $this->templateName, $this->templateType);
    $testObject2->setModel($recordWithRequiredFields->aProperty);
    $this->assertTrue($recordWithRequiredFields->aProperty->isRequired);
    $this->assertEquals(' required="required"', $testObject2->attribute('required'));
  }

  public function testAttributeValueOutput()
  {
    $this->testObject->setModel($this->record->aProperty);
    $this->assertNull($this->testObject->attribute('value'));
  }

  /**
   * Constructor bad template path exception test.
   * - assert throws InvalidArgumentException when arguments do NOT map to a valid template file.
   */
  public function testConstructInvalidArgumentException()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Provided $templateName (NotATemplate) of $templateType (test) is non existant!');
    new Templated(RootView::getInstance(), Str::set('NotATemplate'), $this->templateType);
  }

  /**
   * Alow template type swithiching through set templateType.
   * - assert get $this->template returns valid template full path.
   * - assert changing templateType changes to relevant new full path.
   * - assert exeption throws PropertyNotSetException when teplate file missing and reverts to last valid path.
   */
  public function testSwitchTemplateType()
  {
    $this->assertSame(
      SETTING::$RAMP_LOCAL_DIR . '/ramp/view/document/template/'. $this->templateType .'/'. $this->templateName . '.tpl.php',
      $this->testObject->template
    );
    $this->testObject->templateType = Str::set('html');
    $this->assertSame(
      SETTING::$RAMP_LOCAL_DIR . '/ramp/view/document/template/html/'. $this->templateName . '.tpl.php',
      $this->testObject->template
    );
    $this->expectException(PropertyNotSetException::class);
    $this->expectExceptionMessage('Provided $templateName (path) of $templateType (text) is non existant!');
    $this->testObject->templateType = Str::set('text');
    $this->assertSame(
      SETTING::$RAMP_LOCAL_DIR . '/ramp/view/document/template/html/'. $this->templateName . '.tpl.php',
      $this->testObject->template
    );    
  }

  /**
   * Alow template type swithiching through set templateType.
   * - assert get $this->template returns valid template full path.
   * - assert changing templateType changes to relevant new full path.
   * - assert exeption throws PropertyNotSetException when teplate file missing and reverts to last valid path.
   */
  public function testRender()
  {
    $this->testObject->templateType = Str::set('html');
    ob_start();
    $this->testObject->render();
    $output = ob_get_clean();
    $this->assertSame(
      '<!-- /home/mrenyard/Projects/RAMP/local/ramp/view/document/template/html/path.tpl.php -->' . PHP_EOL .
      'ramp\view\document\Templated',
      $output
    );
  }
  #endregion
}
