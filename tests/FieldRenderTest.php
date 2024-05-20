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
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Integer.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/TinyInt.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Flag.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FormatBasedValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/HexidecimalColorCode.class.php';
require_once '/usr/share/php/ramp/model/business/validation/TelephoneNumber.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Password.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOWeek.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOMonth.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOTime.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISODate.class.php';
require_once '/usr/share/php/ramp/model/business/validation/DateTimeLocal.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/MultipartInput.class.php';

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
    SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'ramp\model\business'; //'\tests\ramp\mocks\model';
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
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->primaryColor);
    $view->style = Str::set('compact');
    $view->label = Str::set('Primary Colour');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('color', (string)$view->inputType);
    $view->title = Str::set('Primary colour, main identifiable brand colour, the core colour, commonly incorporated into a companies logo.');
    $this->assertSame(
      ' title="Primary colour, main identifiable brand colour, the core colour, commonly incorporated into a companies logo."',
      (string)$view->attribute('title')
    );
    $this->assertSame('Primary Colour', (string)$view->label);
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="color input field compact" title="Primary colour, main identifiable brand colour, the core colour, commonly incorporated into a companies logo.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:primary-color">Primary Colour</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:primary-color" name="comprehensive-record:1|1|1:primary-color" type="color" tabindex="0" value="#20771E" />' . PHP_EOL .
      '            <span class="hint">representing the luminescent gradiant of red, green and blue, a hash followed by three pairs of hexadecimal (0 through 9 to F) character character length must be exactly 7</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
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
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->givenName);
    $view->style = Str::set('compact');
    $view->label = Str::set('First Name');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('text', (string)$view->inputType);
    $view->title = Str::set('The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters');
    $this->assertSame(
      ' title="The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters"',
      (string)$view->attribute('title')
    );
    $view->placeholder = Str::set('e.g. John');
    $this->assertSame(' placeholder="e.g. John"', (string)$view->attribute('placeholder'));
    $this->assertSame('First Name', (string)$view->label);
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="text input field compact required" title="The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:given-name">First Name</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:given-name" name="comprehensive-record:1|1|1:given-name" type="text" tabindex="0" placeholder="e.g. John" required="required" pattern="([A-Za-z]*){0,20}" maxlength="20" value="Matt" />' . PHP_EOL .
      '            <span class="hint">single word with only latin alphabet charactered string with a maximum charactor length of 20</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'tel input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldTelRender()
  {
    $this->data->mobile = '07744 123123';
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->mobile);
    $view->style = Str::set('compact');
    $view->label = Str::set('Mobile Number');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('tel', (string)$view->inputType);
    $view->title = Str::set('The series of numbers that you dial when you are making a telephone call to a mobile phone');
    $this->assertSame(
      ' title="The series of numbers that you dial when you are making a telephone call to a mobile phone"',
      (string)$view->attribute('title')
    );
    $view->placeholder = Str::set('e.g. 07744 123456');
    $this->assertSame(' placeholder="e.g. 07744 123456"', (string)$view->attribute('placeholder'));
    $this->assertSame('Mobile Number', (string)$view->label);
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="tel input field compact required" title="The series of numbers that you dial when you are making a telephone call to a mobile phone">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:mobile">Mobile Number</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:mobile" name="comprehensive-record:1|1|1:mobile" type="tel" tabindex="0" placeholder="e.g. 07744 123456" required="required" pattern="(?:\+[1-9]{1,3} ?\(0\)|\+[1-9]{1,3} ?|0)[0-9\- ]{8,12}" maxlength="20" value="07744 123123" />' . PHP_EOL .
      '            <span class="hint">valid telephone number string with a maximum charactor length of 20</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'tel input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldPasswordRender()
  {
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->password);
    $view->style = Str::set('compact');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('password', (string)$view->inputType);
    $view->title = Str::set('A word, phrase, or string of characters intended to differentiate you as an authorized user for the purpose of permitting access');
    $this->assertSame(
      ' title="A word, phrase, or string of characters intended to differentiate you as an authorized user for the purpose of permitting access"',
      (string)$view->attribute('title')
    );
    $view->placeholder = Str::set('e.g. N0T-Pa55W0rd');
    $this->assertSame(' placeholder="e.g. N0T-Pa55W0rd"', (string)$view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="password input field compact required" title="A word, phrase, or string of characters intended to differentiate you as an authorized user for the purpose of permitting access">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:password">Password</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:password" name="comprehensive-record:1|1|1:password" type="password" tabindex="0" placeholder="e.g. N0T-Pa55W0rd" required="required" pattern="[a-zA-Z0-9!#$%&\(\)+,-\.:;<=>?\[\]\^*_\{\|\}\{~@ ]{8,35}" maxlength="35" value="" />' . PHP_EOL .
      '            <span class="hint">8 charactor minimum alphanumeric and special charactors (!#$%&+,-.:;<=>?[]^*_{|}{~@\') string with a maximum charactor length of 35</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'number input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldNumberRender()
  {
    $this->data->wholeNumber = '365';
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->wholeNumber);
    $view->style = Str::set('compact');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('number', (string)$view->inputType);
    $view->title = Str::set('A whole number (not a fractional number) that can be positive, negative, or zero');
    $this->assertSame(
      ' title="A whole number (not a fractional number) that can be positive, negative, or zero"',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="number input field compact required" title="A whole number (not a fractional number) that can be positive, negative, or zero">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:whole-number">Whole Number</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:whole-number" name="comprehensive-record:1|1|1:whole-number" type="number" tabindex="0" required="required" min="-32768" max="32767" step="1" value="365" />' . PHP_EOL .
      '            <span class="hint">whole number from -32768 to 32767</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'number input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldCurrencyRender()
  {
    $this->data->currency = '365.72';
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->currency);
    $view->style = Str::set('compact');
    $view->label = Str::set('Account Balance');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('number', (string)$view->inputType);
    $view->title = Str::set('The amount of money present in your primary named account during the current accounting period in UK pounds sterling.');
    $this->assertSame(
      ' title="The amount of money present in your primary named account during the current accounting period in UK pounds sterling."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="number input field compact required" title="The amount of money present in your primary named account during the current accounting period in UK pounds sterling.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:currency">Account Balance</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:currency" name="comprehensive-record:1|1|1:currency" type="number" tabindex="0" required="required" min="0" max="999.99" step="0.01" value="365.72" />' . PHP_EOL .
      '            <span class="hint">2 place decimal point number</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'week input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldISOWeekRender()
  {
    $this->data->weekYear = 2024;
    $this->data->weekNumber = 2;
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->week);
    $view->style = Str::set('compact');
    $view->label = Str::set('Preferred install week');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('week', (string)$view->inputType);
    $view->title = Str::set('The preferred week of fiber optic broadband installation.');
    $this->assertSame(
      ' title="The preferred week of fiber optic broadband installation."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="week input field compact required" title="The preferred week of fiber optic broadband installation.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:week">Preferred install week</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:week" name="comprehensive-record:1|1|1:week" type="week" tabindex="0" required="required" pattern="[0-9]{4}-W(?:0[1-9]|[1-4][0-9]|5[0-3]){1}" min="2024-W06" max="2024-W52" step="any" value="2024-W02" />' . PHP_EOL .
      '            <span class="hint">valid week formated (yyyy-W00) from 2024-W06 to 2024-W52 total charactors: 8</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'month input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldISOMonthRender()
  {
    $this->data->monthYear = 2024;
    $this->data->monthNumber = 8;
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->month);
    $view->style = Str::set('compact');
    $view->label = Str::set('Target release');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('month', (string)$view->inputType);
    $view->title = Str::set('The target month for the next release edition of our software.');
    $this->assertSame(
      ' title="The target month for the next release edition of our software."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="month input field compact required" title="The target month for the next release edition of our software.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:month">Target release</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:month" name="comprehensive-record:1|1|1:month" type="month" tabindex="0" required="required" pattern="([0-9]{4}-(?:0[1-9]|1[0-2])){7}" min="2024-01" max="2024-12" step="1" value="2024-08" />' . PHP_EOL .
      '            <span class="hint">valid month formated (yyyy-mm) from 2024-01 to 2024-12 total charactors: 7</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'time input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldTimeRender()
  {
    $this->data->time = '16:30';
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->time);
    $view->style = Str::set('compact');
    $view->label = Str::set('Start Time');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('time', (string)$view->inputType);
    $view->title = Str::set('Scheduled start time for this appointment.');
    $this->assertSame(' title="Scheduled start time for this appointment."', (string)$view->attribute('title'));
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="time input field compact required" title="Scheduled start time for this appointment.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:time">Start Time</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:time" name="comprehensive-record:1|1|1:time" type="time" tabindex="0" required="required" pattern="(?:[0,1][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?" min="08:30" max="17:30" step="1800" value="16:30" />' . PHP_EOL .
      '            <span class="hint">an appointment slot avalible ever 30min from 08:30 to 17:30 valid time formated (hh:mm[:ss])</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'date input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldDateRender()
  {
    $this->data->date = '2024-03-04'; // born yesterday!
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->date);
    $view->style = Str::set('compact');
    $view->label = Str::set('Date of Birth');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('date', (string)$view->inputType);
    $view->title = Str::set('The month, day, and year of of your birth.');
    $this->assertSame(' title="The month, day, and year of of your birth."', (string)$view->attribute('title'));
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="date input field compact required" title="The month, day, and year of of your birth.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:date">Date of Birth</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:date" name="comprehensive-record:1|1|1:date" type="date" tabindex="0" required="required" pattern="[0-9]{4}-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1])" min="1900-01-01" max="2023-12-31" step="1" value="2024-03-04" />' . PHP_EOL .
      '            <span class="hint">date of birth from 1900-01-01 to 2023-12-31 valid date formated (yyyy-mm-dd)</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /**
   * Check rendered output of 'datetime-local input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldDateTimeRender()
  {
    $this->data->datetime = '2024-03-04T23:59:59'; // Days end!
    $view = new Templated(RootView::getInstance(), Str::set('input'));
    $view->setModel($this->testObject->datetime);
    $view->style = Str::set('compact');
    $view->label = Str::set('Event start');
    $this->assertSame('input field', (string)$view->type);
    $this->assertSame('input field compact', (string)$view->class);
    $this->assertSame('datetime-local', (string)$view->inputType);
    $view->title = Str::set('The month, day, year, hour and minte of the start of the event.');
    $this->assertSame(' title="The month, day, year, hour and minte of the start of the event."', (string)$view->attribute('title'));
    $this->assertNull($view->attribute('placeholder'));
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <div class="datetime-local input field compact required" title="The month, day, year, hour and minte of the start of the event.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:datetime">Event start</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:datetime" name="comprehensive-record:1|1|1:datetime" type="datetime-local" tabindex="0" required="required" pattern="[0-9]{4}-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1])T(?:[0,1][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?" min="2024-03-05T00:00" max="2025-09-30T00:00" step="60" value="2024-03-04T23:59:59" />' . PHP_EOL .
      '            <span class="hint">Start date and time of your event within the next 18 months, form  from 2024-03-05T00:00 to 2025-09-30T00:00 valid date time formated (yyyy-mm-ddThh:mm:ss)</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output
    );
  }

  /*
   * Check rendered output of 'checkbox input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldFlagRender()
  {
    $this->data->flag = TRUE;
    $view = new Templated(RootView::getInstance(), Str::set('checkbox-fieldset'));
    $view->setModel($this->testObject->flag);
    $view->style = Str::set('compact');
    $view->label = Str::set('Terms &amp; Conditions');
    $this->assertSame('flag field', (string)$view->type);
    $this->assertSame('flag field compact', (string)$view->class);
    $view->title = Str::set('Please agree to our terms and conditions to continue to use this site.');
    $this->assertSame(' title="Please agree to our terms and conditions to continue to use this site."', (string)$view->attribute('title'));
    $view->summary = Str::set('I have read and agree to site terms and conditions.');
    $this->assertSame('I have read and agree to site terms and conditions.', (string)$view->summary);
    ob_start();
    $view->render();
    $output = ob_get_clean();
    $this->assertSame(
      '          <fieldset class="flag field compact required" title="Please agree to our terms and conditions to continue to use this site.">' . PHP_EOL .
      '            <legend>Terms &amp; Conditions</legend>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:flag" name="comprehensive-record:1|1|1:flag:true" type="checkbox" tabindex="0" required="required" checked="checked" />' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:flag">I have read and agree to site terms and conditions.</label>' . PHP_EOL .
      '          </fieldset>' . PHP_EOL . '',
      $output
    );
  }
}