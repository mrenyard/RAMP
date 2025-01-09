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
use ramp\model\business\field\MultipartInput;
use ramp\model\business\RecordCollection;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\validation\dbtype\Flag;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\dbtype\SmallInt;
use ramp\model\business\validation\dbtype\TinyInt;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\ISOMonth;

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 */
class MockRecord extends Record
{
  private bool $requiered;
  
  public int $validateCount;
  public int $hasErrorsCount;
  public int $errorsTouchCount;

  public Str $relationAlphaName;
  public Str $relationBetaName;
  public Str $relationGammaWithRecordName;
  public Str $relationGammaWithPropertyName;
  public Str $selectDescriptionOne;

  public ?Str $propertyName;
  public ?Str $inputName;
  public ?Str $flagName;
  public OptionList $selectFromList;
  public ?Str $selectFromName;
  public OptionList $selectOneList;
  public ?Str $selectOneName;
  public OptionList $selectManyList;
  public ?Str $selectManyName;
  public ?Str $multipartInputName;

  public array $multipartInputDataProperties;

  public Str $title;

  public Str $placeholder;
  public Str $inputHint1;
  public Str $inputHint2;
  public int $maxlength;

  public Str $multipartHint1;
  public Str $multipartHint2;
  public Str $multipartHint3;
  public string $multipartPattern;
  public string $multipartFormat;
  public int $db1From; public int $db1To;
  public int $db2From; public int $db2To;

  public function __construct(\stdClass $dataObject = null, bool $setAllFieldsRequiered = FALSE)
  {
    $this->requiered = $setAllFieldsRequiered;
    $this->relationAlphaName = Str::set('relationAlpha');
    $this->relationBetaName = Str::set('relationBeta');
    $this->relationGammaWithRecordName = Str::set('MockMinRecord');
    $this->relationGammaWithPropertyName = Str::set('relationDelta');
    $this->selectDescriptionOne = Str::set('DESCRIPTION ONE');

    $this->title = Str::set('Expanded description of expected field content.');

    $this->inputHint1 = Str::set('format hint for MockValidationRule!');
    $this->inputHint2 = Str::set('with a character length of');
    $this->placeholder = Str::set('e.g. Some Text');
    $this->maxlength = 10;

    $this->multipartHint1 = Str::set('a 4 digit year from ');
    $this->db1From = 1901; $this->db1To = 2155;
    $this->multipartHint2 = Str::set('a 2 digit month number from ');
    $this->db2From = 01; $this->db2To = 12;
    $this->multipartHint3 = Str::set('formatted: ');
    $this->multipartFormat = 'YYYY-mm';
    $this->multipartPattern = '[0-9]{4}-[0-9]{2}';

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
      $this->initiate(new MockField($this->registeredName, $this, $this->title));
    }
    return $this->registered; 
  }

  protected function get_keyB() : ?RecordComponent
  {
    if ($this->register('keyB', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this, $this->title));
    }
    return $this->registered; 
  }

  protected function get_keyC() : ?RecordComponent
  {
    if ($this->register('keyC', RecordComponentType::KEY)) {
      $this->initiate(new MockField($this->registeredName, $this, $this->title));
    }
    return $this->registered; 
  }

  protected function get_aProperty() : ?RecordComponent
  {
    if ($this->register('aProperty', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->propertyName = $this->registeredName;
      $this->initiate(new MockField(
        $this->propertyName, $this, $this->title));
    }
    return $this->registered; 
  }

  protected function get_input() : ?RecordComponent
  {
    if ($this->register('input', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->inputName = $this->registeredName;
      $this->initiate(new MockInput(
        $this->registeredName, $this, $this->title,
        new VarChar($this->placeholder,
          $this->inputHint2, $this->maxlength,
          // new MockRegexValidationRule(
          //   $this->inputHint1,
          //   $this->inputPattern
          // )
          new MockValidationRule(
            $this->inputHint1 
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_flag() : ?RecordComponent
  {
    if ($this->register('flag', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->flagName = $this->registeredName;
      $this->initiate(new MockFlag(
        $this->registeredName, $this, $this->title));
    }
    return $this->registered;
  }

  protected function get_selectFrom() : ?RecordComponent
  {
    if ($this->register('selectFrom', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->selectFromName = $this->registeredName;
      $this->selectFromList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $this->selectFromList->add(new MockOption(0, Str::set('Please choose:')));
      $this->selectFromList->add(new MockOption(1, $this->selectDescriptionOne));
      $this->selectFromList->add(new MockOption(2, Str::set('DESCRIPTION TWO')));  
      $this->initiate(new MockSelectFrom($this->registeredName, $this,
        $this->title,
        $this->selectFromList
      ));
    }
    return $this->registered; 
  }

  protected function get_selectOne() : ?RecordComponent
  {
    if ($this->register('selectOne', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->selectOneName = $this->registeredName;
      $this->selectOneList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $this->selectOneList->add(new Option(0, Str::set('Please choose:')));
      $this->selectOneList->add(new Option(1, $this->selectDescriptionOne));
      $this->selectOneList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
      $this->initiate(new SelectOne($this->registeredName, $this,
        $this->title,
        $this->selectOneList
      ));
    }
    return $this->registered; 
  }

  protected function get_selectMany() : ?RecordComponent
  {
    if ($this->register('selectMany', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->selectManyName = $this->registeredName;
      $this->selectManyList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $this->selectManyList->add(new Option(0, Str::set('Please choose:')));
      $this->selectManyList->add(new Option(1, $this->selectDescriptionOne));
      $this->selectManyList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
      $this->selectManyList->add(new Option(3, Str::set('DESCRIPTION THREE')));  
      $this->initiate(new SelectMany($this->registeredName, $this,
        $this->title,
        $this->selectManyList
      ));
    }
    return $this->registered;
  }

  protected function get_multipartInput() : ?RecordComponent
  {
    if ($this->register('multipartInput', RecordComponentType::PROPERTY, $this->requiered)) {
      $this->multipartInputName = $this->registeredName;
      $this->multipartInputDataProperties = ['monthYear', 'monthNumber'];
      $this->initiate(new MockMultipartInput($this->registeredName, $this,
        $this->title,
        new MockFormatBasedValidationRule($this->multipartHint3, $this->multipartPattern, $this->multipartFormat),
        ['-'],
        $this->multipartInputDataProperties,
        new SmallInt($this->multipartHint1, $this->db1From, $this->db1To),
        new TinyInt($this->multipartHint2, $this->db2From, $this->db2To)
      ));
    }
    return $this->registered;
  }

  protected function get_relationAlpha() : ?RecordComponent
  {
    if ($this->register((string)$this->relationAlphaName, RecordComponentType::RELATION, $this->requiered)) {
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
     if ($this->register((string)$this->relationBetaName, RecordComponentType::RELATION, $this->requiered)) {
      $this->initiate(new MockRelationToOne(
        $this->registeredName,
        $this,
        Str::set('MockMinRecord')
      ));
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
