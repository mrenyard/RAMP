<?php
/**
 * Svelte - Rapid web application development using best practice.
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
namespace svelte\model\business\validation\dbtype;

use svelte\model\business\Record;
use svelte\model\business\DataWriteException;
use svelte\model\business\FailedValidationException;
use svelte\model\business\validation\ValidationRule;

/**
 * Unique Primary Key validation rule, ensure unique primary key while reserving space in data store.
 * Runs code defined test against provided value.
 */
class UniquePrimaryKey extends ValidationRule
{
  private $associatedRecord;

  /**
   * Default constructor for a validation rule to ensure uniqueness of primary key.
   * Example use within a Record were its PrimarKey is 'uniqueKeybluetooth':
   * ```php
   * private $primaryProperty;
   *
   * public static function primaryKeyName() : Str { return Str::set('uniqueKey'); }
   *
   * protected function get_uniqueKey() : field\Field
   * {
   *   if (!isset($this->primaryProperty))
   *   {
   *     $this->primaryProperty = field\Input(
   *       Str::set('uniqueKey'),
   *       $this,
   *       new validation\dbtype\VarChar(
   *         15,
   *         new validation\LowerCaseAlphanumeric(
   *           new validation\UniquePrimaryKey($this)
   *         ),
   *         Str::set('My error message HERE!')
   *       )
   *     );
   *     if ($this->isNew) { $this['uniqueKey'] = $this->primaryProperty; }
   *   }
   *   return $this->primaryProperty;
   * }
   * ```
   * @param \svelte\model\business\Record $associatedRecord Record that holds primary key to be tested
   */
  public function __construct(Record $associatedRecord)
  {
    $this->associatedRecord = $associatedRecord;
    parent::__construct();
  }

  /**
   * Asserts that $value is unique primary key while reserving space in data store.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value)
  {
    $pkName = (string)$this->associatedRecord->primaryKeyName();
    $MODEL_MANAGER = \svelte\SETTING::$SVELTE_BUSINESS_MODEL_MANAGER;
    $modelManager = $MODEL_MANAGER::getInstance();
    $this->associatedRecord->setPropertyValue($pkName, $value);
    try {
      $modelManager->update($this->associatedRecord);
    } catch (DataWriteException $e) {
      throw new FailedValidationException();
      return;
    }
    unset($this->associatedRecord[$pkName]);
  }
}
