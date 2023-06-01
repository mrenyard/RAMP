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
namespace ramp\model\business\key;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Record;
use ramp\model\business\RecordComponent;

/**
 * Abstract Key Record Component Business Model.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject RAMPObject})
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Hold reference back to parent Record and restrict polymorphic composite association.
 * - Define access to compound key indexes and values based on parent record state.
 * 
 * COLLABORATORS
 * - {@link \ramp\model\business\Record Record}
 *
 * @property-read \ramp\core\StrCollection $indexes Related parent record associated property name.
 * @property-read \ramp\core\StrCollection $values Related parent Record associated with this component.
 */
abstract class Key extends RecordComponent
{
  /**
   * Define a multiple part key related to its parent record.
   * @param \ramp\core\Str $parentPropertyName Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parentRecord Record parent of *this* property.
   */
  public function __construct(Str $parentPropertyName, Record $parentRecord)
  {
    parent::__construct($parentPropertyName, $parentRecord);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return Str::COLON()->prepend(
      $this->parentRecord->id
    )->append(Str::hyphenate($this->parentPropertyName));
  }

  /**
   * Returns indexes for key.
   * **DO NOT CALL DIRECTLY, USE this->indexes;**
   * @return \ramp\core\StrCollection Indexes related to data fields for this key.
   */
  abstract protected function get_indexes() : StrCollection;

  /**
   * Returns primarykey values held by relevant properties of parent record.
   * **DO NOT CALL DIRECTLY, USE this->values;**
   * @return \ramp\core\StrCollection Values held by relevant property of parent record
   */
  abstract protected function get_values() : ?StrCollection;
}