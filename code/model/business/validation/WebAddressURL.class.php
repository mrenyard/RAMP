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
namespace ramp\model\business\validation;

use ramp\core\Str;

/**
 * WebAddressURL (Uniform Resource Locator) regex pattern validation.
 */
class WebAddressURL extends RegexValidationRule
{
    /**
   * Constructor for WebAddressURL regex pattern validation.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.
   * @param bool $allowPorts Optionally allow port number as part of allowed URL (Defaults FALSE).
   * @param bool $allowInpageLinks Optionally allow in-page fragment links (Defaults TRUE).
   */
  public function __construct(Str $errorHint, bool $allowPorts = FALSE, bool $allowInpageLinks = TRUE)
  {
    $ports = ($allowPorts) ? '(:[0-9])?' : '';
    $inpage = ($allowInpageLinks) ? '|#[a-z0-9\-\:]*' : '';
    parent::__construct($errorHint, 'https:\/\/[a-z0-9-\.]+' . $ports . '([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&amp;([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?' . $inpage);
  }
  // ^(?:https:\/\/[a-z0-9-\.]+([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?)$
  // https:\/\/[a-z0-9-\.]+([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?
  // 'https:\/\/[a-z0-9-\.]+' . $ports . '([a-z0-9-\.\/\~]+)?((\?[a-z][a-z0-9\-]*=[a-z][a-z0-9\-]*)+((&([a-z0-9\-]+=[a-z0-9\-]+))*)?)?(#[a-z0-9\-\:]*)?' . $inpage

  /**
   * @ignore 
   */
  #[\Override]
  protected function get_pattern() : ?Str
  {
    return NULL;
  }
}
