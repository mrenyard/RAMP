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
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\validation\dbtype\mocks\UniquePrimaryKeyTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordCollection;
use ramp\model\business\field\Field;
use ramp\model\business\field\Input;
use ramp\model\business\validation\Alphanumeric;
use ramp\model\business\validation\LowerCaseAlphanumeric;
use ramp\model\business\validation\dbtype\VarChar;
use ramp\model\business\validation\dbtype\UniquePrimaryKey;

use tests\ramp\model\business\validation\dbtype\UniquePrimaryKeyTest;

/**
 * Mock Concreate implementation of \ramp\model\business\RecordCollection for testing against.
 */
class SimpleRecordCollection extends RecordCollection { }

/**
 * Mock Concreate implementation of \ramp\model\business\Record for testing against.
 *
 * @property-read \ramp\model\business\field\Field $primaryKey Returns field containing value of property.
 */
class SimpleRecord extends Record
{
  public static $uniquePrimaryKeyTest;
  private $primaryProperty;

  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection { return StrCollection::set('uniqueKey'); }

  /**
   * Get field containing uniqueKey
   * **DO NOT CALL DIRECTLY, USE this->uniqueKey;**
   * @return \ramp\model\business\field\Field Returns field containing value of uniqueKey
   */
  protected function get_uniqueKey() : Field
  {
    // $propertyName = Str::set('uniqueKey');
    self::$uniquePrimaryKeyTest = new UniquePrimaryKey($this);
    if (!isset($this->primaryProperty))
    {
      $this->primaryProperty = new Input(
        $propertyName,
        $this,
        new VarChar(
          15,
          new LowerCaseAlphanumeric(
            self::$uniquePrimaryKeyTest
          ),
          Str::set('My error message HERE!')
        )
      );
    }
    if ($this->isNew) { $this['uniqueKey'] = $this->primaryProperty; }
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
