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
use ramp\core\StrCollection;
use ramp\condition\PostData;
use ramp\model\business\Record;
use ramp\model\business\validation\RegexValidationRule;
use ramp\model\business\validation\FormatBasedValidationRule;
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
  private $parts; // [valueLength, propertyName, validationRule];

  /**
   * Creates a multipart input field related to a single property with mutiply data storage fields.
   * @param \ramp\core\Str $name Related dataObject property name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $title An expanded description of expected field content.
   * @param \ramp\model\business\validation\dbtype\DbTypeValidation $formValidation Validation rule to test user submited value against.
   * @param string[] $splits List of splits to divide input data into its sub parts from first to last. 
   * @param string[] $dataProperties List of property names mapped in datastore.
   * @param \ramp\model\business\validation\dbtype\DbTypeValidation,... $dataValidation One or more Validation rule/s to test sub part data before data submition.
   * 
   * ```php
   * new field\Input(Str::set('propertyName'), $parent,
   *   Str::set('Expanded description of expected field content.'),
   *   new validation\FormValidationRule(
   *     Str::set('expected format'),
   *     new validation\ExtraSubFormValidationRule(
   *       Str::set('expected format')
   *   ),
   *   ['-'],
   *   ['firstPart','secondPart', ...],
   *   new validation\dbtype\FirstPartValidationRule(
   *     Str::set('expected format'), ...
   *   ),
   *   new validation\dbtype\SeconPartValidationRule(
   *     Str::set('expected format'), ...
   *   ),
   *   ...
   * );
   * ```
   */
  public function __construct(Str $name, Record $parent, Str $title, FormatBasedValidationRule $formValidation, array $splits, array $dataProperties, DbTypeValidation ...$dataValidation)
  {
    if (!isset(self::$type)) { self::$type = Str::set('input field'); }
    $this->splits = $splits;
    $format = $formValidation->format;
    $fparts = preg_split('/(' . implode('|', $splits) . ')/', $format);
    if ($format == NULL || count($fparts) != count($dataProperties)) {
      throw new \InvalidArgumentException('');
    }
    // TODO:mrenyard: Internationalise 'total length' & 'maxinmum length'
    parent::__construct($name, $parent, $title, ($format) ?
      new Char(Str::set('total charactors: '), strlen($format), $formValidation) :
        new VarChar(Str::set('maxinmum charactors: '), 250, $formValidation)
    );
    $i = 0;
    $this->parts = [];
    foreach($dataProperties as $property) {
      $this->parts[$i] = [strlen($fparts[$i]), $property, $dataValidation[$i]];
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
    foreach ($this->parts as $propMeta) {
      $valueLength = $propMeta[0];
      $propertyName = $propMeta[1];
      $validationRule = $propMeta[2];
      if ($this->parts[0][1] != $propertyName) { $rtn .= $this->splits[$i]; }
      $value = $this->parent->getPropertyValue($propertyName);
      $rtn .= (is_int($value)) ? str_pad($value, $valueLength, '0',  STR_PAD_LEFT) : $value;
      if (count($this->splits) > ($i+1)) { $i++; }
    }
    return $rtn;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   */
  public function validate(PostData $postdata, $update = TRUE) : void
  {
    foreach ($postdata as $inputdata)
    {
      if ((string)$inputdata->attributeURN == (string)$this->id)
      {
        if (!$this->isEditable) { return; }
        parent::validate($postdata, FALSE);
        if ($this->hasErrors) { return; }
        $i = 0;
        $remainder = $inputdata->value;
        foreach ($this->parts as $propMeta) {
          if (count($this->splits) > $i) { $split = $this->splits[$i++]; }
          $valueLength = $propMeta[0];
          $propertyName = $propMeta[1];
          $validationRule = $propMeta[2];
          if (strlen($remainder) !== $valueLength) {
            $valueRemender= str_split($remainder, $valueLength);
            $value = $valueRemender[0];
            $remainder = str_split($valueRemender[1], strlen($split))[1];              
          } else {
            $value = $remainder;
          }
          try {
            $this->parent->setPropertyValue($propertyName, $value);
          } catch (FailedValidationException $exception) {
            throw new Exception('MultipartInput \'' . $name . '\' FormatBasedValidationRule NOT compatatible with ' . $propertyName . ' DbTypeValidation!');
          }
        }
      }
    }
  }
}
