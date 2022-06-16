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
 * @package RAMP.make
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;

/**
 * Collection of Column.
 */
class RowCollection extends RecordCollection { }

/**
 * Concrete Record for Row.
 */
class Row extends Record
{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('key'); }

  protected function get_key() : string
  {
    return $this->getPropertyValue('key');
  }
  
  protected function get_description() : string
  {
    return $this->getPropertyValue('description');
  }

  protected function get_ucDescription() : string
  {
    return str_replace(' ', '_', \strtoupper($this->description));
  }

  protected function get_option() : void
  {
    foreach ($this as $option) {
      echo "  $this->add(new Option(" . $this->name . "::" . $option->ucDescription . "(), Str::set('" . $option->description . "')));
      ";
    }
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->key) &&
      isset($dataObject->description)
    );
  }
}
