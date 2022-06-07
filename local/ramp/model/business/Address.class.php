<?php
/**
 * RAMP - Rapid web application development using best practice.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordCollection;

/**
 * Collection of Address.
 */
class AddressCollection extends RecordCollection { }

/**
 * Concrete Record for Address.
 */
class Address extends Record
{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('countryCode','postalCode','extendedAddress'); }

  protected function get_extendedAddress() : field\Field
  {
    if (!isset($this['extendedAddress']))
    {
      $this['extendedAddress'] = new field\Input(
        Str::set('extendedAddress'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['extendedAddress'];
  }

  protected function get_streetAddress() : field\Field
  {
    if (!isset($this['streetAddress']))
    {
      $this['streetAddress'] = new field\Input(
        Str::set('streetAddress'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['streetAddress'];
  }

  protected function get_locality() : field\Field
  {
    if (!isset($this['locality']))
    {
      $this['locality'] = new field\Input(
        Str::set('locality'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['locality'];
  }

  protected function get_region() : field\Field
  {
    if (!isset($this['region']))
    {
      $this['region'] = new field\Input(
        Str::set('region'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['region'];
  }

  protected function get_postalCode() : field\Field
  {
    if (!isset($this['postalCode']))
    {
      $this['postalCode'] = new field\Input(
        Str::set('postalCode'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['postalCode'];
  }

  protected function get_countryCode() : field\Field
  {
    if (!isset($this['countryCode']))
    {
      // $this['countryCode'] = new field\SelectOne(
      //   Str::set('countryCode'),
      //   $this,
      //   new LoginAccountType()
      // );
      
      $this['countryCode'] = new field\Input(
        Str::set('countryCode'),
        $this,
        new validation\dbtype\VarChar(
          2,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['countryCode'];
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->countryCode) &&
      isset($dataObject->extendedAddress) &&
      isset($dataObject->postalCode)
    );
  }
}
