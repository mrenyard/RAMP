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
use ramp\model\business\validation\FailedValidationException;

/**
 * Time (ISO 8601) uses the 24-hour clock system, the basic format is (hh:mm[:ss]).
 * @see https://en.wikipedia.org/wiki/ISO_8601#Times
 */
class ISOTime extends FormatBasedValidationRule
{
  private static $inputtType;
  private ?Str $min;
  private ?Str $max;
  private int $step;

   /**
   * Constructor for Time restricted regex pattern validation rule.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.,
   * if providing $min and $max values will be proceeded by $min 'to' $max).
   * @param \ramp\core\Str $min Optional minimum value that is acceptable in the format (hh:mm[:ss]).
   * @param \ramp\core\Str $max Optional maximum value that is acceptable in the format (hh:mm[:ss]).
   * @param int $step Optional number that specifies the granularity that the value must adhere in number of seconds you want to increment by;
   * the default value being 60 seconds, or one minute unless specifed value less than 60 seconds (1 minute), then the time input will show a
   * seconds input area alongside the hours and minutes.
   * @throws \InvalidArgumentException When $min and or $max are invalid.
   */
  public function __construct(Str $errorHint, Str $min = NULL, Str $max = NULL, int $step = NULL)
  {
    $failed = FALSE;
    if (!isset(SELF::$inputtType)) { SELF::$inputtType = Str::set('time'); }
    // TODO:mrenyard: Internationalise 'from' & 'to'.
    $errorHint = ($min !== NULL && $max !== NULL) ?
      $errorHint->append(Str::set(' from '))->append($min)->append(Str::set(' to '))->append($max):
        $errorHint;  
    parent::__construct($errorHint, '(?:[0,1][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?', 'hh:mm:ss');
    try {
      if ($min) { parent::test($min); }
      if ($max) { parent::test($max); }
    } catch (FailedValidationException $e) { $failed = TRUE; }
    if ($min !== NULL && $max !== NULL) {
      $minHms = explode(':', (string)$min); $maxHms = explode(':', (string)$max);
      if ($failed ||
        ($minHms[0] > $maxHms[0]) ||
        ($minHms[0] == $maxHms[0] && $minHms[1] > $maxHms[1]) ||
        ((isset($minHms[2]) && isset($maxHms[2])) && ($minHms[0] == $maxHms[0] && $minHms[1] == $maxHms[1] && $minHms[2] > $maxHms[2]))
      ) { throw new \InvalidArgumentException('The provided $min and or $max values are badly formatted or illogical $min is greater than $max.'); }
    }
    $this->min = $min; $this->max = $max;
    $this->step = ($step) ? $step : 60; // seconds (1min).
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_inputType() : Str
  {
    return SELF::$inputtType;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_min() : ?Str
  {
    return $this->min;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_max() : ?Str
  {
    return $this->max;
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_step() : ?Str
  {
    return Str::set($this->step);
  }
}
