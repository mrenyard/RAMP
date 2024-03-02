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
namespace ramp\model\business\field;

use ramp\core\Str;
use ramp\model\business\Record;
use ramp\model\business\validation\RegexValidationRule;
use ramp\model\business\validation\dbtype\DbTypeValidation;
use ramp\model\business\validation\dbtype\Char;
use ramp\model\business\validation\dbtype\VarChar;

/**
 * MultipartInput field related to a single property with mutiply data storage fields
 * (e.g. week actual (year + weekNumber) month actual (year + monthNumber)).
 */
class MultipartInput extends Input
{
  private static $type; // Str
  private $splits; // string[]
  private $dataProperties; // array

  /**
   * Creates a multipart input field related to a single property with mutiply data storage fields.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $title An expanded description of expected field content.
   * @param \ramp\model\business\validation\dbtype\DbTypeValidation $formValidation Validation rule to test user submited value against.
   * @param array $splits List of splits to divide input data into its sub parts from first to last. 
   * @param \ramp\model\business\validation\dbtype\DbTypeValidation $dataValidation Validation rule to test sub part data before data submition.
   * 
   * ```php
   * new field\Input(Str::set('propertyName'), $parent,
   *   Str::set('Expanded description of expected field content.'),
   *   new validation\FirstValidationRule(
   *     Str::set('expected format'),
   *     new validation\SecondValidationRule(
   *       Str::set('expected format')
   *   ),
   *   ['-'],
   *   ['firstPart','secondPart'],
   *   new validation\dbtype\FirstValidationRule(
   *     Str::set('expected format'), ...
   *   ),
   *   new validation\dbtype\FirstValidationRule(
   *     Str::set('expected format'), ...
   *   ),
   *   ...
   * );
   * ```
   */
  public function __construct(Str $name, Record $parent, Str $title, RegexValidationRule $formValidation, array $splits, array $dataProperties, DbTypeValidation ...$dataValidation)
  {
    if (!isset(self::$type)) { self::$type = Str::set('input field'); }
    $this->splits = $splits;
    $format = $formValidation->format;
    $fparts = preg_split('/(' . implode('|', $splits) . ')/', $format);
    if ($format == NULL || count($fparts) != count($dataProperties)) {
      throw new \InvalidArgumentException('');
    }
    parent::__construct($name, $parent, $title, ($format) ?
      new Char(Str::_EMPTY(), strlen($format), $formValidation) :
        new VarChar(Str::_EMPTY(), 250, $formValidation)
    );
    $i = 0;
    $this->dataProperties = [];
    foreach($dataProperties as $property) {
      $this->dataProperties[$i] = [strlen($fparts[$i]), $property];
      $i++;
    }
  }

  /**
   * @ignore
   */
  protected function get_type() : Str
  {
    return self::$type;
  }

  /**
   * @ignore
   */
  protected function get_value()
  {
    $i = 0; $rtn = '';
    foreach ($this->dataProperties as $propMeta) {
      if ($this->dataProperties[0][1] != $propMeta[1]) { $rtn .= $this->splits[$i]; }
      $value = $this->parent->getPropertyValue($propMeta[1]);
      $rtn .= (is_int($value)) ? str_pad($value, $propMeta[0], '0',  STR_PAD_LEFT) : $value;
      if (count($this->splits) > ($i+1)) { $i++; }
    }
    return $rtn;
  }

  /**
   * Process provided validation rule.
   * @param mixed $rtn Value to be processed
   * @throws \ramp\validation\FailedValidationException When test fails.
   */
  public function processValidationRule($rtn) : void
  {
    $this->validationRule->process($rtn);
  }
}
