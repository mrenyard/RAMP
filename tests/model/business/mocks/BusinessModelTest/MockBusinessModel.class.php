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
namespace tests\svelte\model\business\mocks\BusinessModelTest;

use svelte\core\Str;
use svelte\core\iCollection;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\model\business\BusinessModel;

/**
 * Mock Concreate implementation of \svelte\model\business\BusinessModel for testing against.
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
   * @return \svelte\core\Str Str('uid-1')
   */
  public function get_id() : Str
  {
    return $this->id;
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

  public function get_hasErrors() : bool
  {
    $this->hasErrorsCount++;
    return parent::get_hasErrors();
  }
}
