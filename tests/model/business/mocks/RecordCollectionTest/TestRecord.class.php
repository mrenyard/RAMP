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
namespace tests\ramp\model\business\mocks\RecordCollectionTest;

use ramp\core\Str;
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\field\Input;
use ramp\model\business\validation\dbtype\VarChar;

use tests\ramp\model\business\mocks\RecordTest\ConcreteValidationRule;
use tests\ramp\model\business\mocks\RecordCollectionTest\ConcreteValidationRule2;

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel for testing against.
 * .
 */
class TestRecord extends Record
{
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('keyProperty');
  }

  protected function get_keyProperty()
  {
    if (!isset($this[0])) {
      $this[0] = new Input(
        Str::set('keyProperty'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[0];
  }

  protected function get_aProperty()
  {
    if (!isset($this[1])) {
      $this[1] = new Input(
        Str::set('aProperty'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule2(),
          Str::set('$value does NOT evaluate to GOOD')
        )
      );
    }
    return $this[1];
  }

 protected static function checkRequired($dataObject) : bool
  {
    return isset($dataObject->keyProperty);
  }
}
