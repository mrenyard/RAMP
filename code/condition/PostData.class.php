<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package ramp
 * @version 0.0.9;
 */
namespace ramp\condition;

use ramp\core\Str;
use ramp\core\Collection;

/**
 * Collection of verified 'property value pairs' for submission into system.
 * - restricted and evaluated by the constraints of your business model
 *  - as defined within (RAMPE_BUSINESS_MODEL_NAMESPACE)
 *
 * COLLABORATORS
 * - {@link \ramp\core\Str}
 * - Collection of {@link \ramp\condition\InputDataCondition}s
 */
final class PostData extends Collection
{
  /**
   * Constructs an empty collection for \ramp\condition\InputDataCondition.
   * A collection of verified property value pairs restricted and evaluated by the constraints of
   * your business model as defined within RAMPE_BUSINESS_MODEL_NAMESPACE.
   */
  public function __construct()
  {
    parent::__construct(Str::set('ramp\condition\InputDataCondition'));
  }

  /**
   * Factory method that validates array of name value pairs (i.e. $_POST) as new PostData object.
   * @param array $postdata Simple array containing name value pairs for processing into PostData Object
   * @return PostData Collection of verified 'property value pairs' based on provided array.
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and limits
   * as defined by your locally defined business model within RAMPE_BUSINESS_MODEL_NAMESPACE.
   */
  static public function build(array $postdata) : PostData
  {
    $postData = new PostData();
    foreach ($postdata as $name => $value)
    {
      $URN = explode(':', $name);
      if (count($URN) !== 3) {
        throw new \DomainException(
          'Invalid format for name in $postdata, SHOULD be URN in the form "record:key:property"'
        );
      }
      $record = Str::camelCase(Str::set($URN[0]));
      $primaryKey = Str::set($URN[1]);
      $property = Str::camelCase(Str::set($URN[2]), TRUE);
      // InputDataCondition also throws \DomainException - which we allow to bubble up.
      $postData->add(new InputDataCondition($record, $primaryKey, $property, $value));
    } // END foreach
    return $postData;
  }
}
