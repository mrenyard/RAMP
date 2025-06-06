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
use ramp\core\iOption;
use ramp\core\OptionList;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\field\SelectFrom;
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\dbtype\DbTypeValidation;

/**
 * Mock Concreate implementation of \ramp\model\business\field\SelectFrom for testing against.
 */
class MockSelectFrom extends SelectFrom
{
  public $validateCount;
  public $hasErrorsCount;

  public function __construct(Str $name, Record $parent, Str $title, OptionList $options)
  {
    parent::__construct($name, $parent, $title, $options);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
  }

  public function reset() : void
  {
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata, $update = TRUE) : void
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
   * Returns value held by relevant property of containing record.
   * @return \ramp\core\OptionList|\ramp\core\iOption|string|int|float|bool|NULL Value held by relevant property of containing record
   */
  final protected function get_value() : OptionList|iOption|string|int|float|bool|NULL
  {
    $key = $this->parent->getPropertyValue($this->name);
    foreach ($this as $option) {
      if ($option->key == (int)$key) { return $option; }
    }
    return NULL;
  }

  /**
   * Validate that value is one of available options.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    foreach ($this as $option) {
      if ((string)$value == (string)$option->key) { return; }
    }
    throw new FailedValidationException('Selected value NOT an available option!');
  }
}
