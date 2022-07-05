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

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\core\BadPropertyCallException;
use ramp\model\Model;

/**
 * Definition of presentation format and type to be associated with a given Model.
 * 
 * RESPONSIBILITIES
 * - Defines API for render() method, where a single view (fragment) is defined for presentation.  
 * - Enable read access to associated {@link \ramp\model\Model}.
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@link \ramp\model\Model}.
 * 
 * COLLABORATORS
 * - {@link \ramp\view\View}
 * - {@link \ramp\model\Model}
 */
abstract class View extends RAMPObject
{
  private $viewCollection;
  private $model;

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
   * Called within Template file (.tpl.php), where {@link \ramp\view\Templated} is used.
   * ```php
   *  <p>Some text about <?=$this->aProperty; ?>, or something</p>"
   * ```
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
   * Render children, child view collection of this.
   * 
   * **DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!**
   * 
   * **Call:** `$this->children;`
   * 
   * Called within Render() method
   * ```php
   *   public function render()
   *   {
   *      $this->children;
   *   }
   * ```
   * 
   * Called within Template file (.tpl.php), where {@link \ramp\view\Templated} is used.
   * ```php
   *  <section>
   *    <header>
   *      <h1><?=$this->heading; ?></h1>
   *      <p><?=$this->summary; ?></p>
   *    </header>
   *<?=$this->children; ?>
   *  </section>
   * ```
   */
  final public function get_children() : void
  {
    if (!isset($this->viewCollection)) { return; }
    foreach ($this->viewCollection as $view) {
      $view->render();
    }
  }

  /**
   * Returns whether this has a model set against it.
   * **DO NOT CALL DIRECTLY, USE this->isModified;**
   * @return bool Value of hasModel
   */
  protected function get_hasModel()
  {
    return isset($this->model);
  }

  /**
   * Set associated Model.
   * Model can be a complex hierarchical ordered tree or a simple one level object,
   * either way it will be interlaced appropriately with *this* View up to the same
   * depth of structure unless specified cascade FALSE.
   * @param \ramp\model\Model $model Model containing data used in View
   * @param bool $cascade Set model for child views.
   * @throws \BadMethodCallException Model already set violation.
   */
  public function setModel(Model $model, bool $cascade = TRUE)
  {
    if (isset($this->model)) { throw new \BadMethodCallException('model already set violation'); }
    $this->model = $model;

    if (!$cascade) { return; }

    if ($model instanceof \Traversable) {
      if (!($model instanceof \Countable)) {
        throw new \LogicException('All Traversable Model(s) MUST also implement Countable');
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
   * Add a child View
   * @param View $view Child View to be sequentially added to this. 
   */
  final public function add(View $view)
  {
    if (!isset($this->viewCollection)) {
      $this->viewCollection = new Collection(Str::set('ramp\view\View'), TRUE);
    }
    // todo:Matt Renyard: compatibleDescendantCheck()
    $this->viewCollection->add($view);
  }

  /**
   * Render relevant output.
   * Combining data (@link \ramp\model\Model) with defined presentation ({@link View}).
   */
  abstract public function render();

  /**
   * Defines amendments post copy, cloning.
   * POSTCONDITIONS
   *  - unset associated {@link \ramp\model\Model}
   *  - copy child views
   */
  public function __clone()
  {
    $this->model = null;
    if (isset($this->viewCollection)) { $this->viewCollection = clone $this->viewCollection; }
  }
}
