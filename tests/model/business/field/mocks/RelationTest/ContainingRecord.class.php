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
namespace tests\ramp\model\business\field\mocks\RelationTest;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\field\Relation;

/**
 * Mock Concreate implementation of \ramp\model\business\BusinessModel for testing against.
 */
class ContainingRecord extends Record
{
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set('key');
  }

  protected function get_key()
  {
    if (!isset($this['key'])) {
      $this['key'] = new MockField(
        Str::set('key'),
        $this
      );
    }
    return $this['key']; 
  }

  public function get_relationAlpha()
  {
    if (!isset($this['relationAlpha'])) {
      $this['relationAlpha'] = new Relation(
        Str::set('relationAlpha'),
        $this,
        Str::set('MockRecord')
      );
    }
    return $this['relationAlpha'];
  }
  
  protected static function checkRequired($dataObject) : bool
  {
    return (isset($dataObject->key));
  }
}