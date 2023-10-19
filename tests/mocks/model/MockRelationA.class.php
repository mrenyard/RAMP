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

/**
 * Mock Concreate implementation of \ramp\model\business\Relation (one-to-one) for testing against.
 */
class MockRelationA extends Relation
{
  public $validateCount;
  public $hasErrorsCount;
  private $withType;

  public function __construct(Str $name, Record $parent, Relatable $with = NULL)
  {
    $this->validateCount = 0;
    $this->hasErrorsCount = 0;
    parent::__construct($name, $parent, $with);
    $this->withType = $this->processType($with);
    // Select and store common key (primary).
    $this->primaryKey = $with->primaryKey;
    // Build foreignKey propertyNames.
    $this->foreignKeyNames = StrCollection::set();
    foreach ($this->primaryKey->indexes as $index) {
      $value = $this->name->prepend(Str::FK())
        ->append($this->withType->prepend(Str::UNDERLINE()))
        ->append($index->prepend(Str::UNDERLINE()));
      $this->foreignKeyNames->add($value);
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

  /**   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    if ($this->parent->isNew) {
      return $MODEL_MANAGER::getInstance()->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withType, Str::NEW())
      );
    }
    $filterArray = array();
    $fkns = $this->foreignKeyNames->getIterator();
    $pk = $this->primaryKey->getIterator();
    while ($fkns->valid() && $pk->valid()) {
      $filterArray[(string)$pk->current()->name] = $this->parent->getPropertyValue((string)$fkns->current());
      $fkns->next(); $pk->next();
    }
    return $MODEL_MANAGER->getInstance()->getBusinessModel(
      new SimpleBusinessModelDefinition($this->withType),
      Filter::build($this->withType, $filterArray)
    );
  }
}
