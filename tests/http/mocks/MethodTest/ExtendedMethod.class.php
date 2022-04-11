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
 * @version 0.0.9;
 */
namespace tests\ramp\http\mocks\MethodTest;

use ramp\core\Str;
use ramp\http\Method;

/**
 * Method for testing against.
 */
final class ExtendedMethod extends Method {

  //private static $VERB;

  /**
   * Constructor for new instance of ExtendedMethod.
   * @param int $index Number to be assigned to this Verb
   * @param \ramp\core\Str $verb Name of Method (verb/action) that this object is to represent
   * @throws \InvalidArgumentException When $index is NOT an int
   */
  protected function __construct($index, Str $verb)
  {
    parent::__construct($index, $verb);
  }

  /**
   * Test failed creation of new Method (used exclusivly for testing).
   * Nonsence method (for testing) improper use on constructor (first argument NOT an int)
   * @throws \InvalidArgumentException Expected exception for testing.
   */
  public static function FAIL()
  {
    return new ExtendedMethod('eight', Str::set('VERB'));
  }

  /**
   * Test successful creation of new Method (used exclusivly for testing).
   * Nonsence method (for testing) proper use on constructor (first argument is an int)
   * @return \ramp\Method Method evaluating to 'VERB' at index 8
   */
  public static function SUCCEED()
  {
    return new ExtendedMethod(8, Str::set('VERB'));
  }
}
