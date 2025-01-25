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
namespace tests\ramp\mocks\view;

use ramp\core\Str;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 */
class MockRecord extends Record
{
  private bool $requiered;
  public ?Str $propertyName;
  public Str $title;
  public MockInput $input;

  public function __construct(\stdClass $dataObject = null, bool $setAllFieldsRequiered = FALSE)
  {
    $this->requiered = $setAllFieldsRequiered;
    $this->title = Str::set('Expanded description of expected field content.');
    parent::__construct($dataObject);
  }   

  protected function get_keyA() : ?RecordComponent
  {
    if ($this->register('keyA', RecordComponentType::KEY)) {
      $this->initiate(new MockInput(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation(Str::set('Error hint'))
      ));
    }
    return $this->registered; 
  }

  protected function get_keyB() : ?RecordComponent
  {
    if ($this->register('keyB', RecordComponentType::KEY)) {
      $this->initiate(new MockInput(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation(Str::set('Error hint'))
      ));
    }
    return $this->registered; 
  }

  protected function get_keyC() : ?RecordComponent
  {
    if ($this->register('keyC', RecordComponentType::KEY)) {
      $this->initiate(new MockInput(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation(Str::set('Error hint'))
      ));
    }
    return $this->registered; 
  }

  protected function get_aProperty() : ?RecordComponent
  {
    if ($this->register('aProperty', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->propertyName = $this->registeredName;
      $this->input = new MockInput(
        $this->propertyName, $this, $this->title,
        new MockDbTypeValidation(Str::set('Error hint'))
      );
      $this->initiate($this->input);
    }
    return $this->registered; 
  }
}
