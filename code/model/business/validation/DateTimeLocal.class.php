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
 * Exact Date Time (ISO 8601) entry of a 4 digit year, 2 digit month number and 2 digit day hyphen separated,
 * followd by 'T' then 2 digit hour, 2 digit minutes and optional 2 digit seconds colon separated (yyyy-mm-ddThh:mm[:ss]).
 * @see https://en.wikipedia.org/wiki/ISO_8601#Combined_date_and_time_representations
 */
class DateTimeLocal extends RegexValidationRule
{
  private static $type;
  private $min;
  private $max;
  private $step;
  private $minDT;
  private $maxDT;

   /**
   * Constructor for month restricted regex pattern validation rule.
   * @param \ramp\core\Str $errorHint Format hint to be displayed on failing test.,
   * if providing $min and $max values will be proceeded by $min 'to' $max).
   * @param \ramp\core\Str $min Optional minimum value that is acceptable in the format (yyyy-mm-dd).
   * @param \ramp\core\Str $max Optional maximum value that is acceptable in the format (yyyy-mm-dd).
   * @param int $step Optional number that specifies the granularity that the value must adhere to,
   * for date inputs, the value of step is given in days with the default of 1, indicating 1 day.
   * @throws \InvalidArgumentException When $min and or $max are invalid.
   */
  public function __construct(Str $errorHint, Str $min = NULL, Str $max = NULL, int $step = NULL)
  {
    $failed = FALSE;
    if (!isset(SELF::$type)) { SELF::$type = Str::set('datetime-local'); } 
      // TODO:mrenyard: Internationalise 'from' & 'to'.
      $errorHint = ($min != NULL && $max !== NULL) ?
        $errorHint->append(Str::set(' from '))->append($min)->append(Str::set(' to '))->append($max):
          $errorHint;  
    parent::__construct(
      $errorHint, 
      '[0-9]{4}-(?:0[1-9]|1[0-2])-(?:[0-2][0-9]|3[0-1])T(?:[0,1][0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?',
      NULL,
      'yyyy-mm-ddThh:mm:ss'
    );
    try {
      if ($min) { parent::test($min); }
      if ($max) { parent::test($max); }
    } catch (FailedValidationException $e) { $failed = TRUE; }
    if ($min !== NULL && $max !== NULL) {
      $minDT = explode('T', (string)$min);
      $maxDT = explode('T', (string)$max);
      $minYmd = explode('-', $minDT[0]); $maxYmd = explode('-', $maxDT[0]);
      $minHms = explode(':', $minDT[1]); $minHms[2] = (count($minHms) !== 3) ? '00' :  $minHms[2];
      $maxHms = explode(':', $maxDT[1]); $maxHms[2] = (count($maxHms) !== 3) ? '00' :  $maxHms[2];
      if ($failed ||
        ($minYmd[0] > $maxYmd[0]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] > $maxYmd[1]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] == $maxYmd[1] && $minYmd[2] > $maxYmd[2]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] == $maxYmd[1] && $minYmd[2] == $maxYmd[2] && $minHms[0] > $maxHms[0]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] == $maxYmd[1] && $minYmd[2] == $maxYmd[2] && $minHms[0] == $maxHms[0] && $minHms[1] > $maxHms[1]) ||
        ($minYmd[0] == $maxYmd[0] && $minYmd[1] == $maxYmd[1] && $minYmd[2] == $maxYmd[2] && $minHms[0] == $maxHms[0] && $minHms[1] == $maxHms[1] && $minHms[2] > $maxHms[2])
      ) { throw new \InvalidArgumentException(
          ($e) ? $e->getMessage() :
          'The provided $min and or $max values are badly formatted or illogical $min is greater than $max.'
        );
      }
    }
    $this->minDT = [implode('-', $minYmd), implode(':', $minHms)];
    $this->maxDT = [implode('-', $maxYmd), implode(':', $maxHms)];
    $this->min = $min;
    $this->max = $max;
    $this->step = ($step) ? $step : 60; // seconds (1min).
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
    $valueDT = explode('T', $value);
    $valueYmd = explode('-', $valueDT[0]); $minYmd = explode('-', $this->minDT[0]); $maxYmd = explode('-', $this->maxDT[0]);
    $valueHms = explode(':', $valueDT[1]); $minHms = explode(':', $this->minDT[1]); $maxHms = explode(':', $this->maxDT[1]);
    if (
      (
        ($valueYmd[0] < $maxYmd[0]) || 
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] < $maxYmd[1]) || 
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] == $maxYmd[1] && $valueYmd[2] < $maxYmd[2]) ||
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] == $maxYmd[1] && $valueYmd[2] == $maxYmd[2] && $valueHms[0] < $maxHms[0]) ||
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] == $maxYmd[1] && $valueYmd[2] == $maxYmd[2] && $valueHms[0] == $maxHms[0] && $valueHms[1] < $maxHms[1]) ||
        ($valueYmd[0] == $maxYmd[0] && $valueYmd[1] == $maxYmd[1] && $valueYmd[2] == $maxYmd[2] && $valueHms[0] == $maxHms[0] && $valueHms[1] == $maxHms[1] && $valueHms[2] < $maxHms[2])
      ) 
      ||
      (
        ($valueYwd[0] > $minYmd[0]) ||
        ($valueYmd[0] == $minYm[0] && $valueYmd[1] > $minYmd[1]) ||
        ($valueYmd[0] == $minYm[0] && $valueYmd[1] == $minYmd[1] && $valueYmd[2] > $minYmd[2]) ||
        ($valueYmd[0] == $minYmd[0] && $valueYmd[1] == $minYmd[1] && $valueYmd[2] == $minYmd[2] && $valueHms[0] < $mainms[0]) ||
        ($valueYmd[0] == $minYmd[0] && $valueYmd[1] == $minYmd[1] && $valueYmd[2] == $minYmd[2] && $valueHms[0] == $minHms[0] && $valueHms[1] < $minHms[1]) ||
        ($valueYmd[0] == $minYmd[0] && $valueYmd[1] == $minYmd[1] && $valueYmd[2] == $minYmd[2] && $valueHms[0] == $minHms[0] && $valueHms[1] == $minHms[1] && $valueHms[2] < $minHms[2])
      )
    ) { return; }
    throw new FailedValidationException('Tested $value outside of $min and $max bounds.');
  }
}
