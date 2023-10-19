<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\condition\Filter;

/**
 * Specific Relation association between parent (Record) and collection of (Record)s.
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Hold referance back to contining 'parent' RecordCollection
 * - Manage and maintain association through keys (primaryKey -> ForeignKey), data Lookup and Model Management.
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 * - {@link \ramp\model\business\RecordCollection}
 * - {@link \ramp\model\business\Relatable}
 * - {@link \ramp\model\business\BusinessModelManager}
 */
class RelationToMany extends Relation
{
  /**
   * Creates a relation related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $relatedRecordType Record name of associated Record or Records. 
   * proir to allowing property value change
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName)
  {
    // $NAMESPACE = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE;
    // $recordName = $NAMESPACE . '\\' . $withRecordName;
    // $with = new RecordCollection();
    // $with->add(new $recordName());

    // $toType = $this->processType($parent);
    // // Select and store common key (primary).
    // $this->primaryKey = $parent->primaryKey;
    // // Build foreignKey propertyNames.
    // $this->foreignKeyNames = StrCollection::set();
    // $parentValues = array();
    // foreach ($this->primaryKey as $subKey) {
    //   $value = $this->name->prepend(Str::FK())
    //     ->append($toType->prepend(Str::UNDERLINE()))
    //     ->append($subKey->index->prepend(Str::UNDERLINE()));
    //   $this->foreignKeyNames->add($value);
    //   $this->parentValues[$this->parentValues->count] = $parent->getPropertyValue($subKey->index);
    // }
    // print_r($this->foreignKeyNames);
    // print_r($parentValues);
    // // foreach ($this->primaryKey->indexes as $index) {
    // //   $value = $this->name->prepend(Str::FK())
    // //     ->append($toType->prepend(Str::UNDERLINE()))
    // //     ->append($index->prepend(Str::UNDERLINE()));
    // //   $this->foreignKeyNames->add($value);
    // // }

    // $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    // $with = ($manager->callCount <= 3) ? $MODEL_MANAGER::getInstance()->getBusinessModel
    // (
    //   new SimpleBusinessModelDefinition($withRecordName),
    //   Filter::build($withRecordName, array(
    //     'fk_relationAlpha_MockRecord_keyA' => '1',
    //     'fk_relationAlpha_MockRecord_keyB' => '1',
    //     'fk_relationAlpha_MockRecord_keyC' => '1'
    //   ))
    // ):
    // new RecordCollection(new $recordName());

    // // Check all have a shared foreignKeyNames consistant with primaryKey
    // foreach ($with as $o) {
    //   $fkns = $this->foreignKeyNames->getIterator();
    //   $pk = $this->primaryKey->getIterator();
    //   if ($o === $with[$with->count-1] && $o->isNew) { continue; }
    //   while ($fkns->valid() && $pk->valid()) {
    //     if ($o->getPropertyValue((string)$fkns->current()) != (string)$pk->current()->value) {
    //       throw new \InvalidArgumentException('Argument 3($with) contains inconsistent foreign key (' . $o->id . ')');
    //     }
    //     $fkns->next(); $pk->next();
    //   }
    // }
    parent::__construct($name, $parent); //, $with);
  }

  public function add(Record $object)
  {
    $this[$this->count] = $object;
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    // $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    // if ($this->parent->isNew) { return NULL;
    //   // $collection = new RecordCollection();
    //   // $collection->add($MODEL_MANAGER::getInstance()->getBusinessModel(
    //   //   new SimpleBusinessModelDefinition($this->withType, Str::NEW())
    //   // ));
    //   // return $collection;
    // }
    // $filterArray = array();
    // $fkns = $this->foreignKeyNames->getIterator();
    // $pk = $this->primaryKey->getIterator();
    // while ($fkns->valid() && $pk->valid()) {
    //   $filterArray[(string)$fkns->current()] = $this->parent->getPropertyValue((string)$pk->current()->name);
    //   $fkns->next(); $pk->next();
    // }
    // $recordName = $this->processType($this[0]);
    // return $MODEL_MANAGER::getInstance()->getBusinessModel(
    //   new SimpleBusinessModelDefinition($recordName),
    //   Filter::build($recordName, $filterArray)
    // );
  }
}