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
use ramp\model\business\validation\FailedValidationException;
use ramp\model\business\validation\RegexValidationRule;
use ramp\model\business\validation\FormatBasedValidationRule;
use ramp\model\business\validation\dbtype\DbTypeValidation;
use ramp\model\business\validation\dbtype\Char;
use ramp\model\business\validation\dbtype\VarChar;

/**
 * MultipartInput field related to a single property with mutiply data storage fields
 * (e.g. week actual (year + weekNumber) month actual (year + monthNumber)).
 * 
 * ```php
 * new field\MultipartInput(Str::set('propertyName'), $parent,
 *   Str::set('Expanded description of expected field content.'),
 *   new validation\ISOWeek(Str::set('formated: ')),
 *   ['-W'],
 *   ['monthYear','monthNumber'],
 *   new validation\dbtype\SmaillInt(
 *     Str::set('a 4 digit year from '), 1901, 2155
 *   ),
 *   new validation\dbtype\TinyInt(
 *     Str::set('a 2 digit week number from '), 01, 53
 *   )
 * );
 * ```
 */
class MultipartInput extends Input
{
  private static Str $type;
  private array $splits; // string[]
  private array $parts; // [n][valueLength, propertyName, validationRule];

  /**
   * Creates a multipart input field related to a single property with mutiply data storage fields.
   * @param \ramp\core\Str $name Related field name of parent record.
   * @param \ramp\model\business\Record $parent Record parent of *this* property.
   * @param \ramp\core\Str $title An expanded description of expected field content.
   * @param \ramp\model\business\validation\FormatBasedValidationRule $formValidation Validation rule to test user submited value against.
   * @param string[] $splits List of splits to divide input data into its sub parts from first to last. 
   * @param string[] $dataProperties List of property names mapped in datastore.
   * @param \ramp\model\business\validation\dbtype\DbTypeValidation,... $dataValidation One or more Validation rule/s to test sub part data before data submition.
   * @throws \InvalidArgumentException 
   */
  public function __construct(Str $name, Record $parent, Str $title, FormatBasedValidationRule $formValidation, array $splits, array $dataProperties, DbTypeValidation ...$dataValidation)
  {
    if (!isset(SELF::$type)) { SELF::$type = Str::set('input field'); }
    $this->splits = $splits;
    $fparts = preg_split('/(' . implode('|', $splits) . ')/', $formValidation->format);
    if (count($fparts) != count($dataProperties)) { throw new \InvalidArgumentException(''); }
    // TODO:mrenyard: Internationalise 'total length' & 'maxinmum length'
    parent::__construct($name, $parent, $title,
      new Char(Str::_EMPTY(), Str::set('total characters: '), strlen($formValidation->format), $formValidation)
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
  #[\Override]
  protected function get_type() : Str { return SELF::$type; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_placeholder() : ?Str { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_minlength() : ?int { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_maxlength() : ?int { return NULL; }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_hint() : Str
  {
    $rtn = Str::_EMPTY();
    $followedBy = Str::set('followed by ');
    foreach ($this->parts as $propMeta) {
      $validationRule = $propMeta[2];
      $rtn = $rtn->append($validationRule->hint)->append(Str::SPACE())->append($followedBy);
    }
    return $rtn->trimEnd($followedBy)->append(parent::get_hint());
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_value()
  {
    $i = 0; $rtn = '';
    foreach ($this->parts as $propMeta) {
      $valueLength = $propMeta[0];
      $propertyName = $propMeta[1];
      $validationRule = $propMeta[2];
      if ($this->parts[0][1] != $propertyName) { $rtn .= $this->splits[$i]; }
      $value = $this->parent->getPropertyValue($propertyName);
      if ($value === NULL) { return NULL; }
      $rtn .= (is_int($value)) ? str_pad($value, $valueLength, '0',  STR_PAD_LEFT) : $value;
      if (count($this->splits) > ($i+1)) { $i++; }
    }
    return $rtn;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   * @param bool $update Default is to update on succesful validation, FALSE to skip.
   */
  #[\Override]
  public function validate(PostData $postdata, bool $update = TRUE) : void
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
          if (strlen($remainder) > $valueLength) {
            $valueRemainder = explode($split, $remainder, 2);
            $value = $valueRemainder[0];
            $remainder = $valueRemainder[1];
          } else {
            $value = $remainder;
          }
          try {
            $validationRule->process($value);
          } catch (FailedValidationException $exception) {
            $this->errorCollection->add(Str::set($exception->getMessage())->prepend(Str::set($propertyName)->append(Str::SPACE())));
            return;
          }
          $this->parent->setPropertyValue($propertyName, $value);
        }
      }
    }
  }
}
