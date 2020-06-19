<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
namespace svelte\view;

use svelte\core\SvelteObject;
use svelte\core\BadPropertyCallException;
use svelte\model\Model;

/**
 * Abstract View.
 *
 * RESPONSIBILITIES
 * - Define generalized methods for setting and holding a Model referance and rendering output.
 *
 * COLLABORATORS
 * - Collection of {@link \svelte\model\Model}s
 */
abstract class View extends SvelteObject
{
  private $model;

  /**
   * Allows access to properties including properties of held Model
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**.
   *
   * **Passes:** `$object->aProperty;`
   * **to:** `$object->get_aProperty();`
   *
   * Implementation in concrete Object
   * ```php
   * private aProperty;
   *
   * protected function get_aProperty()
   * {
   *   return $this->aProperty;
   * }
   * ```
   *
   * Called externally (C# style)
   * ```php
   * $object->aProperty; //Get value 'aProperty'
   * ```
   *
   * @param string $propertyName Name of property (handled internally)
   * @return mixed|void The value of requested property
   * @throws \svelte\core\BadPropertyCallException Undefined or inaccessible property called
   */
  public function __get($propertyName)
  {
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $e) {
      if (!isset($this->model)) { throw $e; }
      return $this->model->$propertyName;
    }
  }

  /**
   * Set model.
   * @param \svelte\model\Model $model Model to be used when rendering view.
   */
  public function setModel(Model $model)
  {
    $this->model = $model;
  }

  /**
   * Render desired output.
   */
  abstract protected function render();
}
