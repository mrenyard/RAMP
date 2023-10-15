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

use ramp\model\business\RecordComponent;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRecordMockRelation extends MockRecord
{
  protected function get_relationAlpha() : RecordComponent
  {
    if (!isset($this[1])) {
      $this[1] = new MockRelation($this->relationAlphaName, $this, $this->buildAlphaWith());
    }
    return $this[1];
  }

  protected function get_relationBeta() : RecordComponent
  {
    if (!isset($this[2])) {
      $this[2] = new MockRelation($this->relationBetaName, $this, $this->buildBetaWith());
    }
    return $this[2];
  }
}
