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

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

use ramp\model\business\Record;
use ramp\model\business\RecordCollection;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\Relatable;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\field\Field;

use tests\ramp\mocks\model\MockInput;
use tests\ramp\mocks\model\MockRelationToOne;
use tests\ramp\mocks\model\MockRelationToMany;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRecord extends Record
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  public $propertyName;
  public $relationAlphaName;
  public $relationAlphaWith;
  public $relationBetaName;
  public $relationBetaWith;
  public $inputName;

  public function __construct(\stdClass $dataObject = null)
  {
    $this->relationAlphaName = Str::set('relationAlpha');
    $this->relationBetaName = Str::set('relationBeta');
    $this->inputName = Str::set('input');
    parent::__construct($dataObject);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
  }   

  public function reset()
  {
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
    foreach ($this->primaryKey as $key) { 
      $key->validateCount = 0;
      $key->hasErrorsCount = 0;
    }
    foreach ($this as $property) { 
      $property->validateCount = 0;
      $property->hasErrorsCount = 0;
    }
  }

  protected function get_keyA() : ?RecordComponent
  {
    if ($this->register('keyA', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_keyB() : ?RecordComponent
  {
    if ($this->register('keyB', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_keyC() : ?RecordComponent
  {
    if ($this->register('keyC', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  protected function get_aProperty() : ?RecordComponent
  {
    if ($this->register('aProperty', RecordComponentType::PROPERTY)) {
      $this->propertyName = $this->registeredName;
      $this->initiate(new MockField($this->propertyName, $this));
    }
    return $this->registered; 
  }

  protected function get_relationAlpha() : ?RecordComponent
  {
    if ($this->register('relationAlpha', RecordComponentType::RELATION)) {
      $this->initiate(new MockRelationToMany(
        $this->registeredName,
        $this,
        Str::set('MockMinRecord'),
        Str::set('relationDelta')
      ));
    }
    return $this->registered; 
  }

  protected function get_relationBeta() : ?RecordComponent
  {
     if ($this->register('relationBeta', RecordComponentType::RELATION)) {
      $this->initiate(new MockRelationToOne(
        $this->registeredName,
        $this,
        Str::set('MockMinRecord')
      ));
    }
    return $this->registered; 
  }

  protected function get_input() : ?RecordComponent
  {
    if ($this->register('input', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField($this->registeredName, $this));
    }
    return $this->registered; 
  }

  public function validate(PostData $postdata) : void
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

  protected static function checkRequired($dataObject) : bool
  {
    return TRUE;
  }
}
