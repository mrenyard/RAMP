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
 * @package RAMP Testing
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\key\mocks\ForeignTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\field\Field;

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel for testing against.
 */
class ToRecord extends Record
{
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('keyA','keyB','keyC');
  }
/*
  protected function get_keyA()
  {
    if (!isset($this[1])) {
      $this[1] = new MockField(
        Str::set('keyA'),
        $this
      );
    }
    return $this[1]; 
  }

  protected function get_keyB()
  {
    if (!isset($this[2])) {
      $this[2] = new MockField(
        Str::set('keyB'),
        $this
      );
    }
    return $this[2]; 
  }
  protected function get_keyC()
  {
    if (!isset($this[3])) {
      $this[3] = new MockField(
        Str::set('keyC'),
        $this
      );
    }
    return $this[3];
  }

  protected function get_property()
  {
    if (!isset($this[0])) {
      $this[0] = new MockField(
        Str::set('property'),
        $this
      );
    }
    return $this[0]; 
  }
*/
  protected static function checkRequired($dataObject) : bool
  {
    // return (
    //   isset($dataObject->keyA) &&
    //   isset($dataObject->keyB) &&
    //   isset($dataObject->keyC)
    // );
  }
}
