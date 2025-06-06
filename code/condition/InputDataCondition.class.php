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

use ramp\core\Str;

/**
 * Single representation of a verified 'attribute (record:key:property) value pair' for submission into system.
 * - restricted and evaluated by the constraints of your business model
 *   - as defined within (RAMP_BUSINESS_MODEL_NAMESPACE)
 *
 * RESPONSIBILITIES
 * - Extend BusinessCondition to hold additional value for primaryKey,
 *    along with record and property as component parts of attribute.
 * - Enforce assignment operator as the primary operation.
 * - Set default target environment as {@see \ramp\condition\URNQueryEnvironment}
 * - Ensure components are restricted and evaluated by the constraints of local business model
 *    defined within RAMP_BUSINESS_MODEL_NAMESPACE.
 *
 * COLLABORATORS
 * - {@see \ramp\condition\BusinessCondition}
 * - {@see \ramp\condition\iEnvironment}
 * - {@see \ramp\condition\URNQueryEnvironment} (Default)
 * - {@see \ramp\condition\Operator} (Operator::ASSIGNMENT Enforced)
 *
 * @property-read \ramp\core\Str $primaryKeyValue Returns primary key of target business record.
 * @property-read mixed $value Returns value to be evaluated (synonym for comparable).
 * @property-read \ramp\core\Str $attributeURN Returns name of attribute as URN to be restricted, evaluated or modified.
 */
final class InputDataCondition extends BusinessCondition
{
  private Str $primaryKeyValue;

  /**
   * Constructs a single verified representation of an 'attribute–value pair'.
   * @param \ramp\core\Str $record Name of target business record
   * @param \ramp\core\Str $primaryKeyValue Primary Key value of target business record
   * @param \ramp\core\Str $property Name of target property
   * @param mixed $value Value to be evaluated
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and limits
   * as defined by your locally defined business model within (RAMP_BUSINESS_MODEL_NAMESPACE)
   */
  public function __construct(Str $record, Str $primaryKeyValue, Str $property, $value)
  {
    parent::__construct($record, $property, Operator::ASSIGNMENT(), $value);
    $this->primaryKeyValue = $primaryKeyValue;
  }

  /**
   * @ignore
   */
  protected function get_primaryKeyValue() : Str
  {
    return $this->primaryKeyValue;
  }

  /**
   * @ignore
   */
  protected function get_value()
  {
    return $this->get_comparable();
  }

  /**
   * @ignore
   */
  protected function get_attributeURN() : Str
  {
    return Str::hyphenate($this->record)
      ->append(Str::COLON())
      ->append($this->primaryKeyValue)
      ->append(Str::COLON())
      ->append(Str::hyphenate($this->property)
    );
  }

  /**
   * Returns string representation of input data statement (attribute–value pair) based on target environment.
   * @param \ramp\condition\iEnvironment $targetEnvironment Environment to target, default URN Query.
   * @param mixed $value Value to be compared with attribute by operation.
   * @throws \DomainException when second argument ($value) does Not validate against its
   *  associated property's processValidationRules()
   * @return string Representation of *this* input data statement based on target environment
   */
  #[\Override]
  public function __invoke(iEnvironment $targetEnvironment = NULL, $value = NULL) : string
  {
    $targetEnvironment = ($targetEnvironment !== NULL) ?
      $targetEnvironment : URNQueryEnvironment::getInstance();

    if ($value !== NULL) { $this->comparable = $value; }

    $primaryOperationOperator = $this->operator;
    $memberAccessOperator = Operator::MEMBER_ACCESS();
    $openingParenthesisOperator = Operator::OPENING_PARENTHESIS();
    $closingParenthesisOperator = Operator::CLOSING_PARENTHESIS();
    $record = ($targetEnvironment == URNQueryEnvironment::getInstance()) ?
      Str::hyphenate($this->record) : $this->record;
    $property = ($targetEnvironment == URNQueryEnvironment::getInstance()) ?
      Str::hyphenate($this->property) : $this->property;

    return $record . $memberAccessOperator($targetEnvironment) .
      $this->primaryKeyValue . $memberAccessOperator($targetEnvironment) . $property .
        $primaryOperationOperator($targetEnvironment) .
          $openingParenthesisOperator($targetEnvironment) .
            $this->value . $closingParenthesisOperator($targetEnvironment);
  }
}
