<?php
/**
 * RAMP - Rapid web application development using best practice.
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
 * @package ramp
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\OptionList;
use ramp\model\business\RecordCollection;
use ramp\model\business\AuthenticatableUnit;

/**
 * Collection of Person.
 */
class PersonCollection extends RecordCollection { }

/**
 * Concrete Record for Person.
 */
class Person extends AuthenticatableUnit
{
  private $primaryProperty;
  // private static $countryList;
  // private $country;

  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  static public function primaryKeyName() : Str { return Str::set('uname'); }

  protected function get_uname() : field\Field
  {
    if (!isset($this->primaryProperty))
    {
      $this->primaryProperty = new field\Input(
        Str::set('uname'),
        $this,
        new validation\dbtype\VarChar(
          15,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
      if ($this->isNew) { $this['uname'] = $this->primaryProperty; }
    }
    return $this->primaryProperty;
  }

  protected function get_honorificPrefix() : field\Field
  {
    if (!isset($this['honorificPrefix']))
    {
      $this['honorificPrefix'] = new field\Input(
      Str::set('honorificPrefix'),
      $this,
      new validation\dbtype\VarChar(
        15,
        new validation\Alphanumeric(), //CapitalizedFirstLetter
        Str::set('My error message HERE!')
        )
      );
    }
    return $this['honorificPrefix'];
  }
 
  /**
   * Get given name also known as personal, first, forename, or Christian name.
   * <b>DO NOT CALL DIRECTLY, USE this->givenName;</b>
   *
   * Identifies a specific person, and differentiates that person from other members of a group,
   * such as a family or clan, with whom that person shares a common surname. The term given name
   * refers to the fact that the name is bestowed upon, or given to a child, usually by its
   * parents, at or near the time of birth.
   * @return field\Field Containing value for given name.
   */
  protected function get_givenName() : field\Field
  {
    if (!isset($this['givenName']))
    {
      $this['givenName'] = new field\Input(
        Str::set('givenName'),
        $this,
        new validation\dbtype\VarChar(
          15,
          new validation\Alphanumeric(), //CapitalizedFirstLetter
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['givenName'];
  }

  /**
   * Get family name sometimes referred to as surname or last name.
   * <b>DO NOT CALL DIRECTLY, USE this->familyName;</b>
   *
   * Typically a part of a person's personal name which, according to law or custom, is passed or
   * given to children from one or both of their parents' family names. The use of family names is
   * common in most cultures around the world, with each culture having its own rules as to how
   * these names are formed, passed and used.
   * @return Field Containing value for family name.
   */
  protected function get_familyName() : field\Field
  {
    if (!isset($this['familyName']))
    {
      $this['familyName'] = new field\Input(
        Str::set('familyName'),
        $this,
        new validation\dbtype\VarChar(
          15,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['familyName'];
  }

  /**
   *
   *
  protected function get_country() : field\Field
  {
    if (!isset(self::$countryList)) {
      $MODEL_MANAGER = \ramp\SETTING::$RAMPE_BUSINESS_MODEL_MANAGER;
      $modelManager = $MODEL_MANAGER::getInstance();
      self::$countryList = new OptionList($modelManager->getBusinessModel(
        new SimpleBusinessModelDefinition(Str::set('Country')))
      );
    }
    if (!isset($this->country)) {
      $this->country = new field\SelectOne(
        Str::set('country'),
        $this,
        self::$countryList
      );
    }
    //if ($this->isNew) {
      $this['country'] = $this->country;
    //}
    return $this->country;
  }*/

  /**
   *
  protected function set_country($value)
  {
    $this->setPropertyValue('country', $value);
  }*/

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->uname) &&
      isset($dataObject->email)
    );
  }
}
