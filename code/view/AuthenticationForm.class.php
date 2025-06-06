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

/**
 * Manages a set of Views related to authentication.
 * 
 * RESPONSIBILITIES
 * - Manages definition of Template to be used as view (fragment) for presentation.  
 * - Enable read access to associated {@link \ramp\model\business\BusinessModel} and {@link \ramp\model\document\DocumentModel}
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@link \ramp\model\business\BusinessModel}.
 * 
 * COLLABORATORS
 * - Template used to define view to render (.tpl.php)
 *   - (RAMP\code|local\ramp)\view\document\template\(text|html|pdf)\[...].tpl.php
 * - {@link \ramp\model\business\BusinessModel}
 */
class AuthenticationForm extends document\Templated
{  
  /**
   * Constructs Templated Document View.
   * Uses $templateName and $templateType to locate template file (.tpl.php) as view definition on render().
   */
  public function __construct($errorMessage)
  {
    $body = new document\Templated(
      RootView::getInstance(),
      Str::set('body')
    );
    $body->title = Str::set('Login ' . \ramp\SETTING::$RAMP_DOMAIN);
    parent::__construct($body, Str::set('authentication-form'));
    $this->title = Str::set($errorMessage);
    $this->style = Str::set('dialogue-only');
  }
}
