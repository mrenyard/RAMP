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
namespace ramp\model;

use ramp\core\Str;
use ramp\model\Model;

/**
 * Superclass for *Document*.
 *
 * RESPONSIBILITIES
 * - Inherit generalized methods for property access (from {@see \ramp\core\RAMPObject})
 * - Define generalized properties for Documents
 * @property \svetle\core\Str $id Unique Identifier of document / fragment
 * @property \svetle\core\Str $title Title of document / fragment
 * @property \svetle\core\Str $heading Heading of document / fragment
 * @property \svetle\core\Str $label Synonym for heading (on form fields)
 * @property \svetle\core\Str $summary Overview, intro or description (single paragraph).
 * @property \svetle\core\Str $placeholder Synonym for summary (on form fields)
 * @property \svetle\core\Str $extendedSummary additional paragraphs to summary (optional/HTMLight).
 * @property \svetle\core\Str $extendedContent Main body of content (HTMLight).
 * @property \svetle\core\Str $footnote Footer contained content info: referances, index, glossery, disclamer.
 * @property \svetle\core\Str $style Look, sub group or class.
 */
class Document extends Model
{
  private static $NEXT_ID = 0;
  public static function reset() { SELF:: $NEXT_ID = 0; }

  private $id;
  private $title;
  private $heading;
  private $summary;
  private $extendedSummary;
  private $extendedContent;
  private $footnote;
  private $style;

  /**
   * Constructs a Document.
   */
  public function __construct()
  {
    $this->id = Str::set('uid' . ++SELF::$NEXT_ID);
  }

  /**
   * @ignore
   */
  protected function set_id(Str $value) : void
  {
    $this->id = $value;
  }

  /**
   * @ignore
   */
  protected function get_id() : Str
  {
    return $this->id;
  }

  /**
   * @ignore
   */
  protected function set_title(Str $value) : void
  {
    $this->title = $value;
  }

  /**
   * @ignore
   */
  protected function get_title() : ?Str
  {
    return $this->title;
  }

  /**
   * @ignore
   */
  protected function set_heading(Str $value) : void
  {
    $this->heading = $value;
  }

  /**
   * @ignore
   */
  protected function get_heading() : ?Str
  {
    return $this->heading;
  }

  /**
   * @ignore
   */
  protected function set_label(Str $value) : void
  {
    $this->set_heading($value);
  }

  /**
   * @ignore
   */
  protected function get_label() : ?Str
  {
    return $this->get_heading();
  }

  /**
   * @ignore
   */
  protected function set_summary(Str $value) : void
  {
    $this->summary = $value;
  }

  /**
   * @ignore
   */
  protected function get_summary() : ?Str
  {
    return $this->summary;
  }

  /**
   * @ignore
   */
  protected function set_placeholder(Str $value) : void
  {
    $this->set_summary($value);
  }

  /**
   * @ignore
   */
  protected function get_placeholder() : ?Str 
  {
    return $this->get_summary();
  }
  
  /**
   * @ignore
   */
  protected function set_extendedSummary(string $value) : void
  {
    // TODO:mrenyard: Safe HTMLight validation.
    $this->extendedSummary = Str::set($value);
  }

  /**
   * @ignore
   */
  protected function get_extendedSummary() : ?Str
  {
    return $this->extendedSummary;
  }

  /**
   * @ignore
   */
  protected function set_extendedContent(string $value) : void
  {
    // TODO:mrenyard: Safe HTMLight validation.
    $this->extendedContent = Str::set($value);
  }

  /**
   * @ignore
   */
  protected function get_extendedContent() : ?Str
  {
    return $this->extendedContent;
  }

  /**
   * @ignore
   */
  protected function set_footnote(string $value) : void
  {
    // TODO:mrenyard: Safe HTMLight validation.
    $this->footnote = Str::set($value);
  }

  /**
   * @ignore
   */
  protected function get_footnote() : ?Str
  {
    return $this->footnote;
  }

  /**
   * @ignore
   */
  protected function set_style(Str $value) : void
  {
    $this->style = $value;
  }

  /**
   * @ignore
   */
  protected function get_style() : ?Str
  {
    return $this->style;
  }

  /**
   * Create a copy, ensures a unique id.
   */
  public function __clone()
  {
    $this->id = Str::set('uid' . ++SELF::$NEXT_ID);
  }
}
