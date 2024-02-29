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
// use ramp\core\StrCollection;
// use ramp\core\OptionList;
// use ramp\condition\PostData;
// use ramp\condition\Filter;
// use ramp\condition\SQLEnvironment;
use ramp\model\business\Record;
use ramp\model\business\field\Input;
// use ramp\model\business\field\Option;
// use ramp\model\business\field\SelectOne;
// use ramp\model\business\field\SelectMany;
// use ramp\model\business\RecordCollection;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\validation\dbtype\Char;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\dbtype\SmallInt;
use ramp\model\business\validation\dbtype\TinyInt;
use ramp\model\business\validation\dbtype\DecimalPointNumber;
// use ramp\model\business\validation\dbtype\Flag;
use ramp\model\business\validation\LowerCaseAlphanumeric;
use ramp\model\business\validation\RegexValidationRule;
use ramp\model\business\validation\HexidecimalColorCode;
use ramp\model\business\validation\TelephoneNumber;
// use ramp\model\business\validation\WholeNumber;
// use ramp\model\business\validation\Currency;
use ramp\model\business\validation\Password;

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 * 
 * protected function get_givenName() : ?RecordComponent
 * {
 *   if ($this->register('givenName', RecordComponentType::PROPERTY, TRUE)) {
 *     $this->initiate(new field\Input($this->registeredName, $this,
 *       new validation\dbType\VarChar(20,
 *         new validation\Pattern('[A-Za-z]',
 *           new validation\Special(NULL,
 *             Str::set('This given name is NOT special enough')
 *           ),
 *           Str::set('Please use only Alphanumeric characters')
 *         ),
 *         Str::set('Value Not compatable with datastore')
 *       )
 *       Str::set('The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters')
 *     ));
 *   }
 * }
 * 
 * protected function get_honorificPrefix() : ?RecordComponent
 * {
 *   if ($this->register('honorificPrefix', RecordComponentType::PROPERTY, TRUE)) {
 *     $this->initiate(new field\Input($this->registeredName, $this,
 *       new validation\dbType\VarChar(15,
 *         new validation\Pattern('[A-Za-z]',
 *           new validation\Special(NULL,
 *             Str::set('This title is NOT special enough')
 *           ),
 *           Str::set('Please use only Alphanumeric characters')
 *         ),
 *         Str::set('Value Not compatable with datastore')
 *       )
 *       Str::set('Titles prefixing a person's name, e.g.: Mx, Mr, Mrs, Miss, Ms, Sir, Dame, Dr, Cllr, Lady, or Lord, or other titles or positions'),
 *       DATALISTS[honorificPrefixes]
 *     ));
 *   }
 * }
 * 
 * protected function get_status() : ?RecordComponent
 * {
 *   if ($this->register('status', RecordComponentType::PROPERTY, 0)) {
 *     $this->initiate(new field\Input($this->registeredName, $this,,,, FALSE));
 *   }
 * }
 */
class ComprehensiveRecord extends Record
{
  protected function get_keyA() : ?RecordComponent
  {
    if ($this->register('keyA', RecordComponentType::KEY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new Char(
          // Str::set('My error message HERE!'),
          1, new LowerCaseAlphanumeric()
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_keyB() : ?RecordComponent
  {
    if ($this->register('keyB', RecordComponentType::KEY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new Char(
          // Str::set('My error message HERE!'),
          1, new LowerCaseAlphanumeric()
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_keyC() : ?RecordComponent
  {
    if ($this->register('keyC', RecordComponentType::KEY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new Char(
          // Str::set('My error message HERE!'),
          1, new LowerCaseAlphanumeric()
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_primaryColor() : ?RecordComponent
  {
    if ($this->register('primaryColor', RecordComponentType::PROPERTY)) {
      $this->initiate(new Input($this->registeredName, $this,
        new Char(
          // Str::set('My error message HERE!'),
          7, new HexidecimalColorCode()
        ),
        
      ));
    }
    return $this->registered; 
  }

  protected function get_givenName() : ?RecordComponent
  {
    if ($this->register('givenName', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        //Str::set('title'),
        new VarChar(
          Str::set('My error message HERE!'),
          20, new RegexValidationRule('[A-Za-z]*')
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_mobile() : ?RecordComponent
  {
    if ($this->register('mobile', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        new VarChar(
          Str::set('My error message HERE!'),
          12, new TelephoneNumber()
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_password() : ?RecordComponent
  {
    if ($this->register('password', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        new VarChar(
          Str::set('My error message HERE!'),
          35, new Password()
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_wholeNumber() : ?RecordComponent
  {
    if ($this->register('wholeNumber', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
      new SmallInt(Str::set('My error message HERE!'))));
    }
    return $this->registered; 
  }

  protected function get_currency() : ?RecordComponent
  {
    if ($this->register('currency', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
      new DecimalPointNumber(Str::set('My error message HERE!'), 2, 5)));
    }
    return $this->registered; 
  }

/*
input(
dbtype\MultiPart(
  VarChar(RegexValidation('',)),
  $split ['W']
  $dataProperties ['weekNumber', 'weekYear', 'weekTime']
  TinyInt(),
  TintInt(),
  Time(),
))
*/

  // protected function get_week() : ?RecordComponent
  // {
  //   if ($this->register('week', RecordComponentType::PROPERTY, TRUE)) {
  //     $this->initiate(new Input($this->registeredName, $this,
  //     new TinyInt(Str::set('My error message HERE!'), new Week())));
  //   }
  //   return $this->registered; 
  // }

  // protected function get_flag() : ?RecordComponent
  // {
  //   if ($this->register('flag', RecordComponentType::PROPERTY)) {
  //     $this->flagName = $this->registeredName;
  //     $this->initiate(new MockFlag($this->registeredName, $this
  //     ));
  //   }
  //   return $this->registered; 
  // }

  // protected function get_selectFrom() : ?RecordComponent
  // {
  //   if ($this->register('selectFrom', RecordComponentType::PROPERTY)) {
  //     $this->selectFromName = $this->registeredName;
  //     $this->selectFromList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
  //     $this->selectFromList->add(new MockOption(0, Str::set('Please choose:')));
  //     $this->selectFromList->add(new MockOption(1, $this->selectDescriptionOne));
  //     $this->selectFromList->add(new MockOption(2, Str::set('DESCRIPTION TWO')));  
  //     $this->initiate(new MockSelectFrom($this->registeredName, $this, $this->selectFromList));
  //   }
  //   return $this->registered; 
  // }

  // protected function get_selectOne() : ?RecordComponent
  // {
  //   if ($this->register('selectOne', RecordComponentType::PROPERTY)) {
  //     $this->selectOneName = $this->registeredName;
  //     $this->selectOneList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
  //     $this->selectOneList->add(new Option(0, Str::set('Please choose:')));
  //     $this->selectOneList->add(new Option(1, $this->selectDescriptionOne));
  //     $this->selectOneList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
  //     $this->initiate(new SelectOne($this->registeredName, $this, $this->selectOneList));
  //   }
  //   return $this->registered; 
  // }

  // protected function get_selectMany() : ?RecordComponent
  // {
  //   if ($this->register('selectMany', RecordComponentType::PROPERTY)) {
  //     $this->selectManyName = $this->registeredName;
  //     $this->selectManyList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
  //     $this->selectManyList->add(new Option(0, Str::set('Please choose:')));
  //     $this->selectManyList->add(new Option(1, $this->selectDescriptionOne));
  //     $this->selectManyList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
  //     $this->selectManyList->add(new Option(3, Str::set('DESCRIPTION THREE')));  
  //     $this->initiate(new SelectMany($this->registeredName, $this, $this->selectManyList));
  //   }
  //   return $this->registered; 
  // }

  // protected function get_relationAlpha() : ?RecordComponent
  // {
  //   if ($this->register((string)$this->relationAlphaName, RecordComponentType::RELATION)) {
  //     $this->initiate(new MockRelationToMany(
  //       $this->registeredName,
  //       $this,
  //       Str::set('MockMinRecord'),
  //       Str::set('relationDelta')
  //     ));
  //   }
  //   return $this->registered; 
  // }

  // protected function get_relationBeta() : ?RecordComponent
  // {
  //    if ($this->register((string)$this->relationBetaName, RecordComponentType::RELATION)) {
  //     $this->initiate(new MockRelationToOne(
  //       $this->registeredName,
  //       $this,
  //       Str::set('MockMinRecord')
  //     ));
  //   }
  //   return $this->registered; 
  // }
}
