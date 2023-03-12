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
namespace tests\ramp\model\business\field\mocks\PrimaryKeyTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordCollection;
use ramp\model\business\field\Field;
use ramp\model\business\field\Input;
use ramp\model\business\validation\dbtype\VarChar;

use tests\ramp\model\business\field\mocks\PrimaryKeyTest\ConcreteValidationRule;

/**
 * Collection of MockRecord.
 */
class MockRecordCollection extends RecordCollection { }

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel for testing against.
 */
class MockRecord extends Record
{
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('aProperty','bProperty','cProperty');
  }

  protected function get_aProperty() : Field
  {
    if (!isset($this[0])) {
      $this[0] = new Input(
        Str::set('aProperty'),
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

  protected function get_bProperty() : Field
  {
    if (!isset($this[1])) {
      $this[1] = new Input(
        Str::set('bProperty'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule(),
          Str::set('$value does NOT evaluate to KEY')
        )
      );
    }
    return $this[1];
  }

  protected function get_cProperty() : Field
  {
    if (!isset($this[2])) {
      $this[2] = new Input(
        Str::set('cProperty'),
        $this,
        new VarChar(
          10,
          new ConcreteValidationRule(),
          Str::set('$value does NOT evaluate to KEY')
        )
      );
    }
    return $this[2];
  }
  
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->aProperty) &&
      isset($dataObject->bProperty) &&
      isset($dataObject->cProperty)
    );
  }
}
