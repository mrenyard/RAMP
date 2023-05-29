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
namespace tests\ramp\model\business\field\mocks\SelectFromTest;

use ramp\core\Str;
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\condition\PostData;
use ramp\model\business\BusinessModel;

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel for testing against.
 * .
 */
class MockBusinessModel extends BusinessModel
{
  private static $count;
  private $id;

  public  $label;
  public $validateCount;
  public $hasErrorsCount;
  public $isValidCount;

  public static function reset()
  {
    self::$count = 0;
  }

  public function __construct(string $label, iCollection $children = null)
  {
    $this->id = Str::set('uid-' . self::$count++);
    $this->label = $label;
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->isValidCount = 0;
    parent::__construct($children);
  }

  /**
   * Mocked get_id method
   * @return \ramp\core\Str Str('uid-1')
   */
  public function get_id() : Str
  {
    return $this->id;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata)
  {
    $this->validateCount++;
    parent::validate($postdata);
  }

  public function get_hasErrors() : bool
  {
    $this->hasErrorsCount++;
    return parent::get_hasErrors();
  }
}
