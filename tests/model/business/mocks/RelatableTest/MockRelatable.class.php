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
namespace tests\ramp\model\business\mocks\RelatableTest;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\condition\PostData;
use ramp\model\business\Relatable;

class MockRelatableCollection extends Relatable implements iCollection
{
  protected function get_id() : Str
  {
  }

  /**
   * Add a reference (Record), to this collection.
   * @param \ramp\core\RAMPObject $object RAMPObject reference to be added (Record)
   * @throws \InvalidArgumentException When provided object NOT expected type (Record)
   */
  public function add(RAMPObject $object)
  {
    self::offsetSet($this->get_count(), $object);
  }
}

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRelatable extends Relatable
{
  private static $idCount;
  private $id;

  public $label;
  public $validateCount;
  public $hasErrorsCount;
  public $isValidCount;

  public static function reset()
  {
    self::$idCount = 0;
  }

  public function __construct(string $label, Relatable $children = null)
  {
    parent::__construct($children);
    $this->id = Str::set('uid-' . self::$idCount++);
    $this->label = $label;
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $this->isValidCount = 0;
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
