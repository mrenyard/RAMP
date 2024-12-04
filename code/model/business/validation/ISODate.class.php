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
 * Exact Date (ISO 8601) entry of a 4 digit year plus a 2 digit month number and 2 digit day of month number (yyyy-mm-dd).
 * @see https://en.wikipedia.org/wiki/ISO_8601#Calendar_dates
 */
class ISODate extends FormatBasedValidationRule
{
  private static $type;
  private ?Str $min;
  private ?Str $max;
  private int $step;

  /**
   * Constructor for month restricted regex pattern validation rule.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.,
   * if providing $min and $max values will be proceeded by $min 'to' $max).
   * @param ?\ramp\core\Str $min Optional minimum value that is acceptable in the format (yyyy-mm-dd).
   * @param ?\ramp\core\Str $max Optional maximum value that is acceptable in the format (yyyy-mm-dd).
   * @param ?int $step Optional number that specifies the granularity that the value must adhere to,
   * for date inputs, the value of step is given in days with the default of 1, indicating 1 day.
   * @throws \InvalidArgumentException When $min and or $max are invalid.
   */
  public function __construct(Str $errorHint, ?Str $min = NULL, ?Str $max = NULL, ?int $step = NULL)
  {
    $failed = FALSE;
    if (!isset(SELF::$type)) { SELF::$type = Str::set('date'); } 
    // TODO:mrenyard: Internationalise 'from' & 'to'.
    $errorHint = ($min !== NULL && $max !== NULL) ?
      $errorHint->append(Str::set(' from '))->append($min)->append(Str::set(' to '))->append($max):
        $errorHint;
    parent::__construct($errorHint, '[0-9]{4}-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1])', 'yyyy-mm-dd');
    if ($min !== NULL) { try {
      parent::test($min);
    } catch (FailedValidationException $e) {
      throw new \InvalidArgumentException('The provided $min value is badly formatted!');
    }}
    if ($max !== NULL) { try {
      parent::test($max);
    } catch (FailedValidationException $e) {
      throw new \InvalidArgumentException('The provided $max value is badly formatted!');
    }}
    if ($min !== NULL && $max !== NULL) {
      $minYmd = explode('-', (string)$min);
      $maxYmd = explode('-', (string)$max);
      if (
        ($minYmd[0] > $maxYmd[0]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] > $maxYmd[1]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] == $maxYmd[1] && $minYmd[2] > $maxYmd[2])
      ) {
        throw new \InvalidArgumentException('Illogical $min is greater than $max!');
      }
    }
    $this->min = $min;
    $this->max = $max;
    $this->step = ($step) ? $step : 1; // 1 day
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_inputType() : Str
  {
    return SELF::$type;
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

  /**
   * Asserts that $value is lower case and alphanumeric.
   * @param mixed $value Value to be tested.
   * @throws FailedValidationException When test fails.
   */
  #[\Override]
  protected function test($value) : void
  {
    parent::test($value);
    $valueYmd = explode('-', $value);
    if ($this->max !== NULL) {
      $maxYmd = explode('-', $this->max);
      if (
        ($valueYmd[0] > $maxYmd[0]) || 
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] > $maxYmd[1]) || 
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] == $maxYmd[1] && $valueYmd[2] > $maxYmd[2])
      ) {
        throw new FailedValidationException('Tested $value outside of $max bounds!');
      }  
    }
    if ($this->min !== NULL) {
      $minYmd = explode('-', $this->min);
      if (
        ($valueYmd[0] < $minYmd[0]) ||
        ($valueYmd[0] == $minYmd[0] && $valueYmd[1] < $minYmd[1]) ||
        ($valueYmd[0] == $minYmd[0] && $valueYmd[1] == $minYmd[1] && $valueYmd[2] < $minYmd[2])
      ) {
        throw new FailedValidationException('Tested $value outside of $min bounds!');
      }  
    }
    return;
  }
}
