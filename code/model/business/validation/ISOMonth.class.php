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
 * Exact Month (ISO 8601) entry of a 4 digit year plus a 2 digit month number (yyyy-mm).
 * @see https://en.wikipedia.org/wiki/ISO_8601#Calendar_dates
 */
class ISOMonth extends FormatBasedValidationRule
{
  private static $type;
  private static $maxlength;
  private $min;
  private $max;
  private $step;

   /**
   * Constructor for month restricted regex pattern validation rule.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.,
   * if providing $min and $max values will be proceeded by $min 'to' $max).
   * @param \ramp\core\Str $min Optional minimum value that is acceptable in the format (yyyy-mm).
   * @param \ramp\core\Str $max Optional maximum value that is acceptable in the format (yyyy-mm).
   * @param int $step Optional number that specifies the granularity that the value must adhere to,
   * given in months, the default value of step is 1 month. 
   * @throws \InvalidArgumentException When $min and or $max are invalid.
   */
  public function __construct(Str $errorHint, Str $min = NULL, Str $max = NULL, int $step = NULL)
  {
    $failed = FALSE;
    if (!isset(SELF::$type)) { SELF::$type = Str::set('month'); } 
    if (!isset(SELF::$maxlength)) { SELF::$maxlength = 7; }
    // TODO:mrenyard: Internationalise 'from' & 'to'.
    $errorHint = ($min !== NULL && $max !== NULL) ?
      $errorHint->append(Str::set(' from '))->append($min)->append(Str::set(' to '))->append($max):
      $errorHint;
    parent::__construct($errorHint, '[0-9]{4}-(?:0[1-9]|1[0-2])', 'yyyy-mm');
    try {
      if ($min) { parent::test($min); }
      if ($max) { parent::test($max); }
    } catch (FailedValidationException $e) { $failed = TRUE; }
    if ($min !== NULL && $max !== NULL) {
      $minYm = explode('-', (string)$min); $maxYm = explode('-', (string)$max);
      if ($failed || ($minYm[0] > $maxYm[0]) || ($minYm[0] == $maxYm[0] && $minYm[1] > $maxYm[1])) { throw new \InvalidArgumentException(); }
    }
    $this->min = $min; $this->max = $max;
    $this->step = ($step) ? $step : 1;
  }

  /**
   * @ignore
   */
  protected function get_inputType() : Str
  {
    return SELF::$type;
  }

  /**
   * @ignore
   */
  protected function get_min() : ?Str
  {
    return $this->min;
  }

  /**
   * @ignore
   */
  protected function get_max() : ?Str
  {
    return $this->max;
  }

  /**
   * @ignore
   */
  protected function get_step() : ?Str
  {
    return Str::set($this->step);
  }

  /**
   * Asserts that $value is lower case and alphanumeric.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  protected function test($value) : void
  {
    parent::test($value);
    $valueYm = explode('-', $value);
    $minYm = explode('-', $this->min);
    $maxYm = explode('-', $this->max);
    if (
      ($valueYw[0] < $maxYm[0] || ($valueYm[0] == $maxYm[0] && $valueYm[1] < $maxYm[1])) ||
      ($valueYw[0] > $minYm[0] || ($valueYm[0] == $minYm[0] && $valueYm[1] > $minYm[1]))
    ) { return; }
    throw new FailedValidationException();
  }
}
