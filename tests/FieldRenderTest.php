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
namespace tests\ramp;

require_once '/usr/share/php/tests/ramp/TestBase.php';

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/HexidecimalColorCode.class.php';

require_once '/usr/share/php/tests/ramp/mocks/model/ComprehensiveRecord.class.php';

use tests\ramp\TestBase;

use ramp\SETTING;
use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\view\RootView;
use ramp\view\document\Templated;

use tests\ramp\mocks\model\ComprehensiveRecord;

class FieldRenderTest extends TestBase
{
  private $data;
  private $record;
  protected $testObject;

  #region Setup
  protected function preSetup() : void {
    SETTING::$RAMP_LOCAL_DIR = '/home/mrenyard/Projects/RAMP/local';
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = '\tests\ramp\mocks\model';
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
   * Record level view data.
   */
  public function testRecord()
  { 
    $record = new Templated(RootView::getInstance(), Str::set('record'), Str::set('html'));
    $record->setModel($this->testObject);
    $this->assertSame('1|1|1', $this->testObject->primaryKey->value);
    $this->assertSame('comprehensive-record record', (string)$this->testObject->type);
    $record->style = Str::set('compact');
    $this->assertSame('comprehensive-record record compact', (string)$record->class);
    $this->assertSame(' class="comprehensive-record record compact"', (string)$record->attribute('class'));
    $this->assertSame('comprehensive-record:1|1|1', (string)$this->testObject->id);
    $this->assertSame('comprehensive-record:1|1|1', (string)$record->id);
    $this->assertSame(' id="comprehensive-record:1|1|1"', (string)$record->attribute('id'));
  }

  /**
   * Check rendered output of 'color input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldColorRender()
  {
    $this->data->primaryColor = '#20771E';
    $parentView = RootView::getInstance();
    $view = new Templated($parentView, Str::set('input'));
    $view->setModel($this->testObject->primaryColor);
    $view->style = Str::set('compact');
    $view->label = Str::set('Primary Colour');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $view->title = Str::set('Primary colour, main identifiable brand colour, the core colour, commonly incorporated into a companies logo.');
    $this->assertSame(
      ' title="Primary colour, main identifiable brand colour, the core colour, commonly incorporated into a companies logo."',
      (string)$view->attribute('title')
    );
    $this->assertSame('Primary Colour', (string)$view->label);
    ob_start();
    $parentView->render();
    $output = ob_get_clean();
    $this->assertSame(
      '<div class="color input field compact" title="Primary colour, main identifiable brand colour, the core colour, commonly incorporated into a companies logo.">' . PHP_EOL .
      '          <label for="comprehensive-record:1|1|1:primary-color">Primary Colour</label>' . PHP_EOL .
      '          <input id="comprehensive-record:1|1|1:primary-color" name="comprehensive-record:1|1|1:primary-color" type="color" tabindex="0" value="#20771E" />' . PHP_EOL .
      '        </div>',
      $output
    );
  }

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
    $this->data->givenName = 'Matt';
    $parentView = RootView::getInstance();
    $view = new Templated($parentView, Str::set('input'));
    $view->setModel($this->testObject->givenName);
    $view->style = Str::set('compact');
    $view->label = Str::set('First Name');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $view->title = Str::set('The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters');
    $this->assertSame(
      ' title="The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters"',
      (string)$view->attribute('title')
    );
    $view->placeholder = Str::set('e.g. John');
    $this->assertSame(' placeholder="e.g. John"', (string)$view->attribute('placeholder'));
    $this->assertSame('First Name', (string)$view->label);
    ob_start();
    $parentView->render();
    $output = ob_get_clean();
    $this->assertSame(
      '<div class="text input field compact required" title="The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters">' . PHP_EOL .
      '          <label for="comprehensive-record:1|1|1:given-name">First Name</label>' . PHP_EOL .
      '          <input id="comprehensive-record:1|1|1:given-name" name="comprehensive-record:1|1|1:given-name" type="text" tabindex="0" placeholder="e.g. John" required="required" pattern="[A-Za-z]{0,20}" maxlength="20" value="Matt" />' . PHP_EOL .
      '        </div>',
      $output
    );
  }
}