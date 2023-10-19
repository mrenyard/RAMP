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
  private $manager; // BusinessModelManager
  private $withRecordName; // Str
  private $withKeys; // array
  private $parentValues; // array
  private $foreignKeyNames; // Strcollection

  /**
   * Creates a relation related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $relatedRecordType Record name of associated Record or Records. 
   * proir to allowing property value change
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName)
  {
    $this->withRecordName = $withRecordName;
    // Set BusinesModelManager
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->manager = $MODEL_MANAGER::getInstance();
    // instantiate temporary (new) 'with' record for access to primaryKey
    $withRecordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $withRecordName;
    $withNew = new $withRecordClassName();
    // build $this->withKeys, $this->parentValues, $this->foreignKeyNames 
    $this->withKeys = array();
    $this->parentValues = array();
    $this->foreignKeyNames = StrCollection::set();
    $wNpK = $withNew->primaryKey->getIterator();
    $pRpK = $parent->primaryKey->getIterator();
    $wNpK->rewind(); $pRpK->rewind();
    while ($wNpK->valid() && $pRpK->valid()) {
      $this->withKeys[count($this->withKeys)] = (string)$wNpK->current()->name; 
      $this->parentValues[count($this->parentValues)] = ($value = $pRpK->current()->value) ? $value : NULL;
      $value = $name->prepend(Str::FK())
        ->append($this->withRecordName->prepend(Str::UNDERLINE()))
        ->append($wNpK->current()->name->prepend(Str::UNDERLINE()));
      $this->foreignKeyNames->add($value);
      $wNpK->next(); $pRpK->next();
    }
    parent::__construct($name, $parent);
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
    $i = 0;
    $filterArray = array();
    foreach ($this->foreignKeyNames as $index) {
      $filterArray[$this->withKeys[$i++]] = $this->parent->getPropertyValue($index);
    }
    try {
      return $this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName),
        Filter::build($this->withRecordName, $filterArray)
      );
    } catch (DataFetchException $e) {
      return $this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
      );
    }
  }
}