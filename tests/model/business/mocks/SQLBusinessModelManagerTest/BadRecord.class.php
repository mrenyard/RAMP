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
namespace tests\svelte\model\business\mocks\SQLBusinessModelManagerTest;

use svelte\core\Str;
use svelte\model\business\Record;
use svelte\model\business\RecordCollection;
use svelte\model\business\field\Field;
use svelte\model\business\field\Input;
use svelte\model\business\validation\VarChar;
use svelte\model\business\validation\Alphanumeric;
use svelte\model\business\validation\LowerCaseAlphanumeric;

/**
 * Mock Concreate implementation of \svelte\model\business\RecordCollection for testing against.
 *
 * @property-read \svelte\model\business\field\Field $property eturns field containing value of property.
 * @property-read \svelte\model\business\field\Field $propertyA eturns field containing value of propertyA.
 * @property-read \svelte\model\business\field\Field $propertyB eturns field containing value of propertyB.
 * @property-read \svelte\model\business\field\Field $propertyC eturns field containing value of propertyC.
 */
class BadRecordCollection extends RecordCollection { }

/**
 * Mock Concreate implementation of \svelte\model\business\Record for testing against.
 */
class BadRecord extends Record
{
  /**
   * Returns property name of concrete classes primary key.
   * @return \svelte\core\Str Name of property that is concrete classes primary key
   */
  public static function primaryKeyName() : Str { return Str::set('property'); }

  /**
   * Get field containing property
   * **DO NOT CALL DIRECTLY, USE this->property;**
   * @return \svelte\model\business\field\Field Returns field containing value of property
   */
  protected function get_property() : Field
  {
    if (!isset($this['property']))
    {
      $this['property'] = new Input(
        Str::set('property'),
        $this,
        new VarChar(10, new LowerCaseAlphanumeric())
      );
    }
    return $this['property'];
  }

  /**
   * Get field containing propertyA
   * **DO NOT CALL DIRECTLY, USE this->propertyA;**
   * @return \svelte\model\business\field\Field Returns field containing value of propertyA
   */
  protected function get_propertyA() : Field
  {
    if (!isset($this['propertyA']))
    {
      $this['propertyA'] = new Input(
        Str::set('propertyA'),
        $this,
        new VarChar(10, new Alphanumeric())
      );
    }
    return $this['propertyA'];
  }

  /**
   * Get field containing propertyB
   * **DO NOT CALL DIRECTLY, USE this->propertyB;**
   * @return \svelte\model\business\field\Field Returns field containing value of propertyB
   */
  protected function get_propertyB() : Field
  {
    if (!isset($this['propertyB']))
    {
      $this['propertyB'] = new Input(
        Str::set('propertyB'),
        $this,
        new VarChar(10, new Alphanumeric())
      );
    }
    return $this['propertyB'];
  }

  /**
   * Get field containing propertyC
   * **DO NOT CALL DIRECTLY, USE this->propertyC;**
   * @return \svelte\model\business\field\Field Returns field containing value of propertyC
   */
  protected function get_propertyC() : Field
  {
    if (!isset($this['propertyC']))
    {
      $this['propertyC'] = new Input(
        Str::set('propertyC'),
        $this,
        new VarChar(10, new Alphanumeric())
      );
    }
    return $this['propertyC'];
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
