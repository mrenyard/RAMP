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

require_once '/usr/share/php/tests/ramp/view/ViewTest.php';

require_once '/usr/share/php/ramp/view/RootView.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';

require_once '/usr/share/php/tests/ramp/mocks/view/MockChildView.class.php';

use ramp\core\RAMPObject;
use ramp\view\ChildView;
use ramp\view\RootView;

use tests\ramp\mocks\view\MockChildView;
use tests\ramp\mocks\view\MockViewA;
use tests\ramp\mocks\view\MockViewB;
use tests\ramp\mocks\view\MockViewC;

/**
 * Collection of tests for \ramp\view\ChildView.
 */
class ChildViewTest extends \tests\ramp\view\ViewTest
{
  #region Setup
  protected function preSetup() : void { RootView::reset(); }
  protected function getTestObject() : RAMPObject { return new MockChildView(RootView::getInstance()); }
  protected function postSetup() : void
  {
    if (!isset($this->subCollection)) {
      $this->subCollection = new \SplObjectStorage();
      $this->subCollection->attach(new MockViewA());
      $this->subCollection->attach(new MockViewB());
      $this->subCollection->attach(new MockViewC());
    }
  }
  #endregion

  /**
   * Default base constructor assertions \ramp\view\View::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\ChildView}
   * @see \ramp\view\ChildView
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\ChildView', $this->testObject);
  }

  /**
   * Addition of sub views.
   * - assert each child view added sequentially.
   * - assert View->children output maintains sequance and format.
   * @see \ramp\view\View::add()
   * @see \ramp\view\View::children
   */
  public function testSubViewAddition(string $parentRender = 'tests\ramp\mocks\view\MockChildView ') : void
  {
    $this->testObject->viewOnlyTesting = TRUE;
    parent::testSubViewAddition($parentRender);
  }

  /**
   * Cloning copies sub views.
   * - assert cloned View without associated model is equal to the original
   * @see \ramp\view\View::__clone()
   */
  public function testClone() : void
  {
    parent::testClone();
  }
}
