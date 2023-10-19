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
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\Relation;
use ramp\model\business\Relatable;
use ramp\model\business\RecordCollection;
use ramp\model\business\SimpleBusinessModelDefinition;

/**
 * Mock Relation association between parent (Record) and collection of (Record)s.
 */
class MockRelationB extends Relation
{
  public $validateCount;
  public $hasErrorsCount;
  private $withPropertyName;

  public function __construct(Str $name, Record $parent, Relatable $with = NULL) //, Str $withPropertyName)
  {
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    $with = ($with === NULL) ? new RecordCollection() : $with;
    parent::__construct($name, $parent, $with);
    $toType = $this->processType($parent);
    // Select and store common key (primary).
    $this->primaryKey = $parent->primaryKey;
    // Build foreignKey propertyNames.
    $this->foreignKeyNames = StrCollection::set();
    foreach ($this->primaryKey->indexes as $index) {
      $value = $this->name->prepend(Str::FK())
        ->append($toType->prepend(Str::UNDERLINE()))
        ->append($index->prepend(Str::UNDERLINE()));
      $this->foreignKeyNames->add($value);
    }
    // Check all have a shared foreignKeyNames consistant with primaryKey
    foreach ($with as $o) {
      $fkns = $this->foreignKeyNames->getIterator();
      $pk = $this->primaryKey->getIterator();
      if ($o === $with[$with->count-1] && $o->isNew) { continue; }
      while ($fkns->valid() && $pk->valid()) {
        if ($o->getPropertyValue((string)$fkns->current()) != (string)$pk->current()->value) {
          throw new \InvalidArgumentException('Argument 3($with) contains inconsistent foreign key (' . $o->id . ')');
        }
        $fkns->next(); $pk->next();
      }
    }
  }

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

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    if ($this->parent->isNew) {
      $collection = new RecordCollection();
      $collection->add($MODEL_MANAGER::getInstance()->getBusinessModel(
        new SimpleBusinessModelDefinition(Str::set('MockMinRecord'), Str::NEW())
      ));
      return $collection;
    }
    $filterArray = array();
    $fkns = $this->foreignKeyNames->getIterator();
    $pk = $this->primaryKey->getIterator();
    while ($fkns->valid() && $pk->valid()) {
      $filterArray[(string)$fkns->current()] = $this->parent->getPropertyValue((string)$pk->current()->name);
      $fkns->next(); $pk->next();
    }
    $recordName = $this->processType($this[0]);
    return $MODEL_MANAGER::getInstance()->getBusinessModel(
      new SimpleBusinessModelDefinition($recordName),
      Filter::build($recordName, $filterArray)
    );
  }
}
