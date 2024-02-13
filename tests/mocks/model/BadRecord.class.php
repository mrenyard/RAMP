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
namespace tests\ramp\mocks\model;

// use ramp\core\Str;
// use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
// use ramp\model\business\RecordCollection;
// use ramp\model\business\field\Field;
// use ramp\model\business\field\Input;
// use ramp\model\business\validation\Alphanumeric;
// use ramp\model\business\validation\LowerCaseAlphanumeric;
// use ramp\model\business\validation\dbtype\VarChar;

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 */
class BadRecord extends Record
{

  protected function get_key() : ?RecordComponent
  {
    if ($this->register('key', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_property1() : ?RecordComponent
  {
    if ($this->register('property1', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_property2() : ?RecordComponent
  {
    if ($this->register('property2', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }
}
