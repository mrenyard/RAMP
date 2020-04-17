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
 * @version 0.0.9;
 */
namespace svelte\core;

/**
 * Collection for iOption.
 *
 * RESPONSIBILITIES
 *
 * - Holds references to collection of varifiable {@link iOption}s.
 * - Implements the functionality of {@link iCollection}.
 *
 * COLLABORATORS
 *
 * - {@link \svelte\core\Collection}
 * - {@link \svelte\core\iOption}
 */
class OptionList extends Collection
{
  /**
   * Constructor for new instance of OptionList.
   * POSTCONDITIONS
   * - New collection containing provided {@link \svelte\core\iOption}s from $optionCastableCollection or Empty.
   * @param \svelte\core\iCollection $optionCastableCollection Collection of iOptions to be stored in *this*.
   * @throws \InvalidArgumentException When any composite of provided collection is NOT castable to iOption.
   */
  public function __construct(iCollection $optionCastableCollection = null) {
    parent::__construct(Str::set('svelte\core\iOption'));
    if (isset($optionCastableCollection)) {
      foreach ($optionCastableCollection as $option) {
        parent::add($option);
      }
    }
  }
}
