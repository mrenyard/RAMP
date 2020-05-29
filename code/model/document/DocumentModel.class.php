<?php
/**
 * Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\model\document;

use svelte\core\Str;
use svelte\model\Model;

/**
 * Superclass for *DocumentModel*.
 *
 * RESPONSIBILITIES
 * - Inherit generalized methods for property access (from {@link \svelte\core\SvelteObject})
 * - Define generalized properties for DocumentModels
 */
class DocumentModel extends Model
{
  private static $NEXT_ID = 1;

  private $id;
  private $title;
  private $heading;
  private $summary;
  private $style;

  /**
   */
  public function __construct()
  {
    $this->id = Str::set('uid' . self::$NEXT_ID++);
  }

  /**
   * Setter for Unique Identifier
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->id = $value;
   * ```
   * @param Str Str - Value of the property *id*
   */
  protected function set_id(Str $value)
  {
    $this->id = $value;
  }

  /**
   * Getter for Unique Identifier
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->id;
   * ```
   * @return Str Str - Value of the property *id*
   */
  protected function get_id()
  {
    return $this->id;
  }

  /**
   * Setter for title
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->title = $value;
   * ```
   * @param Str Str - Value of the property *title*
   */
  protected function set_title(Str $value)
  {
    $this->title = $value;
  }

  /**
   * Getter for title
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->title;
   * ```
   * @return Str Str - Value of the property *title*
   */
  protected function get_title()
  {
    return (isset($this->title)) ? $this->title : Str::set('[title]');
  }

  /**
   * Setter for heading
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->heading = $value;
   * ```
   * @param Str Str - Value of the property *heading*
   */
  protected function set_heading(Str $value)
  {
    $this->heading = $value;
  }

  /**
   * Getter for heading
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->heading;
   * ```
   * @return Str Str - Value of the property *heading*
   */
  protected function get_heading()
  {
    return $this->heading;
  }

  /**
   * Setter for label - synonym {@link #set_heading}
   */
  protected function set_label(Str $value)
  {
    $this->set_heading($value);
  }

  /**
   * Getter for label - synonym {@link #get_heading}
   */
  protected function get_label()
  {
    return $this->get_heading();
  }

  /**
   * Setter for summary
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->summary = $value;
   * ```
   * @param Str Str - Value of the property *summary*
   */
  protected function set_summary(Str $value)
  {
    $this->summary = $value;
  }

  /**
   * Getter for summary
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->summary;
   * ```
   * @return Str Str - Value of the property *summary*
   */
  protected function get_summary()
  {
    return (isset($this->summary)) ? $this->summary : '[summary]';
  }

  /**
   * Setter for placeholder - synonym {@link #set_summary}
   */
  protected function set_placeholder(Str $value)
  {
    $this->set_summary($value);
  }

  /**
   * Getter for placeholder - synonym {@link #get_summary}
   */
  protected function get_placeholder()
  {
    return $this->get_summary();
  }

  /**
   * Setter for style
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->style = $value;
   * ```
   * @param Str Str - Value of the property *style*
   */
  protected function set_style(Str $value)
  {
    $this->style = $value;
  }

  /**
   * Getter for style
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->style;
   * ```
   * @return Str Str - Value of the property *style*
   */
  protected function get_style()
  {
    return $this->style;
  }

  /**
   */
  public function __clone()
  {
    $this->id = Str::set('uid' . self::$NEXT_ID++);
  }
}
