<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
namespace ramp\view;

use ramp\core\RAMPObject;
use ramp\core\BadPropertyCallException;
use ramp\model\Model;

/**
 * Abstract View.
 *
 * RESPONSIBILITIES
 * - Define generalized methods for setting and holding a Model referance and rendering output.
 *
 * COLLABORATORS
 * - Collection of {@link \ramp\model\Model}s
 */
abstract class View extends RAMPObject
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
   * @throws \ramp\core\BadPropertyCallException Undefined or inaccessible property called
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
   * @param \ramp\model\Model $model Model to be used when rendering view.
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
