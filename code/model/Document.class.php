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
 * @property Str $id Unique Identifier of document / fragment
 * @property ?Str $title Title of document / fragment
 * @property ?Str $heading Heading of document / fragment
 * @property ?Str $label Synonym for heading (on form fields)
 * @property ?Str $summary Overview, intro or description (single paragraph).
 * @property ?Str $placeholder Synonym for summary (on form fields)
 * @property ?Str $extendedSummary additional paragraphs to summary (optional/HTMLight).
 * @property ?Str $extendedContent Main body of content (HTMLight).
 * @property ?Str $footnote Footer contained content info: referances, index, glossery, disclamer.
 * @property ?Str $style Look, sub group or class.
 */
class Document extends Model
{
  private static $NEXT_ID = 0;
  public static function reset() { SELF:: $NEXT_ID = 0; }

  private Str $id;
  private ?Str $title;
  private ?Str $heading;
  private ?Str $summary;
  private ?Str $extendedSummary;
  private ?Str $extendedContent;
  private ?Str $footnote;
  private ?Str $style;

  /**
   * Constructs a Document.
   */
  public function __construct()
  {
    $this->id = Str::set('uid' . ++SELF::$NEXT_ID);
    $this->title = NULL;
    $this->heading = NULL;
    $this->summary = NULL;
    $this->extendedSummary = NULL;
    $this->extendedContent = NULL;
    $this->footnote = NULL;
    $this->style = NULL;
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
    // TODO:mrenyard: Safe HTMLite validation.
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
    // TODO:mrenyard: Safe HTMLite validation.
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
