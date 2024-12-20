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

use ramp\SETTING;
use ramp\core\Str;
use ramp\core\PropertyNotSetException;
use ramp\view\View;
use ramp\view\document\DocumentView;

/**
 * Templated view (presentation) includes composite DocumentModel and path (location)
 * with template (file name) used as view definition on render().
 * 
 * RESPONSIBILITIES
 * - Manages definition of Template to be used as view (fragment) for presentation.  
 * - Enable read access to associated {@see \ramp\model\business\BusinessModel} and {@see \ramp\model\document\DocumentModel}
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@see \ramp\model\business\BusinessModel}.
 * 
 * COLLABORATORS
 * - Template used to define view to render (.tpl.php)
 *   - (RAMP\code|local\ramp)\view\document\template\(text|html|pdf)\[...].tpl.php
 * - {@see \ramp\view\View}
 * - {@see \ramp\model\business\BusinessModel}
 * - {@see \ramp\model\document\DocumentModel}
 * 
 * @property-write \ramp\core\Str $templateType Set/Change type of template (text|html|pdf) used as part of path to definite view to render().
 * @property-read \ramp\core\Str $template Set/Change type of template (text|html|pdf) used as part of path to definite view to render().
 * @property-read bool $hasModel Returns full template path of template to render().
 */
class Templated extends DocumentView
{
  private Str $templateName;
  private Str $templateType;
  private string $templatePath;

  /**
   * Constructs Templated Document View.
   * Uses $templateName and $templateType to locate template file (.tpl.php) as view definition on render().
   * 
   * @param View $parent Parent of this child
   * @param \ramp\core\Str $templateName Name of template (file) used as view definition on render().
   * @param \ramp\core\Str $templateType Type of template (text|html|pdf) used as part of path to definite view to render().
   * @throws \InvalidArgumentException When provided arguments do NOT translate to a valid file path.
   */
  public function __construct(View $parent, Str $templateName, Str $templateType = NULL)
  {
    parent::__construct($parent);
    $this->templateName = $templateName;
    $this->templateType = ($templateType == NULL) ? Str::set('html') : $templateType;
    try {
      $this->updateTemplatePath();
    } catch (\BadMethodCallException $exception) {
      throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
    }
  }

  private function updateTemplatePath() : void
  {
    $template  = 'ramp/view/document/template';
    $template .= '/' . $this->templateType . '/' . $this->templateName . '.tpl.php';
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
      $path = trim($path, "'"); $fullPath = $path . '/' . $template;
      if (file_exists($fullPath)) {
        $this->templatePath = $fullPath;
        return;
      }
    }
    throw new \BadMethodCallException(
      'Provided $templateName ('.$this->templateName.') of $templateType ('.$this->templateType.') is non existant!'
    );
  }

  /**
   * @ignore
   */
  protected function set_templateType(Str $value) : void
  {
    $oldValue = $this->templateType; 
    $this->templateType = $value;
    try {
      $this->updateTemplatePath();
    } catch (\BadMethodCallException $exception) {
      $this->templateType = $oldValue;
      throw new PropertyNotSetException($exception->getMessage());
    }
  }

  /**
   * @ignore
   */
  protected function get_template() : string
  {
    return $this->templatePath;
  }

  /**
   * Render relevant output.
   * Combining data {@see \ramp\model\business\BusinessModel} and {@see \ramp\model\document\DocumentModel}
   * with defined presentation as defined in referenced template file (.tpl.php).
   */
  #[\Override]
  public function render() : void
  {
    if (SETTING::$DEV_MODE && ((string)$this->templateType == 'html') && (!strrpos($this->template, 'body.tpl.php'))) {
      include 'template/'. $this->templateType .'/info.tpl.php';
    }
    include $this->template;
  }
}
