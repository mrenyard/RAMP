<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest;

use svelte\core\Str;
use svelte\model\business\Record;
use svelte\model\business\RecordCollection;
use svelte\model\business\field\Field;
use svelte\model\business\field\Input;
use svelte\model\business\validation\Alphanumeric;
use svelte\model\business\validation\LowerCaseAlphanumeric;
use svelte\model\business\validation\dbtype\VarChar;
use svelte\model\business\validation\dbtype\UniquePrimaryKey;

use tests\svelte\model\business\validation\dbtype\UniquePrimaryKeyTest;

/**
 * Mock Concreate implementation of \svelte\model\business\RecordCollection for testing against.
 */
class SimpleRecordCollection extends RecordCollection { }

/**
 * Mock Concreate implementation of \svelte\model\business\Record for testing against.
 *
 * @property-read \svelte\model\business\field\Field $primaryKey Returns field containing value of property.
 */
class SimpleRecord extends Record
{
  public static $uniquePrimaryKeyTest;
  private $primaryProperty;

  /**
   * Returns property name of concrete classes primary key.
   * @return \svelte\core\Str Name of property that is concrete classes primary key
   */
  public static function primaryKeyName() : Str { return Str::set('uniqueKey'); }

  /**
   * Get field containing uniqueKey
   * **DO NOT CALL DIRECTLY, USE this->uniqueKey;**
   * @return \svelte\model\business\field\Field Returns field containing value of uniqueKey
   */
  protected function get_uniqueKey() : Field
  {
    self::$uniquePrimaryKeyTest = new UniquePrimaryKey($this);
    if (!isset($this->primaryProperty))
    {
      $this->primaryProperty = new Input(
        Str::set('uniqueKey'),
        $this,
        new VarChar(
          15,
          new LowerCaseAlphanumeric(
            self::$uniquePrimaryKeyTest
          ),
          Str::set('My error message HERE!')
        )
      );
      if ($this->isNew) { $this['uniqueKey'] = $this->primaryProperty; }
    }
    return $this->primaryProperty;
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return TRUE;
  }
}
