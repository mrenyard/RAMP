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
use ramp\core\Collection;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordCollection;

/**
 * Collection of Table.
 */
class TableCollection extends RecordCollection { }

/**
 * Concrete Record for Table.
 */
class Table extends Record
{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('name'); }

  protected function get_primaryKeys() : string
  { 
    $a = array();
    foreach ($this as $property) {
      if ($property->key == 'PRI') {
        $a[] = "'" . $property->name . "'";
      }
    }
    return implode(', ', $a);
  }

  protected function get_name() : string
  {
    return $this->getPropertyValue('TABLE_NAME');
  }

  protected function get_requiered() : Collection
  {
    $oCollection = new Collection();
    foreach ($this as $property) {
      if (!$property->isNullable) {
        $oCollection->add($property);
      }
    }
    return $oCollection;
  }

  /**
   * ArrayAccess method offsetSet, SPECIAL PASS.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   */
  public function offsetSet($offset, $object)
  {
    @parent::offsetSet($offset, $object);
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->TABLE_NAME)
    );
  }
}
