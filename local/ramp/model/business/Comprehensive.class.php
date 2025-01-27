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

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
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
 *   if ($this->register('status', RecordComponentType::PROPERTY)) {
 *     $this->initiate(new field\Input($this->registeredName, $this,,,, FALSE));
 *   }
 * }
 */
class Comprehensive extends Record
{
  protected function get_keyA() : ?RecordComponent
  {
    if ($this->register('keyA', RecordComponentType::KEY)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('First single character key element.'),
        new validation\dbtype\Char(
          Str::set('e.g. A'),
          Str::set('string with a total character length of exactly '), 1,
          new validation\AlphanumericStrict(
            Str::set('strictly latin alphabetical or numeric')
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_keyB() : ?RecordComponent
  {
    if ($this->register('keyB', RecordComponentType::KEY)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('Second single character key element.'),
        new validation\dbtype\Char(
          Str::set('e.g. B'),
          Str::set('string with a total character length of exactly '), 1,
          new validation\AlphanumericStrict(
            Str::set('strictly latin alphabetical or numeric')
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_keyC() : ?RecordComponent
  {
    if ($this->register('keyC', RecordComponentType::KEY)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('Third single character key element.'),
        new validation\dbtype\Char(
          Str::set('e.g. C'),
          Str::set('string with a total character length of exactly '), 1,
          new validation\AlphanumericStrict(
            Str::set('strictly latin alphabetical or numeric')
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_primaryColor() : ?RecordComponent
  {
    if ($this->register('primaryColor', RecordComponentType::PROPERTY)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('Main thematic colour of presentation.'),
        new validation\dbtype\Char(
          Str::set('e.g. #FF0000'),
          Str::set('string with a character length of exactly '), 7,
          new validation\HexidecimalColorCode(
            Str::set('representing the luminescent gradiant of red, green and blue, a hash followed by three pairs of hexadecimal (0 through 9 to F) character ')
          )
        ),
        
      ));
    }
    return $this->registered; 
  }

  protected function get_givenName() : ?RecordComponent
  {
    if ($this->register('givenName', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('The name by which you are refered to; in Western culture usually your first name.'),
        new validation\dbtype\VarChar(
          Str::set('e.g. Jane'),
          Str::set('string with a maximum character length of '), 20,
          new validation\RegexValidationRule(
            Str::set('single latin alphabetical word'),
            '[A-Za-z\-]*'
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_email() : ?RecordComponent
  {
    if ($this->register('email', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('A uniquely identified electronic mailbox at which you receive written messages.'),
        new validation\dbtype\VarChar(
          Str::set('e.g. jdoe@domain.com'),
          Str::set('string with a maximum character length of '), 150,
          new validation\RegexEmailAddress(
            Str::set('validly formatted email address'),
            new validation\specialist\ServerSideEmail()
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_mobile() : ?RecordComponent
  {
    if ($this->register('mobile', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('The number used to contact (call or text) said particular persons mobile device.'),
        new validation\dbtype\VarChar(
          Str::set('e.g. 07700 1234 5678'),
          Str::set('string with a maximum character length of '), 20,
          new validation\TelephoneNumber(
            Str::set('valid telephone number')
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_password() : ?RecordComponent
  {
    if ($this->register('password', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('The secret word or phrase that you wish to used to confirm your identity and gain access.'),
        new validation\dbtype\VarChar(
          Str::set('e.g. N0T-PA55W0RD'),
          Str::set('string with a maximum character length of '), 35,
          new validation\Password(
            Str::set("8 character minimum alphanumeric and special characters (!#$%&+,-.:;?[]^*_{|}{~@')")
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_wholeNumber() : ?RecordComponent
  {
    if ($this->register('wholeNumber', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('The non fractional number related to this query'),
        new validation\dbtype\SmallInt(Str::set('whole number from '))
      ));
    }
    return $this->registered; 
  }

  protected function get_currency() : ?RecordComponent
  {
    if ($this->register('currency', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('The ammount of money in UK pounds and pence that you have access to.'),
        new validation\dbtype\DecimalPointNumber(Str::set('place decimal point number'), 2, 5)
      ));
    }
    return $this->registered; 
  }

  protected function get_week() : ?RecordComponent
  {
    if ($this->register('week', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\MultipartInput($this->registeredName, $this, 
        Str::set('expanded description of expected field content'),
        new validation\ISOWeek(Str::set('valid week formatted (yyyy-W00)'), Str::set('2022-W01'), Str::set('2024-W52')),
        ['-W'],
        ['weekYear', 'weekNumber'],
        new validation\dbtype\SmallInt(Str::set('4 digit year from '), 0, 9999),
        new validation\dbtype\TinyInt(Str::set('2 digit week number from '), 1, 53)
      ));
    }
    return $this->registered;
  }

  protected function get_month() : ?RecordComponent
  {
    if ($this->register('month', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\MultipartInput($this->registeredName, $this,
        Str::set('expanded description of expected field content'),
        new validation\ISOMonth(Str::set('valid month formatted (yyyy-mm)'), Str::set('2024-01'), Str::set('2024-12')),
        ['-'],
        ['monthYear', 'monthNumber'],
        new validation\dbtype\SmallInt(Str::set('a 4 digit year'), 0, 9999),
        new validation\dbtype\TinyInt(Str::set('a 2 digit month number'), 1, 12)
      ));
    }
    return $this->registered;
  }

  protected function get_time() : ?RecordComponent
  {
    if ($this->register('time', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('expanded description of expected field content'),
        new validation\dbtype\Time(Str::set('valid time formatted (hh:mm[:ss])'),
          new validation\ISOTime(Str::set('an appointment slot available ever 30min'),
            Str::set('08:30'), Str::set('17:30'), (30*60)
          )
        )
      ));
    }
    return $this->registered;
  }

  protected function get_date() : ?RecordComponent
  {
    if ($this->register('date', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('expanded description of expected field content'),
        new validation\dbtype\Date(Str::set('valid date formatted (yyyy-mm-dd)'),
          new validation\ISODate(Str::set('date of birth from '),
            Str::set('1900-01-01'), Str::set('2023-12-31')
          )
        )
      ));
    }
    return $this->registered;
  }

  protected function get_datetime() : ?RecordComponent
  {
    if ($this->register('datetime', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        Str::set('expanded description of expected field content'),
        new validation\dbtype\DateTime(Str::set('valid date time formatted (yyyy-mm-ddThh:mm:ss)'),
          new validation\DateTimeLocal(Str::set('Start date and time of your event within the next 18 months'),
            Str::set('2024-03-05T00:00'), Str::set('2025-09-30T00:00')
          )
        )
      ));
    }
    return $this->registered;
  }

  protected function get_flag() : ?RecordComponent
  {
    if ($this->register('flag', RecordComponentType::PROPERTY)) { //, TRUE)) {
      $this->initiate( new field\Flag($this->registeredName, $this,
        Str::set('expanded description of expected field content')
      ));
    }
    return $this->registered; 
  }

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
