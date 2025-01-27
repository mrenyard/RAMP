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
namespace ramp\view;

use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\BadPropertyCallException;
use ramp\model\business\BusinessModel;
use ramp\view\ChildView;

/**
 * Definition of presentation format and type to be associated with a given Model.
 * 
 * RESPONSIBILITIES
 * - Inherits API for render() method, where a single view (fragment) is defined for presentation.  
 * - Enable read access to associated {@see \ramp\model\Model}.
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@see \ramp\model\Model}.
 * 
 * COLLABORATORS
 * - {@see \ramp\view\View}
 * - {@see \ramp\model\Model}
 * 
 * @property-read bool $hasModel Returns whether *this* has a model set against it.
 */
abstract class ComplexView extends ChildView
{
  private ?BusinessModel $model = NULL;

  /**
   * Provide read access to associated Model's properties.
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   *
   * **Passes:** `$this->aProperty;` **to:** `$this->model->get_aProperty();`
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
   * @return array|string|int|float|bool|NULL $value The value of requested property
   * @throws \ramp\core\BadPropertyCallException Undefined or inaccessible property called
   */
  #[\Override]
   public function __get($propertyName)
  {
    if ($propertyName == 'isRequired' && $this->model !== NULL && $this->model instanceof \ramp\model\business\field\Field) {
      return ($this->model->isRequired);
    }
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $exception) {
      if ($this->model === NULL) { throw $exception; }
      return $this->model->$propertyName;
    }
  }

  /**
   * @ignore
   */
  protected function get_hasModel() : bool
  {
    return ($this->model !== NULL);
  }

  /**
   * Set associated Model.
   * Model can be a complex hierarchical ordered tree or a simple one level object,
   * either way it will be interlaced appropriately with *this* View up to the same
   * depth of structure unless specified cascade FALSE.
   * @param \ramp\model\business\BusinessModel $model BusinessModel containing data used in View
   * @param bool $cascade Set model for child views.
   * @throws \BadMethodCallException Model already set violation.
   */
  public function setModel(BusinessModel $model, bool $cascade = TRUE)
  {
    if ($this->model !== NULL) { throw new \BadMethodCallException('model already set violation'); }
    $this->model = $model;
    if (!$cascade) { return; }
    // TODO:mrenyard: Fix tests and reinstate code.
    // $web = WebRoot::getInstance();
    // if ($web->pageType != PageType::DATA) { $web->type = PageType::DATA; }

    if ($model instanceof \Traversable) {
      if (!($model instanceof \Countable)) { // @codeCoverageIgnoreStart
        throw new \LogicException('All Traversable Model(s) MUST also implement Countable');
        // @codeCoverageIgnoreEnd
      }
      if (!isset($this->viewCollection)) { return; }

      $viewTemplate = clone $this->viewCollection;
      $viewTemplateIterator = $viewTemplate->getIterator();
      $i = $model->count();

      // increase number of subView(s) sequentially to match number of subModel(s).
      $viewTemplateIterator->rewind();
      while ($this->viewCollection->count() < $i) {
        $view = clone $viewTemplateIterator->current();
        $this->add($view);
        $viewTemplateIterator->next();
        if (!$viewTemplateIterator->valid()) { $viewTemplateIterator->rewind(); }
      }

      // set position subModel to same position subView
      $viewIterator = $this->viewCollection->getIterator();
      $viewIterator->rewind();
      $i=0;
      foreach ($model as $subModel) {
        if (!$viewIterator->valid()) { throw new \LengthException('SHOULD NEVER REACH HERE!'); }
        $view = $viewIterator->current();
        $view->setModel($subModel);
        $viewIterator->next();
      } // END foreach
    } // END if
  }

  /**
   * Defines amendments post copy, cloning.
   * POSTCONDITIONS
   *  - unset associated {@see \ramp\model\Model}
   *  - copy child views
   */
  #[\Override]
  public function __clone()
  {
    $this->model = NULL;
    parent::__clone();
  }
}
