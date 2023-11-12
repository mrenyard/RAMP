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
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
// require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/RootView.class.php';

require_once '/usr/share/php/tests/ramp/view/mocks/RootViewTest/MockView.class.php';
// require_once '/usr/share/php/tests/ramp/view/mocks/RootViewTest/MockModel.class.php';

use tests\ramp\view\mocks\RootViewTest\MockView;
use tests\ramp\view\mocks\RootViewTest\MockViewA;
use tests\ramp\view\mocks\RootViewTest\MockViewB;
use tests\ramp\view\mocks\RootViewTest\MockViewC;
// use tests\ramp\view\mocks\RootViewTest\MockModel;

use ramp\view\View;
use ramp\view\RootView;
// use ramp\model\Model;
use ramp\core\BadPropertyCallException;

/**
 * Collection of tests for \ramp\view\RootView.
 */
class RootViewTest extends \PHPUnit\Framework\TestCase
{
  
  /**
   * Setup test articles
   */
  public function setUp() : void
  {
    RootView::reset();
  }

  /**
   * Collection of assertions for \ramp\view\RootView::__construct().
   * - assert constructor inaccessible
   * @see ramp.view.RootView ramp\view\RootView
   */
  public function test__construct()
  {
    $this->expectException('\Error');
    $throwError = new RootView();
  }

  /**
   * Collection of assertions for \ramp\view\RootView::getInstance().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\RootView}
   * - assert is same instance on every call (Singleton)
   * @see ramp.view.RootView#method_getInstance ramp\view\RootView::getInstance()
   */
  public function testGetInstance()
  {
    $testObject = RootView::getInstance();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\view\View', $testObject);
    $this->assertInstanceOf('\ramp\view\RootView', $testObject);
    $this->assertSame(RootView::getInstance(), $testObject);
  }

  /**
   * Collection of assertions for \ramp\view\RootView::render()
   * and \ramp\view\RootView::add().
   * - assert each child view added sequentially
   * - assert render() output maintains sequance and format
   * @see ramp.view.RootView#method_add ramp\view\RootView::add()
   * @see ramp.view.RootView#method_render ramp\view\RootView::render()
   */
  public function testAddRender()
  {
    $mockViewCollection = new \SplObjectStorage();
    $mockViewCollection->attach(new MockViewA());
    $mockViewCollection->attach(new MockViewB());
    $mockViewCollection->attach(new MockViewC());

    $testObject = RootView::getInstance();

    $i=0;
    foreach ($mockViewCollection as $view)
    {
      // $view->setModel(new MockModel());
      $testObject->add($view);
      $i++;
      ob_start();
      $testObject->render();
      $output = ob_get_clean();
      if ($i === 1)
      {
        $this->assertEquals(
          'tests\ramp\view\mocks\RootViewTest\MockViewA',
          $output
        );
      }
      if ($i === 2)
      {
        $this->assertEquals(
          'tests\ramp\view\mocks\RootViewTest\MockViewA'.
          'tests\ramp\view\mocks\RootViewTest\MockViewB',
          $output
        );
      }
      if ($i === 3)
      {
        $this->assertEquals(
          'tests\ramp\view\mocks\RootViewTest\MockViewA'.
          'tests\ramp\view\mocks\RootViewTest\MockViewB'.
          'tests\ramp\view\mocks\RootViewTest\MockViewC',
          $output
        );
      }
    }
  }

  /**
   * Collection of assertions for \ramp\view\RootView::__clone.
   * - assert cannot be cloned, throwing \BadMethodCallException
   *   - with message *Cloning is not allowed*
   * @see ramp.view.RootView#method__clone ramp\view\RootView::__clone()
   */
  public function test__clone()
  {
    $testObject = RootView::getInstance();
    $this->expectException('\BadMethodCallException');
    $this->expectExceptionMessage('Cloning is not allowed');
    $clone = clone $testObject;
  }
}
