<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\view;

use ramp\view\View;
use ramp\model\Model;

/**
 * Top level view, defined once per HTTP request
 */
final class RootView extends View
{
  private static $instance;

  private function __construct() { }

  /**
   * Get instance - same instance (singleton) within same HTTP request.
   * @return RootView Singelton instance of RootView
   */
  public static function getInstance()
  {
    if (!isset(self::$instance)) { self::$instance = new RootView(); }
    return self::$instance;
  }

  /**
   * Prevent setting of Model.
   * @throws \BadMethodCallException SHOULD NOT USE THIS METHOD
   * @param \ramp\model\Model $model Model NOT to be set
   */
  public function setModel(Model $model)
  {
    throw new \BadMethodCallException('SHOULD NOT USE THIS METHOD');
  }

  /**
   * Render relevant output from child views
   */
  public function render()
  {
    $this->children;
  }

  /**
   * Prevent cloning.
   * @throws \BadMethodCallException Cloning is not allowed!
   */
  public function __clone()
  {
    throw new \BadMethodCallException('Cloning is not allowed');
  }
}