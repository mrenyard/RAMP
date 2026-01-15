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
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
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
require_once '/usr/share/php/ramp/model/business/validation/dbtype/SmallInt.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Flag.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Char.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Date.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/Time.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DateTime.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DecimalPointNumber.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/FormatBasedValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowercaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/HexidecimalColorCode.class.php';
require_once '/usr/share/php/ramp/model/business/validation/TelephoneNumber.class.php';
require_once '/usr/share/php/ramp/model/business/validation/WebAddressURL.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexEmailAddress.class.php';
require_once '/usr/share/php/ramp/model/business/validation/Password.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOWeek.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOMonth.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISOTime.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ISODate.class.php';
require_once '/usr/share/php/ramp/model/business/validation/DateTimeLocal.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/SpecialistValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/specialist/ServerSideEmail.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Flag.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectMany.class.php';
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
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->text);
    $view->style = Str::set('compact');
    $this->assertEquals('Text', $view->label);
    $view->label = Str::set('Given Name');
    $this->assertEquals('Given Name', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('text', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="The name by which you are refered by, in western culture usually your first name."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(' placeholder="e.g. John"', $view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="text input field compact required" title="The name by which you are refered by, in western culture usually your first name.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:text">Given Name</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:text" name="comprehensive-record:1|1|1:text" type="text" tabindex="0" placeholder="e.g. John" required="required" pattern="([A-Za-z]*){0,20}" maxlength="20" value="Matt" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
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
      '            <span class="hint">single word with only latin alphabet charactered string with a maximum character length of 20. <em>Previous value was: Matt</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
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
    $this->data->tel = '01234 567 890';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->tel);
    $view->style = Str::set('compact');
    $this->assertEquals('Tel', $view->label);
    $view->label = Str::set('Mobile');
    $this->assertEquals('Mobile', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('tel', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="The number used to contact (call or text) said particular persons mobile device."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(' placeholder="e.g. 0234 567 891"', $view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="tel input field compact required" title="The number used to contact (call or text) said particular persons mobile device.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:tel">Mobile</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:tel" name="comprehensive-record:1|1|1:tel" type="tel" tabindex="0" placeholder="e.g. 0234 567 891" required="required" pattern="(?:\+[1-9]{1,3} ?\(0\)|\+[1-9]{1,3} ?|0)[0-9\- ]{8,12}" maxlength="20" value="01234 567 890" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:tel' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="tel input field compact required error" title="The number used to contact (call or text) said particular persons mobile device.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:tel">Mobile</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:tel" name="comprehensive-record:1|1|1:tel" type="tel" tabindex="1" placeholder="e.g. 0234 567 891" required="required" pattern="(?:\+[1-9]{1,3} ?\(0\)|\+[1-9]{1,3} ?|0)[0-9\- ]{8,12}" maxlength="20" value="" />' . PHP_EOL .
      '            <span class="hint">valid telephone number string with a maximum character length of 20. <em>Previous value was: 01234 567 890</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'url input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldUrlRender()
  {
    $this->data->url = 'https://www.rampapp.info/';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->url);
    $view->style = Str::set('compact');
    $this->assertEquals('Url', $view->label);
    $view->label = Str::set('Web address');
    $this->assertEquals('Web address', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('url', (string)$view->inputType);
    $this->assertFalse($view->isRequired);
    $this->assertEquals(
      ' title="The Uniform Resource Locator (URL) that points to a webpage you would like to visit."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(' placeholder="e.g. https://www.domain-name.com/top-article"', $view->attribute('placeholder'));
    $this->assertNull($view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="url input field compact" title="The Uniform Resource Locator (URL) that points to a webpage you would like to visit.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:url">Web address</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:url" name="comprehensive-record:1|1|1:url" type="url" tabindex="0" placeholder="e.g. https://www.domain-name.com/top-article" pattern="((https:\/\/[a-z0-9-\.]+)?\/([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&amp;([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?){0,150}" maxlength="150" value="https://www.rampapp.info/" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:url' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="url input field compact error" title="The Uniform Resource Locator (URL) that points to a webpage you would like to visit.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:url">Web address</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:url" name="comprehensive-record:1|1|1:url" type="url" tabindex="1" placeholder="e.g. https://www.domain-name.com/top-article" pattern="((https:\/\/[a-z0-9-\.]+)?\/([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&amp;([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?){0,150}" maxlength="150" value="" />' . PHP_EOL .
      '            <span class="hint">a validly formatted web address (URL) string with a maximum character length of 150. <em>Previous value was: https://www.rampapp.info/</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'email input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldEmailRender()
  {
    $this->data->email = 'renyard.m@gmail.com';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->email);
    $view->style = Str::set('compact');
    $this->assertEquals('Email', $view->label);
    $view->label = Str::set('E-mail');
    $this->assertEquals('E-mail', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('email', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="A uniquely identified electronic mailbox at which you receive written messages."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(' placeholder="e.g. jsmith@domain.com"', $view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="email input field compact required" title="A uniquely identified electronic mailbox at which you receive written messages.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:email">E-mail</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:email" name="comprehensive-record:1|1|1:email" type="email" tabindex="0" placeholder="e.g. jsmith@domain.com" required="required" pattern="[a-zA-Z0-9\._%+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}" maxlength="150" value="renyard.m@gmail.com" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:email' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="email input field compact required error" title="A uniquely identified electronic mailbox at which you receive written messages.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:email">E-mail</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:email" name="comprehensive-record:1|1|1:email" type="email" tabindex="1" placeholder="e.g. jsmith@domain.com" required="required" pattern="[a-zA-Z0-9\._%+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}" maxlength="150" value="" />' . PHP_EOL .
      '            <span class="hint">validly formatted email address string with a maximum character length of 150. <em>Previous value was: renyard.m@gmail.com</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'password input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldPasswordRender()
  {
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->password);
    $view->style = Str::set('compact');
    $this->assertEquals('Password', $view->label);
    $view->label = Str::set('Passphrase');
    $this->assertEquals('Passphrase', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('password', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="The secret word or phrase that you wish to used to confirm your identity and gain access."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(' placeholder="e.g. N0t-Pa55w0rd!"', $view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="password input field compact required" title="The secret word or phrase that you wish to used to confirm your identity and gain access.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:password">Passphrase</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:password" name="comprehensive-record:1|1|1:password" type="password" tabindex="0" placeholder="e.g. N0t-Pa55w0rd!" required="required" pattern="[a-zA-Z0-9!#$%&\(\)+,-\.:;?\[\]\^*_\{\|\}\{~@ ]{8,75}" maxlength="75" value="" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:password' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="password input field compact required error" title="The secret word or phrase that you wish to used to confirm your identity and gain access.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:password">Passphrase</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:password" name="comprehensive-record:1|1|1:password" type="password" tabindex="1" placeholder="e.g. N0t-Pa55w0rd!" required="required" pattern="[a-zA-Z0-9!#$%&\(\)+,-\.:;?\[\]\^*_\{\|\}\{~@ ]{8,75}" maxlength="75" value="" />' . PHP_EOL .
      '            <span class="hint">8 character minimum alphanumeric and special characters (!#$%&+,-.:;<=>?[]^*_{|}{~@\') string with a maximum character length of 75.</span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
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
    $this->data->date = '2023-06-21';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->date);
    $view->style = Str::set('compact');
    $this->assertEquals('Date', $view->label);
    $view->label = Str::set('Date of Birth');
    $this->assertEquals('Date of Birth', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('date', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="The day, month, and year that you were born."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="date input field compact required" title="The day, month, and year that you were born.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:date">Date of Birth</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:date" name="comprehensive-record:1|1|1:date" type="date" tabindex="0" required="required" min="1900-01-01" max="2023-12-31" step="1" value="2023-06-21" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:date' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="date input field compact required error" title="The day, month, and year that you were born.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:date">Date of Birth</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:date" name="comprehensive-record:1|1|1:date" type="date" tabindex="1" required="required" min="1900-01-01" max="2023-12-31" step="1" value="" />' . PHP_EOL .
      '            <span class="hint">valid ISO formated date from 1900-01-01 to 2023-12-31 (yyyy-mm-dd) date of birth. <em>Previous value was: 2023-06-21</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
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
  public function testFieldMonthRender()
  {
    $this->data->monthYear = '2024';
    $this->data->monthNumber = '10';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->month);
    $view->style = Str::set('compact');
    $this->assertEquals('Month', $view->label);
    $view->label = Str::set('Estimated Month of Conception');
    $this->assertEquals('Estimated Month of Conception', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('month', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="An estimate of when a pregnancy began, based on the first day of the last menstrual period (LMP)."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="month input field compact required" title="An estimate of when a pregnancy began, based on the first day of the last menstrual period (LMP).">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:month">Estimated Month of Conception</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:month" name="comprehensive-record:1|1|1:month" type="month" tabindex="0" required="required" min="2024-01" max="2024-12" step="1" value="2024-10" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:month' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="month input field compact required error" title="An estimate of when a pregnancy began, based on the first day of the last menstrual period (LMP).">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:month">Estimated Month of Conception</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:month" name="comprehensive-record:1|1|1:month" type="month" tabindex="1" required="required" min="2024-01" max="2024-12" step="1" value="" />' . PHP_EOL .
      '            <span class="hint">valid ISO formated month from 2024-01 to 2024-12 (yyyy-mm) total characters: 7. <em>Previous value was: 2024-10</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
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
  public function testFieldWeekRender()
  {
    $this->data->weekYear = '2024';
    $this->data->weekNumber = '50';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->week);
    $view->style = Str::set('compact');
    $this->assertEquals('Week', $view->label);
    $view->label = Str::set('YOY Week');
    $this->assertEquals('YOY Week', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('week', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="Year-over-year week used for comparison of financial performance to the same week in the previous year."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="week input field compact required" title="Year-over-year week used for comparison of financial performance to the same week in the previous year.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:week">YOY Week</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:week" name="comprehensive-record:1|1|1:week" type="week" tabindex="0" required="required" min="2024-W01" max="2024-W52" step="1" value="2024-W50" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:week' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="week input field compact required error" title="Year-over-year week used for comparison of financial performance to the same week in the previous year.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:week">YOY Week</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:week" name="comprehensive-record:1|1|1:week" type="week" tabindex="1" required="required" min="2024-W01" max="2024-W52" step="1" value="" />' . PHP_EOL .
      '            <span class="hint">valid ISO formated week from 2024-W01 to 2024-W52 (yyyy-W00) total characters: 8. <em>Previous value was: 2024-W50</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
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
    $this->data->time = '13:30';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->time);
    $view->style = Str::set('compact');
    $this->assertEquals('Time', $view->label);
    $view->label = Str::set('Start Time');
    $this->assertEquals('Start Time', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('time', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="Requested time when your 30 minute appointment is scheduled to begin."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="time input field compact required" title="Requested time when your 30 minute appointment is scheduled to begin.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:time">Start Time</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:time" name="comprehensive-record:1|1|1:time" type="time" tabindex="0" required="required" min="08:30" max="17:30" step="' . (30*60) .'" value="13:30" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:time' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="time input field compact required error" title="Requested time when your 30 minute appointment is scheduled to begin.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:time">Start Time</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:time" name="comprehensive-record:1|1|1:time" type="time" tabindex="1" required="required" min="08:30" max="17:30" step="' . (30*60) .'" value="" />' . PHP_EOL .
      '            <span class="hint">valid ISO formated time from 08:30 to 17:30 (hh:mm:ss) for your appointment start. <em>Previous value was: 13:30</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'datetime input field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldDatatimeRender()
  {
    $this->data->datetime = '2026-06-21T05:35';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->datetime);
    $view->style = Str::set('compact');
    $this->assertEquals('Datetime', $view->label);
    $view->label = Str::set('Booking Start');
    $this->assertEquals('Booking Start', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('datetime-local', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="Start date and time of your event within the next 18 months."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="datetime-local input field compact required" title="Start date and time of your event within the next 18 months.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:datetime">Booking Start</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:datetime" name="comprehensive-record:1|1|1:datetime" type="datetime-local" tabindex="0" required="required" min="2025-03-05T00:00" max="2026-09-30T00:00" step="60" value="2026-06-21T05:35" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:datetime' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="datetime-local input field compact required error" title="Start date and time of your event within the next 18 months.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:datetime">Booking Start</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:datetime" name="comprehensive-record:1|1|1:datetime" type="datetime-local" tabindex="1" required="required" min="2025-03-05T00:00" max="2026-09-30T00:00" step="60" value="" />' . PHP_EOL .
      '            <span class="hint">valid ISO formated date-time from 2025-03-05T00:00 to 2026-09-30T00:00 (yyyy-mm-ddThh:mm:ss) for the start of your event. <em>Previous value was: 2026-06-21T05:35</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'number input field' (whole).
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldWholeNumberRender()
  {
    $this->data->wholeNumber = 168;
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->wholeNumber);
    $view->style = Str::set('compact');
    $this->assertEquals('Whole Number', $view->label);
    $view->label = Str::set('Number of items');
    $this->assertEquals('Number of items', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('number', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="The non fractional number related to this query."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="number input field compact required" title="The non fractional number related to this query.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:whole-number">Number of items</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:whole-number" name="comprehensive-record:1|1|1:whole-number" type="number" tabindex="0" required="required" min="-32768" max="32767" step="1" value="168" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:whole-number' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="number input field compact required error" title="The non fractional number related to this query.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:whole-number">Number of items</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:whole-number" name="comprehensive-record:1|1|1:whole-number" type="number" tabindex="1" required="required" min="-32768" max="32767" step="1" value="" />' . PHP_EOL .
      '            <span class="hint">whole number from -32768 to 32767. <em>Previous value was: 168</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'number input field' (decimal point).
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldDecimalPointNumberRender()
  {
    $this->data->decimalPointNumber = '12.75';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->decimalPointNumber);
    $view->style = Str::set('compact');
    $this->assertEquals('Decimal Point Number', $view->label);
    $view->label = Str::set('Currency');
    $this->assertEquals('Currency', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('number', $view->inputType);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="The ammount of money in UK pounds and pence that you have access to."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="number input field compact required" title="The ammount of money in UK pounds and pence that you have access to.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:decimal-point-number">Currency</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:decimal-point-number" name="comprehensive-record:1|1|1:decimal-point-number" type="number" tabindex="0" required="required" min="0" max="999.99" step="0.01" value="12.75" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:decimal-point-number' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="number input field compact required error" title="The ammount of money in UK pounds and pence that you have access to.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:decimal-point-number">Currency</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:decimal-point-number" name="comprehensive-record:1|1|1:decimal-point-number" type="number" tabindex="1" required="required" min="0" max="999.99" step="0.01" value="" />' . PHP_EOL .
      '            <span class="hint">2 place decimal point number. <em>Previous value was: 12.75</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

// TODO:mrenyard: 'range input field' (percentage) (special:enum) (rate(0-10) star)
/*
        <div class="percent range field error" title="Expanded description of expected field content">
          <label for="comprehensive:error:percentage">Percentage range</label>
          <input id="comprehensive:error:percentage" name="comprehensive:error:percentage" type="range" value="0">
          <span class="ind error" id="comprehensive:error:percentage:ind">&cross; Error!</span>
        </div>
        <div class="special range field error" title="Expanded description of expected field content" data-steps="A B C D E F G H I J K L M N O P Q R S T U V W X Y Z" data-value="M">
          <label for="comprehensive:error:alphabet">Range special</label>
          <input id="comprehensive:error:alphabet" name="comprehensive:error:alphabet" type="range" value="0">
          <span class="ind error" id="comprehensive:error:alphabet:ind">&cross; Error!</span>
        </div>
        <div class="rate range field error" title="Expanded description of expected field content">
          <label for="comprehensive:error:star">Rating</label>
          <input id="comprehensive:error:star" name="comprehensive:error:star" type="range" min="1" max="10" value="0">
          <span class="ind error" id="comprehensive:error:stat:ind">&cross; Error!</span>
        </div>
*/

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
    $this->data->color = '#800080';
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->color);
    $view->style = Str::set('compact');
    $this->assertEquals('Color', $view->label);
    $view->label = Str::set('Primary Colour');
    $this->assertEquals('Primary Colour', $view->label);
    $this->assertEquals('input field', $view->type);
    $this->assertEquals('input field compact', $view->class);
    $this->assertEquals('color', $view->inputType);
    $this->assertFalse($view->isRequired);
    $this->assertEquals(
      ' title="Main thematic colour of presentation."',
      (string)$view->attribute('title')
    );
    $this->assertNull($view->attribute('placeholder'));
    $this->assertNull($view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <div class="color input field compact" title="Main thematic colour of presentation.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:color">Primary Colour</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:color" name="comprehensive-record:1|1|1:color" type="color" tabindex="0" value="#800080" />' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:color' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <div class="color input field compact error" title="Main thematic colour of presentation.">' . PHP_EOL .
      '            <label for="comprehensive-record:1|1|1:color">Primary Colour</label>' . PHP_EOL .
      '            <input id="comprehensive-record:1|1|1:color" name="comprehensive-record:1|1|1:color" type="color" tabindex="1" value="" />' . PHP_EOL .
      '            <span class="hint">representing the luminescent gradiant of red, green and blue, a hash followed by three pairs of hexadecimal characters (0 through 9 to F) formated: (#000000) with a length of exactly 7. <em>Previous value was: #800080</em></span>' . PHP_EOL .
      '          </div>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of required 'flag field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldRequiredFlagRender()
  {
    $this->data->requiredFlag = TRUE;
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->requiredFlag);
    $view->style = Str::set('compact');
    $this->assertEquals('Required Flag', $view->label);
    $view->label = Str::set('Terms &amp; conditions');
    $this->assertEquals('Terms &amp; conditions', $view->label);
    $this->assertEquals('flag field', $view->type);
    $this->assertEquals('flag field compact', $view->class);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="You must agree to terms and conditions to continue to use this site."',
      (string)$view->attribute('title')
    );
    $this->assertEquals(
      'I have read and agree to site terms and conditions.',
      $view->placeholder
    );
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <fieldset class="flag field compact required" title="You must agree to terms and conditions to continue to use this site.">' . PHP_EOL . 
      '            <legend>Terms &amp; conditions</legend>' . PHP_EOL . 
      '            <input id="comprehensive-record:1|1|1:required-flag" name="comprehensive-record:1|1|1:required-flag" type="checkbox" tabindex="0" required="required" checked="checked" />' . PHP_EOL . 
      '            <label for="comprehensive-record:1|1|1:required-flag">I have read and agree to site terms and conditions.</label>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:required-flag' => FALSE // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <fieldset class="flag field compact required error" title="You must agree to terms and conditions to continue to use this site.">' . PHP_EOL . 
      '            <legend>Terms &amp; conditions</legend>' . PHP_EOL . 
      '            <input id="comprehensive-record:1|1|1:required-flag" name="comprehensive-record:1|1|1:required-flag" type="checkbox" tabindex="1" required="required" />' . PHP_EOL . 
      '            <label for="comprehensive-record:1|1|1:required-flag">I have read and agree to site terms and conditions.</label>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered output of 'flag field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldFlagRender()
  {
    $this->data->flag = FALSE;
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    $view->setModel($this->testObject->flag);
    $view->style = Str::set('compact');
    $this->assertEquals('Flag', $view->label);
    $view->label = Str::set('Gravy Chips Y/N?');
    $this->assertEquals('Gravy Chips Y/N?', $view->label);
    $this->assertEquals('flag field', $view->type);
    $this->assertEquals('flag field compact', $view->class);
    $this->assertFalse($view->isRequired);
    $this->assertEquals(
      ' title="Do you like Chips and gravy; the popular comfort food in the UK?"',
      (string)$view->attribute('title')
    );
    $this->assertEquals('I like gravy on my chips.', $view->placeholder);
    $this->assertNull($view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <fieldset class="flag field compact" title="Do you like Chips and gravy; the popular comfort food in the UK?">' . PHP_EOL . 
      '            <legend>Gravy Chips Y/N?</legend>' . PHP_EOL . 
      '            <input id="comprehensive-record:1|1|1:flag" name="comprehensive-record:1|1|1:flag" type="checkbox" tabindex="0" />' . PHP_EOL . 
      '            <label for="comprehensive-record:1|1|1:flag">I like gravy on my chips.</label>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:flag' => 'BAD' // invalid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <fieldset class="flag field compact error" title="Do you like Chips and gravy; the popular comfort food in the UK?">' . PHP_EOL . 
      '            <legend>Gravy Chips Y/N?</legend>' . PHP_EOL . 
      '            <input id="comprehensive-record:1|1|1:flag" name="comprehensive-record:1|1|1:flag" type="checkbox" tabindex="1" />' . PHP_EOL . 
      '            <label for="comprehensive-record:1|1|1:flag">I like gravy on my chips.</label>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output2
    );
  }

/*
          <fieldset id="comprehensive-record:1|1|1:select-one" class="select-one field compact required" title="Select your favourte ... from the list of items below."
            data-placeholder="Select from available" data-placeholder-selected="See selected" tabindex="-1">
            <legend>Select One</legend>
            <label><input name="comprehensive-record:1|1|1:select-one" type="radio" value="1">DESCRIPTION ONE</label>
            <label><input name="comprehensive-record:1|1|1:select-one" type="radio" value="2" checked>DESCRIPTION TWO</label>
            <label><input name="comprehensive-record:1|1|1:select-one" type="radio" value="3">DESCRIPTION THREE</label>
          </fieldset>
*/
  /**
   * Check rendered dropdown output of 'select-one field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldSelectOneRender()
  {
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    new Templated($view, Str::set('option'));
    $view->setModel($this->testObject->selectOne);
    $view->style = Str::set('compact');
    $this->assertEquals('Select One', $view->label);
    $view->label = Str::set('Choose your favourte...');
    $this->assertEquals('Choose your favourte...', $view->label);
    $this->assertEquals('select-one field', (string)$view->type);
    $this->assertEquals('select-one field compact', $view->class);
    $this->assertTrue($view->isRequired);
    $this->assertEquals(
      ' title="Select your favourte ... from the list of items below."',
      (string)$view->attribute('title')
    );
    // $this->assertEquals('select an item',$view->placeholder);
    $this->assertEquals(' required="required"',$view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <fieldset class="select-one field compact required" title="Select your favourte ... from the list of items below."' . PHP_EOL .
      '            data-placeholder="Select from available" data-placeholder-selected="See selected" tabindex="-1">' . PHP_EOL . 
      '            <legend>Choose your favourte...</legend>' . PHP_EOL . 
      '            <ul>' . PHP_EOL . 
      '<!-- /usr/share/php/ramp/view/document/template/html/option.tpl.php -->' . PHP_EOL .
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-one:1" name="comprehensive-record:1|1|1:select-one" type="radio" value="1">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-one:1">DESCRIPTION ONE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '<!-- /usr/share/php/ramp/view/document/template/html/option.tpl.php -->' . PHP_EOL .
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-one:2" name="comprehensive-record:1|1|1:select-one" type="radio" value="2">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-one:2">DESCRIPTION TWO</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '<!-- /usr/share/php/ramp/view/document/template/html/option.tpl.php -->' . PHP_EOL .
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-one:3" name="comprehensive-record:1|1|1:select-one" type="radio" value="3">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-one:3">DESCRIPTION THREE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '            </ul>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:select-one' => 2 // valid
    )));
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <fieldset class="select-one field compact required" title="Select your favourte ... from the list of items below."' . PHP_EOL .
      '            data-placeholder="Select from available" data-placeholder-selected="See selected" tabindex="-1">' . PHP_EOL . 
      '            <legend>Choose your favourte...</legend>' . PHP_EOL . 
      '            <ul>' . PHP_EOL . 
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-one:1" name="comprehensive-record:1|1|1:select-one" type="radio" value="1">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-one:1">DESCRIPTION ONE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-one:2" name="comprehensive-record:1|1|1:select-one" type="radio" value="2" checked>' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-one:2">DESCRIPTION TWO</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-one:3" name="comprehensive-record:1|1|1:select-one" type="radio" value="3">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-one:3">DESCRIPTION THREE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '            </ul>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output2
    );
  }

  /**
   * Check rendered dropdown output of 'select-many field'.
   * - assert 'value' same as relevant record property value.
   * - assert 'type' relates to  field type definition.
   * - assert 'style' is a concatination of type + style as set on documentView.
   * - assert 'title' same as set on documentView.
   * - assert 'label' on documnentView overrides model label.
   * - assert render() matches expected format as defined in Templated. 
   */
  public function testFieldSelectManyRender()
  {
    $view = new Templated(RootView::getInstance(), Str::set('field-relation'));
    new Templated($view, Str::set('option'));
    $view->setModel($this->testObject->selectMany);
    $view->style = Str::set('compact');
    $this->assertEquals('Select Many', $view->label);
    $view->label = Str::set('List of multi selectable checkboxes');
    $this->assertEquals('List of multi selectable checkboxes', $view->label);
    $this->assertEquals('select-many field', (string)$view->type);
    $this->assertEquals('select-many field compact', $view->class);
    $this->assertFalse($view->isRequired);
    $this->assertEquals(
      ' title="Select your favourites ... from the list of items below."',
      (string)$view->attribute('title')
    );
    // $this->assertEquals('select an item',$view->placeholder);
    $this->assertNull($view->attribute('required'));
    ob_start();
    $view->render();
    $output1 = ob_get_clean();
    $this->assertSame('<!-- /usr/share/php/ramp/view/document/template/html/field-relation.tpl.php -->' . PHP_EOL .
      '          <fieldset class="select-many field compact" title="Select your favourites ... from the list of items below."' . PHP_EOL .
      '            data-placeholder="Select from available" data-placeholder-selected="See selected" tabindex="-1">' . PHP_EOL . 
      '            <legend>List of multi selectable checkboxes</legend>' . PHP_EOL . 
      '            <ul>' . PHP_EOL . 
      '<!-- /usr/share/php/ramp/view/document/template/html/option.tpl.php -->' . PHP_EOL .
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-many:1" name="comprehensive-record:1|1|1:select-many[]" type="checkbox" value="1">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-many:1">DESCRIPTION ONE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '<!-- /usr/share/php/ramp/view/document/template/html/option.tpl.php -->' . PHP_EOL .
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-many:2" name="comprehensive-record:1|1|1:select-many[]" type="checkbox" value="2">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-many:2">DESCRIPTION TWO</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '<!-- /usr/share/php/ramp/view/document/template/html/option.tpl.php -->' . PHP_EOL .
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-many:3" name="comprehensive-record:1|1|1:select-many[]" type="checkbox" value="3">' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-many:3">DESCRIPTION THREE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '            </ul>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output1
    );
    SETTING::$DEV_MODE = FALSE;
    $this->testObject->validate(PostData::build(array(
      'comprehensive-record:1|1|1:select-many' => array(1,3) // valid
    )));
    $this->testObject->updated();
    ob_start();
    $view->render();
    $output2 = ob_get_clean();
    $this->assertSame(
      '          <fieldset class="select-many field compact" title="Select your favourites ... from the list of items below."' . PHP_EOL . 
      '            data-placeholder="Select from available" data-placeholder-selected="See selected" tabindex="-1">' . PHP_EOL . 
      '            <legend>List of multi selectable checkboxes</legend>' . PHP_EOL . 
      '            <ul>' . PHP_EOL . 
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-many:1" name="comprehensive-record:1|1|1:select-many[]" type="checkbox" value="1" checked>' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-many:1">DESCRIPTION ONE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-many:2" name="comprehensive-record:1|1|1:select-many[]" type="checkbox" value="2">' . PHP_EOL .
      '                <label for="comprehensive-record:1|1|1:select-many:2">DESCRIPTION TWO</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '              <li>' . PHP_EOL . 
      '                <input id="comprehensive-record:1|1|1:select-many:3" name="comprehensive-record:1|1|1:select-many[]" type="checkbox" value="3" checked>' . PHP_EOL . 
      '                <label for="comprehensive-record:1|1|1:select-many:3">DESCRIPTION THREE</label>' . PHP_EOL . 
      '              </li>' . PHP_EOL . 
      '            </ul>' . PHP_EOL . 
      '          </fieldset>' . PHP_EOL . '',
      $output2
    );
  }
}