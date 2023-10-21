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
  private $withRecordName; // Str
  public $keys; // array
  public $foreignKeyNames; // Strcollection

  /**
   * Creates a relation from a single property of containing Record to another Record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $relatedRecordType Record name of associated Record.
   * proir to allowing property value change
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName)
  {
    $this->withRecordName = $withRecordName;
    // instantiate temporary (new) 'with' record for access to primaryKey
    $withRecordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $withRecordName;
    $withNew = new $withRecordClassName();
    $this->buildMapping($parent, $withNew, $name, $withRecordName);
    parent::__construct($name, $parent);
  }

  protected function buildMapping(Record $from, Record $to, Str $fromPropertyName) : void
  {
    $i = 0;
    $this->keys = array();
    $this->foreignKeyNames = StrCollection::set();
    $fromPK = $from->primaryKey->getIterator();
    $toPK = $to->primaryKey->getIterator();
    $fromPK->rewind(); $toPK->rewind();
    while ($toPK->valid() && $fromPK->valid()) {
      $this->keys[$i] = (string)$toPK->current()->name; 
      $value = $fromPropertyName->prepend(Str::FK())
        ->append($this->processType($to)->prepend(Str::UNDERLINE()))
        ->append($toPK->current()->name->prepend(Str::UNDERLINE()));
      $this->foreignKeyNames->add($value);
      $fromPK->next(); $toPK->next();
      $i++;
    }
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
      $filterArray[$this->keys[$i++]] = $this->parent->getPropertyValue($index);
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