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
namespace ramp\model\business\field;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\iOption;
use ramp\core\BadPropertyCallException;
use ramp\model\business\BusinessModel;

/**
 * A Single option.
 *
 * RESPONSIBILITIES
 * - Define a simple base implementation of iOption for easy reuse.
 *
 * @property-read mixed $key Returns key (enum:int|URN:Str).
 * @property-read \ramp\core\Str $description Returns description.
 */
class Option extends BusinessModel implements iOption
{
  private int $key;
  private Str $description;
  private SelectFrom $parentField;

  /**
   * Constructor for new instance of Option.
   * @param int $key Value to be set for key.
   * @param \ramp\core\Str $description String value to be set for description.
   */
  public function __construct(int $key, Str $description)
  {
    parent::__construct();
    $this->key = $key;
    $this->description = $description;
  }

  /**
   * Sets relationship to parent field for use by isSelected.
   * @param \ramp\model\business\field\SelectFrom $value Parent field.
   */
  public function setParentField(SelectFrom $value) : void
  {
    $this->parentField = $value;
  }

  /**
   * Get key (enum:int|URN:Str)
   * **DO NOT CALL DIRECTLY, USE**
   * ```php
   * $this->key;
   * ```
   * @return int Key
   */
  public function get_key() : int
  {
    return $this->key;
  }

  /**
   * Get unique identifier
   * **DO NOT CALL DIRECTLY, USE:**
   * ```php
   * $this->id;
   * ```
   * @return \ramp\core\Str id
   */
  public function get_id() : Str
  {
    return Str::set($this->key);
  }

  /**
   * Get Description.
   * **DO NOT CALL DIRECTLY, USE:**
   * ```php
   * $this->description;
   * ```
   * @return Str Description
   */
  public function get_description() : Str
  {
    return $this->description;
  }

  /**
   * Returns whether this option has been chosen/selected.
   * **DO NOT CALL DIRECTLY, USE**
   * ```php
   * $this->isSelected;
   * ```
   * @return bool This option has been chosen/selected.
   * @throws BadPropertyCallException When called without parentField first being set.
   */
  public function get_isSelected() : bool
  {
    if (!isset($this->parentField)) {
      throw new BadPropertyCallException('Must set parentField before calling isSelected.');
    }
    if ($this->parentField instanceof SelectMany) {
      foreach($this->parentField->value as $selected) {
        if ($selected->key === $this->key) { return TRUE; }
      }
      return FALSE;
    }
    return ($this->parentField->value->key === $this->key);
  }
}
