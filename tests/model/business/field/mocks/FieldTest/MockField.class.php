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
 * @package RAMP Testing
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\field\mocks\FieldTest;

use ramp\core\Str;
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\condition\PostData;
use ramp\model\business\field\Field;
use ramp\model\business\FailedValidationException;

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel as field for testing against.
 */
class MockField extends Field
{
  public static $processValidationRuleCount;

  public static function reset()
  {
    self::$processValidationRuleCount = 0;
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
  }

  public function processValidationRule($value) : void
  {
    self::$processValidationRuleCount++;
    if ($value == 'BAD') {
      throw new FailedValidationException('MockField\'s has error due to $value of BAD!');
    }
  }
}
