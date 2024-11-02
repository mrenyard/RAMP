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
namespace ramp\core;

/**
 * Collection for iOption.
 *
 * RESPONSIBILITIES
 * - Holds references to collection of varifiable {@see iOption}s.
 * - Implements the functionality of {@see iCollection}.
 *
 * COLLABORATORS
 * - {@see \ramp\core\Collection}
 * - {@see \ramp\core\iOption}
 */
class OptionList extends Collection
{
  /**
   * Constructor for new instance of OptionList.
   * POSTCONDITIONS
   * - New collection containing provided {@see \ramp\core\iOption}s from $optionCastableCollection or Empty.
   * @param \ramp\core\iCollection $iOptionCastableCollection Collection of iOptions to be stored in *this*.
   * @param \ramp\core\Str $iOptionCastableType Full class name for Type of objects to be stored in this collection.
   * @throws \InvalidArgumentException When any composite of provided collection is NOT castable to provided $iOptionCastableType or iOption.
   */
  public function __construct(iCollection $iOptionCastableCollection = NULL, Str $iOptionCastableType = NULL)
  {
    $compositeType = ($iOptionCastableType == NULL)? Str::set('ramp\core\iOption') : $iOptionCastableType;
    parent::__construct($compositeType);
    if ($iOptionCastableCollection !== NULL) {
      foreach ($iOptionCastableCollection as $option) {
        parent::add($option);
      }
    }
  }
}
