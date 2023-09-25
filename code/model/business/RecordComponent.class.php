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

/**
 * Abstract Business Model Record Component.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject RAMPObject})
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Hold reference back to parent Record and restrict polymorphic composite association. 
 * 
 * COLLABORATORS
 * - {@link \ramp\model\business\Record Record}
 *
 * @property-read \ramp\core\Str $propertyName Related parent record associated property name.
 * @property-read \ramp\model\business\Record $record Related parent Record associated with this component.
 * @property-read mixed $value Relevent value based on parent record state.
 */
abstract class RecordComponent extends BusinessModel
{
  private $propertyName;
  private $record;

  /**
   * Creates a multiple part primary key field related to a collection of property of associated record.
   * @param \ramp\core\Str $propertyName Related dataObject property name of associated record.
   * @param \ramp\model\business\Record $record Record parent of *this* property.
   * @param \ramp\model\business\BusinessModel $children Next sub BusinessModel.
   */
  public function __construct(Str $propertyName, Record $record, BusinessModel $children = NULL)
  {
    $this->propertyName = $propertyName;
    $this->record = $record;
    parent::__construct($children);
  }

  /**
   * Get parent record
   * **DO NOT CALL DIRECTLY, USE this->record;**
   * @return \ramp\model\business\Record Parent record of *this*
   */
  final protected function get_record() : Record
  {
    return $this->record;
  }

  /**
   * Get dataobject property name
   * **DO NOT CALL DIRECTLY, USE this->propertyName;**
   * @return \ramp\core\Str Property name for dataobject of *this* containing record
   */
  final protected function get_propertyName() : Str
  {
    return $this->propertyName;
  }

  /**
   * Returns value held by relevant property of associated record.
   * @return mixed Value held by relevant property of associated record
   */
  protected function get_value()
  {
    return $this->record->getPropertyValue((string)$this->propertyName);
  }
}
