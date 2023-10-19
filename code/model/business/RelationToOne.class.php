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
 * Specific Relation association between parent (Record) and single (Record).
 *
 * RESPONSIBILITIES
 * - Implement property specific methods for iteration, validity checking & error reporting
 * - Hold referance back to contining 'parent' Record
 * - Manage and maintain association through keys (primaryKey -> ForeignKey), data Lookup and Model Management.
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 * - {@link \ramp\model\business\Relatable}
 * - {@link \ramp\model\business\BusinessModelManager}
 */
class RelationToOne extends Relation
{
  // private $withType;
  private $value;

  /**
   * Creates a relation related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $relatedRecordType Record name of associated Record or Records. 
   * proir to allowing property value change
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName)
  {
    // Set a default new Returned 
    $NAMESPACE = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE;
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $recordName = $NAMESPACE . '\\' . $withRecordName;
    // $withNew = $MODEL_MANAGER::getInstance()->getBusinessModel(
    //   new SimpleBusinessModelDefinition($withRecordName, Str::NEW())
    // );
    $withNew = new $recordName();
    // build $parentValues and $this->foreignKeyNames 
    $parentValues = array();
    $this->foreignKeyNames = StrCollection::set();
    $wNpK = $withNew->primaryKey->getIterator();
    $pRpK = $parent->primaryKey->getIterator();
    $wNpK->rewind(); $pRpK->rewind();
    while ($wNpK->valid() && $pRpK->valid()) {
      $parentValues[(string)$pRpK->current()->name] = ($value = $pRpK->current()->value) ? $value : NULL;
      $value = $name->prepend(Str::FK())
        ->append($withRecordName->prepend(Str::UNDERLINE()))
        ->append($wNpK->current()->name->prepend(Str::UNDERLINE()));
      $this->foreignKeyNames->add($value);
      $wNpK->next(); $pRpK->next();
    }

    // print_r($parentValues);
    // print_r((string)$this->foreignKeyNames->implode());

    parent::__construct($name, $parent); //, $withNew);
    $this->value = $withNew;
  }

  public function addForeignKey(\stdClass $dataObject) : void
  {
    foreach ($this->foreignKeyNames as $index) {
      $propertyName = (string)$index;
      if (!isset($dataObject->$propertyName)) {
        $dataObject->$propertyName = NULL;
      }
    }
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  final protected function get_value()
  {
    return $this->value;
    // return $this->parent->getPropertyValue((string)$this->name);

    // $filterArray = array();
    // $fkns = $this->foreignKeyNames->getIterator();
    // $pk = $this->primaryKey->getIterator();
    // while ($fkns->valid() && $pk->valid()) {
    //   $filterArray[(string)$pk->current()->name] = $this->parent->getPropertyValue((string)$fkns->current());
    //   $fkns->next(); $pk->next();
    // }
    // return $MODEL_MANAGER::getInstance()->getBusinessModel(
    //   new SimpleBusinessModelDefinition($this->withType),
    //   Filter::build($this->withType, $filterArray)
    // );
  }
}