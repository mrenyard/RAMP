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
use ramp\model\business\field\Input;

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 */
class MockRecord extends Record
{
  public ?Str $propertyName;
  public Str $title;
  public Input $input;
  public Str $hint;

  public function __construct(\stdClass $dataObject = null)
  {
    $this->title = Str::set('Expanded description of expected field content.');
    $this->hint = Str::set('Error hint');
    parent::__construct($dataObject);
  }   

  protected function get_keyA() : ?RecordComponent
  {
    if ($this->register('keyA', RecordComponentType::KEY)) {
      $this->initiate(new Input(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation($this->hint)
      ));
    }
    return $this->registered; 
  }

  protected function get_keyB() : ?RecordComponent
  {
    if ($this->register('keyB', RecordComponentType::KEY)) {
      $this->initiate(new Input(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation($this->hint)
      ));
    }
    return $this->registered; 
  }

  protected function get_keyC() : ?RecordComponent
  {
    if ($this->register('keyC', RecordComponentType::KEY)) {
      $this->initiate(new Input(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation($this->hint)
      ));
    }
    return $this->registered; 
  }

  protected function get_aProperty() : ?RecordComponent
  {
    if ($this->register('aProperty', RecordComponentType::PROPERTY)) {
      $this->propertyName = $this->registeredName;
      $this->input = new Input(
        $this->propertyName, $this, $this->title,
        new MockDbTypeValidation($this->hint)
      );
      $this->initiate($this->input);
    }
    return $this->registered; 
  }

  protected function get_readonlyProperty() : ?RecordComponent
  {
    if ($this->register('readonlyProperty', RecordComponentType::PROPERTY)) {
      $this->initiate(new Input(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation($this->hint),
        FALSE
      ));
    }
    return $this->registered;
  }

  protected function get_requiredProperty() : ?RecordComponent
  {
    if ($this->register('requiredProperty', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input(
        $this->registeredName, $this, $this->title,
        new MockDbTypeValidation($this->hint)
      ));
    }
    return $this->registered;
  }
}
