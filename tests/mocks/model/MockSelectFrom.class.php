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
use ramp\core\OptionList;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\field\SelectFrom;
use ramp\model\business\FailedValidationException;
use ramp\model\business\validation\dbtype\DbTypeValidation;

/**
 * Mock Concreate implementation of \ramp\model\business\field\SelectFrom for testing against.
 */
class MockSelectFrom extends SelectFrom
{
  public $validateCount;
  public $hasErrorsCount;

  public function __construct(Str $name, Record $parent, OptionList $options)
  {
    parent::__construct($name, $parent, $options);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
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
   * Validate that value is one of avalible options.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    foreach ($this as $option)
    {
      if ((string)$value == (string)$option->id) { return; }
    }
    throw new FailedValidationException('Selected value NOT an avalible option!');
  }
}
