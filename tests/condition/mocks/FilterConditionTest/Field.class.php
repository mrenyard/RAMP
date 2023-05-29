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
namespace tests\ramp\condition\mocks\FilterConditionTest;

use ramp\core\RAMPObject;
use ramp\model\business\FailedValidationException;

/**
 * Mock Field with processValidationRules method.
 * .
 */
class Field extends RAMPObject
{
  /**
   * Test processValidationRule.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value)
  {
    if ($value == 'GOOD' || $value == 'COMPARABLE' || $value == 'NEW COMPARABLE' ) { return; }
    throw new FailedValidationException('$value NOT one of GOOD, COMPARABLE or NEW COMPARABLE');
  }
}
