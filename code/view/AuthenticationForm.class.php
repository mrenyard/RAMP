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

use svelte\core\Str;

/**
 * Manages a set of Views related to authentication.
 * 
 * RESPONSIBILITIES
 * - Manages definition of Template to be used as view (fragment) for presentation.  
 * - Enable read access to associated {@link \svelte\model\business\BusinessModel} and {@link \svelte\model\document\DocumentModel}
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@link \svelte\model\business\BusinessModel}.
 * 
 * COLLABORATORS
 * - Template used to define view to render (.tpl.php)
 *   - (Svelte\code|local\svelte)\view\document\template\(text|html|pdf)\[...].tpl.php
 * - {@link \svelte\model\business\BusinessModel}
 */
class AuthenticationForm extends ChildView
{
  private $formTemplate;
  
  /**
   * Constructs Templated Document View.
   * Uses $templateName and $templateType to locate template file (.tpl.php) as view definition on render().
   */
  public function __construct()
  {
    $parentView = new document\Templated(
      RootView::getInstance(),
      Str::set('body')
    );
    // ...
    $parentView->title = Str::set('Login ' . \svelte\SETTING::$SVELTE_DOMAIN);
    //$parentView->summary = Str::set('[summary]');
    $parentView->style = Str::set('dialogue-only');
    parent::__construct($parentView);
  }

  /**
   * Render relevant output.
   * Combining data (@link \svelte\model\business\BusinessModel) and (@link \svelte\model\document\DocumentModel)
   * with defined presentation as defined in referenced template file (.tpl.php).
   */
  public function render()
  {
    $view = new document\Templated($this,Str::set('authentication-form'));
    $this->children;
  }
}
