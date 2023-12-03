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
namespace tests\ramp\view;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/RootView.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';

require_once '/usr/share/php/tests/ramp/view/mocks/ChildViewTest/MockChildView.class.php';
require_once '/usr/share/php/tests/ramp/view/mocks/ChildViewTest/MockView.class.php';
require_once '/usr/share/php/tests/ramp/view/mocks/ChildViewTest/MockRecordModel.class.php';
require_once '/usr/share/php/tests/ramp/view/mocks/ChildViewTest/MockFieldModel.class.php';

use tests\ramp\view\mocks\ChildViewTest\MockChildView;
use tests\ramp\view\mocks\ChildViewTest\MockView;
use tests\ramp\view\mocks\ChildViewTest\MockFieldModel;
use tests\ramp\view\mocks\ChildViewTest\MockRecordModel;
use tests\ramp\view\mocks\ChildViewTest\MockRecordModelCollection;

use ramp\core\Str;
use ramp\view\View;
use ramp\view\RootView;
use ramp\view\ChildView;
use ramp\model\business\RecordCollection;

/**
 * Collection of tests for \ramp\view\ChildView.
 */
class ChildViewTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for \ramp\view\ChildView::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\RootView}
   * - assert output of children on provided parentView is as expected maintaining sequance and format
   * - assert output of render on provided parentView is as expected maintaining sequance and format
   * @see ramp.view.ChildView ramp\view\ChildView
   */
  public function test__construct()
  {
    $parentView = new MockView();
    $testObject = new MockChildView($parentView);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\view\View', $testObject);
    $this->assertInstanceOf('\ramp\view\ChildView', $testObject);

    ob_start();
    $parentView->children;
    $output = ob_get_clean();
    $this->assertEquals(
      'tests\ramp\view\mocks\ChildViewTest\MockChildView ',
      $output
    );

    ob_start();
    $parentView->render();
    $output = ob_get_clean();
    $this->assertEquals(
      'tests\ramp\view\mocks\ChildViewTest\MockView ' .
      'tests\ramp\view\mocks\ChildViewTest\MockChildView ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\document\ChildView::setModel() and
   * \ramp\view\document\ChildView::hasModel with cascade.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * @see \ramp\view\document\ChildVieww#method_setModel \ramp\view\document\ChildView::setModel()
   * @see \ramp\view\document\ChildVieww#method_hasModel \ramp\view\document\ChildView::hasModel()
   *
  public function testSetModelWithCascade() : void
  {
    $subModel1 = new MockRecordModel();
    $parentModel = new MockRecordModelCollection();
    $parentModel->add($subModel1);

    $parentView = new MockView(RootView::getINstance());
    $childView = new MockChildView($parentView);
    $this->assertFalse($parentView->hasModel);
    $this->assertFalse($childView->hasModel);
    $parentView->setModel($parentModel);
    $this->assertTrue($parentView->hasModel);
    $this->assertTrue($childView->hasModel);
  }*/

  /**
   * Collection of assertions for \ramp\view\document\ChildView::setModel() and
   * \ramp\view\document\ChildView::hasModel no cascade.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * @see \ramp\view\document\ChildVieww#method_setModel \ramp\view\document\ChildView::setModel()
   * @see \ramp\view\document\ChildVieww#method_hasModel \ramp\view\document\ChildView::hasModel()
   *
  public function testSetModelNoCascade() : void
  {
    // $subField = new MockFieldModel();
    $recordModel = new MockRecordModel();
    $parentModel = new MockRecordModelCollection();
    $parentModel->add($recordModel);
    // $recordModel->add($subField);

    $parentView = new MockView(RootView::getINstance());
    $childView = new MockChildView($parentView);
    $this->assertFalse($parentView->hasModel);
    $this->assertFalse($childView->hasModel);
    $parentView->setModel($parentModel, FALSE);
    $this->assertTrue($parentView->hasModel);
    $this->assertFalse($childView->hasModel);
  }*/

  /**
   * Collection of assertions for \ramp\view\document\ChildView::setModel() and
   * \ramp\view\document\ChildView::hasModel traverse upward.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * @see \ramp\view\document\ChildVieww#method_setModel \ramp\view\document\ChildView::setModel()
   * @see \ramp\view\document\ChildVieww#method_hasModel \ramp\view\document\ChildView::hasModel()
   *
  public function testUpwardTraverseOnSetModelIsField() : void
  {
    $parentModel = new MockRecordModelCollection();
    $recordModel = new MockRecordModel();

    $parentModel->add($recordModel);
    $subField = new MockFieldModel(Str::set(''), $recordModel);

    $parentView = new MockView(RootView::getInstance());
    $recordView = new MockChildView($parentView);
    $subFieldView = new MockChildView($recordView);

    $this->assertFalse($parentView->hasModel);
    $this->assertFalse($recordView->hasModel);
    $this->assertFalse($subFieldView->hasModel);
    $subFieldView->setModel($subField);
    $this->assertTrue($subFieldView->hasModel);
    $this->assertTrue($recordView->hasModel);
    $this->assertFalse($parentView->hasModel);
  }*/
}