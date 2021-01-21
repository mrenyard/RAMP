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
 */
abstract class DocumentView extends ChildView
{
  private $documentModel;

  /**
   */
  public function __construct(View $parentView)
  {
    parent::__construct($parentView);
    $this->documentModel = new DocumentModel();
  }

  /**
   */
  final public function __set($propertyName, $value)
  {
    try {
      parent::__set($propertyName, $value);
    } catch (PropertyNotSetException $e) {
      try {
        $this->documentModel->$propertyName = $value;
      } catch (PropertyNotSetException $f) { throw $e; }
    }
  }

  /**
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
   */
  final public function setModel(Model $model)
  {
    if (!($model instanceof BusinessModel)) {
      throw new \InvalidArgumentException('Expecting instanceof BusinessModel');
    }
    parent::setModel($model);
  }
}
