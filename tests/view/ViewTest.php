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

require_once '/usr/share/php/tests/ramp/core/ObjectTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/tests/ramp/mocks/view/MockView.class.php';

use ramp\core\RAMPObject;
use tests\ramp\mocks\view\MockView;
use tests\ramp\mocks\view\MockViewA;
use tests\ramp\mocks\view\MockViewB;
use tests\ramp\mocks\view\MockViewC;
use tests\ramp\mocks\view\MockViewD;

/**
 * Collection of tests for \ramp\view\View.
 */
class ViewTest extends \tests\ramp\core\ObjectTest
{
  protected $subCollection;
  
  #region Setup
  protected function preSetup() : void { }
  protected function getTestObject() : RAMPObject { return new MockView(); }
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
   * @see \ramp\model\Model
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\View', $this->testObject);
  }

  /**
   * Addition of sub views.
   * - assert each child view added sequentially.
   * - assert View->children output maintains sequance and format.
   * @see \ramp\view\View::add()
   * @see \ramp\view\View::children
   */
  public function testSubViewAddition(string $parentRender = 'tests\ramp\mocks\view\MockView ') : void
  {
    $i = 0;
    foreach ($this->subCollection as $subView) {
      $subView->viewOnlyTesting = TRUE;
      $this->testObject->add($subView);
      $i++;
      ob_start();
      $this->testObject->render();
      $output = ob_get_clean();
      if ($i === 1)
      {
        $this->assertEquals(
          $parentRender .
          'tests\ramp\mocks\view\MockViewA ',
          $output
        );
      }
      if ($i === 2)
      {
        $this->assertEquals(
          $parentRender.
          'tests\ramp\mocks\view\MockViewA '.
          'tests\ramp\mocks\view\MockViewB ',
          $output
        );
      }
      if ($i === 3)
      {
        $this->assertEquals(
          $parentRender.
          'tests\ramp\mocks\view\MockViewA '.
          'tests\ramp\mocks\view\MockViewB '.
          'tests\ramp\mocks\view\MockViewC ',
          $output
        );
      }
    }
    $this->assertSame(3, $i);
  }

  /**
   * Cloning copies sub views.
   * - assert cloned View without associated model is equal to the original
   * @see \ramp\view\View::__clone()
   */
  public function testClone() : void
  {
    $clone = clone $this->testObject; 
    $this->assertNotSame($this->testObject, $clone);
    $this->assertEquals($this->testObject, $clone);
    unset($clone);
  }
}
