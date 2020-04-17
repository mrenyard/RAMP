<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\condition;

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\model\business\Property;

/**
 * Code defined statement restricted and evaluated by the constraints of your local business model
 * as defined within SVELTE_BUSINESS_MODEL_NAMESPACE.
 *
 * RESPONSIBILITIES
 * - Extend Condition to hold additional values for record and property as component parts of attribute.
 * - Ensure components are restricted and evaluated by the constraints of local business model
 *    defined within SVELTE_BUSINESS_MODEL_NAMESPACE.
 *
 * COLLABORATORS
 * - {@link \svelte\condition\Condition}
 * - {@link \svelte\condition\iEnvironment}
 * - {@link \svelte\condition\PHPEnvironment} (Default)
 * - {@link \svelte\condition\Operator}
 */
abstract class BusinessCondition extends Condition {

  private $record;
  private $property;

  /**
   * Default constructor for BusinessCondition.
   *
   * PRECONDITIONS
   * - Requires the following SETTING to have been set (usually via svelte.ini):
   *  - {@link \svelte\SETTING}::$SVELTE_BUSINESS_MODEL_NAMESPACE
   * @param \svelte\core\Str $record Name of business record containing property to evaluate
   * @param \svelte\core\Str $property Name of property to be evaluated
   * @param \svelte\condition\Operator $operator Operator to perform operation
   * @param mixed $comparable Value to be compared
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and limits
   * as defined by your locally defined business model (SVELTE_BUSINESS_MODEL_NAMESPACE)
   */
  public function __construct(Str $record, Str $property, Operator $operator, $comparable = null)
  {
    $recordClassName = \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $record;
    if (!class_exists($recordClassName) || !method_exists(new $recordClassName(), 'get_' . $property)) {
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
   * @return \svelte\core\Str Name of record containing property to evaluate.
   */
  protected function get_record() : Str
  {
    return $this->record;
  }

  /**
   * Returns name of business property.
   * **DO NOT CALL DIRECTLY, USE this->property;**
   * @return \svelte\core\Str Name of property to be evaluated
   */
  protected function get_property() : Str
  {
    return $this->property;
  }

  /**
   * Sets value of comparable while comparing its validity against business model.
   * **DO NOT CALL DIRECTLY, USE this->comparable = $value;**
   *
   * PRECONDITIONS
   * - Requires the following SETTING to have been set (usually via svelte.ini):
   *  - SETTING::SVELTE_BUSINESS_MODEL_NAMESPACE
   * @param mixed $value Value to be compared
   * @throws \DomainException when argument does Not validate against its associated property's processValidationRules()
   * @link svelte.model.business.Property#method_processValidationRules \svelte\model\business\Property::processValidationRules()
   */
  protected function set_comparable($value)
  {
    $value = (is_string($value) && is_numeric($value)) ?
      ((float)$value == (int)$value) ? (int)$value :
        (float)$value :
          $value;

    $recordClassName = \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE . '\\' . $this->record;
    $recordClass = new $recordClassName();
    $propertyName = (string)$this->property;
    $propertyClass = $recordClass->$propertyName;
    if ($propertyClass->processValidationRules($value) == false) {
      throw new \DomainException('Supplied argument does Not validate against associated property');
    }
    parent::set_comparable($value);
  }
}
