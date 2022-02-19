<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\field\mocks\FieldTest;

use svelte\core\Str;
use svelte\core\iOption;
use svelte\core\iCollection;
use svelte\condition\PostData;
use svelte\model\business\BusinessModel;
use svelte\model\business\field\Option;

/**
 * Mock Concreate implementation of \svelte\model\business\BusinessModel for testing against.
 * .
 */
class MockBusinessModel extends BusinessModel implements iOption
{
  private static $count;
  private $key;

  public $label;
  public $validateCount;
  public $hasErrorsCount;
  public $isValidCount;

  public static function reset()
  {
    self::$count = 0;
  }
 
  public function __construct(string $label, iCollection $children = null)
  {
    $this->key = self::$count++;
    $this->label = $label;
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->isValidCount = 0;
    parent::__construct($children);
  }

  public function get_id() : Str
  {
    return Str::set('mock-business-model:' . $this->key);;
  }

  public function get_key()
  {
    return $this->id;
  }

  public function get_description() : Str
  {
  }

  public function get_isSelected() : bool
  {
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \svelte\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata)
  {
    $this->validateCount++;
    parent::validate($postdata);
  }

  public function hasErrors() : bool
  {
    $this->hasErrorsCount++;
    return parent::hasErrors();
  }
}
