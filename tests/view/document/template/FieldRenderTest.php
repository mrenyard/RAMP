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

require_once '/usr/share/php/tests/ramp/TestBase.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/Document.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FailedValidationException.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Integer.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/TinyInt.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Flag.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Char.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FormatBasedValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowercaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/HexidecimalColorCode.class.php';
require_once '/usr/share/php/ramp/model/business/validation/TelephoneNumber.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Password.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOWeek.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOMonth.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOTime.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISODate.class.php';
require_once '/usr/share/php/ramp/model/business/validation/DateTimeLocal.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/MultipartInput.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/RootView.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';
require_once '/usr/share/php/ramp/view/ComplexView.class.php';
require_once '/usr/share/php/ramp/view/document/DocumentView.class.php';
require_once '/usr/share/php/ramp/view/document/Templated.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';

require_once '/usr/share/php/tests/ramp/mocks/view/ComprehensiveRecord.class.php';

use tests\ramp\TestBase;

use ramp\SETTING;
use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\view\RootView;
use ramp\view\document\Templated;
use \ramp\condition\PostData;

use ramp\model\business\ComprehensiveRecord;

class FieldRenderTest extends TestBase
{
  private $data;
  private $record;
  protected $testObject;

  #region Setup
  protected function preSetup() : void {
    SETTING::$DEV_MODE = TRUE;
    SETTING::$RAMP_LOCAL_DIR = '/home/mrenyard/Projects/RAMP/local';
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'ramp\model\business';
    if (!\str_contains(get_include_path(), SETTING::$RAMP_LOCAL_DIR)) {
      \set_include_path( "'" . SETTING::$RAMP_LOCAL_DIR . "'" . PATH_SEPARATOR . get_include_path());
    }  
    RootView::reset();
    $this->data = new \stdClass();
    $this->data->keyA = 1; $this->data->keyB = 1; $this->data->keyC = 1;
  }
  protected function getTestObject() : RAMPObject { return new ComprehensiveRecord($this->data); }
  protected function postSetup() : void {  }
  #endregion

  /**
   * Check rendered output of 'text input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldTextRender()
  {
    $this->data->text = 'Matt';
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->text);
    $view->style = Str::set('compact');
    $this->assertEquals('Text', $view->label);
    $view->label = Str::set('Given Name');
    $this->assertSame('Given Name', (string)$view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('text', $view->inputType);
    $this->assertEquals(
      ' title="The name by which you are refered by, in western culture usually your first name."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(' placeholder="e.g. John"', $view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/input.tpl.php -->' . PHP_EOL .
      '          <div class="text input field compact required" title="The name by which you are refered by, in western culture usually your first name.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:text">Given Name</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:text" name="comprehensive-record:1|1|1:text" type="text" tabindex="0" placeholder="e.g. John" required="required" pattern="([A-Za-z]*){0,20}" maxlength="20" value="Matt" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:text' => 100 // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="text input field compact required error" title="The name by which you are refered by, in western culture usually your first name.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:text">Given Name</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:text" name="comprehensive-record:1|1|1:text" type="text" tabindex="1" placeholder="e.g. John" required="required" pattern="([A-Za-z]*){0,20}" maxlength="20" value="" />' . PHP_EOL .
      '            <span class="hint">single word with only latin alphabet charactered string with a maximum charactor length of 20. <em>Previous value was: Matt.</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }
}