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
use ramp\model\business\validation\LowercaseAlphanumeric;

/**
 * Mock AuthenticatableUnit for testing.
 */
class AnAuthenticatableUnit extends AuthenticatableUnit
{
  protected function get_uname() : ?RecordComponent
  {
    if ($this->register('uname', RecordComponentType::KEY)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('A special single word used alongside your email to uniquely identify you on our network'),
        new VarChar( Str::set('e.g. aname'),
          Str::set('string with a maximum length of '),
          20, new LowercaseAlphanumeric(
            Str::set('lowercase, letter and number charactered')
          )
        )
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
        Str::set('The mostly hereditary portion of a persons name that indicates family, in wester culture often refered to as lastname or surname.'),
        new VarChar( Str::set('e.g. Smith'),
          Str::set('string with a maximum character length of '),
          50, new Alphanumeric(
            Str::set('numbered, lowercase and uppercase lettered')
          )
        )
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
        Str::set('The name by which you are refered by, in western culture usually your first name.'),
        new VarChar( Str::set('e.g. Jane'),
          Str::set('string with a maximum character length of '),
          50, new Alphanumeric(
            Str::set('numbered, lowercase and uppercase lettered')
          )
        )
      ));
    }
    return $this->registered; 
  }
}
