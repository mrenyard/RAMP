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
class RelationLookup extends Relation
{
  private $withRecordName; // Str
  public $keys; // array
  public $foreignKeyNames; // Strcollection

  /**
   * Creates a relation from a single property of containing record to a Record collection.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $relatedRecordType Record name of associated Record or Records. 
   * proir to allowing property value change
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName, Str $usesLookupTable, Str $toLookupProperty = NULL)
  {
    $toLookupProperty = ($toLookupProperty == NULL) ? $withRecordName : $toLookupProperty;
    $this->withRecordName = $withRecordName;
    // instantiate temporary (new) 'with' record for access to primaryKey
    $withRecordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $withRecordName;
    $withNew = new $withRecordClassName();
    $this->buildMapping($withNew, $parent, $toLookupProperty); //$withPropertyName);
    parent::__construct($name, $parent);
  }

  public function add(Record $object)
  {
    $this[$this->count] = $object;
  }

  /**
   *
   */
  final protected function get_object()
  {
    $i = 0;
    $filterArray = array();
    foreach ($this->foreignKeyNames as $index) {
      $filterArray[(string)$index] = $this->parent->getPropertyValue($this->keys[$i++]);
    }
    // print_r($filterArray);
    $collection = new RecordCollection();
    try {
      $collection = $this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName),
        Filter::build($this->withRecordName, $filterArray)
      );
    } catch (DataFetchException $e) {
      $collection->add($this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
      ));
    }
    return $collection;
  }
}