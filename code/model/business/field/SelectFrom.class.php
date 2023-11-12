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
use \ramp\model\business\Record;
use \ramp\core\OptionList;

/**
 * Abstract field related to a single property of its containing \ramp\model\business\Record.
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject}).
 * - Implement property specific methods for iteration, validity checking & error reporting.
 * - Hold referance back to parent Record and restrict polymorphic composite association. 
 * - Implement template method, processValidationRule to process provided ValidationRule.
 * - Manage and present list of Options avalible for selection.
 *
 * COLLABORATORS
 * - {@see \ramp\core\OptionList}
 * - {@see \ramp\model\business\Record}
 *
 * @property-read \ramp\core\OptionList $options Return reference to list of Options avalible for selection.
 */
abstract class SelectFrom extends Field
{
  private $options;

  /**
   * Base constructor for Field related to a single property of containing record.
   * @param \ramp\core\Str $parentPropertyName Related dataObject property name of containing record
   * @param \ramp\model\business\Record $containingRecord Record parent of *this* property
   * @param \ramp\core\OptionList $options Collection of field\Options, either suggestions or to select from.
   * @throws \InvalidArgumentException When OptionList CastableType is NOT field\Option or highter.
   */
  public function __construct(Str $parentPropertyName, Record $containingRecord, OptionList $options)
  {
    if ($options != null) {
      if (!$options->isCompositeType('\ramp\model\business\field\Option')) {
        throw new \InvalidArgumentException('OptionList $options compositeType MUST be \ramp\model\business\field\Option'); 
      }
      foreach ($options as $option) { $option->setParentField($this); }
    }
    $this->options = $options;
    parent::__construct($parentPropertyName ,$containingRecord);
  }

  /**
   * @ignore
   */
  protected function get_options() : OptionList
  {
    return $this->options;
  }
}