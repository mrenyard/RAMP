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
namespace tests\ramp\model\business\mocks\SQLBusinessModelManagerTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordCollection;
use ramp\model\business\field\Field;
use ramp\model\business\field\Input;
use ramp\model\business\field\MultiPartPrimary;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\LowerCaseAlphanumeric;
use ramp\model\business\validation\dbtype\VarChar;

/**
 * Mock Concreate implementation of \ramp\model\business\RecordCollection for testing against.
 */
class MockRecordMultiKeyCollection extends RecordCollection { }

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 */
class MockRecordMultiKey extends Record
{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('propertyA','propertyB','propertyC'); }

  protected function get_propertyA() : Field
  {
    if (!isset($this['propertyA']))
    {
      $this['propertyA'] = new Input(
        Str::set('propertyA'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['propertyA'];
  }

  protected function get_propertyB() : Field
  {
    if (!isset($this['propertyB']))
    {
      $this['propertyB'] = new Input(
        Str::set('propertyB'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['propertyB'];
  }

  protected function get_propertyC() : Field
  {
    if (!isset($this['propertyC']))
    {
      $this['propertyC'] = new Input(
        Str::set('propertyC'),
        $this,
        new VarChar(
          10,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['propertyC'];
  }

  protected function get_propertyD() : Field
  {
    if (!isset($this['propertyD']))
    {
      $this['propertyD'] = new Input(
        Str::set('propertyD'),
        $this,
        new VarChar(
          15,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['propertyD'];
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->propertyA) &&
      isset($dataObject->propertyB) &&
      isset($dataObject->propertyC)
    );
  }
}
