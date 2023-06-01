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
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\Relatable;
use ramp\model\business\field\Field;

/**
 * Foreign Key, used by {@link \ramp\model\business\Relation Relation} to connect {@link \ramp\model\business\Relatable Relatable}s.
 * 
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject RAMPObject})
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Hold reference back to parent Record and restrict polymorphic composite association. 
 * - Define access to relevent value based on parent record state.
 * 
 * COLLABORATORS
 * - Composite Parent{@link \ramp\model\business\Record Record} and Target
 * - Used by {@link \ramp\model\business\Relation Relation}
 * - {@link \ramp\model\business\Record Record}
 *
 */
class Foreign extends Key
{
  /**
   * Creates a foreignkey in multiple parts with special id for use by model\business\Relation for searching.
   * @param \ramp\core\Str $parentPropertyName Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parentRecord Record parent of *this* property.
   * @param \ramp\model\business\Relatable $target Next sub BusinessModel.
   */
  public function __construct(Str $parentPropertyName, Record $parentRecord, Primary $targetPrimaryKey)
  {
    $i = 0;
    parent::__construct($parentPropertyName, $parentRecord);
    foreach ($targetPrimaryKey->indexes as $index)
    {
      $this[$i++] = new class($parentPropertyName, $parentRecord, $index) extends Field
      {
        private static $type;
        private $index;
        public function __construct(Str $parentPropertyName, Record $parentRecord, Str $index)
        {
          $this->index = $index;
          if (!isset(self::$type)) { self::$type = Str::set('foreign-key-part field'); }
          parent::__construct($parentPropertyName, $parentRecord);
        }
        protected function get_id() : Str { return parent::get_id()->append(Str::hyphenate($this->index)->prepend(Str::set('['))->append(Str::set(']'))); }
        protected function get_type() : Str { return self::$type; }
        protected function get_label() : Str { return Str::set(ucwords(trim(preg_replace('/((?:^|[A-Z])[a-z]+)/', '$0 ', $this->key)))); }
        protected function get_value() { /* STUB */ }
        public function validate(PostData $postdata) : void { /* STUB */ }
        public function processValidationRule($value) : void { /* STUB */ }
      };
    }
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  protected function get_id() : Str
  {
    return parent::get_id()->append(Str::set('[foreign-key]'));
  }

  /**
   * Returns indexes for key.
   * **DO NOT CALL DIRECTLY, USE this->indexes;**
   * @return \ramp\core\StrCollection Indexes related to data fields for this key.
   */
  protected function get_indexes() : StrCollection
  {
  }

  /**
   * Returns primarykey values held by relevant properties of parent record.
   * **DO NOT CALL DIRECTLY, USE this->values;**
   * @return \ramp\core\StrCollection Values held by relevant property of parent record
   */
  protected function get_values() : ?StrCollection
  {
  }
}