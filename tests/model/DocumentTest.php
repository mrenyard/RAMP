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

require_once '/usr/share/php/tests/ramp/model/ModelTest.php';

require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/model/Document.class.php';

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\model\Document;

/**
 * Collection of tests for \ramp\model\document\Document.
 */
class DocumentTest extends \tests\ramp\model\ModelTest
{
  protected static $NEXT_ID = 1;

  #region Setup
  #[\Override]
  protected function preSetup() : void { self::$NEXT_ID = 1; Document::reset(); }
  #[\Override]
  protected function getTestObject() : RAMPObject { return new Document(); }
  #endregion

  /**
   * Collection of assertions for \ramp\model\document\Document.
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\model\Model}
   * - assert is instance of {@see \ramp\model\document\Document}
   * @see \ramp\model\document\Document
   */
  #[\Override]
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\model\Document', $this->testObject);
  }

  #region Inherited Tests
  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__set().
   * - assert {@see ramp\core\PropertyNotSetException} thrown when unable to set undefined or inaccessible property
   * @see \ramp\model\Model::__set()
   */
  #[\Override]
  public function testPropertyNotSetExceptionOn__set() : void
  {
    parent::testPropertyNotSetExceptionOn__set();
  }

  /**
   * Bad property (name) NOT accessable on \ramp\model\Model::__get().
   * - assert {@see \ramp\core\BadPropertyCallException} thrown when calling undefined or inaccessible property
   * @see \ramp\model\Model::__get()
   */
  #[\Override]
  public function testBadPropertyCallExceptionOn__get() : void
  {
    parent::testBadPropertyCallExceptionOn__get();
  }

  /**
   * Check property access through get and set methods.
   * - assert get returns same as set.
   * ```php
   * $value = $object->aProperty
   * $object->aProperty = $value
   * ```
   * @see \ramp\core\RAMPObject::__set()
   * @see \ramp\core\RAMPObject::__get()
   */
  #[\Override]
  public function testAccessPropertyWith__set__get() : void
  {
    parent::testAccessPropertyWith__set__get();
  }

  /**
   * Correct return of ramp\model\Model::__toString().
   * - assert {@see \ramp\model\Model::__toString()} returns string 'class name'
   * @see \ramp\model\Model::__toString()
   */
  #[\Override]
  public function testToString() : void
  {
    parent::testToString();
  }
  #endregion

  #region New Specialist Tests
  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::id.
   * - assert property 'id' is gettable
   * - assert property 'id' is settable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see \ramp\model\business\BusinessModel::id
   * @see \ramp\model\business\BusinessModel::id
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
   * @see \ramp\model\business\BusinessModel::title
   * @see \ramp\model\business\BusinessModel::title
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
   * @see \ramp\model\business\BusinessModel::heading
   * @see \ramp\model\business\BusinessModel::heading
   * @see \ramp\model\business\BusinessModel::label
   * @see \ramp\model\business\BusinessModel::label
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
   * @see \ramp\model\business\BusinessModel::summary
   * @see \ramp\model\business\BusinessModel::summary
   * @see \ramp\model\business\BusinessModel::placeholder
   * @see \ramp\model\business\BusinessModel::placeholder
   */
  public function testGetSet_summaryPlaceholder() : void
  {
    $this->testObject->summary = Str::set('Heading');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->summary);
    $this->assertEquals('Heading', (string)$this->testObject->summary);
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->placeholder);
    $this->assertEquals('Heading', (string)$this->testObject->placeholder);
    $this->testObject->placeholder = Str::set('Label');
    $this->assertEquals('Label', (string)$this->testObject->summary);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::extendedSummary.
   * - assert property 'extendedSummary' is settable
   * - assert property 'extendedSummary' is gettable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see \ramp\model\business\BusinessModel::extendedSummary
   * @see \ramp\model\business\BusinessModel::extendedSummary
   */
  public function testGetSet_extendedSummary() : void
  {
    $this->testObject->extendedSummary = Str::set('An extended summary');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->extendedSummary);
    $this->assertEquals('An extended summary', (string)$this->testObject->extendedSummary);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::extendedContent.
   * - assert property 'extendedContent' is settable
   * - assert property 'extendedContent' is gettable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see \ramp\model\business\BusinessModel::extendedContent
   * @see \ramp\model\business\BusinessModel::extendedContent
   */
  public function testGetSet_extendedContent() : void
  {
    $this->testObject->extendedContent = Str::set('An extended content');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->extendedContent);
    $this->assertEquals('An extended content', (string)$this->testObject->extendedContent);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::footnote.
   * - assert property 'footnote' is settable
   * - assert property 'footnote' is gettable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see \ramp\model\business\BusinessModel::footnote
   * @see \ramp\model\business\BusinessModel::footnote
   */
  public function testGetSet_footnote() : void
  {
    $this->testObject->footnote = Str::set('Some footnotes');
    $this->assertInstanceOf('\ramp\core\Str', $this->testObject->footnote);
    $this->assertEquals('Some footnotes', (string)$this->testObject->footnote);
  }

  /**
   * Collection of assertions for \ramp\model\business\BusinessModel::style.
   * - assert property 'style' is gettable
   * - assert property 'style' is settable
   * - assert returned value instance of {@see \ramp\core\Str}.
   * - assert returned value matches expected results.
   * @see \ramp\model\business\BusinessModel::style
   * @see \ramp\model\business\BusinessModel::style
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
   * @see \ramp\model\business\BusinessModel::clone()
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
  #endegion
}
