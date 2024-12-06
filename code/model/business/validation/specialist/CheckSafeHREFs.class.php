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
namespace ramp\model\business\validation\specialist;

use ramp\core\Str;
use ramp\model\business\validation\WebAddressURL;
use ramp\model\business\validation\FailedValidationException;

/**
 * Validates any and all href links within provided string as safe.
 * - link: &lt;a href="[safe-web-address]"&gt;text&lt;/a&gt;
 */
class CheckSafeHREFs extends SpecialistValidationRule
{
  private WebAddressURL $urlValidator;

  /**
   * Constructor for validation checking the saftey of 'href=' links within provided text.
   * ```php
   * $myRule = new validation\specialist\CheckSafeHREFs(Str::set(' safe links,'));
   * ```
   * @param Str $errorHint Format hint to be displayed on failing test.
   */
  public function __construct(Str $errorHint)
  {
    $this->urlValidator = new WebAddressURL(Str::_EMPTY());
    parent::__construct($errorHint);
  }

  /**
   * Asserts that $value conforms to safe (allowed) href. 
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    $hrefMatches = array();
    if (preg_match_all('/href="([^"]*)"/', $value, $hrefMatches)) {
      foreach ($hrefMatches[1] as $url) {
        $this->urlValidator->process($url);
      }
    }
    return;
  }
}
