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
 * Abstract Relation association between parent (Record) and \ramp\model\business\Relatable.
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
abstract class Relation extends RecordComponent
{
  protected BusinessModelManager $manager;
  private ?Relatable $with;

  /**
   * Creates a relation related to a single property of containing record.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* RecordComponent.
   * @param bool $editable Optional set preferance for editability (defaults FALSE).
   */
  public function __construct(Str $name, Record $parent, bool $editable = FALSE)
  {
    $MODEL_MANAGER = \ramp\SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
    $this->manager = $MODEL_MANAGER::getInstance();
    $this->with = NULL;
    parent::__construct($name, $parent, $editable);
  }

  protected function getWith() : ?Relatable  { return $this->with; }
  protected function setWith(?Relatable $value) : void
  {
    $this->with = $value;
    if ($value) { parent::setChildren($value); }
  }

  /**
   * Builds array where 'key, value pair' map $from->primaryKey subKeys to $to foreignKey subKeys.
   * @param \ramp\model\business\Record $from Record 'parent' to be mapped from.
   * @param \ramp\model\business\Record $to Record 'with' to be mapped to.
   * @param \ramp\core\Str $fromPropertyName Name of property for this relation.
   * @return array Mapping from 'key, value pair' $from->primaryKey subKeys to $to foreignKey subKeys.
   * @return string Representation of *this* filter based on provided target environment
   */
  final static protected function buildMapping(Record $from, Record $to, Str $fromPropertyName) : array
  {
    $i = 0; $keys = array(); $keyMap = array();
    $fromPK = $from->primaryKey->getIterator();
    $toPK = $to->primaryKey->getIterator();
    $fromPK->rewind(); $toPK->rewind();
    while ($toPK->valid() && $fromPK->valid()) {
      $keys[$i] = (string)$toPK->current()->name; 
      $value = $fromPropertyName->prepend(Str::FK())
        ->append(SELF::processType($to)->prepend(Str::UNDERLINE()))
        ->append($toPK->current()->name->prepend(Str::UNDERLINE()));
      $keyMap[$keys[$i]] = (string)$value;
      $fromPK->next(); $toPK->next();
      $i++;
    }
    return $keyMap;
  }

  /**
   * ArrayAccess method offsetSet, DO NOT USE THROWS InvalidArgumentException.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \InvalidArgumentException Adding properties through offsetSet BLOKED!
   */
  #[\Override]
  final public function offsetSet($offset, $object) : void
  {
    throw new \InvalidArgumentException('Adding properties through offsetSet BLOKED!');
  }
  
  /**
   * Returns value held by relevant property of associated record.
   * @return mixed Value held by relevant property of associated record
   */
  #[\Override]
  final protected function get_value()
  {
    return ($this->with !== NULL) ? (string)$this->with->id : NULL;
  }
}
