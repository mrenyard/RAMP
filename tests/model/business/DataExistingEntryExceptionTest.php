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
namespace tests\ramp\model\business;

require_once '/usr/share/php/ramp/model/business/DataWriteException.class.php';
require_once '/usr/share/php/ramp/model/business/DataExistingEntryException.class.php';

use ramp\model\business\DataExistingEntryException;

/**
 * Collection of tests for \ramp\model\business\DataExistingEntryException.
 */
class DataExistingEntryExceptionTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Default throw/construct assertions \ramp\model\business\DataExistingEntryException
   * - assert is instance of {@see  https://www.php.net/manual/class.runtimeexception.php \RuntimeException}
   * - assert is instance of {@see \ramp\model\business\DataWriteException}
   * - assert is instance of {@see \ramp\model\business\DataExistingEntryException}
   * @see \ramp\model\business\DataExistingEntryException
   */
  public function testConstruct() : void
  {
    try {
      throw new DataExistingEntryException();
    } catch (DataExistingEntryException $expected) {
      $this->assertInstanceOf('\RuntimeException', $expected);
      $this->assertInstanceOf('ramp\model\business\DataWriteException', $expected);
      $this->assertInstanceOf('ramp\model\business\DataExistingEntryException', $expected);
      return;
    }
    $this->fail('FAILED: to throw DataExistingEntryException');
  }

  /**
   * Throw/construct with a targetID and message.
   * - assert returned getTargetID() same as provided at construction.
   * - assert returned getMessage() same as provided at construction.
   */
  public function testExistingEntryID() : void
  {
    try {
      throw new DataExistingEntryException('A|B|C', 'Error message!');
    } catch (DataExistingEntryException $expected) {
      $this->assertSame('A|B|C', $expected->getTargetID());
      $this->assertSame('Error message!', $expected->getMessage());
      return;
    }
    $this->fail('FAILED: to throw DataExistingEntryException');
  }
}
