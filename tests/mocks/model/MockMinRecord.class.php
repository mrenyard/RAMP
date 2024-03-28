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
 * You should have received a copy of the GNU General Public License along relationAlphaWith this program; if
 * not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\mocks\model;

use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\field\SelectOne;
use ramp\model\business\field\SelectMany;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockMinRecord extends Record
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  public $propertyName;
  private $withError;

  public function __construct($dataObject = NULL, $withError = FALSE)
  {
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
    $this->withError = $withError;
    parent::__construct($dataObject);
  }

  public function reset()
  {
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
    foreach ($this->primaryKey as $key) { $key->reset(); }
    foreach ($this as $field) { 
      if ($field instanceof selectOne || $field instanceof selectMany) { continue; }
      $field->reset();
    }
  }

  protected function get_key1() : ?RecordComponent
  {
    if ($this->register('key1', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_key2() : ?RecordComponent
  {
    if ($this->register('key2', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_key3() : ?RecordComponent
  {
    if ($this->register('key3', RecordComponentType::KEY)) {
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
      $this->initiate(new MockField($this->registeredName, $this, $this->withError));
    }
    return $this->registered; 
  }

  public function validate(PostData $postdata, $update = TRUE) : void
  {
    $this->validateCount++;
    parent::validate($postdata);
  }

  public function get_hasErrors() : bool
  {
    $this->hasErrorsCount++;
    return parent::get_hasErrors();
  }

  public function get_errors() : StrCollection
  {
    $this->errorsTouchCount++;
    return parent::get_errors();
  }
}