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
 * Email like Templated view includes composite DocumentModel and path (location)
 * with template (file name) used as view definition on render() and send.
 * 
 * RESPONSIBILITIES
 * - Sends an email to provided recipient.  
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
final class Email extends Templated
{
  /**
   * Render and send via email relevant output.
   * Combining data {@see \ramp\model\business\BusinessModel} and {@see \ramp\model\document\DocumentModel}
   * with defined presentation as defined in referenced template file (.tpl.php).
   */
  #[\Override]
  final public function render() : void
  {
    // catch the rendered output so we can email it
    ob_start();
    include $this->template;
    $message = ob_get_contents();
    ob_end_clean();

    $from = 'From: ' . SETTING::$EMAIL_FROM_NAME . ' <' . SETTING::$EMAIL_FROM_ADDRESS . '>' . "\r\n";
    $replay = 'Reply-To: ' . SETTING::$EMAIL_REPLY_ADDRESS . "\r\n";
    $params = 'MIME-Version: 1.0'."\r\n";
  //$params .= 'Content-type: '.$this->style.'; charset=utf-8'."\r\n";
    $params .= 'Content-type: text/plain; charset=iso-8859-1'."\r\n";

    $header = $from . $replay . $params;

    if (mail($this->email->value, $this->title, $message, $header)) {
      if (SETTING::$DEV_MODE) { \ChromePhp::info('e-mail sent Succesfully to ' . $this->email->value); }
    } else {
      if (SETTING::$DEV_MODE) { \ChromePhp::error('Failed to send e-mail'); }
      // TODO:mrenyard: try fix sendmail and retry
      // TODO:mrenyard: else log error for admin
    }
  }
}
