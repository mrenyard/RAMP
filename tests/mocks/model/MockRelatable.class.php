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

use ramp\model\business\Relatable;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRelatable extends Relatable
{
  public $validateCount;
  public $hasErrorsCount;
  public $errorsTouchCount;
  private $withError;

  public function __construct($withError = FALSE)
  {
    parent::__construct(NULL);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->errorsTouchCount = 0;
    $this->withError = $withError;
  }

  /**
   * Mocked get_id method
   * @return \ramp\core\Str Str('uid-1')
   */
  public function get_id() : Str
  {
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
}
