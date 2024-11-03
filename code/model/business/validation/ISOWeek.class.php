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
 * Exact Week (ISO 8601) entry of a 4 digit year plus a 2 digit week number in that year (yyyy-W00).
 * @see https://en.wikipedia.org/wiki/ISO_8601#Week_dates
 */
class ISOWeek extends FormatBasedValidationRule
{
  private static Str $type;
  private static int $maxlength;
  private ?Str $min;
  private ?Str $max;
  private int $step;

   /**
   * Constructor for week restricted regex pattern validation rule.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.,
   * if providing $min and $max values will be proceeded by $min 'to' $max).
   * @param \ramp\core\Str $min Optional minimum value that is acceptable in the format (yyyy-W00).
   * @param \ramp\core\Str $max Optional maximum value that is acceptable in the format (yyyy-W00).
   * @param int $step Optional number that specifies the granularity that the value must adhere to,
   * given in weeks, the default value of step is 1, indicating 1 week.
   * @throws \InvalidArgumentException When $min and or $max are invalid.
   */
  public function __construct(Str $errorHint, Str $min = NULL, Str $max = NULL, int $step = NULL)
  {
    $failed = FALSE;
    if (!isset(SELF::$type)) { SELF::$type = Str::set('week'); } 
    if (!isset(SELF::$maxlength)) { SELF::$maxlength = 8; }
    // TODO:mrenyard: Internationalise 'from' & 'to'.
    $errorHint = ($min !== NULL && $max !== NULL) ?
      $errorHint->append(Str::set(' from '))->append($min)->append(Str::set(' to '))->append($max):
        $errorHint;
    parent::__construct($errorHint, '[0-9]{4}-W(?:0[1-9]|[1-4][0-9]|5[0-3]){1}', 'yyyy-W00');
    try {
      if ($min) { parent::test($min); }
      if ($max) { parent::test($max); }
    } catch (FailedValidationException $e) { $failed = TRUE; }
    if ($min !== NULL && $max !== NULL) {
      $minYw = explode('-W', (string)$min); $maxYw = explode('-W', (string)$max);
      if ($failed || ($minYw[0] > $maxYw[0]) || ($minYw[0] == $maxYw[0] && $minYw[1] > $maxYw[1])) {
        throw new \InvalidArgumentException('Provided $min and or $max values are badly formatted or illogical $min is greater than $max.');
      }
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
    $valueYw = explode('-W', $value);
    $minYw = explode('-W', $this->min);
    $maxYw = explode('-W', $this->max);
    if (
      ($valueYw[0] < $maxYw[0] || ($valueYw[0] == $maxYw[0] && $valueYw[1] < $maxYw[1])) ||
      ($valueYw[0] > $minYw[0] || ($valueYw[0] == $minYw[0] && $valueYw[1] > $minYw[1]))
    ) { return; }
    throw new FailedValidationException();
  }
}
