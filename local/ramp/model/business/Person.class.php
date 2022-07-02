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

/**
 * Collection of Person.
 */
class PersonCollection extends RecordCollection { }

/**
 * Concrete Record for Person.
 */
class Person extends AuthenticatableUnit{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('uname');
  }

  private $uname;
  protected function get_uname() : field\Input
  {
    if (!isset($this->uname))
    {
      $this->uname = new field\Input(
        Str::set('uname'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
      if ($this->isNew) { $this['uname'] = $this->uname; }
    }
    return $this->uname;
  }

  protected function get_honorificPrefix() : field\Input
  {
    if (!isset($this['honorificPrefix']))
    {
      $this['honorificPrefix'] = new field\Input(
        Str::set('honorificPrefix'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['honorificPrefix'];
  }

  protected function get_givenName() : field\Input
  {
    if (!isset($this['givenName']))
    {
      $this['givenName'] = new field\Input(
        Str::set('givenName'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['givenName'];
  }

  protected function get_additionalNames() : field\Input
  {
    if (!isset($this['additionalNames']))
    {
      $this['additionalNames'] = new field\Input(
        Str::set('additionalNames'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['additionalNames'];
  }

  protected function get_familyName() : field\Input
  {
    if (!isset($this['familyName']))
    {
      $this['familyName'] = new field\Input(
        Str::set('familyName'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['familyName'];
  }

  protected function get_honorificSuffix() : field\Input
  {
    if (!isset($this['honorificSuffix']))
    {
      $this['honorificSuffix'] = new field\Input(
        Str::set('honorificSuffix'),
        $this,
        new validation\dbtype\VarChar(
          45,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['honorificSuffix'];
  }

  /*
  protected function get_primaryAddressID() : field\Field
  {
    if (!isset($this['primaryAddressID']))
    {
      $this['primaryAddressID'] = new field\Relation(
        Str::set('primaryAddressID'),
        $this
        // Get Business Model From Data Store.
      );
    }
    return $this['primaryAddressID'];
  }*/

  /*
  protected function get_primaryPhoneNumberID() : field\Field
  {
    if (!isset($this['primaryPhoneNumberID']))
    {
      $this['primaryPhoneNumberID'] = new field\Relation(
        Str::set('primaryPhoneNumberID'),
        $this
        // Get Business Model From Data Store.
      );
    }
    return $this['primaryPhoneNumberID'];
  }*/

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->uname) 
    );
  }
}
