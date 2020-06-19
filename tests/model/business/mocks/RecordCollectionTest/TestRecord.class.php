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
namespace tests\svelte\model\business\mocks\RecordCollectionTest;

use svelte\core\Str;
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\business\Record;
use svelte\model\business\field\Input;
use svelte\model\business\validation\dbtype\VarChar;

use tests\svelte\model\business\mocks\RecordTest\ConcreteValidationRule;
use tests\svelte\model\business\mocks\RecordCollectionTest\ConcreteValidationRule2;

/**
 * Mock Concreate implementation of \svelte\model\business\BusinessModel for testing against.
 * .
 */
class TestRecord extends Record
{
  public static function primaryKeyName() : Str
  {
    return Str::set('keyProperty');
  }

  protected function get_keyProperty()
  {
    if (!isset($this['keyProperty'])) {
      $this['keyProperty'] = new Input(
        Str::set('keyProperty'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['keyProperty'];
  }

  protected function get_aProperty()
  {
    if (!isset($this['aProperty'])) {
      $this['aProperty'] = new Input(
        Str::set('aProperty'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule2(),
          Str::set('$value does NOT evaluate to GOOD')
        )
      );
    }
    return $this['aProperty'];
  }

 protected static function checkRequired($dataObject) : bool
  {
    return isset($dataObject->keyProperty);
  }
}
