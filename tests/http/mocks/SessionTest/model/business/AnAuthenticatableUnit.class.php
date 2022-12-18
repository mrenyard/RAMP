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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\AuthenticatableUnit;
use ramp\model\business\field\Field;
use ramp\model\business\field\Input;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\LowerCaseAlphanumeric;
use ramp\model\business\validation\RegexEmail;

/**
 * Mock AuthenticatableUnit for testing \ramp\http\Session
 */
class AnAuthenticatableUnit extends AuthenticatableUnit
{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('uname'); }

  // private $primaryProperty;
  private $uname;
  protected function get_uname() : Field
  {
    if (!isset($this->uname))
    {
      $this->uname = new Input(
        Str::set('uname'),
        $this,
        new VarChar(
          15,
          new LowerCaseAlphanumeric(),
          Str::set('My error message HERE!')
        )
      );
      if ($this->isNew) { $this[-1] = $this->uname; }
    }
    return $this->uname;
  }

  /**
   * Get familyName
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Email address associated with *this*.
   */
  protected function get_familyName() : Field
  {
    if (!isset($this[1]))
    {
      $this[1] = new Input(
        Str::set('familyName'),
        $this,
        new VarChar(
          15,
          new Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[1];
  }

  /**
   * Get givenName
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Email address associated with *this*.
   */
  protected function get_givenName() : Field
  {
    if (!isset($this[2]))
    {
      $this[2] = new Input(
        Str::set('givenName'),
        $this,
        new VarChar(
          15,
          new Alphanumeric(), //CapitalizedFirstLetter
          Str::set('My error message HERE!')
        )
      );
    }
    return $this[2];
  }

  /**
   * Check requeried properties have value or not.
   * @param dataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->email) &&
      isset($dataObject->uname)
    );
  }
}
