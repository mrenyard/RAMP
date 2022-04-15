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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

//use ramp\SETTING;
use ramp\core\Str;
use ramp\core\iOption;
//use ramp\model\business\Option;
use ramp\model\business\RecordCollection;
use ramp\model\business\Record;

/**
 * Collection of Coutry.
 */
class CountryCollection extends RecordCollection { }

/**
 * Concrete Record for Country.
 */
class Country extends Record
{
  private $code;
  private $nid;

  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  static public function primaryKeyName() : Str { return Str::set('code'); }

  protected function get_code() : field\Input
  {
    if (!isset($this->code))
    {
      $this->code = new field\Input(
        Str::set('code'),
        $this,
        new validation\dbtype\VarChar(
          2,
          new validation\Alphanumeric(),
          Str::set('Please provide a valid 2 digit country code')
        )
      );
      if ($this->isNew) { $this['code'] = $this->code; }
    }
    return $this->code;
  }

  protected function get_nid() : field\Input
  {
    if (!isset($this->nid))
    {
      $this->nid = new field\Input(
        Str::set('nid'),
        $this,
        new validation\dbtype\Interger(
          Str::set('Please provide an interager value')
        )
      );
      if ($this->isNew) { $this['nid'] = $this->nid; }
    }
    return $this->nid;
  }

  protected function get_name() : field\Input
  {
    if (!isset($this['name']))
    {
       $this['name'] = new field\Input(
          Str::set('name'),
          $this,
          new validation\dbtype\VarChar(
            45,
            new validation\Alphanumeric(),
            Str::set('Please provide a country name!')
          )
      );
    }
    return $this['name'];
  }

  protected function get_sageCode() : field\Input
  {
    if (!isset($this['sageCode']))
    {
       $this['sageCode'] = new field\Input(
          Str::set('sageCode'),
          $this,
          new validation\dbtype\VarChar(
            5,
            new validation\Alphanumeric(),
            Str::set('Please provide a valid sage code!')
          )
      );
    }
    return $this['sageCode'];
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->code) &&
      isset($dataObject->nid) &&
      isset($dataObject->name)
    );
  }
}
