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
use ramp\model\business\BusinessModel;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;

/**
 * Mock Concreate implementation of \ramp\model\business\RecordComponent for testing against.
 */
class MockRecordComponent extends RecordComponent
{
  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  protected function get_value()
  {
    // STUB
  }
}
