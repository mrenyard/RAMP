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
namespace ramp\view\document;

use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\core\BadPropertyCallException;
use ramp\view\View;
use ramp\view\ComplexView;
use ramp\model\Document;

/**
 * Abstract specialist document view (presentation) includes composite DocumentModel.
 * 
 * RESPONSIBILITIES
 * - Defines API for render() method, where a single view (fragment) is defined for presentation.  
 * - Enable read access to associated {@see \ramp\model\business\BusinessModel} and {@see \ramp\model\document\DocumentModel}
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@see \ramp\model\business\BusinessModel}.
 * 
 * COLLABORATORS
 * - {@see \ramp\view\View}
 * - {@see \ramp\model\business\BusinessModel}
 * - {@see \ramp\model\document\DocumentModel}
 */
abstract class DocumentView extends ComplexView
{
  private Document $documentModel;

  /**
   * Base constructor for document based views.
   * @param View $parent Parent of this child
   */
  public function __construct(View $parent)
  {
    parent::__construct($parent);
    $this->documentModel = new Document();
  }
  
  /**
   * Returns complete attbibute and value.
   * @todo mrenyard:Moving this to Templated makes more sence?
   * @param string $propertyName Attribute Property Name 
   * @return \ramp\core\Str Attribute and value of requested property 
   * @throws \BadMethodCallException Unable to set property when undefined or inaccessible
   */
  public function attribute($propertyName) : ?Str
  {
    if ($propertyName == 'extendedSummary' || $propertyName == 'extendedContent' || $propertyName == 'footnote') {
      throw new \BadMethodCallException($propertyName . ' is NOT avalible as an HTML attribute');
    }
    if ($this->hasModel) {
      if ($propertyName == 'required') { return ($this->isRequired) ? Str::set(' required="required"') : NULL; }
      if ($propertyName == 'value' && ($this->hasErrors || $this->inputType == 'password')) { return Str::set(' value=""'); }
    }
    try {
      $value = $this->__get($propertyName);
      if ($value == '[PLACEHOLDER]' && ((string)$this->inputType != 'text' && (string)$this->inputType != 'search' && (string)$this->inputType != 'url' &&
      (string)$this->inputType != 'tel' && (string)$this->inputType != 'email' && (string)$this->inputType != 'password')) {
        return NULL;
      }
    } catch (\ramp\core\BadPropertyCallException $exception) {
      throw new \BadMethodCallException($exception);
    }
    if ($value !== NULL && (!($value instanceof \ramp\core\Str))) { $value = Str::set((string)$value); }
    return ($value) ? $value->prepend(Str::set(' ' . $propertyName . '="'))->append(Str::set('"')) : NULL;
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
   * @throws \ramp\core\PropertyNotSetException Unable to set property when undefined or inaccessible
   */
  final public function __set($propertyName, $propertyValue) : void
  {
    try {
      parent::__set($propertyName, $propertyValue);
    } catch (PropertyNotSetException $exception) {
      try {
        $this->documentModel->$propertyName = $propertyValue;
      } catch (PropertyNotSetException $f) { throw $exception; }
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
   * Called within Template file (.tpl.php), where {@see \ramp\view\Templated} is used.
   * ```php
   *  <p>Some text about <?=$this->aProperty; ?>, or something</p>"
   * ```
   * @param string $propertyName Name of property (handled internally)
   * @return mixed The value of requested property
   * @throws \ramp\core\BadPropertyCallException Undefined or inaccessible property called
   */
  final public function __get($propertyName)
  {
    if ($propertyName == 'id' && $this->hasModel) {
      return parent::__get($propertyName);
    }
    if ($propertyName == 'class') {
      $value = Str::_EMPTY();
      if ($this->hasModel) { $value = $value->prepend(parent::__get('type')); } 
      if ($value !== Str::_EMPTY() && $this->documentModel->style) { $value = $value->append(Str::SPACE()); }
      if ($this->documentModel->style) { $value = $value->append($this->documentModel->style); }
      return ($value === Str::_EMPTY()) ? NULL : $value;
    }
    try {
      if ($value = $this->documentModel->$propertyName) { return $value; }
    } catch (BadPropertyCallException $exception) { }
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $f) {
      if (isset($exception)) { throw $f; }
      if ($propertyName == 'extendedSummary' || $propertyName == 'extendedContent' || $propertyName == 'footnote') { return NULL; }
      return Str::set('[' . $propertyName . ']')->uppercase;
    }
  }
}
