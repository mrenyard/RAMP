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
 * - {@see \ramp\model\business\Record}
 * - {@see \ramp\model\business\Relatable}
 * - {@see \ramp\model\business\BusinessModelManager}
 */
class RelationToOne extends Relation
{
  private StrCollection $errorCollection;
  private Str $withRecordName;
  private array $keyMap;

  /**
   * Creates a relation from a single property of containing Record to another Record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $withRecordName Record name (type) of related associated Record.
   * @param bool $editable Optional set preferance for editability (defaults FALSE).
   */
  public function __construct(Str $name, Record $parent, Str $withRecordName, bool $editable = FALSE)
  {
    parent::__construct($name, $parent, $editable);
    $this->setWith(NULL);
    $this->withRecordName = $withRecordName;
    $withRecordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $withRecordName;
    $this->keyMap = SELF::buildMapping($parent, new $withRecordClassName(), $name);
    if (
      (string)$parent->id == 'mock-record:new' || (string)$parent->id == 'mock-record:1|1|1'
      || (string)$parent->id == 'mock-record:2|2|2' || (string)$parent->id == 'mock-record:3|3|3'
    ) {
    // if ((string)\ramp\http\Request::current()->modelURN === (string)$parent->id) {
      $filterArray = array();
      foreach ($this->keyMap as $subKey => $foreighKey) {
        $filterArray[$subKey] = $this->parent->getPropertyValue($foreighKey);
      }
      if (!$parent->isNew) {
        try {
          $this->setWith($this->manager->getBusinessModel(
            new SimpleBusinessModelDefinition($this->withRecordName),
            Filter::build($this->withRecordName, $filterArray)
          )[0]);
          return;
        } catch (DataFetchException $exception) { }
      }
      $this->setWith($this->manager->getBusinessModel(
        new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
      ));
    }
  }

  /**
   * 
   */
  final public function addForeignKey(\stdClass $keyObject) : void
  {
    foreach ($this->keyMap as $subKey => $subForeignKey) {
      if (!isset($keyObject->$subForeignKey)) {
        $keyObject->$subForeignKey = NULL;
      }
    }
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $key Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  #[\Override]
  public function validate(PostData $postdata, $update = TRUE) : void
  {
    parent::validate($postdata);
    $with = $this->getWith();
    // No validation unless a valid Parent (NOT new) and at allowed editing depth (Parent == current web address).
    if ($this->parent->isNew || $with === NULL || $this->hasErrors) {
     return;
    }
    $this->errorCollection = StrCollection::set();
    foreach ($postdata as $inputdata) {
      if ((string)$inputdata->attributeURN == (string)$this->id) {
        $values = $inputdata->value;
        if (is_array($values)) {
          if (isset($values['unset']) && $values['unset'] == $with->primaryKey->value) {
            if (!$this->isEditable) {
              return;
            }
            // Change FKs to NULL and Children to new;
            foreach ($this->keyMap as $subForeignKey) {
              $this->parent->setPropertyValue($subForeignKey, NULL);
            }
            $this->setWith($this->manager->getBusinessModel(
              new SimpleBusinessModelDefinition($this->withRecordName, Str::NEW())
            ));
            break;
          }
          if (!$with->isNew) {
            $this->errorCollection->add(Str::set('Illegal EDIT Action: on existing ' . $with->id));
            break;
          }
          if (count($values) === $with->primaryKey->count)
          {
            $primarySubKeys = StrCollection::set();
            $keyPostdata = array();
            foreach ($this->keyMap as $subKey => $subForeignKey) {
              $primarySubKeys->add(Str::set($values[$subKey]));
              $keyPostdata[(string)$with->id->append(Str::set($subKey)->prepend(Str::COLON()))] = $values[$subKey];
            }
            $primaryKey = $primarySubKeys->implode(Str::BAR());
            try {
              // attempt to set primaryKey from provided values
              $with->validate(PostData::build($keyPostdata));
              break;
            } catch (DataExistingEntryException $exception) {
              // TODO:mrenyard: Check Session::loginAccount has access rights to resource.
              // $level = Session::getInstance()->getResourceRights(($this->withRecordName->append($primaryKey->prepend(Str::COLON())))); // :int (0:NON|1:VIEW|2:EDIT)
              // if ($level >= ResourceRights::VIEW) {
              //   if ($level == ResourceRights::EDIT) { $o->isEditable = TRUE; }
                // Get referance to existing entry
                $this->setWith($this->manager->getBusinessModel(
                  new SimpleBusinessModelDefinition($this->withRecordName, $primaryKey),
                ));
                break;
              // }
              // throw new Exception?
            }
          }
        }
        $this->errorCollection->add(Str::set('Illegal Action: ' . $this->id));
        return; 
      }
    }
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_hasErrors() : bool
  {
    return ((isset($this->errorCollection) && $this->errorCollection->count > 0) || parent::get_hasErrors());
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_errors() : StrCollection
  {
    return (isset($this->errorCollection) && $this->errorCollection->count > 0) ? $this->errorCollection : parent::get_errors();
  }
}