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
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/view/View.class.php';
require_once '/usr/share/php/svelte/view/RootView.class.php';

require_once '/usr/share/php/tests/svelte/view/mocks/RootViewTest/MockView.class.php';
require_once '/usr/share/php/tests/svelte/view/mocks/RootViewTest/MockModel.class.php';

use tests\svelte\view\mocks\RootViewTest\MockView;
use tests\svelte\view\mocks\RootViewTest\MockViewA;
use tests\svelte\view\mocks\RootViewTest\MockViewB;
use tests\svelte\view\mocks\RootViewTest\MockViewC;
use tests\svelte\view\mocks\RootViewTest\MockModel;

use svelte\view\View;
use svelte\view\RootView;
use svelte\model\Model;
use svelte\core\BadPropertyCallException;

/**
 * Collection of tests for \svelte\view\RootView.
 */
class RootViewTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for \svelte\view\RootView::__construct().
   * - assert constructor inaccessible
   * @link svelte.view.RootView svelte\view\RootView
   */
  public function test__construct()
  {
    $this->expectException('\Error');
    $throwError = new RootView();
  }

  /**
   * Collection of assertions for \svelte\view\RootView::getInstance().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\view\View}
   * - assert is instance of {@link \svelte\view\RootView}
   * - assert is same instance on every call (Singleton)
   * @link svelte.view.RootView#method_getInstance svelte\view\RootView::getInstance()
   */
  public function testGetInstance()
  {
    $testObject = RootView::getInstance();
    $this->assertInstanceOf('\svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('\svelte\view\View', $testObject);
    $this->assertInstanceOf('\svelte\view\RootView', $testObject);
    $this->assertSame(RootView::getInstance(), $testObject);
  }

  /**
   * Collection of assertions for \svelte\view\RootView::setModel.
   * - assert BadMethodCallException thrown when calling setModel().
   * @link svelte.view.RootView#method_setModel svelte\view\RootView::setModel()
   */
  public function testSetModel()
  {
    $testObject = RootView::getInstance();
    $this->expectException('\BadMethodCallException');
    $this->expectExceptionMessage('SHOULD NOT USE THIS METHOD');
    $testObject->setModel(new MockModel());
  }

  /**
   * Collection of assertions for \svelte\view\RootView::render()
   * and \svelte\view\RootView::add().
   * - assert each child view added sequentially
   * - assert render() output maintains sequance and format
   * @link svelte.view.RootView#method_add svelte\view\RootView::add()
   * @link svelte.view.RootView#method_render svelte\view\RootView::render()
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
      $view->setModel(new MockModel());
      $testObject->add($view);
      $i++;
      ob_start();
      $testObject->render();
      $output = ob_get_clean();
      if ($i === 1)
      {
        $this->assertEquals(
          'tests\svelte\view\mocks\RootViewTest\MockViewA ',
          $output
        );
      }
      if ($i === 2)
      {
        $this->assertEquals(
          'tests\svelte\view\mocks\RootViewTest\MockViewA '.
          'tests\svelte\view\mocks\RootViewTest\MockViewB ',
          $output
        );
      }
      if ($i === 3)
      {
        $this->assertEquals(
          'tests\svelte\view\mocks\RootViewTest\MockViewA '.
          'tests\svelte\view\mocks\RootViewTest\MockViewB '.
          'tests\svelte\view\mocks\RootViewTest\MockViewC ',
          $output
        );
      }
    }
  }

  /**
   * Collection of assertions for \svelte\view\RootView::__clone.
   * - assert cannot be cloned, throwing \BadMethodCallException
   *   - with message *Cloning is not allowed*
   * @link svelte.view.RootView#method__clone svelte\view\RootView::__clone()
   */
  public function test__clone()
  {
    $testObject = RootView::getInstance();
    $this->expectException('\BadMethodCallException');
    $this->expectExceptionMessage('Cloning is not allowed');
    $clone = clone $testObject;
  }
}
