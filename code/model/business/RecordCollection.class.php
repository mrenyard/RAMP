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
namespace svelte\model\business;

use svelte\core\SvelteObject;
use svelte\core\iCollection;
use svelte\core\Str;
use svelte\model\business\BusinessModel;

/**
 * Referance and maintain a collection of \svelte\model\business\Records.
 *
 * RESPONSIBILITIES
 * - Implement methods for property access
 * - Implement methods for validity checking & reporting
 * - Provide access to this Collection
 * - Provide methods to maintain a Collection of {@link Record}s
 *
 * COLLABORATORS
 * - Collection of {@link \svelte\model\business\Record}s
 */
abstract class RecordCollection extends BusinessModel implements iCollection
{
  /**
   * {@inheritdoc}
   */
  final public function get_id() : Str
  {
    return $this->processType((string)$this, TRUE);
  }

  /**
   * {@inheritdoc}
   * @param \svelte\core\SvelteObject $object SvelteObject reference to be added (Record)
   * @throws \InvalidArgumentException When provided object NOT expected type (Record)
   */
  final public function add(SvelteObject $object)
  {
    parent::offsetSet($this->count(), $object);
  }

  /**
   * ArrayAccess method offsetSet, DO NOT USE.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object SvelteObject to be placed at provided index.
   * @throws \BadMethodCallException Array access setting is not allowed.
   */
  public function offsetSet($offset, $object)
  {
    throw new \BadMethodCallException('Array access setting is not allowed.');
  }
}
