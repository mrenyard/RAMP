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

use ramp\core\Str;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\field\Field;

/**
 * Single field (part) of ForeignKey.
 *
 * RESPONSIBILITIES
 * - [...]
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\Record}
 * - {@link \ramp\model\business\field\Field}
 * - Component of {@link \ramp\model\business\ForeignKey}
 */
class ForeignKeyPart extends Field
{
  private $key;

  public function __construct(Record $from, Str $property, Str $key)
  {
    $this->key = $key;
    parent::__construct($property, $from);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return parent::get_id()->append(
      Str::hyphenate($this->key)->prepend(
        Str::set('[')
      )->append(
        Str::set(']')
      )
    );
  }

  /**
   * Get Label
   * **DO NOT CALL DIRECTLY, USE this->label;**
   * @return \ramp\core\Str Label for *this*
   */
  protected function get_label() : Str
  {
    return Str::set(ucwords(trim(preg_replace('/((?:^|[A-Z])[a-z]+)/', '$0 ', str_replace('KEY', '', $this->key)))));
  }


  /**
   * Set isEditable
   * **DO NOT CALL DIRECTLY, USE this->isEditable = $value;**
   * Use to request change of isEditable, some defaults are NOT overridable.
   * @param bool $value of requested value.
   */
  protected function set_isEditable(bool $value)
  {
  }

  /**
   * Returns value held by relevant property of containing record.
   * @return mixed Value held by relevant property of containing record
   */
  protected function get_value()
  {
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata) : void
  {
    // STUB
  }

  /**
   * Template method for use in validation.
   * @param mixed $value Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($value) : void
  {
    // STUB
  }
}
