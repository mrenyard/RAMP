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
namespace tests\ramp\model\business\field\mocks\FlagTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\field\Flag;
use ramp\model\business\field\Input;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\dbtype\VarChar;

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel for testing against.
 */
class MockRecord extends Record
{
  public static $setPropertyCallCount = 0;
  public static function reset() { self::$setPropertyCallCount = 0; }
  public function setPropertyValue(string $propertyName, $value)
  {
    self::$setPropertyCallCount++;
    parent::setPropertyValue($propertyName, $value);
  }

  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('key');
  }

  public function get_key()
  {
    if (!isset($this[-1])) {
      $this[-1] = new Input(
        Str::set('key'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('$value does NOT evaluate to KEY')
        )
      );
    }
    return $this[-1];
  }

  protected function get_aProperty()
  {
  }
  
  protected static function checkRequired($dataObject) : bool
  {
  }
}
