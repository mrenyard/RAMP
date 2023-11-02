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
use ramp\model\business\RelationLookup;
use ramp\model\business\field\Field;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class RecordB extends Record
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

  protected function get_key1() : Field
  {
    if (!isset($this->primaryKey[0])) {
      $this->primaryKey[0] = new MockField(Str::set('key1'), $this);
    }
    return $this->primaryKey[0]; 
  }

  protected function get_key2() : Field
  {
    if (!isset($this->primaryKey[1])) {
      $this->primaryKey[1] = new MockField(Str::set('key2'), $this);
    }
    return $this->primaryKey[1];
  }

  // protected function get_relationLookupBA() : Relation
  // {
  //   if (!isset($this[0])) {
  //     $this[0] = new RelationLookup(
  //       Str::set('relationLookupBA'),
  //       $this,
  //       Str::set('Recorda'),
  //       Str::set('Lookup'),
  //       Str::set('fk_recordA'));
  //   }
  // }

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
    if ($this->withError) { return TRUE; }
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
    if ($this->withError) { return StrCollection::set('Error MESSAGE BadValue Submited!'); }
    return parent::get_errors();
  }

  protected static function checkRequired($dataObject) : bool
  {
    return TRUE;
  }
}