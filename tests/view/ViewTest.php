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
namespace tests\svelte\view;

//require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/view/View.class.php';

require_once '/usr/share/php/tests/svelte/view/mocks/ViewTest/MockView.class.php';
//require_once '/usr/share/php/tests/svelte/view/mocks/ViewTest/MockModel.class.php';
//require_once '/usr/share/php/tests/svelte/model/MockNoCountModel.class.php';
//require_once '/usr/share/php/tests/svelte/model/MockIterableModel.class.php';

use tests\svelte\view\mocks\ViewTest\MockView;
//use tests\svelte\view\mocks\ViewTest\MockModel;
//use tests\svelte\model\MockNoCountModel;
//use tests\svelte\model\MockIterableModel;

use svelte\view\View;
use svelte\core\PropertyNotSetException;
use svelte\core\BadPropertyCallException;

/**
 * Collection of tests for \svelte\view\View.
 */
class ViewTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  //private $mockViewCollection;
  //private $mockModel;

  public function setUp()
  {
    $this->testObject = new MockView();
/*
    $this->mockViewCollection = new \SplObjectStorage();
    $this->mockViewCollection->attach(new MockViewA());
    $this->mockViewCollection->attach(new MockViewB());
    $this->mockViewCollection->attach(new MockViewC());

    $this->mockModel = new MockModel();*/
  }

  /**
   * Collection of assertions for \svelte\view\View::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * @link svelte.view.View svelte\view\View
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\view\View', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\view\View::__get().
   * - assert BadPropertyCallException throw when property does NOT exist
   * - assert get `Object->aProperty` returns same as set `Object->aProperty = $value`
   *
  public function test__get()
  {
    $value = 'aValue';
    try {
      $this->testObject->noProperty = $value;
    } catch (PropertyNotSetException $expected) {
      try {
        $value = $this->testObject->noProperty;
      } catch (BadPropertyCallException $expected) {
        $this->testObject->aProperty = $value;
        $this->assertSame($value, $this->testObject->aProperty);
        return;
      }
      $this->fail('An expected BadPropertyCallException has NOT been raised.');
    }
    $this->fail('An expected PropertyNotSetException has NOT been raised.');
  }*/

  /**
   * Collection of assertions for {@link View#get_children}.
   * - assert .
   *
  public function testAddGet_children()
  {
    $i=0;
    foreach ($this->mockViewCollection as $view) {

      $this->testObject->add($view); $i++;

      ob_start();
      $this->testObject->children;
      $output = ob_get_clean();

      if ($i === 1) {
        $this->assertSame('tests\svelte\view\MockViewA ', $output);
      }

      if ($i === 2) {
        $this->assertSame('tests\svelte\view\MockViewA tests\svelte\view\MockViewB ', $output);
      }

      if ($i === 3) {
        $this->assertSame(
          'tests\svelte\view\MockViewA tests\svelte\view\MockViewB tests\svelte\view\MockViewC ',
          $output
        );
      }
    }
  }*/

  /**
   * Collection of assertions for {@link View#setModel}.
   * - assert .
   *
  public function testSetModelGet_property()
  {
    $value = 'bValue';
    $this->mockModel->bProperty = $value;
    $this->testObject->setModel($this->mockModel);
    $this->assertSame($value, $this->testObject->bProperty);
  }*/

  /**
   * Collection of assertions for {@link View#setModel}.
   * - assert .
   *
  public function testSetModelNoCount()
  {
    $noCountMode = new MockNoCountModel();
    try {
      $this->testObject->setModel($noCountMode);
    } catch (\LogicException $expected) { return; }
    $this->fail('An expected \LogicalException has NOT been raised.');
  }*/

  /**
   * Collection of assertions for {@link View#setModel}.
   * - assert .
   *
  public function testSetModelComplex1()
  {
    foreach ($this->mockViewCollection as $view) {
      $this->testObject->add($view);
    }

    $model = new MockIterableModel(); $model->bProperty = 'parent';
    $subModel1 = new MockModel(); $subModel1->bProperty = 'one'; $model->add($subModel1);
    $subModel2 = new MockModel(); $subModel2->bProperty = 'two'; $model->add($subModel2);
    $subModel3 = new MockModel(); $subModel3->bProperty = 'three'; $model->add($subModel3);
    $this->testObject->setModel($model);

    ob_start();
    $this->testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\svelte\view\MockView:parent '.
      'tests\svelte\view\MockViewA:one '.
      'tests\svelte\view\MockViewB:two '.
      'tests\svelte\view\MockViewC:three ',
      $output
    );
  }*/

  /**
   * Collection of assertions for {@link View#setModel}.
   * - assert .
   *
  public function testSetModelComplex2()
  {

    foreach ($this->mockViewCollection as $view) {
      $this->testObject->add($view);
    }

    $model = new MockIterableModel(); $model->bProperty = 'parent';
    $subModel1 = new MockModel(); $subModel1->bProperty = 'one'; $model->add($subModel1);
    $subModel2 = new MockModel(); $subModel2->bProperty = 'two'; $model->add($subModel2);
    $subModel3 = new MockModel(); $subModel3->bProperty = 'three'; $model->add($subModel3);
    $subModel4 = new MockModel(); $subModel4->bProperty = 'four'; $model->add($subModel4);
    $subModel5 = new MockModel(); $subModel5->bProperty = 'five'; $model->add($subModel5);
    $subModel6 = new MockModel(); $subModel6->bProperty = 'six'; $model->add($subModel6);
    $this->testObject->setModel($model);

    ob_start();
    $this->testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\svelte\view\MockView:parent '.
      'tests\svelte\view\MockViewA:one '.
      'tests\svelte\view\MockViewB:two '.
      'tests\svelte\view\MockViewC:three '.
      'tests\svelte\view\MockViewA:four '.
      'tests\svelte\view\MockViewB:five '.
      'tests\svelte\view\MockViewC:six ',
      $output
    );
  }*/

  /**
   * Collection of assertions for {@link }.
   * - assert .
   *
  public function testSetModelHierarchy1()
  {
    $viewA = new MockViewA(); $viewB = new MockViewB(); $viewC = new MockViewC();
    $viewA->add($viewB); $viewB->add($viewC);

    $model1 = new MockIterableModel(); $model1->bProperty = 'one';
    $model2 = new MockIterableModel(); $model2->bProperty = 'two';
    $model3 = new MockIterableModel(); $model3->bProperty = 'three';
    $model4 = new MockIterableModel(); $model4->bProperty = 'four';
    $model5 = new MockIterableModel(); $model5->bProperty = 'five';
    $model6 = new MockIterableModel(); $model6->bProperty = 'six';

      $model1->add($model2);
        $model2->add($model3);
        $model2->add($model4);
      $model1->add($model5);
        $model5->add($model6);

    $viewA->setModel($model1);

    ob_start();
    $viewA->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\svelte\view\MockViewA:one '.
      'tests\svelte\view\MockViewB:two '.
      'tests\svelte\view\MockViewC:three '.
      'tests\svelte\view\MockViewC:four '.
      'tests\svelte\view\MockViewB:five '.
      'tests\svelte\view\MockViewC:six ',
      $output
    );
  }*/

  /**
   * Collection of assertions for {@link }.
   * - assert .
   *
  public function testSetModelHierarchy2()
  {
    $viewA = new MockViewA(); $viewB = new MockViewB();
    $viewC = new MockViewC(); $viewD = new MockViewD();
    $viewA->add($viewB); $viewB->add($viewC); $viewC->add($viewD);

    $model1 = new MockIterableModel(); $model1->bProperty = 'one';
    $model2 = new MockIterableModel(); $model2->bProperty = 'two';
    $model3 = new MockIterableModel(); $model3->bProperty = 'three';
    $model4 = new MockIterableModel(); $model4->bProperty = 'four';
    $model5 = new MockIterableModel(); $model5->bProperty = 'five';
    $model6 = new MockIterableModel(); $model6->bProperty = 'six';

    $model7 = new MockIterableModel(); $model7->bProperty = 'seven';
    $model8 = new MockIterableModel(); $model8->bProperty = 'eight';
    $model9 = new MockIterableModel(); $model9->bProperty = 'nine';
    $model10 = new MockIterableModel(); $model10->bProperty = 'ten';
    $model11 = new MockIterableModel(); $model11->bProperty = 'eleven';
    $model12 = new MockIterableModel(); $model12->bProperty = 'twelve';

      $model1->add($model2);
        $model2->add($model3);
          $model3->add($model7);
          $model3->add($model10);
        $model2->add($model4);
          $model4->add($model8);
          $model4->add($model11);
      $model1->add($model5);
        $model5->add($model6);
          $model6->add($model9);
          $model6->add($model12);

    $viewA->setModel($model1);

    ob_start();
    $viewA->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\svelte\view\MockViewA:one '.
      'tests\svelte\view\MockViewB:two '.
      'tests\svelte\view\MockViewC:three '.
      'tests\svelte\view\MockViewD:seven '.
      'tests\svelte\view\MockViewD:ten '.
      'tests\svelte\view\MockViewC:four '.
      'tests\svelte\view\MockViewD:eight '.
      'tests\svelte\view\MockViewD:eleven '.
      'tests\svelte\view\MockViewB:five '.
      'tests\svelte\view\MockViewC:six '.
      'tests\svelte\view\MockViewD:nine '.
      'tests\svelte\view\MockViewD:twelve ',
      $output
    );
  }*/

  /**
   * Collection of assertions for {@link View#__clone}.
   * - assert .
   *
  public function test__clone()
  {
    $clone = clone $this->testObject;
    $this->assertNotSame($this->testObject, $clone);
    $this->assertEquals($this->testObject, $clone);
    unset($clone);

    $this->testObject->setModel($this->mockModel);
    $clone = clone $this->testObject;
    $this->assertNotSame($this->testObject, $clone);
    $this->assertNotEquals($this->testObject, $clone);
    $clone->setModel($this->mockModel);
    $this->assertEquals($this->testObject, $clone);
    $this->assertNotSame($this->testObject, $clone);
    unset($clone);
  }*/
}
