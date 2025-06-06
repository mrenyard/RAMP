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
use ramp\core\OptionList;
use ramp\model\business\Record;
use ramp\model\business\field\Input;
use ramp\model\business\field\MultipartInput;
use ramp\model\business\field\Flag;
use ramp\model\business\field\Option;
use ramp\model\business\field\SelectOne;
use ramp\model\business\field\SelectMany;
use ramp\model\business\RecordComponent;
use ramp\model\business\RecordComponentType;
use ramp\model\business\validation\dbtype\Char;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\dbtype\SmallInt;
use ramp\model\business\validation\dbtype\TinyInt;
use ramp\model\business\validation\dbtype\DecimalPointNumber;
use ramp\model\business\validation\dbtype\Time;
use ramp\model\business\validation\dbtype\Date;
use ramp\model\business\validation\dbtype\DateTime;
use ramp\model\business\validation\RegexValidationRule;
use ramp\model\business\validation\TelephoneNumber;
use ramp\model\business\validation\WebAddressURL;
use ramp\model\business\validation\RegexEmailAddress;
use ramp\model\business\validation\LowercaseAlphanumeric;
use ramp\model\business\validation\HexidecimalColorCode;
use ramp\model\business\validation\ISOWeek;
use ramp\model\business\validation\ISOMonth;
use ramp\model\business\validation\ISOTime;
use ramp\model\business\validation\ISODate;
use ramp\model\business\validation\DateTimeLocal;
use ramp\model\business\validation\Password;
use ramp\model\business\validation\specialist\ServerSideEmail;

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
        Str::set('First single character key element.'),
        new Char(
          Str::set('.e.g. A'),
          Str::set('string with a total character length of exactly '), 1,
          new LowercaseAlphanumeric(
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
      $this->initiate(new Input($this->registeredName, $this,
      Str::set('Second single character key element.'),
        new Char(
          Str::set('.e.g. B'),
          Str::set('string with a total character length of exactly '), 1,
          new LowercaseAlphanumeric(
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
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('Third single character key element.'),
        new Char(
          Str::set('.e.g. C'),
          Str::set('string with a total character length of exactly '), 1,
          new LowercaseAlphanumeric(
            Str::set('strictly latin alphabetical or numeric')
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_text() : ?RecordComponent
  {
    if ($this->register('text', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The name by which you are refered by, in western culture usually your first name.'),
        new VarChar(
          Str::set('e.g. John'),
          Str::set('string with a maximum character length of '), 20,
          new RegexValidationRule(
            Str::set('single word with only latin alphabet charactered'),
            '[A-Za-z]*'
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_tel() : ?RecordComponent
  {
    if ($this->register('tel', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The number used to contact (call or text) said particular persons mobile device.'),
        new VarChar(
          Str::set('e.g. 0234 567 891'),
          Str::set('string with a maximum character length of '), 20,
          new TelephoneNumber(
            Str::set('valid telephone number')
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_url() : ?RecordComponent
  {
    if ($this->register('url', RecordComponentType::PROPERTY)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The Uniform Resource Locator (URL) that points to a webpage you would like to visit.'),
        new VarChar(
          Str::set('e.g. https://www.domain-name.com/top-article'),
          Str::set('string with a maximum character length of '), 150,
          new WebAddressURL(
            Str::set('a validly formatted web address (URL)'), FALSE, FALSE
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_email() : ?RecordComponent
  {
    if ($this->register('email', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('A uniquely identified electronic mailbox at which you receive written messages.'),
        new VarChar(
          Str::set('e.g. jsmith@domain.com'),
          Str::set('string with a maximum character length of '), 150,
          new RegexEmailAddress(
            Str::set('validly formatted email address'),
            new ServerSideEmail()
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_password() : ?RecordComponent
  {
    if ($this->register('password', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The secret word or phrase that you wish to used to confirm your identity and gain access.'),
        new VarChar(
          Str::set('e.g. N0t-Pa55w0rd!'),
          Str::set('string with a maximum character length of '), 75,
          new Password(
            Str::set("8 character minimum alphanumeric and special characters (!#$%&+,-.:;<=>?[]^*_{|}{~@')")
          )
        )
      ));
    }
    return $this->registered; 
  }

  protected function get_date() : ?RecordComponent
  {
    if ($this->register('date', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The day, month, and year that you were born.'),
        new Date(
          Str::set('date of birth'),
          new ISODate(
            Str::set('valid ISO formated date'),
            Str::set('1900-01-01'), Str::set('2023-12-31')
          )
        )
      ));
    }
    return $this->registered;
  }

  protected function get_month() : ?RecordComponent
  {
    if ($this->register('month', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new MultipartInput($this->registeredName, $this,
        Str::set('An estimate of when a pregnancy began, based on the first day of the last menstrual period (LMP).'),
        new ISOMonth(Str::set('valid ISO formated month'), Str::set('2024-01'), Str::set('2024-12')),
        ['-'],
        ['monthYear', 'monthNumber'],
        new SmallInt(Str::set('a 4 digit year from '), 0, 9999),
        new TinyInt(Str::set('a 2 digit month number from '), 1, 12)
      ));
    }
    return $this->registered;
  }

  protected function get_week() : ?RecordComponent
  {
    if ($this->register('week', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new MultipartInput($this->registeredName, $this,
        Str::set('Year-over-year week used for comparison of financial performance to the same week in the previous year.'),
        new ISOWeek(Str::set('valid ISO formated week'), Str::set('2024-W01'), Str::set('2024-W52')),
        ['-W'],
        ['weekYear', 'weekNumber'],
        new SmallInt(Str::set('4 digit year from '), 0, 9999),
        new TinyInt(Str::set('2 digit week number from '), 1, 53)
      ));
    }
    return $this->registered;
  }

  protected function get_time() : ?RecordComponent
  {
    if ($this->register('time', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('Requested time when your 30 minute appointment is scheduled to begin.'),
        new Time(Str::set('for your appointment start'),
          new ISOTime(Str::set('valid ISO formated time'),
            Str::set('08:30'), Str::set('17:30'), (30*60)
          )
        )
      ));
    }
    return $this->registered;
  }

  protected function get_datetime() : ?RecordComponent
  {
    if ($this->register('datetime', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('Start date and time of your event within the next 18 months.'),
        new DateTime(Str::set('for the start of your event'),
          new DateTimeLocal(Str::set('valid ISO formated date-time'),
            Str::set('2025-03-05T00:00'), Str::set('2026-09-30T00:00')
          )
        )
      ));
    }
    return $this->registered;
  }

  protected function get_wholeNumber() : ?RecordComponent
  {
    if ($this->register('wholeNumber', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The non fractional number related to this query.'),
        new SmallInt(Str::set('whole number from '))
      ));
    }
    return $this->registered; 
  }

  protected function get_decimalPointNumber() : ?RecordComponent
  {
    if ($this->register('decimalPointNumber', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('The ammount of money in UK pounds and pence that you have access to.'),
        new DecimalPointNumber(Str::set('place decimal point number'), 2, 5)
      ));
    }
    return $this->registered; 
  }

  protected function get_color() : ?RecordComponent
  {
    if ($this->register('color', RecordComponentType::PROPERTY)) {
      $this->initiate(new Input($this->registeredName, $this,
        Str::set('Main thematic colour of presentation.'),
        new Char(
          Str::set('e.g. #FFFFFF'),
          Str::set('with a length of exactly '), 7,
          new HexidecimalColorCode(
            Str::set('representing the luminescent gradiant of red, green and blue, a hash followed by three pairs of hexadecimal characters (0 through 9 to F) formated:')
          )
        ),
        
      ));
    }
    return $this->registered; 
  }

  protected function get_requiredFlag() : ?RecordComponent
  {
    if ($this->register('requiredFlag', RecordComponentType::PROPERTY, TRUE)) {
      $this->initiate(new Flag($this->registeredName, $this,
        Str::set('You must agree to terms and conditions to continue to use this site.'),
        Str::set('I have read and agree to site terms and conditions.')
      ));
    }
    return $this->registered; 
  }
  
  protected function get_flag() : ?RecordComponent
  {
    if ($this->register('flag', RecordComponentType::PROPERTY)) {
      $this->initiate(new Flag($this->registeredName, $this,
        Str::set('Do you like Chips and gravy; the popular comfort food in the UK?'),
        Str::set('I like gravy on my chips.')
      ));
    }
    return $this->registered;
  }

  protected function get_selectOne() : ?RecordComponent
  {
    if ($this->register('selectOne', RecordComponentType::PROPERTY, TRUE)) {
      $selectOneList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $selectOneList->add(new Option(1, Str::set('DESCRIPTION ONE')));
      $selectOneList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
      $selectOneList->add(new Option(3, Str::set('DESCRIPTION THREE')));  
      $this->initiate(new SelectOne($this->registeredName, $this,
        Str::set('Select your favourte ... from the list of items below.'),
        $selectOneList
      ));
    }
    return $this->registered; 
  }

  protected function get_selectMany() : ?RecordComponent
  {
    if ($this->register('selectMany', RecordComponentType::PROPERTY)) {
      $selectManyList = new OptionList(null, Str::set('\ramp\model\business\field\Option'));
      $selectManyList->add(new Option(1, Str::set('DESCRIPTION ONE')));
      $selectManyList->add(new Option(2, Str::set('DESCRIPTION TWO')));  
      $selectManyList->add(new Option(3, Str::set('DESCRIPTION THREE')));  
      $this->initiate(new SelectMany($this->registeredName, $this,
        Str::set('Select your favourites ... from the list of items below.'),
        $selectManyList
      ));
    }
    return $this->registered; 
  }

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
