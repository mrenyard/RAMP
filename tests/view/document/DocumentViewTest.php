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
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/RootView.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';
require_once '/usr/share/php/ramp/view/ComplexView.class.php';
require_once '/usr/share/php/ramp/view/document/DocumentView.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/document/DocumentModel.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';

require_once '/usr/share/php/tests/ramp/view/document/mocks/DocumentViewTest/MockDocumentView.class.php';
require_once '/usr/share/php/tests/ramp/view/document/mocks/DocumentViewTest/MockView.class.php';
require_once '/usr/share/php/tests/ramp/view/document/mocks/DocumentViewTest/MockBusinessModel.class.php';
require_once '/usr/share/php/tests/ramp/view/document/mocks/DocumentViewTest/MockModel.class.php';

use tests\ramp\view\document\mocks\DocumentViewTest\MockDocumentView;
use tests\ramp\view\document\mocks\DocumentViewTest\MockView;
use tests\ramp\view\document\mocks\DocumentViewTest\MockModel;
use tests\ramp\view\document\mocks\DocumentViewTest\MockBusinessModel;
use tests\ramp\view\document\mocks\DocumentViewTest\MockBusinessModelCollection;

use ramp\core\Str;
use ramp\core\BadPropertyCallException;
use ramp\core\PropertyNotSetException;
use ramp\view\RootView;
use ramp\view\document\DocumentView;

/**
 * Collection of tests for \ramp\view\document\DocumentView.
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
    RootView::reset();
    $this->parentView = new MockView(RootView::getInstance());
    $this->testObject = new MockDocumentView($this->parentView);
  }

  /**
   * Collection of assertions for \ramp\view\document\DocumentView::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\view\View}
   * - assert is instance of {@link \ramp\view\ChildView}
   * - assert is instance of {@link \ramp\view\document\DocumentView}
   * @link ramp.view.document.DocumentView ramp\view\document\DocumentView
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\view\View', $this->testObject);
    $this->assertInstanceOf('\ramp\view\ChildView', $this->testObject);
    $this->assertInstanceOf('\ramp\view\document\DocumentView', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\view\document\DocumentView::__set() and
   * \ramp\view\document\DocumentView::__get().
   * - assert PropertyNotSetException thrown when trying to set value of none existant property
   * - assert BadPropertyCallException thrown when trying to get value of none existant property
   * - assert setting of property on component (contained) \ramp\model\document\DocumentModel passed through 
   * - assert that property calls are passes to its component (contained) \ramp\model\document\DocumentModel
   * - assert that property calls are passes to its component (contained) \ramp\model\business\BusinessModel
   * @link ramp.view.document.DocumentView#method__set ramp\view\document\DocumentView::__set()
   * @link ramp.view.document.DocumentView#method__get ramp\view\document\DocumentView::__get()
   */
  public function test__set__get()
  {
    try {
      $fail = $this->testObject->noProperty;
    } catch (BadPropertyCallException $expected) {
      $this->assertEquals(
        'Unable to locate noProperty of \'tests\ramp\view\document\mocks\DocumentViewTest\MockDocumentView\'',
        $expected->getMessage()
      );
      try {
        $this->testObject->noProperty = 'aValue';
      } catch (PropertyNotSetException $expected) {
        $this->assertEquals(
          'tests\ramp\view\document\mocks\DocumentViewTest\MockDocumentView->noProperty is NOT settable',
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
   * Collection of assertions for \ramp\view\document\DocumentView::setModel(), and 
   * \ramp\view\document\DocumentView::hasModel and \ramp\view\document\DocumentView::__get().
   * - Prior to model set hasModel returns FALSE and post set TRUE.
   * - assert that property calls are passes to its component (contained) {@link \ramp\model\business\BusinessModel}
   * @link ramp.view.document.DocumentView#method_setModel ramp\view\document\DocumentView::setModel()
   * @link ramp.view.document.DocumentView#method__get ramp\view\document\DocumentView::__get()
   */
  public function testSetModel()
  {
    $businessModel = new MockBusinessModel();
    $this->assertFalse($this->testObject->hasModel);
    $this->testObject->setModel($businessModel);
    $this->assertTrue($this->testObject->hasModel);
    $value = 'aValue';
    $businessModel->aProperty = $value;
    $this->assertSame($this->testObject->aProperty, $value);
    $this->assertSame($businessModel->aProperty, $this->testObject->aProperty);
  }

  /**
   * Collection of assertions for \ramp\view\document\DocumentView::setModel() and
   * \ramp\view\document\DocumentView::hasModel with cascade.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * @link \ramp\view\document\DocumentVieww#method_setModel \ramp\view\document\DocumentView::setModel()
   * @link \ramp\view\document\DocumentVieww#method_hasModel \ramp\view\document\DocumentView::hasModel()
   */
  public function testSetModelWithCascade()
  {
    $subModel1 = new MockBusinessModel();
    $parentModel = new MockBusinessModelCollection();
    $parentModel->add($subModel1);

    $parentView = new MockDocumentView(RootView::getINstance());
    $childView = new MockDocumentView($parentView);
    $this->assertFalse($parentView->hasModel);
    $this->assertFalse($childView->hasModel);
    $parentView->setModel($parentModel);
    $this->assertTrue($parentView->hasModel);
    $this->assertTrue($childView->hasModel);
  }

  /**
   * Collection of assertions for \ramp\view\document\DocumentView::setModel() and
   * \ramp\view\document\DocumentView::hasModel no cascade.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * @link \ramp\view\document\DocumentVieww#method_setModel \ramp\view\document\DocumentView::setModel()
   * @link \ramp\view\document\DocumentVieww#method_hasModel \ramp\view\document\DocumentView::hasModel()
   */
  public function testSetModelNoCascade()
  {
    $subModel1 = new MockBusinessModel();
    $parentModel = new MockBusinessModelCollection();
    $parentModel->add($subModel1);

    $parentView = new MockDocumentView(RootView::getINstance());
    $childView = new MockDocumentView($parentView);
    $this->assertFalse($parentView->hasModel);
    $this->assertFalse($childView->hasModel);
    $parentView->setModel($parentModel, FALSE);
    $this->assertTrue($parentView->hasModel);
    $this->assertFalse($childView->hasModel);
  }
}
