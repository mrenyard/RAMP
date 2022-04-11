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
namespace tests\ramp\view\document;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/RootView.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';
require_once '/usr/share/php/ramp/view/document/DocumentView.class.php';
require_once '/usr/share/php/ramp/view/document/Templated.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/document/DocumentModel.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';

require_once '/usr/share/php/tests/ramp/view/document/mocks/TemplatedTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/ramp/view/document/mocks/TemplatedTest/MockModel.class.php';

use tests\ramp\view\document\mocks\TemplatedTest\MockBusinessModel;
use tests\ramp\view\document\mocks\TemplatedTest\MockModel;

use ramp\core\Str;
use ramp\core\BadPropertyCallException;
use ramp\core\PropertyNotSetException;
use ramp\view\RootView;
use ramp\view\document\Templated;

/**
 * Collection of tests for \ramp\view\document\template\Templated.
 */
class TemplatedTest extends \PHPUnit\Framework\TestCase
{
  private $templateName;
  private $testObject;

  /**
   * Setup test articles
   */
  public function setUp() : void
  {
    $this->templateName = Str::set('info');
    $this->testObject = new Templated(RootView::getInstance(), $this->templateName);
  }

  /**
   * Collection of assertions for \ramp\view\document\Templated::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\view\View}
   * - assert is instance of {@link \ramp\view\ChildView}
   * - assert is instance of {@link \ramp\view\document\DocumentView}
   * - assert is instance of {@link \ramp\view\document\Templated}
   * - assert throws {@link \InvalidArgumentException} when provided arguments do NOT translate to a valid file path. 
   *  - with message 'Provided $templateName ([...]) of $templateType ([...]) is non existant!'
   * @link ramp.view.document.Templated ramp\view\document\Templated
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\view\View', $this->testObject);
    $this->assertInstanceOf('\ramp\view\ChildView', $this->testObject);
    $this->assertInstanceOf('\ramp\view\document\DocumentView', $this->testObject);
    $this->assertInstanceOf('\ramp\view\document\Templated', $this->testObject);

    try {
      new Templated(RootView::getInstance(), Str::set('bad'));
    } catch (\InvalidArgumentException $expected) {
      $this->assertEquals(
        'Provided $templateName (bad) of $templateType (html) is non existant!',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\view\document\Templated::__set() and
   * \ramp\view\document\Templated::__get().
   * - assert {@link \ramp\core\PropertyNotSetException} thrown when trying to set value of none existant property
   * - assert {@link \ramp\core\BadPropertyCallException} thrown when trying to get value of none existant property
   * - assert setting of property on component (contained) {@link \ramp\model\document\DocumentModel} passed through 
   * - assert that property calls are passed to its component (contained) {@link \ramp\model\document\DocumentModel}
   * - assert that property calls are passed to its component (contained) {@link \ramp\model\business\BusinessModel}
   * @link ramp.view.document.Templated#method__set ramp\view\document\Templated::__set()
   * @link ramp.view.document.Templated#method__get ramp\view\document\Templated::__get()
   */
  public function test__set__get()
  {
    $this->testObject = new Templated(RootView::getInstance(), $this->templateName);
    try {
      $fail = $this->testObject->noProperty;
    } catch (BadPropertyCallException $expected) {
      $this->assertEquals(
        'Unable to locate noProperty of \'ramp\view\document\Templated\'',
        $expected->getMessage()
      );
      try {
        $this->testObject->noProperty = 'aValue';
      } catch (PropertyNotSetException $expected) {
        $this->assertEquals(
          'ramp\view\document\Templated->noProperty is NOT settable',
          $expected->getMessage()
        );
        $value = Str::set('[TITLE]');
        $this->testObject->title = $value;
        $this->assertSame($value, $this->testObject->title);
        return;
      }
      $this->fail('An expected PropertyNotSetException has NOT been raised.');
    }
    $this->fail('An expected BadPropertyCallException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\view\document\Templated::setModel()
   *  and \ramp\view\document\Templated::__get().
   * - assert throws {@link \InvalidArgumentException} when not presented with {@link \ramp\model\business\BusinessModel}
   *  - with message 'Expecting instanceof BusinessModel'
   * - assert that property calls are passes to its component (contained) {@link \ramp\model\business\BusinessModel}
   * @link ramp.view.document.Templated#method_setModel ramp\view\document\Templated::setModel()
   * @link ramp.view.document.Templated#method__get ramp\view\document\Templated::__get()
   */
  public function testSetModel()
  {
    $this->testObject = new Templated(RootView::getInstance(), $this->templateName);
    try {
      $this->testObject->setModel(new MockModel());
    } catch (\InvalidArgumentException $expected) {
      $this->assertEquals('Expecting instanceof BusinessModel', $expected->getMessage());
      $businessModel = new MockBusinessModel();
      $this->testObject->setModel($businessModel);
      $value = 'aValue';
      $businessModel->aProperty = $value;
      $this->assertSame($this->testObject->aProperty, $value);
      $this->assertSame($businessModel->aProperty, $this->testObject->aProperty);
      return;
    }
    $this->fail('An expected InvalidArgumentException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\view\document\Templated::templateType.
   * - assert path (OBJECT::$templte) contains template type (html) by default.
   * - assert post set path (OBJECT::$template) contains template type as expected. 
   * - assert throws {@link \ramp\core\PropertyNotSetException} when provided value does NOT translate to a valid file path. 
   *  - with message 'Provided $templateName ([...]) of $templateType ([...]) is non existant!'
   * @link ramp.view.document.Templated#method_set_templateType ramp\view\document\Templated::templateType
   */
  public function testTemplateType()
  {
    $this->assertStringContainsString('/html/', $this->testObject->template);
    $this->testObject->templateType = Str::set('text');
    $this->assertStringContainsString('/text/', $this->testObject->template);
    try {
      $this->testObject->templateType = Str::set('bad');
    } catch (PropertyNotSetException $expected) {
      $this->assertEquals(
        'Provided $templateName (info) of $templateType (bad) is non existant!',
        $expected->getMessage()
      );
      return;
    }
    $this->fail('An expected PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\view\document\Templated::render().
   * - assert renders expected output using named and type of template
   * - assert renders expected output including 'info' when in DEV_MODE  
   * @link ramp.view.document.Templated#method_render ramp\view\document\Templated::Render()
   */
  public function testRender()
  {
    ob_start();
    $this->testObject->render();
    $render1 = ob_get_contents();
    ob_end_clean();
    $this->assertEquals(
'<!-- ' . $this->testObject->template . ' -->
',
      $render1
    );

    define('DEV_MODE', TRUE);
    ob_start();
    $this->testObject->render();
    $render2 = ob_get_contents();
    ob_end_clean();
    $this->assertEquals(
'<!-- ' . $this->testObject->template . ' -->
<!-- ' . $this->testObject->template . ' -->
',
      $render2
    );
    $DEV_MODE = FALSE;
  }
}
