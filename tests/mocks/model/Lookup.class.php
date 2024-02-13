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
 * You should have received a copy of the GNU General Public License along relationAlphaWith this program; if
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
use ramp\model\business\RecordComponent;
use ramp\model\business\field\Field;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class Lookup extends Record
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  public $propertyName;
  private $withError;

  public function __construct($dataObject = NULL, $withError = FALSE)
  {
    parent::__construct($dataObject);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
    $this->withError = $withError;
  }

  protected function get_RecordA_keyA() : Field
  {
    if (!isset($this->primaryKey[0])) {
      $this->primaryKey[0] = new MockField(Str::set('RecordA_keyA'), $this);
    }
    return $this->primaryKey[0]; 
  }

  protected function get_RecordA_keyB() : Field
  {
    if (!isset($this->primaryKey[1])) {
      $this->primaryKey[1] = new MockField(Str::set('RecordA_keyB'), $this);
    }
    return $this->primaryKey[1]; 
  }

  protected function get_RecordA_keyC() : Field
  {
    if (!isset($this->primaryKey[2])) {
      $this->primaryKey[2] = new MockField(Str::set('RecordA_keyC'), $this);
    }
    return $this->primaryKey[2];
  }

  protected function get_RecordB_key1() : Field
  {
    if (!isset($this->primaryKey[3])) {
      $this->primaryKey[3] = new MockField(Str::set('RecordB_key1'), $this);
    }
    return $this->primaryKey[3];
  }

  protected function get_RecordB_key2() : Field
  {
    if (!isset($this->primaryKey[4])) {
      $this->primaryKey[4] = new MockField(Str::set('RecordB_key2'), $this);
    }
    return $this->primaryKey[4];
  }

  protected function get_relationRecordA() : RecordComponent
  {
    if (!isset($this[1])) {
      $this[1] = new MockRelationToMany(Str::set('relationRecordA'), $this, Str::set('RecordA'), Str::set('relationLoolupB'));
    }
    return $this[1];
  }

  protected function get_relationRecordB() : RecordComponent
  {
    if (!isset($this[1])) {
      $this[1] = new MockRelationToMany(Str::set('relationRecordB'), $this, Str::set('RecordB'), Str::set('relationLookupA'));
    }
    return $this[1];
  }

  protected function get_relationType() : RecordComponent
  {
    if (!isset($this[0])) {
      $this[0] = new MockField(Str::set('relationType'), $this);
    }
    return $this[0];
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

  /**
   * @ignore
   */
  public function get_hasErrors() : bool
  {
    $this->hasErrorsCount++;
    if ($this->withError) { return TRUE; }
    return parent::get_hasErrors();
  }

  /**
   * @ignore
   */
  public function get_errors() : StrCollection
  {
    $this->errorsTouchCount++;
    if ($this->withError) { return StrCollection::set('Error MESSAGE BadValue Submited!'); }
    return parent::get_errors();
  }
}