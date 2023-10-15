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
namespace ramp\core;

/**
 * Strongly typed string used universally.
 *
 * RESPONSIBILITIES
 * - Act as strongly typed Str class for type checking.
 * - Keep single referance to a set of simple regularly used strings.
 * - Provide an API for set of simple Str based functions.
 *
 * INVARIANT
 * - State of *this* is always unchanged (all operations return an existing or new Str)
 * 
 * @property-read \ramp\core\Str $lowercase Lowercase instance of *this*.
 * @property-read \ramp\core\Str $uppercase Uppercase instance of *this*.
 */
final class Str extends RAMPObject
{
  /**
   * Singleton reference to {@link Str} with value ''.
   */
  private static $EMPTY;

  /**
   * Singleton reference to {@link Str} with value ' '.
   */
  private static $SPACE;

  /**
   * Singleton reference to {@link Str} with value ':'.
   */
  private static $COLON;

  /**
   * Singleton reference to {@link Str} with value ';'.
   */
  private static $SEMICOLON;

  /**
   * Singleton reference to {@link Str} with value '|'.
   */
  private static $BAR;

  /**
   * Singleton reference to {@link Str} with value 'new'.
   */
  private static $NEW;

  /**
   * Singleton reference to {@link Str} with value '+'.
   */
  private static $PLUS;

  /**
   * Singleton reference to {@link Str} with value 'FK'.
   */
  private static $FK;

  /**
   * this value i.e. '', ':', ';' 'word', 'a sentance'.
   */
  private $value;

  private $lower;
  private $upper;

  /**
   * Constructor for new instance of Str.
   * Takes string for strong type Str
   * @param string $value Str to be stored as value
   */
  private function __construct($value)
  {
    $this->value = (string)$value;
  }

  /**
   * Returns 'this' lowercase.
   * @return \ramp\core\Str Lowercase instance of *this*.
   */
  public function get_lowercase() : Str
  {
    if (!isset($this->lower)) { $this->lower = Str::set(strtolower($this->value)); }
    return $this->lower;
  }

  /**
   * Returns 'this' uppercase.
   * @return \ramp\core\Str Uppercase instance of *this*.
   */
  public function get_uppercase() : Str
  {
    if (!isset($this->upper)) { $this->upper = Str::set(strtoupper($this->value)); }
    return $this->upper;
  }

  /**
   * Instantiate or get copy of Str based on provided string literal.
   * @param string $value Value of encapsulated string literal
   * @return \ramp\core\Str Relevant Str object
   */
  public static function set($value = \NULL) : Str
  {
    $s;
    switch((string)$value) {
      case NULL:
      case '':
        $s = self::_EMPTY();
        break;
      case ' ':
        $s = self::SPACE();
        break;
      case ':':
        $s = self::COLON();
        break;
      case ';':
        $s = self::SEMICOLON();
        break;
      case '|':
        $s = self::BAR();
        break;
      case 'new':
        $s = self::NEW();
        break;
      case '+':
        $s = self::PLUS();
        break;
      case 'FK_':
        $s = self::FK();
        break;
      default:
        $s = new Str((string)$value);
    }
    return $s;
  }

  /**
   * Returns Empty Str.
   * @return \ramp\core\Str Str object composed empty string literal ('')
   */
  public static function _EMPTY() : Str
  {
    if (!isset(self::$EMPTY)) {
      self::$EMPTY = new Str('');
    }
    return self::$EMPTY;
  }

  /**
   * Returns Space Str.
   * @return \ramp\core\Str Str object composed space (' ')
   */
  public static function SPACE() : Str
  {
    if (!isset(self::$SPACE)) {
      self::$SPACE = new Str(' ');
    }
    return self::$SPACE;
  }

  /**
   * Returns Colon Str (':')
   * @return \ramp\core\Str Str object composed colon (':')
   */
  public static function COLON() : Str
  {
    if (!isset(self::$COLON)) {
      self::$COLON = new Str(':');
    }
    return self::$COLON;
  }

  /**
   * Returns Semicolon Str (';')
   * @return \ramp\core\Str Str object composed semicolon (';')
   */
  public static function SEMICOLON() : Str
  {
    if (!isset(self::$SEMICOLON)) {
      self::$SEMICOLON = new Str(';');
    }
    return self::$SEMICOLON;
  }

  /**
   * Returns Bar Str ('|')
   * @return \ramp\core\Str Str object composed bar ('|')
   */
  public static function BAR() : Str
  {
    if (!isset(self::$BAR)) {
      self::$BAR = new Str('|');
    }
    return self::$BAR;
  }

  /**
   * Returns NEW Str ('new')
   * @return \ramp\core\Str Str object composed new ('new')
   */
  public static function NEW() : Str
  {
    if (!isset(self::$NEW)) {
      self::$NEW = new Str('new');
    }
    return self::$NEW;
  }

  /**
   * Returns NEW Str ('+')
   * @return \ramp\core\Str Str object composed PLUS ('+')
   */
  public static function PLUS() : Str
  {
    if (!isset(self::$PLUS)) {
      self::$PLUS = new Str('+');
    }
    return self::$PLUS;
  }

