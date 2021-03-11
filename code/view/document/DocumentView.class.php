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
namespace svelte\view\document;

use svelte\core\PropertyNotSetException;
use svelte\core\BadPropertyCallException;
use svelte\model\Model;
use svelte\model\document\DocumentModel;
use svelte\model\business\BusinessModel;
use svelte\view\View;
use svelte\view\ChildView;

/**
 * Abstract specialist document view (presentation) includes composite DocumentModel.
 * 
 * RESPONSIBILITIES
 * - Defines API for render() method, where a single view (fragment) is defined for presentation.  
 * - Enable read access to associated {@link \svelte\model\business\BusinessModel} and {@link \svelte\model\document\DocumentModel}
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@link \svelte\model\business\BusinessModel}.
 * 
 * COLLABORATORS
 * - {@link \svelte\view\View}
 * - {@link \svelte\model\business\BusinessModel}
 * - {@link \svelte\model\document\DocumentModel}
 */
abstract class DocumentView extends ChildView
{
  private $documentModel;

  /**
   * Base constructor for document based views.
   * @param View $parentView Parent of this child
   */
  public function __construct(View $parentView)
  {
    parent::__construct($parentView);
    $this->documentModel = new DocumentModel();
  }

  /**
   * Allows C# type access to properties.
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   *
   * **Passes:** `$object->aProperty = $value;` **to:** `$this->set_aProperty($value);`
   * 
   * **or** `$object->header = $vlaue;` **to** `$this->documentModel->set_header($value);`
   *
   * Implementation in concrete Object
   * ```php
   * private aProperty;
   *
   * protected function set_aProperty($value)
   * {
   *   $this->aProperty = $value;
   * }
   * ```
   *
   * Called externally (C# style)
   * ```php
   * $object->aProperty = $value; //Set value of 'aProperty'
   * ```
   *
   * @param string $propertyName Name of property (handled internally)
   * @param mixed $propertyValue The value to set on requested property (handled internally)
   * @throws \svelte\core\PropertyNotSetException Unable to set property when undefined or inaccessible
   */
  final public function __set($propertyName, $propertyValue)
  {
    try {
      parent::__set($propertyName, $propertyValue);
    } catch (PropertyNotSetException $e) {
      try {
        $this->documentModel->$propertyName = $propertyValue;
      } catch (PropertyNotSetException $f) { throw $e; }
    }
  }

  /**
   * Provide read access to associated Models' properties.
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   *
   * **Passes:** `$object->aProperty;` **to:** `$this->model->get_aProperty();`
   * 
   * **or** `$object->header;` **to:** `$this->documentModel->get_header();`
   * 
   * Called within Render() method
   * ```php
   *   public function render()
   *   {
   *      print_r($this->aProperty);
   *   }
   * ```
   * Called within Template file (.tpl.php), where {@link \svelte\view\Templated} is used.
   * ```php
   *  <p>Some text about <?=$this->aProperty; ?>, or something</p>"
   * ```
   * @param string $propertyName Name of property (handled internally)
   * @return mixed|void The value of requested property
   * @throws \svelte\core\BadPropertyCallException Undefined or inaccessible property called
   */
  public function __get($propertyName)
  {
    if ($propertyName == 'children') { return $this->get_children(); }
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $e) {
      try {
        return $this->documentModel->$propertyName;
      } catch (BadPropertyCallException $f) { throw $e; }
    }
  }

  /**
   * Set associated Model.
   * Model can be a complex hierarchical ordered tree or a simple one level object,
   * either way it will be interlaced appropriately with *this* View up to the
   * same depth of structure.
   * @param \svelte\model\Model $model Model containing data used in View
   * @throws \InvalidArgumentException Expecting instanceof BusinessModel
   */
  final public function setModel(Model $model)
  {
    if (!($model instanceof BusinessModel)) {
      throw new \InvalidArgumentException('Expecting instanceof BusinessModel');
    }
    parent::setModel($model);
  }
}
