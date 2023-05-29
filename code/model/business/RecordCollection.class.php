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

use ramp\core\RAMPObject;
use ramp\core\iCollection;
use ramp\core\Collection;
use ramp\core\Str;
use ramp\model\business\BusinessModel;

/**
 * Referance and maintain a collection of \ramp\model\business\Records.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject}).
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Define and restrict relational association to objects of this type ({@link \ramp\model\business\Relatable}). 
 * - Provide methods to maintain and access a Collection of {@link Record}s
 *
 * COLLABORATORS
 * - Collection of {@link \ramp\model\business\Record}s
 */
abstract class RecordCollection extends Relatable implements iCollection
{
  /**
   * Default constructor for collection of \ramp\model\business\Records.
   * - Sets composite type for this collection as <i>this</i> class-name with string <i>Collection</i> truncated:
   *  - e.g. {@link \ramp\model\business\UserCollection} would expect to referance only {@link \ramp\model\business\User}s.
   */
  final public function __construct()
  {
    parent::__construct();
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  final public function get_id() : Str
  {
    return $this->processType((string)$this, TRUE);
  }

  /**
   * Add a reference (Record), to this collection.
   * @param \ramp\core\RAMPObject $object RAMPObject reference to be added (Record)
   * @throws \InvalidArgumentException When provided object NOT expected type (Record)
   */
  final public function add(RAMPObject $object)
  {
    parent::offsetSet($this->count, $object);
  }
}
