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
use ramp\core\Collection;
use ramp\condition\SQLEnvironment;

/**
 * Collection of verified filters for filtering collections of \ramp\model\business\Records.
 * - restricted and evaluated by the constraints of your business model
 *  - as defined within (RAMP_BUSINESS_MODEL_NAMESPACE)
 *
 * RESPONSIBILITIES
 * - Ensure components are restricted and evaluated by the constraints of local business model
 *    defined within RAMP_BUSINESS_MODEL_NAMESPACE.
 *
 * COLLABORATORS
 * - Collection of {@see \ramp\condition\FilterCondition}s
 * - {@see \ramp\model\business\RecordCollection}
 * - {@see \ramp\model\business\Record}
 */
final class Filter extends Collection
{
  private $subOrGroups;

  /**
   * Constructs an empty collection for \ramp\condition\FilterCondition.
   * A collection of verified filters for filtering collections of {@see \ramp\model\business\Record}s
   * - restricted and evaluated by the constraints of your business model
   *  - as defined within (RAMP_BUSINESS_MODEL_NAMESPACE)
   */
  public function __construct()
  {
    parent::__construct(Str::set('ramp\condition\FilterCondition'));
  }

  /**
   * Factory method that validates array of filters (i.e. $_GET) as new Filter object.
   * @param \ramp\core\Str $recordName Name of RecordCollection to apply filter/s
   * @param array $filters Simple array containing filterProperty filterValue pairs for processing
   * against RecordCollection
   * @throws \LengthException when $filters is empty
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and limits
   * as defined by your locally defined business model (RAMP_BUESINESS_MODEL_NAMESPACE)
   */
  public static function build(Str $recordName, array $filters) : Filter
  {
    if (count($filters) == 0 ) {
      throw new \LengthException('2nd argument $filters, MUST NOT be empty');
    }
    $filter = new Filter();
    foreach ($filters as $name => $value) {
      $value = ($value === NULL)? '' : $value;
      $value = str_replace(['+','%20'], ' ', $value);
      $operator = NULL;
      $a = explode('|', $name);
      if (count($a) > 1) {
        $name = $a[0];
        switch ($a[1]) {
          case 'not':
            $operator = Operator::NOT_EQUAL_TO();
            break;
          case 'lt':
            $operator = Operator::LESS_THAN();
            break;
          case 'gt':
            $operator = Operator::GREATER_THAN();
            break;
        }
      }
      $record = Str::camelCase($recordName);
      $property = Str::camelCase(Str::set($name), TRUE);
      if ($value !== NULL) { $values = explode('|', $value); }
      if (($operator === NULL) && (count($values) > 1)) {
        if (!isset($filter->subOrGroups)) {
          $filter->subOrGroups = new Collection(Str::set($filter));
        }
        $subFilter = new Filter();
        $filter->subOrGroups->add($subFilter);
        foreach ($values as $comparable) {
          $subFilter->add(new FilterCondition($record, $property, $comparable, NULL));
        }
      } else {
        foreach ($values as $comparable) {
          $filter->add(
            new FilterCondition(
              // $record, $property, $comparable, (($operator !== NULL) ? $operator : NULL)
              $record, $property, $comparable, $operator
            )
          );
        }
      }
    }
    return $filter;
  }

  /**
   * Returns representation of this filter based on target environment.
   * @param \ramp\condition\iEnvironment $targetEnvironment Environment to target, default SQL.
   * @return string representation of *this* based on provided target environment
   */
  public function __invoke(iEnvironment $targetEnvironment = NULL) : string
  {
    $andOperator = Operator::AND();
    $environmentTargettedAndOperator  = ($targetEnvironment !== NULL) ?
      $andOperator($targetEnvironment) : $andOperator(SQLEnvironment::getInstance());

    $orOperator = Operator::OR();
    $environmentTargettedOrOperator  = ($targetEnvironment !== NULL) ?
      $orOperator($targetEnvironment) : $orOperator(SQLEnvironment::getInstance());

    $openingGroupingParenthesisOperator = Operator::OPENING_GROUPING_PARENTHESIS();
    $environmentTargettedOpeningGroupingParenthesisOperator  = ($targetEnvironment !== NULL) ?
      $openingGroupingParenthesisOperator($targetEnvironment) :
        $openingGroupingParenthesisOperator(SQLEnvironment::getInstance());

    $closingGroupingParenthesisOperator = Operator::CLOSING_GROUPING_PARENTHESIS();
    $environmentTargettedClosingGroupingParenthesisOperator  = ($targetEnvironment !== NULL) ?
      $closingGroupingParenthesisOperator($targetEnvironment) :
        $closingGroupingParenthesisOperator(SQLEnvironment::getInstance());

    $i=0;
    $output = '';
    $iterator = $this->getIterator();
    $iterator->rewind();
    if ($iterator->valid()) {
      $currentFilter = $iterator->current();
      $output .= $currentFilter($targetEnvironment);
      $iterator->next();
      while ($iterator->valid()) {
        $currentFilter = $iterator->current();
        $output .= $environmentTargettedAndOperator . $currentFilter($targetEnvironment);
        $iterator->next();
      }
    }
    if (isset($this->subOrGroups)) {
      foreach($this->subOrGroups as $subGroup) {
        $output .= $environmentTargettedAndOperator .
          $environmentTargettedOpeningGroupingParenthesisOperator;
        $subIterator = $subGroup->getIterator();
        $subIterator->rewind();
        while ($subIterator->valid()) {
          $currentSubFilter = $subIterator->current();
          $output .= $currentSubFilter($targetEnvironment) . $environmentTargettedOrOperator;
          $subIterator->next();
        }
        $output = Str::set($output)
          ->trimEnd(Str::set($environmentTargettedOrOperator))
            ->append(Str::set($environmentTargettedClosingGroupingParenthesisOperator));
      }
    }
    return $output;
  }
}
