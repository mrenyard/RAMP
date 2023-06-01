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
namespace tests\ramp\model\business\key\mocks\KeyTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\key\Key;

class MockKey extends Key
{
  private static $propertyName;

  /**
   * Creates a multiple part primary key field related to a collection of property of parent record.
   * @param \ramp\model\business\Record $parentRecord Record parent of *this* property.
   */
  public function __construct(Record $parentRecord)
  {
    if (!isset(self::$propertyName)) { self::$propertyName = Str::set('MockKey'); }
    parent::__construct(self::$propertyName, $parentRecord);
  }

  /**
   * Returns indexes for key.
   * @return \ramp\core\StrCollection Indexes related to data fields for this key.
   */
  final protected function get_indexes() : StrCollection
  {
    // STUB
  }

  /**
   * Returns primarykey values held by relevant properties of parent record.
   * @return \ramp\core\StrCollection Values held by relevant property of parent record
   */
  final protected function get_values() : ?StrCollection
  {
    // STUB
  }
}