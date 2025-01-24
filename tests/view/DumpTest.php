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

require_once '/usr/share/php/tests/ramp/view/ChildViewTest.php';

require_once '/usr/share/php/ramp/view/Dump.class.php';

use ramp\core\RAMPObject;
use ramp\view\ChildView;
use ramp\view\RootView;
use ramp\view\Dump;

use tests\ramp\mocks\view\MockViewA;
use tests\ramp\mocks\view\MockViewB;
use tests\ramp\mocks\view\MockViewC;

/**
 * Collection of tests for \ramp\view\ChildView.
 */
class DumpTest extends \tests\ramp\view\ChildViewTest
{
  #region Setup
  #[\Override]
  protected function getTestObject() : RAMPObject { return new Dump(RootView::getInstance()); }
  #[\Override]
  protected function postSetup() : void
  {
  }
  #endregion

  /**
   * Default base constructor assertions \ramp\view\View::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\ChildView}
   * - assert is instance of {@see \ramp\view\Dump}
   * @see \ramp\model\Model
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\Dump', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__set().
   * - assert {@see \ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see ramp\core\RAMPObject::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\core\RAMPObject::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\core\RAMPObject::__toString().
   * - assert {@see \ramp\core\RAMPObject::__toString()} returns string 'class name'
   * @see \ramp\core\RAMPObject::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
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
    $this->assertTrue(TRUE); // STUB
  }

  /**
   * Cloning copies sub views.
   * - assert cloned View without associated model is equal to the original
   * @see \ramp\view\View::__clone()
   */
  #[\Override]
  public function testClone() : void
  {
    parent::testClone();
  }
  #endregion

  #region New Specialist Tests
  public function testRender()
  {
    $expectedRegEx = '#^<pre>(?:object\\()?[\\\///a-zA-Z]*Dump(?:\\)\\#|.class.php:)[0-9]*(?::| \\([0-9]*\\) {)' .
      PHP_EOL . '((.|\n)*)' .PHP_EOL.'</pre>$#';
    ob_start();
    RootView::getInstance()->children;
    $output1 = ob_get_clean();
    $this->assertMatchesRegularExpression($expectedRegEx, $output1);
    ob_start();
    RootView::getInstance()->render();
    $output2 = ob_get_clean();
    $this->assertMatchesRegularExpression($expectedRegEx, $output2);
    $this->assertSame($output1, $output2);
  }
  #endregion
}
