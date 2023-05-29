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
namespace tests\ramp\model\business\mocks\SQLBusinessModelManagerTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordCollection;
use ramp\model\business\field\Field;
use ramp\model\business\field\Input;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\LowerCaseAlphanumeric;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\dbtype\UniquePrimaryKey;

/**
 * Mock Concreate implementation of \ramp\model\business\RecordCollection for testing against.
 */
class MockRecordCollection extends RecordCollection { }

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 *
 * @property-read \ramp\model\business\field\Field $property Returns field containing value of property.
 * @property-read \ramp\model\business\field\Field $propertyA Returns field containing value of propertyA.
 * @property-read \ramp\model\business\field\Field $propertyB Returns field containing value of propertyB.
 * @property-read \ramp\model\business\field\Field $propertyC Returns field containing value of propertyC.
 */
class MockRecord extends Record
{

  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('property'); }

  protected function get_property() : Field
  {
    if (!isset($this[-1]))
    {
      $this[-1] = new Input(
        Str::set('property'),
        $this,
        new VarChar(
          10,
          new LowerCaseAlphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[-1];
  }

  protected function get_propertyA() : Field
  {
    if (!isset($this[1]))
    {
      $this[1] = new Input(
        Str::set('propertyA'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[1];
  }

  protected function get_propertyB() : Field
  {
    if (!isset($this[2]))
    {
      $this[2] = new Input(
        Str::set('propertyB'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[2];
  }

  protected function get_propertyC() : Field
  {
    if (!isset($this[3]))
    {
      $this[3] = new Input(
        Str::set('propertyC'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[3];
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->property)
    );
  }
}
