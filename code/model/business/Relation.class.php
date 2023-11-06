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
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\condition\Filter;

/**
 * Abstract Relation association between parent (Record) and \ramp\model\business\Relatable.
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
abstract class Relation extends RecordComponent
{
  protected $manager; // BusinessModelManager

  /**
   * Creates a relation related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property
   * @param bool $editable Optional set preferance for editability (defaults FALSE).
   */
  public function __construct(Str $name, Record $parent, bool $editable = FALSE)
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->manager = $MODEL_MANAGER::getInstance();
    parent::__construct($name, $parent, NULL, $editable);
  }

  final static protected function buildMapping(Record $from, Record $to, Str $fromPropertyName) : array
  {
    $i = 0;
    $keys = array();
    $keyMap = array(); //StrCollection::set();
    $fromPK = $from->primaryKey->getIterator();
    $toPK = $to->primaryKey->getIterator();
    $fromPK->rewind(); $toPK->rewind();
    while ($toPK->valid() && $fromPK->valid()) {
      $keys[$i] = (string)$toPK->current()->name; 
      $value = $fromPropertyName->prepend(Str::FK())
        ->append(self::processType($to)->prepend(Str::UNDERLINE()))
        ->append($toPK->current()->name->prepend(Str::UNDERLINE()));
      $keyMap[$keys[$i]] = (string)$value;
      $fromPK->next(); $toPK->next();
      $i++;
    }
    return $keyMap;
  }

  /**
   * Get isEditable
   * **DO NOT CALL DIRECTLY, USE this->isEditable;**
   * @return bool isEditable for *this*
   *
  protected function get_isEditable() : bool
  {
    return ($this->with->isNew || $this->editable !== FALSE);
  }*/

  /**
   * ArrayAccess method offsetSet, USE DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \InvalidArgumentException Adding properties through offsetSet STRONGLY DISCOURAGED!
   */
  final public function offsetSet($offset, $object)
  {
    // if (
    //   $this instanceof RelationToMany || (!($object instanceof Record)) || ($offset != $this->count) ||
    //   ($this->count > 0 && (string)$object->primaryKey->value !== (string)$this[0]->primaryKey->value)
    // )
    // if (
    //   ($this instanceof RelationToOne && (!$object instanceof RecordComponent)) ||
    //   ($this instanceof RelationToMany && $object instanceof Record)
    // )
    // {
      throw new \InvalidArgumentException(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    // }
    // parent::offsetSet($offset, $object);
  }
}
