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
namespace tests\ramp\model\business\mocks\RecordTest;

use ramp\core\Str;
use ramp\core\OptionList;
use ramp\core\StrCollection;
use ramp\core\PropertyNotSetException;
use ramp\model\business\Record;
use ramp\model\business\field\Input;
use ramp\model\business\field\SelectOne;
use ramp\model\business\field\SelectMany;
use ramp\model\business\field\Relation;
use ramp\model\business\validation\dbtype\VarChar;

class ConcreteRecord extends Record
{
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('propertyA');
  }

  public function get_propertyA()
  {
    if (!isset($this[0])) {
      $this[0] = new Input(
        Str::set('propertyA'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule(),
          Str::set('$value does NOT evaluate to KEY')
        )
      );
    }
    return $this[0];
  }

  public function get_property1()
  {
    if (!isset($this[1])) {
      $this[1] = new SelectOne(
        Str::set('property1'),
        $this,
        new ConcreteOptionList()
      );
    }
    return $this[1];
  }

  public function get_property2()
  {
    if (!isset($this[2])) {
      $this[2] = new SelectMany(
        Str::set('property2'),
        $this,
        new ConcreteOptionList()
      );
    }
    return $this[2];
  }

  public function get_alphRelation()
  {
    if (!isset($this[3])) {
      $this[3] = new Relation(
        Str::set('alphRelation'),
        $this,
        Str::set('MockRecord')
      );
    }
    return $this[3];
  }

  protected static function checkRequired($dataObject) : bool
  {
    return isset($dataObject->propertyA);
  }
}
