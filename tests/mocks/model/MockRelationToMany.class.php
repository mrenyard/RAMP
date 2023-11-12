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
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\RelationToMany;
use ramp\model\business\Relatable;

/**
 * Mock Concreate implementation of \ramp\model\business\Relation for testing against.
 */
class MockRelationToMany extends RelationToMany
{
  public $validateCount;
  public $hasErrorsCount;

  public function __construct(Str $name, Record $parent, Str $withRecordName, Str $withPropertyName, bool $editable = FALSE)
  {
    parent::__construct($name, $parent, $withRecordName, $withPropertyName, $editable);
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
  }

  // protected function get_with() { return $this->getWith(); }
  // protected function set_with(?Relatable $value) { $this->setWith($value); }
  // public function getModelManager() : BusinessModelManager { return $this->manager; }
  // public function callBuildMapping(Record $from, Record $to, Str $fromPropertyName) : array { return self::buildMapping($from, $to, $fromPropertyName); }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
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
