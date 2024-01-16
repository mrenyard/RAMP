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

use \ramp\view\RootView;

use ramp\core\RAMPObject;
use tests\ramp\mocks\view\MockViewA;
use tests\ramp\mocks\view\MockViewB;
use tests\ramp\mocks\view\MockViewC;
use tests\ramp\mocks\view\MockViewD;

/**
 * Collection of tests for \ramp\view\RootView.
 */
class RootViewTest extends \tests\ramp\view\ViewTest
{
  #region Setup
  protected function preSetup() : void { RootView::reset(); }
  protected function getTestObject() : RAMPObject { return RootView::getInstance(); }
  #endregion

  /**
   * Default base constructor assertions \ramp\view\View::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\RootView}
   * @see \ramp\model\Model
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\RootView', $this->testObject);
  }

  /**
   * Addition of sub views.
   * - assert each child view added sequentially.
   * - assert View->children output maintains sequance and format.
   * @see \ramp\view\View::add()
   * @see \ramp\view\View::children
   */
  public function testSubViewAddition(string $parentRender = '') : void
  {
    parent::testSubViewAddition($parentRender);
  }

  /**
   * Cloning of RootView BadMethodCallException.
   * - assert cannot be cloned, throws \BadMethodCallException
   *   - with message *Cloning is not allowed*
   * @see \ramp\view\View::__clone()
   */
  public function testClone() : void
  {
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage('Cloning is not allowed');
    $clone = clone $this->testObject;
  }
}
