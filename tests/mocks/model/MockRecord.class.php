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
use ramp\core\OptionList;
use ramp\condition\PostData;
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;

use ramp\model\business\Record;
use ramp\model\business\field\Option;
use ramp\model\business\field\SelectOne;
use ramp\model\business\field\SelectMany;
use ramp\model\business\RecordCollection;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\validation\dbtype\Text;
use ramp\model\business\validation\dbtype\Flag;
use ramp\model\business\validation\Alphanumeric;

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 */
class MockRecord extends Record
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  public $relationAlphaName;
  public $relationBetaName;
  public $relationGammaWithRecordName;
  public $relationGammaWithPropertyName;
  public $selectDescriptionOne;
  public $propertyName;
  public $inputName;
  public $flagName;
  public $selectFromList;
  public $selectFromName;
  public $selectOneList;
  public $selectOneName;
  public $selectManyList;
  public $selectManyName;

  public function __construct(\stdClass $dataObject = null)
  {
    $this->relationAlphaName = Str::set('relationAlpha');
    $this->relationBetaName = Str::set('relationBeta');
    $this->relationGammaWithRecordName = Str::set('MockMinRecord');
    $this->relationGammaWithPropertyName = Str::set('relationDelta');
    $this->selectDescriptionOne = Str::set('DESCRIPTION ONE');
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
    foreach ($this->primaryKey as $key) { $key->reset(); }
    foreach ($this as $field) { 
      if ($field instanceof selectOne || $field instanceof selectMany) { continue; }
      $field->reset();
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

  protected function get_input() : ?RecordComponent
  {
    if ($this->register('input', RecordComponentType::PROPERTY)) {
      $this->inputName = $this->registeredName;
      $this->initiate(new MockInput($this->registeredName, $this,
        new Text(NULL, new MockValidationRule(), Str::set('Error MESSAGE BadValue Submited!'))
      ));
    }
    return $this->registered; 
  }

  protected function get_flag() : ?RecordComponent
  {
    if ($this->register('flag', RecordComponentType::PROPERTY)) {
      $this->flagName = $this->registeredName;
      $this->initiate(new MockFlag($this->registeredName, $this
      ));
    }
    return $this->registered; 
  }

  protected function get_selectFrom() : ?RecordComponent
  {
    if ($this->register('selectFrom', RecordComponentType::PROPERTY)) {
      $this->selectFromName = $this->registeredName;
      $this->selectFromList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $this->selectFromList->add(new MockOption(0, Str::set('Please choose:')));
      $this->selectFromList->add(new MockOption(1, $this->selectDescriptionOne));
      $this->selectFromList->add(new MockOption(2, Str::set('DESCRIPTION TWO')));  
      $this->initiate(new MockSelectFrom($this->registeredName, $this, $this->selectFromList));
    }
    return $this->registered; 
  }

  protected function get_selectOne() : ?RecordComponent
  {
    if ($this->register('selectOne', RecordComponentType::PROPERTY)) {
      $this->selectOneName = $this->registeredName;
      $this->selectOneList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $this->selectOneList->add(new Option(0, Str::set('Please choose:')));
      $this->selectOneList->add(new Option(1, $this->selectDescriptionOne));
      $this->selectOneList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
      $this->initiate(new SelectOne($this->registeredName, $this, $this->selectOneList));
    }
    return $this->registered; 
  }

  protected function get_selectMany() : ?RecordComponent
  {
    if ($this->register('selectMany', RecordComponentType::PROPERTY)) {
      $this->selectManyName = $this->registeredName;
      $this->selectManyList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $this->selectManyList->add(new Option(0, Str::set('Please choose:')));
      $this->selectManyList->add(new Option(1, $this->selectDescriptionOne));
      $this->selectManyList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
      $this->selectManyList->add(new Option(3, Str::set('DESCRIPTION THREE')));  
      $this->initiate(new SelectMany($this->registeredName, $this, $this->selectManyList));
    }
    return $this->registered; 
  }

  protected function get_relationAlpha() : ?RecordComponent
  {
    if ($this->register((string)$this->relationAlphaName, RecordComponentType::RELATION)) {
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
     if ($this->register((string)$this->relationBetaName, RecordComponentType::RELATION)) {
      $this->initiate(new MockRelationToOne(
        $this->registeredName,
        $this,
        Str::set('MockMinRecord')
      ));
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
}
