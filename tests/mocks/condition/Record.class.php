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
namespace tests\ramp\mocks\condition;

use ramp\core\RAMPObject;

/**
 * Mock business record with properties.
 * .
 */
class Record extends RAMPObject
{
  private $propertyA;
  private $propertyB;
  private $propertyC;
  private $propertyInt;

  protected function get_propertyA()
  {
    if (!isset($this->propertyA)) { $this->propertyA = new Field(); }
    return $this->propertyA;
  }

  protected function get_propertyB()
  {
    if (!isset($this->propertyB)) { $this->propertyB = new Field(); }
    return $this->propertyB;
  }

  protected function get_propertyC()
  {
    if (!isset($this->propertyC)) { $this->propertyC = new Field(); }
    return $this->propertyC;
  }

  protected function get_propertyInt()
  {
    if (!isset($this->propertyInt)) { $this->propertyInt = new Field(); }
    return $this->propertyInt;
  }
}
