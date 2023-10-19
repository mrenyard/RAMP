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
namespace ramp\condition;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\model\business\Property;

/**
 * Code defined statement restricted and evaluated by the constraints of your local business model
 * as defined within RAMP_BUSINESS_MODEL_NAMESPACE.
 *
 * RESPONSIBILITIES
 * - Extend Condition to hold additional values for record and property as component parts of attribute.
 * - Ensure components are restricted and evaluated by the constraints of local business model
 *   defined within RAMP_BUSINESS_MODEL_NAMESPACE.
 *
 * COLLABORATORS
 * - {@link \ramp\condition\Condition}
 * - {@link \ramp\condition\iEnvironment}
 * - {@link \ramp\condition\PHPEnvironment} (Default)
 * - {@link \ramp\condition\Operator}
 *
 * @property-read \ramp\core\Str $record Returns name of record containing property to evaluate.
 * @property-read \ramp\core\Str $property Returns name of property to be evaluated.
 */
abstract class BusinessCondition extends Condition
{
  private $record;
  private $property;

  /**
   * Default constructor for BusinessCondition.
   *
   * PRECONDITIONS
   * - Requires the following SETTING to have been set (usually via ramp.ini):
   *  - {@link \ramp\SETTING}::$RAMP_BUSINESS_MODEL_NAMESPACE
   * @param \ramp\core\Str $record Name of business record containing property to evaluate
   * @param \ramp\core\Str $property Name of property to be evaluated
   * @param \ramp\condition\Operator $operator Operator to perform operation
   * @param mixed $comparable Value to be compared
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and limits
   * as defined by your locally defined business model (RAMP_BUSINESS_MODEL_NAMESPACE)
   */
  public function __construct(Str $record, Str $property, Operator $operator, $comparable = null)
  {
    //  
    $recordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $record;
    if (
      !class_exists($recordClassName) ||
      (!$property->contains(StrCollection::set('fk_')) && !method_exists(new $recordClassName(), 'get_' . $property))
    ) {
      throw new \DomainException('Invalid: ' . $record . '->' . $property . ', does NOT match business model');
    }
    $this->record = $record;
    $this->property = $property;
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    parent::__construct(
      $this->property->prepend(
        $this->record->append(
          Str::set($memberAccessOperator(PHPEnvironment::getInstance()))
        )
      ),
      $operator,
      $comparable
    );
  }

  /**
   * Returns name of business record.
   * **DO NOT CALL DIRECTLY, USE this->record;**
   * @return \ramp\core\Str Name of record containing property to evaluate.
   */
  protected function get_record() : Str
  {
    return $this->record;
  }

  /**
   * Returns name of business property.
   * **DO NOT CALL DIRECTLY, USE this->property;**
   * @return \ramp\core\Str Name of property to be evaluated
   */
  protected function get_property() : Str
  {
    return $this->property;
  }
}
