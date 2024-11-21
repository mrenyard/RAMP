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
 * - Keep single reference to a set of simple regularly used strings.
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
   * Singleton reference to {@see Str} with value ''.
   */
  private static $EMPTY;

  /**
   * Singleton reference to {@see Str} with value ' '.
   */
  private static $SPACE;

  /**
   * Singleton reference to {@see Str} with value ':'.
   */
  private static $COLON;

  /**
   * Singleton reference to {@see Str} with value ';'.
   */
  private static $SEMICOLON;

  /**
   * Singleton reference to {@see Str} with value '|'.
   */
  private static $BAR;

  /**
   * Singleton reference to {@see Str} with value 'new'.
   */
  private static $NEW;

  /**
   * Singleton reference to {@see Str} with value '+'.
   */
  private static $PLUS;

  /**
   * Singleton reference to {@see Str} with value 'FK_'.
   */
  private static $FK;

  /**
   * Singleton reference to {@see Str} with value '_'.
   */
  private static $UNDERLINE;

  /**
   * this value i.e. '', ':', ';' 'word', 'a sentence'.
   */
  private string $value;
  // Case variants.
  private Str $lower;
  private Str $upper;

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
   * @ignore
   */
  protected function get_lowercase() : Str
  {
    if (!isset($this->lower)) { $this->lower = Str::set(strtolower($this->value)); }
    return $this->lower;
  }

  /**
   * @ignore
   */
  protected function get_uppercase() : Str
  {
    if (!isset($this->upper)) { $this->upper = Str::set(strtoupper($this->value)); }
    return $this->upper;
  }

  /**
   * Instantiate or get copy of Str based on provided string literal.
   * @param string $value Value of encapsulated string literal
   * @return \ramp\core\Str Relevant Str object
   */
  public static function set($value = NULL) : Str
  {
    $s;
    switch((string)$value) {
      case NULL:
      case '':
        $s = SELF::_EMPTY();
        break;
      case ' ':
        $s = SELF::SPACE();
        break;
      case ':':
        $s = SELF::COLON();
        break;
      case ';':
        $s = SELF::SEMICOLON();
        break;
      case '|':
        $s = SELF::BAR();
        break;
      case 'new':
        $s = SELF::NEW();
        break;
      case '+':
        $s = SELF::PLUS();
        break;
      case 'fk_':
        $s = SELF::FK();
        break;
      case '_':
        $s = SELF::UNDERLINE();
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
    if (!isset(SELF::$EMPTY)) {
      SELF::$EMPTY = new Str('');
    }
    return SELF::$EMPTY;
  }

  /**
   * Returns Space Str.
   * @return \ramp\core\Str Str object composed space (' ')
   */
  public static function SPACE() : Str
  {
    if (!isset(SELF::$SPACE)) {
      SELF::$SPACE = new Str(' ');
    }
    return SELF::$SPACE;
  }

  /**
   * Returns Colon Str (':')
   * @return \ramp\core\Str Str object composed colon (':')
   */
  public static function COLON() : Str
  {
    if (!isset(SELF::$COLON)) {
      SELF::$COLON = new Str(':');
    }
    return SELF::$COLON;
  }

  /**
   * Returns Semicolon Str (';')
   * @return \ramp\core\Str Str object composed semicolon (';')
   */
  public static function SEMICOLON() : Str
  {
    if (!isset(SELF::$SEMICOLON)) {
      SELF::$SEMICOLON = new Str(';');
    }
    return SELF::$SEMICOLON;
  }

  /**
   * Returns Bar Str ('|')
   * @return \ramp\core\Str Str object composed bar ('|')
   */
  public static function BAR() : Str
  {
    if (!isset(SELF::$BAR)) {
      SELF::$BAR = new Str('|');
    }
    return SELF::$BAR;
  }

  /**
   * Returns NEW Str ('new')
   * @return \ramp\core\Str Str object composed new ('new')
   */
  public static function NEW() : Str
  {
    if (!isset(SELF::$NEW)) {
      SELF::$NEW = new Str('new');
    }
    return SELF::$NEW;
  }

  /**
   * Returns NEW Str ('+')
   * @return \ramp\core\Str Str object composed PLUS ('+')
   */
  public static function PLUS() : Str
  {
    if (!isset(SELF::$PLUS)) {
      SELF::$PLUS = new Str('+');
    }
    return SELF::$PLUS;
  }

  /**
   * Returns NEW Str ('FK_')
   * @return \ramp\core\Str Str object composed KEY ('FK_')
   */
  public static function FK() : Str
  {
    if (!isset(SELF::$FK)) {
      SELF::$FK = new Str('fk_');
    }
    return SELF::$FK;
  }

  /**
   * Returns NEW Str ('_')
   * @return \ramp\core\Str Str object composed KEY ('_')
   */
  public static function UNDERLINE() : Str
  {
    if (!isset(SELF::$UNDERLINE)) {
      SELF::$UNDERLINE = new Str('_');
    }
    return SELF::$UNDERLINE;
  }

  /**
   * Appends provided Str to 'this' and returns new appended Str.
   * @param \ramp\core\Str $value Str to be appended to *this*
   * @return \ramp\core\Str Concatenation of *this* with provided appended
   */
  public function append(Str $value) : Str
  {
    if ($this === SELF::_EMPTY()){ return $value; } // cannot append to an empty string
    if ($value === SELF::_EMPTY()) { return $this; } // empty strings do not append 
    return Str::set(($this->value . $value));
  }

  /**
   * Prepends provided Str with 'this' and returns new prepended Str.
   * @param \ramp\core\Str $value Str to be prepended to *this*
   * @return \ramp\core\Str Concatenation of *this* with provided prepended
   */
  public function prepend(Str $value) : Str
  {
    if ($this === SELF::_EMPTY()) { return $value; } // cannot prepend to an empty string
    if ($value === SELF::_EMPTY()) { return $this; } // empty strings do not prepend 
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
    if ($this === SELF::_EMPTY() || $value == SELF::_EMPTY()){ return $this; }
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
    if ($this === SELF::_EMPTY() || $value == SELF::_EMPTY()){ return $this; }
    $value = ($value === NULL)? Str::SPACE() : $value;
    $value = Str::set(substr_replace((string)$this, '', 0, strlen((string)$value)));
    return ((string)$value == (string)$this)? $this : $value;
  }

  // public function trim(Str $value = NULL) : Str
  // {
  //   return $this->trimStart()->trimEnd();
  // }

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
   * Creates a camelCase version of the provided Str.
   * @param \ramp\core\Str $value Str to be camelCased
   * @param boolean $lowerCaseFirstLetter Lowercase the first letter
   * @return \ramp\core\Str New Str camelCased based on provided
   */
  public static function camelCase(Str $value, bool $lowerCaseFirstLetter = NULL) : Str
  {
    if ($value === SELF::_EMPTY()){ return $value; } // cannot camelCase an empty string
    $value = str_replace(' ', '', ucwords(str_replace('-', ' ', (string)$value)));
    if ($lowerCaseFirstLetter !== NULL && $lowerCaseFirstLetter) { $value = lcfirst($value); }
    return Str::set($value);
  }

  /**
   * Creates a hyphenated version of the provided Str.
   * @param \ramp\core\Str $value Str to hyphenate
   * @return \ramp\core\Str New Str hyphenated based on provided
   */
  public static function hyphenate(Str $value) : Str
  {
    if ($value === SELF::_EMPTY()){ return $value; } // cannot hyphenate an empty string
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
    if ($this === SELF::_EMPTY()){ return FALSE; } // cannot search an empty string
    foreach($searchSubstrings as $searchSubstring) {
        if (stripos((string)$this, (string)$searchSubstring) !== FALSE) { return TRUE; }
    }
    return FALSE;
  }

  /**
   * Returns a StrCollection split at the point of designated separator.
   * @param \ramp\core\StrCollection $separator Separator used to determine points of separation. 
   */
  public function explode(Str $separator) : StrCollection
  {
    $value = StrCollection::set();
    if ($this === SELF::_EMPTY()){ return $value->add($this); } // cannot explode an empty string
    foreach (explode((string)$separator, $this->value) as $part) { $value->add(Str::set($part)); }
    return $value;
  }

  /**
   * Returns value of 'this' as string literal
   * @return string Stored value
   */
  #[\Override]
  public function __toString() : string
  {
    return $this->value;
  }
}
