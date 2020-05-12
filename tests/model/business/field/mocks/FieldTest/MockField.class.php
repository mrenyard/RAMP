<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
namespace tests\svelte\model\business\field\mocks\FieldTest;

use svelte\core\Str;
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\business\field\Field;
use svelte\model\business\validation\FailedValidationException;

/**
 * Mock Concreate implementation of \svelte\model\business\BusinessModel as field for testing against.
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

  public function processValidationRule($value)
  {
    self::$processValidationRuleCount++;
    if ($value == 'BAD') {
      throw new FailedValidationException('MockField\'s has error due to $value of BAD!');
    }
  }
}
