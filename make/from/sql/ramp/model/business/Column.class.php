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
class ColumnCollection extends RecordCollection { }

/**
 * Concrete Record for Column.
 */
class Column extends Record
{
  private $isForeignKeyColumn;

  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('name'); }

  protected function get_name() : string
  {
    $value = Str::set($this->getPropertyValue('COLUMN_NAME'));
    $this->isForeignKeyColumn = $value->contains(StrCollection::set('ID','KEY'));
    return (string)$value;
  }

  protected function get_selectType() : string
  {
    $tableName = Str::set($this->name);
    return ($tableName->contains(StrCollection::set('Collection', 'List'))) ? 2 : 
      (($tableName->contains(StrCollection::set('Type', 'Status','Code'))) ? 1 : 0);
  }

  protected function get_isForeignKey()
  {
    if (!isset($this->isForeignKeyColumn)) { $this->name; }
    return $this->isForeignKeyColumn; 
  }
  
  protected function get_key() : string
  {
    return $this->getPropertyValue('COLUMN_KEY');
  }
        
  protected function get_dataType() : string
  {
    $message = "Str::set('Format error message/hint')";
    $values = \explode('(', trim($this->getPropertyValue('COLUMN_TYPE'), ')'));
    switch ($values[0]) {
      case 'varchar':
        return "VarChar(\n          " . $values[1] . ",\n          new validation\Alphanumeric(),\n          " . $message . "\n        )";
      case 'tinyint':
        return (Str::set($this->name)->contains(StrCollection::set('is', 'has', 'FLAG'))) ?
          "Flag(\n          " . $message . "\n        )" :
            "TinyInt(\n          " . $message . "\n        )";
      case 'smallint':
          "SmallInt(\n          " . $message . "\n        )";
        default:
        return $values[0] . "(\n          " . $message . "\n        )";
    }
  }
 
  protected function get_isNullable() : bool
  {
    return ($this->getPropertyValue('IS_NULLABLE') == 'YES');
  }
}
