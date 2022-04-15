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
 */
final class Email extends Templated {

  /**
   */
  final public function render()
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

    if (@mail($this->email->value, $this->title, $message, $header)) {
      if (isset($DEV_MODE) && $DEV_MODE ) { \ChromePhp::info('e-mail sent Succesfully to ' . $this->email->value); }
    } else {
      if (isset($DEV_MODE) && $DEV_MODE ) { \ChromePhp::error('Failed to send e-mail'); }
      // todo:mrenyard: try fix sendmail and retry
      // todo:mrenyard: else log error for admin
    }
  }
}
