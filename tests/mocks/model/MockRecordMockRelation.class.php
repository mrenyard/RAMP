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
use ramp\condition\Filter;
use ramp\model\business\Relatable;
use ramp\model\business\RecordCollection;
use ramp\model\business\RecordComponent;
use ramp\model\business\SimpleBusinessModelDefinition;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class MockRecordMockRelation extends MockRecord
{
  
  protected function buildAlphaWith() : Relatable
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $manager = $MODEL_MANAGER::getInstance();
    $recordName = Str::set('MockMinRecord');
    if ($manager->callCount > 3 || $this->primaryKey->value == '1|1|1') { return new RecordCollection(new MockMinRecord()); }
    return $manager->getBusinessModel(
      new SimpleBusinessModelDefinition($recordName),
      Filter::build($recordName, array(
        'fk_relationAlpha_MockRecord_keyA' => '1',
        'fk_relationAlpha_MockRecord_keyB' => '1',
        'fk_relationAlpha_MockRecord_keyC' => '1'
      ))
    );
  }

  protected function get_relationAlpha() : RecordComponent
  {
    if (!isset($this[1])) {
      $this[1] = new MockRelationB($this->relationAlphaName, $this, $this->buildAlphaWith());
    }
    return $this[1];
  }

  protected function buildBetaWith() : Relatable
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $manager = $MODEL_MANAGER::getInstance();
    $d = new \stdClass();
    if ($manager->callCount < 3 || !$this->isNew) {
      $d->key1 = 1;
      $d->key2 = 1;
      $d->key3 = 1;
    }
    $this->relationBetaWith = new MockMinRecord($d);
    return $this->relationBetaWith;
  }

  protected function get_relationBeta() : RecordComponent
  {
    if (!isset($this[2])) {
      $this[2] = new MockRelationA($this->relationBetaName, $this, $this->buildBetaWith());
    }
    return $this[2];
  }
}
