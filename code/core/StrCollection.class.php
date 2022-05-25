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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\core;

/**
 * Collection for Strongly typed Str(ings).
 *
 * RESPONSIBILITIES
 * - Hold reference to a collection of {@link \ramp\core\Str} objects.
 * - Provide an API for set of simple Str collection based functions.
 *
 * COLLABORATORS
 * - {@link \ramp\core\Str}
 */
final class StrCollection extends Collection
{
  /**
   * Default constructor for collection of \ramp\core\Str.
   * - Sets composite type for this collection as \ramp\core\Str
   */
  final public function __construct() { parent::__construct(Str::set('ramp\core\Str')); }

  /**
   * Instantiate a new StrCollection - Can take a (comma seperated (args)) list of string literals.
   * @param string[] ...$values Zero or more string literal values to form collection. 
   * @return \ramp\core\StrCollection Relevant StrCollection object
   */
  public static function set(string ...$values) : StrCollection
  {
    // NOTE: ...$value === func_get_args()
    $o = new StrCollection();
    foreach ($values as $value) {
      $o->add(Str::set($value));
    }
    return $o;
  }

  /**
   * Returns Str representation of this collection, ordered, with proveded 'glue' between each item.
   * @param \ramp\core\Str $glue Glue to be used to stich each item together. 
   */
  public function implode(Str $glue = NULL) : Str
  {
    $value = Str::_EMPTY();
    $glue = (isset($glue))? $glue : $value;
    foreach ($this as $part)
    {
      $value = $value->append($part)->append($glue);
    }
    return $value->trimEnd($glue);
  }
}