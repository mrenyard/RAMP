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
namespace ramp\model\business;

use ramp\core\Str;
use ramp\model\business\AuthenticatableUnit;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\field\Input;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\LowerCaseAlphanumeric;

/**
 * Mock AuthenticatableUnit for testing.
 */
class AnAuthenticatableUnit extends AuthenticatableUnit
{
  protected function get_uname() : ?RecordComponent
  {
    if ($this->register('uname', RecordComponentType::KEY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new VarChar(20, new LowerCaseAlphanumeric(), Str::set('My error message HERE!'))
      ));
    }
    return $this->registered; 
  }

  /**
   * Get familyName
   * **DO NOT CALL DIRECTLY, USE:**
   * ```php
   * $this->id;
   * ```
   * @return \ramp\core\Str Email address associated with *this*.
   */
  protected function get_familyName() : ?RecordComponent
  {
    if ($this->register('familyName', RecordComponentType::PROPERTY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new VarChar(150, new Alphanumeric(), Str::set('My error message HERE!'))
      ));
    }
    return $this->registered; 
  }

  /**
   * Get givenName
   * **DO NOT CALL DIRECTLY, USE:**
   * ```php
   * $this->id;
   * ```
   * @return \ramp\core\Str Email address associated with *this*.
   */
  protected function get_givenName() : ?RecordComponent
  {
    if ($this->register('givenName', RecordComponentType::PROPERTY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new VarChar(150, new Alphanumeric(), Str::set('My error message HERE!'))
      ));
    }
    return $this->registered; 
  }

  /**
   * Check requeried properties have value or not.
   * @param $dataObject Data object to checke for requiered property values.
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return TRUE;
  }
}
