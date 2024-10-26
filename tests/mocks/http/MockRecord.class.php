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
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;

/**
 * Mock business record with property.
 * .
 */
class MockRecord extends Record
{
  protected function get_propertyA() : ?RecordComponent
  {
    if ($this->register('propertyA', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField($this->registeredName, $this, Str::set()));
    }
    return $this->registered;
  }

  protected function get_propertyB()
  {
    if ($this->register('propertyB', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField($this->registeredName, $this, Str::set()));
    }
    return $this->registered;
  }

  protected function get_propertyC() : ?RecordComponent
  {
    if ($this->register('propertyC', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField($this->registeredName, $this, Str::set()));
    }
    return $this->registered;
  }
}
