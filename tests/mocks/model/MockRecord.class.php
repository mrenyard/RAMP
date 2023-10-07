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
namespace tests\ramp\mocks\model;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\field\Field;
use ramp\model\business\RecordComponent;

// use tests\ramp\mocks\model\MockRecordComponent;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRecord extends Record
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  public $foreignKeyName;
  public $propertyName;

  public function __construct(\stdClass $dataObject = null)
  {
    parent::__construct($dataObject);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
  }

  protected function get_keyA() : Field
  {
    if (!isset($this->primaryKey['keyA'])) {
      $this->primaryKey['keyA'] = new MockField(Str::set('keyA'), $this);
    }
    return $this->primaryKey['keyA']; 
  }

  protected function get_keyB() : Field
  {
    if (!isset($this->primaryKey['keyB'])) {
      $this->primaryKey['keyB'] = new MockField(Str::set('keyB'), $this);
    }
    return $this->primaryKey['keyB']; 
  }

  protected function get_keyC() : Field
  {
    if (!isset($this->primaryKey['keyC'])) {
      $this->primaryKey['keyC'] = new MockField(Str::set('keyC'), $this);
    }
    return $this->primaryKey['keyC']; 
  }

  protected function get_aProperty() : RecordComponent
  {
    if (!isset($this[0])) {
      $this->propertyName = Str::set('aProperty');
      $this[0] = new MockField($this->propertyName, $this);
    }
    return $this[0];
  }

  protected function get_foreignKey() : RecordComponent
  {
    if (!isset($this[1])) {
      $this->foreignKeyName = Str::set('foreignKey');
      $this[1] = new MockKey($this->foreignKeyName, $this);
    }
    return $this[1];
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    $this->validateCount++;
    parent::validate($postdata);
  }

  public function get_hasErrors() : bool
  {
    $this->hasErrorsCount++;
    return parent::get_hasErrors();
  }

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return StrCollection List of recorded errors.
   */
  public function get_errors() : StrCollection
  {
    $this->errorsTouchCount++;
    return parent::get_errors();
  }

  protected static function checkRequired($dataObject) : bool
  {
    return TRUE;
  }
}
