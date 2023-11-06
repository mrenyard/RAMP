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
  private $errorCollection; // StrCollection
  private $withRecordName; // Str
  private $keyMap; // Strcollection
  private $with; // Record

  /**
   * Creates a relation from a single property of containing Record to another Record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $relatedRecordType Record name of associated Record.
   * @param bool $editable Optional set preferance for editability (defaults FALSE).
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName, bool $editable = FALSE)
  {
    parent::__construct($name, $parent, $editable);
    $this->withRecordName = $withRecordName;
    $withRecordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $withRecordName;
    $this->keyMap = self::buildMapping($parent, new $withRecordClassName(), $name);
    // TODO:mrenyard: Change once Request is back
    // if ((string)/ramp/http/Request::current()->modelURN == (string)$parent->id) {
    if ((string)$parent->id == 'mock-record:new' || (string)$parent->id == 'mock-record:2|2|2' || (string)$parent->id == 'mock-record:3|3|3') {
      $filterArray = array();
      foreach ($this->keyMap as $subKey => $foreighKey) {
        $filterArray[$subKey] = $this->parent->getPropertyValue($foreighKey);
      }
      try {
        $this->with = $this->manager->getBusinessModel(
          new SimpleBusinessModelDefinition($this->withRecordName),
          Filter::build($this->withRecordName, $filterArray)
        )[0];
      } catch (DataFetchException $e) {
        $this->with = $this->manager->getBusinessModel(
          new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
        );
      }
      $this->setChildren($this->with);
    }
  }

  final public function addForeignKey(\stdClass $keyObject) : void
  {
    foreach ($this->keyMap as $subKey => $subForeignKey) {
      if (!isset($keyObject->$subForeignKey)) {
        $keyObject->$subForeignKey = NULL;
      }
    }
  }

  /**
   * Returns value held by relevant property of associated record.
   * @return mixed Value held by relevant property of associated record
   */
  final protected function get_value()
  {
    return (isset($this->with)) ? (string)$this->with->id : NULL;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $key Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    // No validation unless a valid Parent (NOT new) and at allowed editing depth (Parent == current web address).
    if ($this->parent->isNew || $this->with === NULL) { return; }
    $this->errorCollection = StrCollection::set();
    foreach ($postdata as $inputdata)
    {
      if ((string)$inputdata->attributeURN == (string)$this->id)
      {
        $values = $inputdata->value;
        if (is_array($values))
        {
          if (isset($values['unset']) && $values['unset'] == 'on')
          {
            // if (!$this->isEditable) { return; }
            // change FKs to NULL and Children to new;
            return;
          }
          if (!$this->with->isNew) { return; }
          if (count($values) === $this->with->primaryKey->count)
          {
            $filter = array();
            $keyPostdata = array();
            foreach ($this->keyMap as $subKey => $subForeignKey) {
              $filter[$subForeignKey] = $values[$subKey];
              $keyPostdata[(string)$this->with->id->append(Str::set($subKey)->prepend(Str::COLON()))] = $values[$subKey];
            }
            try {
              // attempt to set primaryKey from provided values
              $this->with->validate(PostData::build($keyPostdata));
            } catch (DataExistingEntryException $e) {
              // Get referance to existing entry and set as relation
              $this->with = $this->manager->getBusinessModel(
                new SimpleBusinessModelDefinition($this->withRecordName, Filter::build($this->withRecordName, $filter))
              )[0];
              $this->setChildren($this->with);
            }
            return;
          }
          $this->errorCollection->add(Str::set('Illegal Action on $this->id'));
        }
      }
    }
  }
}