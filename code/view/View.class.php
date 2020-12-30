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
namespace svelte\view;

use svelte\core\SvelteObject;
//use svelte\core\Str;
//use svelte\core\Collection;
//use svelte\core\BadPropertyCallException;
//use svelte\model\Model;

/**
 */
abstract class View extends SvelteObject
{
  private $viewCollection;
  private $model;

  /**
   * Gives provided \svelte\view\{@link Render} access to *this* {@link render()}ing
   * \svelte\model\{@link Model}.
   *
   * PRECONDITIONS
   * - ?requires *this* to be currently {@link render()}ing a \svelte\view{@link Render};
   * - ?requires *this* has valid \svelte\model\{@link Model} passed with {@link render()}
   *
   * INVARIANTS
   * - ensures *this* state is unchanged
   *
   * DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!
   *
   * NOTE: ONLY ACCESSIBLE TO ASSOCIATED \svlete\view\{@link Render} FROM WIHIN {@link render()}
   * @param Str name of property (handled internally)
   * @return mixed|void mixed|void - Relevant property value from *self* |
   * \svelte\model\{@link Model}
   * @throws BadPropertyCallException - Property does NOT exist
   *
  public function __get($propertyName)
  {
    try {
      return parent::__get($propertyName);
    } catch (BadPropertyCallException $e) {
      if (!isset($this->model)) { throw $e; }
      return $this->model->$propertyName;
    }
  }*/

  /**
   *
  final protected function get_children()
  {
    if (!isset($this->viewCollection)) { return; }
    foreach ($this->viewCollection as $view) {
      $view->render();
    }
  }*/

  /**
   *
  public function setModel(Model $model)
  {
    //if ($this->modelIsSet()) { throw new \Exception('model already set violation'); }

    $this->model = $model;

    /*if ($model instanceof \Traversable) {
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
      $viewIterator->rewind(); $i=0;
      foreach ($model as $subModel) {
        if (!$viewIterator->valid()) { throw new \LengthException('SHOULD NEVER REACH HERE!'); }
        $view = $viewIterator->current();
        $view->setModel($subModel);

        $viewIterator->next();
      } // END foreach
    } // END if *
  }*/

  /**
   *
  protected function modelIsSet()
  {
    return (isset($this->model));
  }*/

  /**
   *
  final public function add(View $view)
  {
    if (!isset($this->viewCollection)) {
      $this->viewCollection = new Collection(Str::set('svelte\view\View'), TRUE);
    }
    // todo:Matt Renyard: compatibleDescendantCheck()
    $this->viewCollection->add($view);
  }*/

  /**
   * Render provided {@link DocumentModel} within *this*.
   *
   * PRECONDITIONS
   * - requires *this* to be in a valid state
   * - ?requires a valid {@link DocumentModel} to be provided using {@link setModel()}
   *
   * POSTCONDITIONS
   * - ?ensures the rendering of \svelte\model\{@link Model} in\svelte\view\{@link Render}
   * - ?ensures {@link $model} reset to null upon successful rendering
   *
   * INVARIANTS
   * - ensures {@link $view} able to access {@link __get()} during {@link render()}ing
   * - **all** ensures that *this* state is unchanged post render()
   */
  abstract public function render();

  /**
   */
  public function __clone()
  {
    $this->model = null;
    if (isset($this->viewCollection)) { $this->viewCollection = clone $this->viewCollection; }
  }
}
