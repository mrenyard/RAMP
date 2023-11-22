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
 * - {@see \ramp\model\business\Record}
 * - {@see \ramp\model\business\RecordCollection}
 * - {@see \ramp\model\business\Relatable}
 * - {@see \ramp\model\business\BusinessModelManager}
 */
class RelationToMany extends Relation
{
  private $errorCollection; // StrCollection
  private $withRecordName; // Str
  private $keyMap; // Strcollection

  /**
   * Creates a relation from a single property of containing record to a Record collection.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* RecordComponent.
   * @param \ramp\core\Str $withRecordName Record name (type) of related associated Record.
   * @param \ramp\core\Str $withPropertyName Related dataObject property name (type) of related associated Records.
   * @param bool $editable Optional set preferance for editability (defaults FALSE).
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName, Str $withPropertyName, bool $editable = FALSE)
  {
    parent::__construct($name, $parent, $editable);
    $this->setWith(NULL);
    $this->withRecordName = $withRecordName;
    $withRecordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $withRecordName;
    $this->keyMap = $this->buildMapping(new $withRecordClassName(), $parent, $withPropertyName);
    // TODO:mrenyard: Change once Request is back
    // if ((string)/ramp/http/Request::current()->modelURN == (string)$parent->id) {
    if ((string)$parent->id == 'mock-record:1|1|1') {
      $filterArray = array();
      foreach ($this->keyMap as $subKey => $foreignKey) {
        $filterArray[$foreignKey] = $this->parent->getPropertyValue($subKey);
      }
      try {
        $this->setWith($this->manager->getBusinessModel(
          new SimpleBusinessModelDefinition($this->withRecordName),
          Filter::build($this->withRecordName, $filterArray)
        ));
      } catch (DataFetchException $exception) {
        $this->setWith(new RecordCollection());
      }
      if ($editable === TRUE) {
        $this->getWith()->add($this->manager->getBusinessModel(
          new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
        ));
      }
    }
  }

  /**
   * @ignore
   */
  final protected function set_isEditable(bool $value)
  {
    $with = $this->getWith();
    if ($value === TRUE && isset($with[(count($with) - 1)]) && (!($with[(count($with) - 1)]->isNew))) {
      $with->add($this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
      ));
    }
    parent::set_isEditable($value);
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $key Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    // No validation unless a valid Parent (NOT new) and at allowed editing depth (Parent == current web address).
    if ($this->parent->isNew || $this->getWith() === NULL) { return; }
    $with = $this->getWith();
    $i = ($with->count - 1);
    try {
      parent::validate($postdata);
    } catch (DataExistingEntryException $exception) {
      // TODO:mrenyard: Check Session::loginAccount has access rights to resource.
      // $level = Session::getInstance()->getResourceRights(($this->withRecordName->append($primaryKey->prepend(Str::COLON())))); // :int (0:NON|1:VIEW|2:EDIT)
      // if ($level < ResourceRights::VIEW && $level < ResourceRights::EDIT) { throw new Exception;? }
      // TODO:mrenyard: Fix hack for ID (A|B|C) once DataExistingEntryException changed to accomidate targetID.
      $new = $with[$i];
      $with[$i] = $this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName, Str::set('A|B|C')) // $exception->targetID)
      );
      if ($this->isEditable) { $with->add($new); }
      return;
    }
    if ((!$this->parent->hasErrors) && $this->parent->isValid) {
      $with[$i]->updated();
      if ($this->isEditable) {
        $with->add($this->manager->getBusinessModel(
          new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
        ));
      }
      return;
    }
  }
}
