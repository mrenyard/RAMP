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
use ramp\view\document\DocumentView;
use ramp\view\document\Templated;

enum PageType : string
{
  case CONTENT = 'content';
  case INDEX = 'index';
  case DATA = 'data';
}

/**
 * Defined once per HTTP request.
 */
class WebRoot extends View
{
  private static $instance;
  private Templated $body;
  private PageType $pageType;
  private Templated $dialog;
  private Templated $main;
  private Templated $datalists;
  private ?Templated $modal;
    
  protected function set_type(PageType $type) { $this->pageType = $type; }
  protected function get_type() : PageType { return (isset($this->pageType)) ? $this->pageType : PageType::DATA; }
  protected function get_pageType() : string { return $this->type->value . '-page'; }
  protected function get_dialog() { $this->dialog->render(); }
  protected function get_main() { $this->main->render(); }
  protected function get_datalists() { $this->datalists->render(); }
  protected function get_isModal() : bool { return (isset($this->modal)); }
  protected function get_modalForm() : void { $this->modal->render(); }
  protected function get_modalOpen() : string { return ($this->isModal) ? 'open ' : ''; }

  private function __construct()
  {
    $this->body = new Templated(RootView::getInstance(), Str::set('body'));
    $this->dialog = new Templated(RootView::getInstance(), Str::set('dialog'));
    $this->main = new Templated(RootView::getInstance(), Str::set('page'));
    $this->datalists = new Templated(RootView::getInstance(), Str::set('empty'));
  }

  protected function get_children() : void
  {
    $this->dialog->render();
    $this->main->render();
    $this->datalists->render();
  }

  /**
   * Get instance - same instance (singleton) within same HTTP request.
   * @return WebView Singelton instance of WebRoot
   */
  public static function getInstance() : WebRoot
  {
    if (!isset(SELF::$instance)) { SELF::$instance = new WebRoot(); }
    return SELF::$instance;
  }

  public static function reset() : void
  {
    RootView::getInstance()->reset();
    SELF::$instance = NULL;
  }

  public function add(View $view) : void { $this->main->add($view); }

  public function addDatalist(OptionList $value) : void
  {

  }

  public function setModal(string $templateName) : DocumentView
  {
    $this->modal = new Templated($this->dialog, Str::set($templateName));
    return $this->modal;
  }

  public function clearModal() { $this->modal = NULL; }

  public function render() : void { $this->body->render(); }
  public function __clone() { throw new \BadMethodCallException('Cloning is not allowed'); }
}