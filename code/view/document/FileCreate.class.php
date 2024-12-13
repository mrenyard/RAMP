<?php
/**
 * RAMP - Rapid web application development using best practice.
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
use ramp\view\View;

/**
 * Creates or replaces a file using Templated view
 * includes composite DocumentModel and path (location)
 * with template (file name) used as view definition on render() and writes file.
 * 
 * RESPONSIBILITIES
 * - Write or replaces a file at the provided location.  
 * - Manages definition of Template to be used as view (fragment) for presentation.  
 * - Enable read access to associated {@see \ramp\model\business\BusinessModel} and {@see \ramp\model\document\DocumentModel}
 * - Provide Decorator pattern implementation
 *  - enabling Ordered and Hierarchical structures that interlace with provided {@see \ramp\model\business\BusinessModel}.
 * 
 * COLLABORATORS
 * - Template used to define view to render and send (.tpl.php)
 *   - (RAMP\code|local\ramp)\view\document\template\(text|html|pdf)\[...].tpl.php
 * - {@see \ramp\view\View}
 * - {@see \ramp\model\business\BusinessModel}
 * - {@see \ramp\model\document\DocumentModel}
 */
final class FileCreate extends Templated
{
  private Str $fullFileName;

  /**
   * Constructs FileCreat templated document View.
   * Uses $templateName and $templateType to locate template file (.tpl.php) as view definition on render().
   * 
   * @param View $parent Parent of this child
   * @param \ramp\core\Str $templateName Name of template (file) used as view definition on render().
   * @param \ramp\core\Str $templateType Type of template (text|html|pdf) used as part of path to definite view to render().
   * @param \ramp\core\Str $fullFileName Full file name including extension (type) to be writen or replaced.
   * @throws \InvalidArgumentException When provided arguments do NOT translate to a valid file path.
   */
  public function __construct(View $parent, Str $templateName, Str $templateType = NULL, Str $fullFileName)
  {
    $templateType = ($templateType == NULL) ? Str::set('text') : $templateType;
    $this->fullFileName = $fullFileName;
    parent::__construct($parent, $templateName, $templateType, $fullFileName);
  }

  /**
   * Render and write to file the relevant output.
   * Combining data {@see \ramp\model\business\BusinessModel} and {@see \ramp\model\document\DocumentModel}
   * with defined presentation as defined in referenced template file (.tpl.php).
   */
  #[\Override]
  final public function render() : void
  {
    // catch the rendered output so we can wrire it to file.
    ob_start();
    include $this->template;
    $content = ob_get_contents();
    ob_end_clean();

    $file = fopen($this->fullFileName, 'w');
    fwrite($file, $content);
    fclose($file);
  }
}
