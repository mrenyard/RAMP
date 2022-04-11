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
namespace tests\ramp\view\mocks\ViewTest;

use ramp\core\Str;
use ramp\view\View;

/**
 * Mock Concreate implementation of \ramp\view\View for testing login against.
 */
class MockView extends View
{
  /**
   */
  public function render()
  {
    print($this);
    if ($this->bProperty != NULL) {
      print(':' . $this->bProperty);
    }
    print(' ');
    $this->children;
  }
}

class MockViewA extends MockView {}
class MockViewB extends MockView {}
class MockViewC extends MockView {}
class MockViewD extends MockView {}