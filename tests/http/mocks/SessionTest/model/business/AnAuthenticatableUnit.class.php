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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\model\business;

use svelte\core\Str;
use svelte\model\business\RecordCollection;
use svelte\model\business\AuthenticatableUnit;
use svelte\model\business\field\Field;
use svelte\model\business\field\Input;
use svelte\model\business\validation\dbtype\VarChar;
use svelte\model\business\validation\Alphanumeric;
use svelte\model\business\validation\LowerCaseAlphanumeric;
use svelte\model\business\validation\RegexEmail;


/**
 * Mock Collection of Person for testing \svelte\http\Session
 * .
 */
class AnAuthenticatibleUnitCollection extends RecordCollection
{
}

/**
 * Mock AuthenticatableUnit for testing \svelte\http\Session
 */
class AnAuthenticatableUnit extends AuthenticatableUnit
{
  private $primaryProperty;

  /**
   * Returns property name of concrete classes primary key.
   * @return \svelte\core\Str Name of property that is concrete classes primary key
   */
  public static function primaryKeyName() : Str { return Str::set('uname'); }

  protected function get_uname() : Field
  {
    if (!isset($this->primaryProperty))
    {
      $this->primaryProperty = new Input(
        Str::set('uname'),
        $this,
        new VarChar(
          15,
          new LowerCaseAlphanumeric(),
          Str::set('My error message HERE!')
        )
      );
      if ($this->isNew) { $this['uname'] = $this->primaryProperty; }
    }
    return $this->primaryProperty;
  }

  /**
   * Get familyName
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Email address associated with *this*.
   */
  protected function get_familyName() : Field
  {
    if (!isset($this['familyName']))
    {
      $this['familyName'] = new Input(
        Str::set('familyName'),
        $this,
        new VarChar(
          15,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['familyName'];
  }

  /**
   * Get givenName
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Email address associated with *this*.
   */
  protected function get_givenName() : Field
  {
    if (!isset($this['givenName']))
    {
      $this['givenName'] = new Input(
        Str::set('givenName'),
        $this,
        new VarChar(
          15,
          new Alphanumeric(), //CapitalizedFirstLetter
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['givenName'];
  }

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
