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

/**
 * Definition of presentation format and type to be associated with a given Model.
 * 
 * RESPONSIBILITIES
 * - Defines API for render() method, where a single view (fragment) is defined for presentation.  
 * - Enable read access to associated {@see \ramp\model\Model}.
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@see \ramp\model\Model}.
 * 
 * COLLABORATORS
 * - {@see \ramp\view\View}
 * - {@see \ramp\model\Model}
 */
abstract class View extends RAMPObject
{
  protected $viewCollection;

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
   * Called within Template file (.tpl.php), where {@see \ramp\view\Templated} is used.
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
  protected function get_children() : void
  {
    if (!isset($this->viewCollection)) { return; }
    foreach ($this->viewCollection as $view) {
      $view->render();
    }
  }

  /**
   * Add a child View
   * @param View $view Child View to be sequentially added to this. 
   */
  public function add(View $view)
  {
    if (!isset($this->viewCollection)) {
      $this->viewCollection = new Collection(Str::set('ramp\view\View'), TRUE);
    }
    // TODO:mrenyard: compatibleDescendantCheck()
    $this->viewCollection->add($view);
  }

  /**
   * Render relevant output.
   * Combining data (@see \ramp\model\Model) with defined presentation ({@see View}).
   */
  abstract public function render();

  /**
   * Defines amendments post copy, cloning.
   * POSTCONDITIONS
   *  - copy child views
   */
  public function __clone()
  {
    if (isset($this->viewCollection)) { $this->viewCollection = clone $this->viewCollection; }
  }
}