  /**
   * Returns NEW Str ('FK_')
   * @return \ramp\core\Str Str object composed KEY ('FK_')
   */
  public static function FK() : Str
  {
    if (!isset(self::$FK)) {
      self::$FK = new Str('FK_');
    }
    return self::$FK;
  }

  /**
   * Appends provided Str to 'this' and returns new appended Str.
   * @param \ramp\core\Str $value Str to be appended to *this*
   * @return \ramp\core\Str Concatenation of *this* with provided appended
   */
  public function append(Str $value) : Str
  {
    if ($this === self::_EMPTY()){ return $value; } // cannot append to an empty string
    return Str::set(($this->value . $value));
  }

  /**
   * Prepends provided Str with 'this' and returns new prepended Str.
   * @param \ramp\core\Str $value Str to be prepended to *this*
   * @return \ramp\core\Str Concatenation of *this* with provided prepended
   */
  public function prepend(Str $value) : Str
  {
    if ($this === self::_EMPTY()){ return $value; } // cannot prepend to an empty string
    return Str::set(($value . $this->value));
  }

  /**
   * Returns a new Str 'this' with provided removed from end.
   * @param \ramp\core\Str $value Str to be removed from end
   * @return \ramp\core\Str New Str with provided value removed from end
   */
  public function trimEnd(Str $value = NULL) : Str
  {
    // cannot remove from an empty string
    if ($this === self::_EMPTY() || $value == self::_EMPTY()){ return $this; }
    $value = ($value === NULL)? Str::SPACE() : $value;
    $value =  Str::set(substr_replace((string)$this, '', strrpos((string)$this, (string)$value),));
    return ((string)$value == (string)$this)? $this : $value;
  }

  /**
   * Returns a new Str 'this' with provided removed from start.
   * @param \ramp\core\Str $value Str to be removed from start
   * @return \ramp\core\Str New Str with provided value removed from start
   */
  public function trimStart(Str $value = NULL) : Str
  {
    // cannot remove from an empty string
    if ($this === self::_EMPTY() || $value == self::_EMPTY()){ return $this; }
    $value = ($value === NULL)? Str::SPACE() : $value;
    $value = Str::set(substr_replace((string)$this, '', 0, strlen((string)$value)));
    return ((string)$value == (string)$this)? $this : $value;
  }

  /**
   * Returns a new Str based on 'this' with all occurrences of $search:Str replaced as directed.
   * @param \ramp\core\Str $search Sub string to be search and replaced.
   * @param \ramp\core\Str $replace Replacement value.
   */
  public function replace(Str $search, Str $replace) : Str
  {
    return Str::set(\str_replace((string)$search, (string)$replace, (string)$this));
  }

  /**
   * Creates a camelcase version of the provided Str.
   * @param \ramp\core\Str $value Str to camelcase
   * @param boolean $lowerCaseFirstLetter Lowercase the first letter
   * @return \ramp\core\Str New Str camel cased based on provided
   */
  public static function camelCase(Str $value, bool $lowerCaseFirstLetter = \NULL) : Str
  {
    if ($value === self::_EMPTY()){ return $value; } // cannot camelCase an empty string
    $value = str_replace(' ', '', ucwords(str_replace('-', ' ', (string)$value)));
    if ($lowerCaseFirstLetter !== \NULL && $lowerCaseFirstLetter) { $value = lcfirst($value); }
    return Str::set($value);
  }

  /**
   * Creates a hyphenated version of the provided Str.
   * @param \ramp\core\Str $value Str to hyphenate
   * @return \ramp\core\Str New Str hyphenated based on provided
   */
  public static function hyphenate(Str $value) : Str
  {
    if ($value === self::_EMPTY()){ return $value; } // cannot hyphenate an empty string
    $value = preg_replace('/(([A-Z]{1})+)/', ' $0', $value);
    $value = strtolower(trim(preg_replace('/\s+/', '-', $value), '-'));
    return Str::set($value);
  }

  /**
   * Ascertain if 'this' Str contains any of provided Strs provided in StrCollection.
   * @param \ramp\core\StrCollection $searchSubstrings Collection of Strs to be checked
   * @return bool *This* does/not contain any of Strs in provided Collection
   */
  public function contains(StrCollection $searchSubstrings) : bool
  {
    if ($this === self::_EMPTY()){ return FALSE; } // cannot search an empty string
    foreach($searchSubstrings as $searchSubstring) {
        if (stripos((string)$this, (string)$searchSubstring) !== FALSE) { return TRUE; }
    }
    return FALSE;
  }

  public function explode(Str $seperator) : StrCollection
  {
    $value = StrCollection::set();
    if ($this === self::_EMPTY()){ return $value->add($this); } // cannot explode an empty string
    foreach (explode((string)$seperator, $this->value) as $part) { $value->add(Str::set($part)); }
    return $value;
  }

  /**
   * Returns value of 'this' as string literal
   * @return string Stored value
   */
  public function __toString() : string
  {
    return $this->value;
  }
}
