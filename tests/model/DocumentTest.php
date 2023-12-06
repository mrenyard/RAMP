<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\model;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/Document.class.php';

use ramp\core\Str;
use ramp\model\Document;

/**
 * Collection of tests for \ramp\model\document\Document.
 */
class DocumentTest extends \PHPUnit\Framework\TestCase
{
  private static $NEXT_ID = 1;
  private $testObject;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    self::$NEXT_ID = 1;
    Document::reset();
    $this->testObject = new Document();
  }

  /**
   * Collection of assertions for \ramp\model\document\Document::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\model\document\Document}
   * @see ramp.model.Model ramp\model\document\Document
   */
  public function testConstruct() : void
  {
    $this->assertInstanceOf('\ramp\core\RAMPObject', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Model', $this->testObject);
    $this->assertInstanceOf('\ramp\model\Document', $this->testObject);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::id.
   * - assert property 'id' is gettable
   * - assert property 'id' is settable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see ramp.model.business.BusinessModel#method_set_id ramp\model\business\BusinessModel::id
   * @see ramp.model.business.BusinessModel#method_get_id ramp\model\business\BusinessModel::id
   */
  public function testGetSet_id() : void
  {
    $value = $this->testObject->id;
    $this->assertInstanceOf('\ramp\core\Str', $value);
    $this->assertEquals('uid' . self::$NEXT_ID, (string)$value);
    $testObject2 = new Document();
    self::$NEXT_ID++;
    $this->assertEquals('uid' . self::$NEXT_ID, (string)$testObject2->id);
    $this->testObject->id = Str::set('newid');
    $this->assertEquals('newid', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::title.
   * - assert property 'title' is gettable
   * - assert property 'title' is settable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see ramp.model.business.BusinessModel#method_set_title ramp\model\business\BusinessModel::title
   * @see ramp.model.business.BusinessModel#method_get_title ramp\model\business\BusinessModel::title
   */
  public function testGetSet_title() : void
  {
    $value = $this->testObject->title;
    $this->assertNull($value);
    $this->testObject->title = Str::set('newTitle');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->title);
    $this->assertEquals('newTitle', (string)$this->testObject->title);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::heading and
   * \ramp\model\business\BusinessModel::label.
   * - assert property 'heading' is settable
   * - assert property 'heading' is gettable
   * - assert property 'label' is gettable (synonym)
   * - assert property 'label' is settable (synonym)
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see ramp.model.business.BusinessModel#method_set_heading ramp\model\business\BusinessModel::heading
   * @see ramp.model.business.BusinessModel#method_get_heading ramp\model\business\BusinessModel::heading
   * @see ramp.model.business.BusinessModel#method_set_label ramp\model\business\BusinessModel::label
   * @see ramp.model.business.BusinessModel#method_get_label ramp\model\business\BusinessModel::label
   */
  public function testGetSet_headingLabel() : void
  {
    $this->testObject->heading = Str::set('Heading');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->heading);
    $this->assertEquals('Heading', (string)$this->testObject->heading);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->label);
    $this->assertEquals('Heading', (string)$this->testObject->label);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->label);
    $this->assertEquals('Heading', (string)$this->testObject->label);
    $this->testObject->label = Str::set('Label');
    $this->assertEquals('Label', (string)$this->testObject->heading);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::summary and
   * \ramp\model\business\BusinessModel::placeholder.
   * - assert property 'summary' is settable
   * - assert property 'summary' is gettable
   * - assert property 'placeholder' is gettable (synonym)
   * - assert property 'placeholder' is settable (synonym)
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see ramp.model.business.BusinessModel#method_set_summary ramp\model\business\BusinessModel::summary
   * @see ramp.model.business.BusinessModel#method_get_summary ramp\model\business\BusinessModel::summary
   * @see ramp.model.business.BusinessModel#method_set_placeholder ramp\model\business\BusinessModel::placeholder
   * @see ramp.model.business.BusinessModel#method_get_placeholder ramp\model\business\BusinessModel::placeholder
   */
  public function testGetSet_summaryLabel() : void
  {
    $this->testObject->summary = Str::set('Heading');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->summary);
    $this->assertEquals('Heading', (string)$this->testObject->summary);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->placeholder);
    $this->assertEquals('Heading', (string)$this->testObject->placeholder);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->placeholder);
    $this->assertEquals('Heading', (string)$this->testObject->placeholder);
    $this->testObject->placeholder = Str::set('Label');
    $this->assertEquals('Label', (string)$this->testObject->summary);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::style.
   * - assert property 'style' is gettable
   * - assert property 'style' is settable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see ramp.model.business.BusinessModel#method_set_style ramp\model\business\BusinessModel::style
   * @see ramp.model.business.BusinessModel#method_get_style ramp\model\business\BusinessModel::style
   */
  public function testGetSet_style() : void
  {
    $value = $this->testObject->style;
    $this->assertNull($value);
    $this->testObject->style = Str::set('style');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->style);
    $this->assertEquals('style', (string)$this->testObject->style);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::clone().
   * - assert clone is instance of {@see \ramp\model\document\Document}.
   * - assert clone id has been set to next avalible uniqie id
   * - assert clone has same properties as cloned excluding id
   * - assert clone property values matches expected results.
   * @see ramp.model.business.BusinessModel#method_clone ramp\model\business\BusinessModel::clone()
   */
  public function testClone() : void
  {
    $expectedTitle = Str::set('clonedTitle');
    $expectedHeading = Str::set('clonedHeading');
    $expectedSummary = Str::set('clonedSummary');
    $expectedStyle = Str::set('clonedStyle');
    $this->testObject->title = $expectedTitle;
    $this->testObject->heading = $expectedHeading;
    $this->testObject->summary = $expectedSummary;
    $this->testObject->style = $expectedStyle;
    $cloneObject = clone $this->testObject;
    $this->assertInstanceOf('\ramp\model\Document', $cloneObject);
    $this->assertInstanceOf('\ramp\core\Str', $cloneObject->id);
    $this->assertNotEquals((string)$this->testObject->id, (string)$cloneObject->id);
    $this->assertEquals('uid' . ++self::$NEXT_ID, (string)$cloneObject->id);
    $this->assertInstanceOf('\ramp\core\Str', $cloneObject->title);
    $this->assertSame($this->testObject->title, $cloneObject->title);
    $this->assertEquals((string)$expectedTitle, (string)$cloneObject->title);
    $this->assertInstanceOf('\ramp\core\Str', $cloneObject->heading);
    $this->assertSame($this->testObject->heading, $cloneObject->heading);
    $this->assertEquals((string)$expectedHeading, (string)$cloneObject->heading);
    $this->assertInstanceOf('\ramp\core\Str', $cloneObject->summary);
    $this->assertSame($this->testObject->summary, $cloneObject->summary);
    $this->assertEquals((string)$expectedSummary, (string)$cloneObject->summary);
    $this->assertInstanceOf('\ramp\core\Str', $cloneObject->style);
    $this->assertSame($this->testObject->style, $cloneObject->style);
    $this->assertEquals((string)$expectedStyle, (string)$cloneObject->style);
  }
}
