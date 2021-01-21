<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
namespace tests\svelte\view;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/view/View.class.php';
require_once '/usr/share/php/svelte/view/ChildView.class.php';
require_once '/usr/share/php/svelte/view/document/DocumentView.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/document/DocumentModel.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';

require_once '/usr/share/php/tests/svelte/view/document/mocks/DocumentViewTest/MockDocumentView.class.php';
require_once '/usr/share/php/tests/svelte/view/document/mocks/DocumentViewTest/MockView.class.php';
require_once '/usr/share/php/tests/svelte/view/document/mocks/DocumentViewTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/svelte/view/document/mocks/DocumentViewTest/MockModel.class.php';

use tests\svelte\view\document\mocks\DocumentViewTest\MockDocumentView;
use tests\svelte\view\document\mocks\DocumentViewTest\MockView;
use tests\svelte\view\document\mocks\DocumentViewTest\MockBusinessModel;
use tests\svelte\view\document\mocks\DocumentViewTest\MockModel;

use svelte\core\Str;
use svelte\core\BadPropertyCallException;
use svelte\core\PropertyNotSetException;
use svelte\view\document\DocumentView;

/**
 * Collection of tests for \svelte\view\document\DocumentView.
 */
class DocumentViewTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $parentView;

  /**
   * Setup test articles
   */
  public function setUp() : void
  {
    $this->parentView = new MockView();
    $this->testObject = new MockDocumentView($this->parentView);
  }

  /**
   * Collection of assertions for \svelte\view\document\DocumentView::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\view\View}
   * - assert is instance of {@link \svelte\view\RootView}
   * @link svelte.view.document.DocumentView svelte\view\document\DocumentView
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\view\View', $this->testObject);
    $this->assertInstanceOf('\svelte\view\ChildView', $this->testObject);
    $this->assertInstanceOf('\svelte\view\document\DocumentView', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\view\document\DocumentView::__set() and
   * \svelte\view\document\DocumentView::__get().
   * - assert PropertyNotSetException thrown when trying to set value of none existant property
   * - assert BadPropertyCallException thrown when trying to get value of none existant property
   * - assert setting of property on component (contained) \svelte\model\document\DocumentModel passed through 
   * - assert that property calls are passes to its component (contained) \svelte\model\document\DocumentModel
   * - assert that property calls are passes to its component (contained) \svelte\model\business\BusinessModel
   * @link svelte.view.document.DocumentView#method__set svelte\view\document\DocumentView::__set()
   * @link svelte.view.document.DocumentView#method__get svelte\view\document\DocumentView::__get()
   */
  public function test__set__get()
  {
    try {
      $fail = $this->testObject->noProperty;
    } catch (BadPropertyCallException $expected) {
      $this->assertEquals(
        'Unable to locate noProperty of \'tests\svelte\view\document\mocks\DocumentViewTest\MockDocumentView\'',
        $expected->getMessage()
      );
      try {
        $this->testObject->noProperty = 'aValue';
      } catch (PropertyNotSetException $expected) {
        $this->assertEquals(
          'tests\svelte\view\document\mocks\DocumentViewTest\MockDocumentView->noProperty is NOT settable',
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
   * Collection of assertions for \svelte\view\document\DocumentView::setModel()
   *  and \svelte\view\document\DocumentView::__get().
   * - assert throws InvalidArgumentException when not presented with {@link \svelte\model\business\BusinessModel} 
   *  - with message 'Expecting instanceof BusinessModel'
   * - assert that property calls are passes to its component (contained) {@link \svelte\model\business\BusinessModel}
   * @link svelte.view.document.DocumentView#method_setModel svelte\view\document\DocumentView::setModel()
   * @link svelte.view.document.DocumentView#method__get svelte\view\document\DocumentView::__get()
   */
  public function testSetModel()
  {
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
}
