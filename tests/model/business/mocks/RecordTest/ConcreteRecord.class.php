<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
namespace tests\svelte\model\business\mocks\RecordTest;

use svelte\core\Str;
use svelte\core\OptionList;
use svelte\core\PropertyNotSetException;
use svelte\model\business\Record;
use svelte\model\business\field\Input;
use svelte\model\business\field\SelectOne;
use svelte\model\business\field\SelectMany;

class ConcreteRecord extends Record
{
  public static function primaryKeyName() : Str
  {
    return Str::set('property1');
  }

  public function get_property1()
  {
    if (!isset($this['property1'])) {
      $this['property1'] = new Input(
        Str::set('property1'),
        $this,
        new ConcreteValidationRule()
      );
    }
    return $this['property1'];
  }

  public function get_property2()
  {
    if (!isset($this['property2'])) {
      $this['property2'] = new SelectOne(
        Str::set('property2'),
        $this,
        ConcreteOptionList::getInstance()
      );
    }
    return $this['property2'];
  }

  public function get_property3()
  {
    if (!isset($this['property3'])) {
      $this['property3'] = new SelectMany(
        Str::set('property3'),
        $this,
        ConcreteOptionList::getInstance()
      );
    }
    return $this['property3'];
  }

  protected function checkRequired($dataObject) : bool
  {
    return isset($dataObject->property1);
  }
}
