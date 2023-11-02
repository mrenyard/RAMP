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
use ramp\condition\Filter;
use ramp\condition\SQLEnvironment;


use ramp\model\business\Record;
use ramp\model\business\RecordCollection;
use ramp\model\business\RecordComponent;
use ramp\model\business\Relatable;
use ramp\model\business\SimpleBusinessModelDefinition;
use ramp\model\business\field\Field;

use tests\ramp\mocks\model\MockRelationToOne;
use tests\ramp\mocks\model\MockRelationToMany;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRecord extends Record
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  public $propertyName;
  public $relationAlphaName;
  public $relationAlphaWith;
  public $relationBetaName;
  public $relationBetaWith;

  public function __construct(\stdClass $dataObject = null)
  {
    $this->relationAlphaName = Str::set('relationAlpha');
    $this->relationBetaName = Str::set('relationBeta');
    parent::__construct($dataObject);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
  }   

  protected function get_keyA() : Field
  {
    if (!isset($this->primaryKey[0])) {
      $this->primaryKey[0] = new MockField(Str::set('keyA'), $this);
    }
    return $this->primaryKey[0]; 
  }

  protected function get_keyB() : Field
  {
    if (!isset($this->primaryKey[1])) {
      $this->primaryKey[1] = new MockField(Str::set('keyB'), $this);
    }
    return $this->primaryKey[1]; 
  }

  protected function get_keyC() : Field
  {
    if (!isset($this->primaryKey[2])) {
      $this->primaryKey[2] = new MockField(Str::set('keyC'), $this);
    }
    return $this->primaryKey[2]; 
  }

  protected function get_aProperty() : RecordComponent
  {
    if (!isset($this[0])) {
      $this->propertyName = Str::set('aProperty');
      $this[0] = new MockField($this->propertyName, $this);
    }
    return $this[0];
  }

  protected function get_relationAlpha() : RecordComponent
  {
    if (!isset($this[1])) {
      $this[1] = new MockRelationToMany($this->relationAlphaName, $this, Str::set('MockMinRecord'), Str::set('relationDelta'));
    }
    return $this[1];
  }

  protected function get_relationBeta() : RecordComponent
  {
    if (!isset($this[2])) {
      $this[2] = new MockRelationToOne($this->relationBetaName, $this, Str::set('MockMinRecord'));
    }
    return $this[2];
  }

  /*
  protected function get_relationGamma() : RecordComponent
  {
    // TODO:mrenyard: Use concrete RelationLookup
    if (!isset($this[3])) {
      $this[3] = new MockRelation($this->relationGammaName, $this, $this->buildBetaWith());
    }
    return $this[3];
  }*/

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
