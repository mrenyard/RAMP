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
 * @todo:mrenyard: Find my original defintions for each HTML attribute context spesific etc.
 * @property \svetle\core\Str $id Unique Identifier of document / fragment
 * @property \svetle\core\Str $title Title of document / fragment
 * @property \svetle\core\Str $heading Heading of document / fragment
 * @property \svetle\core\Str $label Unique Identifier
 * @property \svetle\core\Str $summary Unique Identifier
 * @property \svetle\core\Str $placeholder Unique Identifier
 * @property \svetle\core\Str $style Unique Identifier
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
   * Constructs a DocumentModel.
   */
  public function __construct()
  {
    $this->id = Str::set('uid' . self::$NEXT_ID++);
  }

  /**
   * Setter for Unique Identifier.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->id = $value;
   * ```
   * @param \svetle\core\Str $value Value of the property *id*
   */
  protected function set_id(Str $value)
  {
    $this->id = $value;
  }

  /**
   * Getter for Unique Identifier.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->id;
   * ```
   * @return \svetle\core\Str $value Value of the property *id*
   */
  protected function get_id()
  {
    return $this->id;
  }

  /**
   * Setter for title.
   * DO NOT CALL THIS SETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->title = $value;
   * ```
   * @param \svetle\core\Str $value Value of the property *title*
   */
  protected function set_title(Str $value)
  {
    $this->title = $value;
  }

  /**
   * Getter for title.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->title;
   * ```
   * @return \svetle\core\Str Value of the property *title*
   */
  protected function get_title()
  {
    return (isset($this->title)) ? $this->title : Str::set('[title]');
  }

  /**
   * Setter for heading.
   * DO NOT CALL THIS SETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->heading = $value;
   * ```
   * @param \svetle\core\Str $value Value of the property *heading*
   */
  protected function set_heading(Str $value)
  {
    $this->heading = $value;
  }

  /**
   * Getter for heading.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->heading;
   * ```
   * @return \svetle\core\Str Value of the property *heading*
   */
  protected function get_heading()
  {
    return $this->heading;
  }

  /**
   * Setter for label - synonym {@link #set_heading}.
   * DO NOT CALL THIS SETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->label = $value;
   * ```
   * @param \svetle\core\Str $value Value of the property *label*
   */
  protected function set_label(Str $value)
  {
    $this->set_heading($value);
  }

  /**
   * Getter for label - synonym {@link #get_heading}.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->label;
   * ```
   * @param \svetle\core\Str Value of the property *label*
   */
  protected function get_label()
  {
    return $this->get_heading();
  }

  /**
   * Setter for summary.
   * DO NOT CALL THIS SETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->summary = $value;
   * ```
   * @param \svetle\core\Str $value Value of the property *summary*
   */
  protected function set_summary(Str $value)
  {
    $this->summary = $value;
  }

  /**
   * Getter for summary.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->summary;
   * ```
   * @return \svetle\core\Str Value of the property *summary*
   */
  protected function get_summary()
  {
    return (isset($this->summary)) ? $this->summary : '[summary]';
  }

  /**
   * Setter for placeholder - synonym {@link #set_summary}.
   * DO NOT CALL THIS SETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->placeholder = $value;
   * ```
   * @return \svetle\core\Str Value of the property *placeholder*
   */
  protected function set_placeholder(Str $value)
  {
    $this->set_summary($value);
  }

  /**
   * Getter for placeholder - synonym {@link #get_summary}.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->placeholder;
   * ```
   * @return \svetle\core\Str Value of the property *placeholder*
   */
  protected function get_placeholder()
  {
    return $this->get_summary();
  }

  /**
   * Setter for style.
   * DO NOT CALL THIS SETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->style = $value;
   * ```
   * @param \svetle\core\Str $value Value of the property *style*
   */
  protected function set_style(Str $value)
  {
    $this->style = $value;
  }

  /**
   * Getter for style.
   * DO NOT CALL THIS GETTER METHOD DIRECTLY - USE PROPERTY NAME!
   * ```php
   * $this->style;
   * ```
   * @return \svetle\core\Str Value of the property *style*
   */
  protected function get_style()
  {
    return $this->style;
  }

  /**
   * Create a copy, ensures a unique id.
   */
  public function __clone()
  {
    $this->id = Str::set('uid' . self::$NEXT_ID++);
  }
}
