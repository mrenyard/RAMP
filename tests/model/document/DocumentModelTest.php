<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */
namespace tests\svelte\model;

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/document/DocumentModel.class.php';

use svelte\core\Str;
use svelte\model\document\DocumentModel;

/**
 * Collection of tests for \svelte\model\document\DocumentModel.
 */
class DocumentModelTest extends \PHPUnit\Framework\TestCase
{
  private static $NEXT_ID = 0;
  private $testObject;

  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    $this->testObject = new DocumentModel();
    self::$NEXT_ID++;
  }

  /**
   * Collection of assertions for \svelte\model\document\DocumentModel::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\model\Model}
   * - assert is instance of {@link \svelte\model\document\DocumentModel}
   * @link svelte.model.Model svelte\model\document\DocumentModel
   */
  public function testConstruct()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\model\Model', $this->testObject);
    $this->assertInstanceOf('\svelte\model\document\DocumentModel', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::id.
   * - assert property 'id' is gettable
   * - assert property 'id' is settable
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected results.
   * @link svelte.model.business.BusinessModel#method_set_id svelte\model\business\BusinessModel::id
   * @link svelte.model.business.BusinessModel#method_get_id svelte\model\business\BusinessModel::id
   */
  public function testGetSet_id()
  {
    $value = $this->testObject->id;
    $this->assertEquals('uid' . self::$NEXT_ID, (string)$value);
    $this->assertInstanceOf('\svelte\core\Str', $value);
    $testObject2 = new DocumentModel();
    self::$NEXT_ID++;
    $this->assertEquals('uid' . self::$NEXT_ID, (string)$testObject2->id);
    $this->testObject->id = Str::set('newid');
    $this->assertEquals('newid', (string)$this->testObject->id);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::title.
   * - assert property 'title' is gettable
   * - assert property 'title' is settable
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected results.
   * @link svelte.model.business.BusinessModel#method_set_title svelte\model\business\BusinessModel::title
   * @link svelte.model.business.BusinessModel#method_get_title svelte\model\business\BusinessModel::title
   */
  public function testGetSet_title()
  {
    $value = $this->testObject->title;
    $this->assertNull($value);
    $this->testObject->title = Str::set('newTitle');
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->title);
    $this->assertEquals('newTitle', (string)$this->testObject->title);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::heading and
   * \svelte\model\business\BusinessModel::label.
   * - assert property 'heading' is settable
   * - assert property 'heading' is gettable
   * - assert property 'label' is gettable (synonym)
   * - assert property 'label' is settable (synonym)
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected results.
   * @link svelte.model.business.BusinessModel#method_set_heading svelte\model\business\BusinessModel::heading
   * @link svelte.model.business.BusinessModel#method_get_heading svelte\model\business\BusinessModel::heading
   * @link svelte.model.business.BusinessModel#method_set_label svelte\model\business\BusinessModel::label
   * @link svelte.model.business.BusinessModel#method_get_label svelte\model\business\BusinessModel::label
   */
  public function testGetSet_headingLabel()
  {
    $this->testObject->heading = Str::set('Heading');
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->heading);
    $this->assertEquals('Heading', (string)$this->testObject->heading);
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->label);
    $this->assertEquals('Heading', (string)$this->testObject->label);
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->label);
    $this->assertEquals('Heading', (string)$this->testObject->label);
    $this->testObject->label = Str::set('Label');
    $this->assertEquals('Label', (string)$this->testObject->heading);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::summary and
   * \svelte\model\business\BusinessModel::placeholder.
   * - assert property 'summary' is settable
   * - assert property 'summary' is gettable
   * - assert property 'placeholder' is gettable (synonym)
   * - assert property 'placeholder' is settable (synonym)
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected results.
   * @link svelte.model.business.BusinessModel#method_set_summary svelte\model\business\BusinessModel::summary
   * @link svelte.model.business.BusinessModel#method_get_summary svelte\model\business\BusinessModel::summary
   * @link svelte.model.business.BusinessModel#method_set_placeholder svelte\model\business\BusinessModel::placeholder
   * @link svelte.model.business.BusinessModel#method_get_placeholder svelte\model\business\BusinessModel::placeholder
   */
  public function testGetSet_summaryLabel()
  {
    $this->testObject->summary = Str::set('Heading');
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->summary);
    $this->assertEquals('Heading', (string)$this->testObject->summary);
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->placeholder);
    $this->assertEquals('Heading', (string)$this->testObject->placeholder);
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->placeholder);
    $this->assertEquals('Heading', (string)$this->testObject->placeholder);
    $this->testObject->placeholder = Str::set('Label');
    $this->assertEquals('Label', (string)$this->testObject->summary);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::style.
   * - assert property 'style' is gettable
   * - assert property 'style' is settable
   * - assert returned value instance of {@link \svelte\core\Str}.
   * - assert returned value matches expected results.
   * @link svelte.model.business.BusinessModel#method_set_style svelte\model\business\BusinessModel::style
   * @link svelte.model.business.BusinessModel#method_get_style svelte\model\business\BusinessModel::style
   */
  public function testGetSet_style()
  {
    $value = $this->testObject->style;
    $this->assertNull($value);
    $this->testObject->style = Str::set('style');
    $this->assertInstanceOf('\svelte\core\Str', $this->testObject->style);
    $this->assertEquals('style', (string)$this->testObject->style);
  }

  /**
   * Collection of assertions for \svelte\model\business\BusinessModel::clone().
   * - assert clone is instance of {@link \svelte\model\document\DocumentModel}.
   * - assert clone id has been set to next avalible uniqie id
   * - assert clone has same properties as cloned excluding id
   * - assert clone property values matches expected results.
   * @link svelte.model.business.BusinessModel#method_clone svelte\model\business\BusinessModel::clone()
   */
  public function testClone()
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
    $this->assertInstanceOf('\svelte\model\document\DocumentModel', $cloneObject);
    $this->assertInstanceOf('\svelte\core\Str', $cloneObject->id);
    $this->assertNotEquals((string)$this->testObject->id, (string)$cloneObject);
    $this->assertEquals('uid' . ++self::$NEXT_ID, (string)$cloneObject->id);
    $this->assertInstanceOf('\svelte\core\Str', $cloneObject->title);
    $this->assertSame($this->testObject->title, $cloneObject->title);
    $this->assertEquals((string)$expectedTitle, (string)$cloneObject->title);
    $this->assertInstanceOf('\svelte\core\Str', $cloneObject->heading);
    $this->assertSame($this->testObject->heading, $cloneObject->heading);
    $this->assertEquals((string)$expectedHeading, (string)$cloneObject->heading);
    $this->assertInstanceOf('\svelte\core\Str', $cloneObject->summary);
    $this->assertSame($this->testObject->summary, $cloneObject->summary);
    $this->assertEquals((string)$expectedSummary, (string)$cloneObject->summary);
    $this->assertInstanceOf('\svelte\core\Str', $cloneObject->style);
    $this->assertSame($this->testObject->style, $cloneObject->style);
    $this->assertEquals((string)$expectedStyle, (string)$cloneObject->style);
  }
}
