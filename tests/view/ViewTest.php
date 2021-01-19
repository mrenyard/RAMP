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

require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/core/iOption.class.php';
require_once '/usr/share/php/svelte/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/svelte/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/model/Model.class.php';
require_once '/usr/share/php/svelte/model/business/BusinessModel.class.php';
require_once '/usr/share/php/svelte/model/business/Record.class.php';
require_once '/usr/share/php/svelte/model/business/RecordCollection.class.php';
require_once '/usr/share/php/svelte/view/View.class.php';

require_once '/usr/share/php/tests/svelte/view/mocks/ViewTest/MockView.class.php';
require_once '/usr/share/php/tests/svelte/view/mocks/ViewTest/MockModel.class.php';
require_once '/usr/share/php/tests/svelte/view/mocks/ViewTest/MockNoCountModel.class.php';
require_once '/usr/share/php/tests/svelte/view/mocks/ViewTest/MockModelCollection.class.php';

use tests\svelte\view\mocks\ViewTest\MockModel;
use tests\svelte\view\mocks\ViewTest\MockView;
use tests\svelte\view\mocks\ViewTest\MockViewA;
use tests\svelte\view\mocks\ViewTest\MockViewB;
use tests\svelte\view\mocks\ViewTest\MockViewC;
use tests\svelte\view\mocks\ViewTest\MockViewD;
use tests\svelte\view\mocks\ViewTest\MockNoCountModel;
use tests\svelte\view\mocks\ViewTest\MockModelCollection;

use svelte\view\View;
use svelte\core\PropertyNotSetException; 
use svelte\core\BadPropertyCallException;

/**
 * Collection of tests for \svelte\view\View.
 */
class ViewTest extends \PHPUnit\Framework\TestCase
{
  private $testObject;
  private $mockViewCollection;
  private $mockModel;

  /**
   * Setup test articles
   */
  public function setUp() : void
  {
    $this->testObject = new MockView();
    $this->mockViewCollection = new \SplObjectStorage();
    $this->mockViewCollection->attach(new MockViewA());
    $this->mockViewCollection->attach(new MockViewB());
    $this->mockViewCollection->attach(new MockViewC());
    $this->mockModel = new MockModel();
  }

  /**
   * Collection of assertions for \svelte\view\View::__construct().
   * - assert is instance of {@link \svelte\core\SvelteObject}
   * - assert is instance of {@link \svelte\view\View}
   * @link svelte.view.View svelte\view\View
   */
  public function test__construct()
  {
    $this->assertInstanceOf('\svelte\core\SvelteObject', $this->testObject);
    $this->assertInstanceOf('\svelte\view\View', $this->testObject);
  }

  /**
   * Collection of assertions for \svelte\view\View::__get() and \svelte\view\View::__set
   * - assert BadPropertyCallException thrown when trying to get value pre model set.
   * - assert PropertyNotSetException thrown when trying to set value of none existant property
   * - assert BadPropertyCallException thrown when trying to get value of none existant property
   * - assert that property calls are passes to its component (contained) \svelte\model\Model
   */
  public function test__get__set()
  {
    try {
      $this->testObject->aProperty;
    } catch (BadPropertyCallException $expected) {
      $this->testObject->setModel($this->mockModel);
      try {
        $value = 'aValue';
        $this->testObject->noProperty = $value;
      } catch (PropertyNotSetException $expected) {
        try {
          $value = $this->testObject->noProperty;
        } catch (BadPropertyCallException $expected) {
          $this->mockModel->aProperty = $value;
          $this->assertSame($value, $this->testObject->aProperty);
          $this->assertSame($this->mockModel->aProperty, $this->testObject->aProperty);
          return;
        }
        $this->fail('An expected BadPropertyCallException has NOT been raised.');
      }
    }
    $this->fail('An expected PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \svelte\view\View::children and \svelte\view\View::add(Model $model).
   * - assert each child view added sequentially
   * - assert View->children output maintains sequance and format
   */
  public function testAddGet_children()
  {
    $i=0;
    foreach ($this->mockViewCollection as $view)
    {
      $view->setModel(new MockModel());
      $this->testObject->add($view);
      $i++;
      ob_start();
      $this->testObject->children;
      $output = ob_get_clean();
      if ($i === 1)
      {
        $this->assertEquals(
          'tests\svelte\view\mocks\ViewTest\MockViewA ',
          $output
        );
      }
      if ($i === 2)
      {
        $this->assertEquals(
          'tests\svelte\view\mocks\ViewTest\MockViewA '.
          'tests\svelte\view\mocks\ViewTest\MockViewB ',
          $output
        );
      }
      if ($i === 3)
      {
        $this->assertEquals(
          'tests\svelte\view\mocks\ViewTest\MockViewA '.
          'tests\svelte\view\mocks\ViewTest\MockViewB '.
          'tests\svelte\view\mocks\ViewTest\MockViewC ',
          $output
        );
      }
    }
  }

  /**
   * Collection of assertions for \svelte\view\View::setModel}.
   * - assert Exception thrown when model already set.
   */
  public function testSetModelAlreadySet()
  {
    $this->testObject->setModel($this->mockModel);
    $this->expectException('\Exception');
    $this->expectExceptionMessage('model already set violation');
    $this->testObject->setModel($this->mockModel);
  }

  /**
   * Collection of assertions for \svelte\view\View::setModel}.
   * - assert LogicException thrown when model \Traversable but NOT \Countable.
   */
  public function testSetModelNoCount()
  {
    $this->expectException('\LogicException');
    $this->expectExceptionMessage('All Traversable Model(s) MUST also implement Countable');
    $this->testObject->setModel(new MockNoCountModel());
  }

  /**
   * Collection of assertions for \svelte\view\View::setModel().
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   */
  public function testSetModelComplex1()
  {
    foreach ($this->mockViewCollection as $view) {
      $this->testObject->add($view);
    }

    $model = new MockModelCollection();
    $model->bProperty = 'parent';
    $subModel1 = new MockModel();
    $subModel1->bProperty = 'one';
    $model->add($subModel1);
    $subModel2 = new MockModel();
    $subModel2->bProperty = 'two';
    $model->add($subModel2);
    $subModel3 = new MockModel();
    $subModel3->bProperty = 'three';
    $model->add($subModel3);
    
    $this->testObject->setModel($model);

    ob_start();
    $this->testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\svelte\view\mocks\ViewTest\MockView:parent '.
      'tests\svelte\view\mocks\ViewTest\MockViewA:one '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:two '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:three ',
      $output
    );
  }

  /**
   * Collection of assertions for \svelte\view\View::setModel().
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   */
  public function testSetModelComplex2()
  {

    foreach ($this->mockViewCollection as $view) {
      $this->testObject->add($view);
    }

    $model = new MockModelCollection();
    $model->bProperty = 'parent';
    $subModel1 = new MockModel();
    $subModel1->bProperty = 'one';
    $model->add($subModel1);
    $subModel2 = new MockModel();
    $subModel2->bProperty = 'two';
    $model->add($subModel2);
    $subModel3 = new MockModel();
    $subModel3->bProperty = 'three';
    $model->add($subModel3);
    $subModel4 = new MockModel();
    $subModel4->bProperty = 'four'; 
    $model->add($subModel4);
    $subModel5 = new MockModel();
    $subModel5->bProperty = 'five';
    $model->add($subModel5);
    $subModel6 = new MockModel();
    $subModel6->bProperty = 'six';
    $model->add($subModel6);

    $this->testObject->setModel($model);

    ob_start();
    $this->testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\svelte\view\mocks\ViewTest\MockView:parent '.
      'tests\svelte\view\mocks\ViewTest\MockViewA:one '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:two '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:three '.
      'tests\svelte\view\mocks\ViewTest\MockViewA:four '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:five '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:six ',
      $output
    );
  }

  /**
   * Collection of assertions for \svelte\view\View::setModel().
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   */
  public function testSetModelHierarchy1()
  {
    $viewA = new MockViewA();
    $viewB = new MockViewB();
    $viewC = new MockViewC();
    $viewA->add($viewB);
    $viewB->add($viewC);

    $model1 = new MockModelCollection();
    $model1->bProperty = 'one';
    $model2 = new MockModelCollection();
    $model2->bProperty = 'two';
    $model3 = new MockModel();
    $model3->bProperty = 'three';
    $model4 = new MockModel();
    $model4->bProperty = 'four';
    $model5 = new MockModelCollection();
    $model5->bProperty = 'five';
    $model6 = new MockModel();
    $model6->bProperty = 'six';

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
      'tests\svelte\view\mocks\ViewTest\MockViewA:one '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:two '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:three '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:four '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:five '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:six ',
      $output
    );
  }

  /**
   * Collection of assertions for \svelte\view\View::setModel.
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   */
  public function testSetModelHierarchy2()
  {
    $viewA = new MockViewA();
    $viewB = new MockViewB();
    $viewC = new MockViewC();
    $viewD = new MockViewD();
    $viewA->add($viewB);
    $viewB->add($viewC);
    $viewC->add($viewD);

    $model1 = new MockModelCollection();
    $model1->bProperty = 'one';
    $model2 = new MockModelCollection();
    $model2->bProperty = 'two';
    $model3 = new MockModelCollection();
    $model3->bProperty = 'three';
    $model4 = new MockModelCollection();
    $model4->bProperty = 'four';
    $model5 = new MockModelCollection();
    $model5->bProperty = 'five';
    $model6 = new MockModelCollection();
    $model6->bProperty = 'six';

    $model7 = new MockModel();
    $model7->bProperty = 'seven';
    $model8 = new MockModel();
    $model8->bProperty = 'eight';
    $model9 = new MockModel();
    $model9->bProperty = 'nine';
    $model10 = new MockModel();
    $model10->bProperty = 'ten';
    $model11 = new MockModel();
    $model11->bProperty = 'eleven';
    $model12 = new MockModel();
    $model12->bProperty = 'twelve';

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
      'tests\svelte\view\mocks\ViewTest\MockViewA:one '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:two '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:three '.
      'tests\svelte\view\mocks\ViewTest\MockViewD:seven '.
      'tests\svelte\view\mocks\ViewTest\MockViewD:ten '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:four '.
      'tests\svelte\view\mocks\ViewTest\MockViewD:eight '.
      'tests\svelte\view\mocks\ViewTest\MockViewD:eleven '.
      'tests\svelte\view\mocks\ViewTest\MockViewB:five '.
      'tests\svelte\view\mocks\ViewTest\MockViewC:six '.
      'tests\svelte\view\mocks\ViewTest\MockViewD:nine '.
      'tests\svelte\view\mocks\ViewTest\MockViewD:twelve ',
      $output
    );
  }

  /**
   * Collection of assertions for \svelte\view\View::__clone.
   * - assert cloned View without associated model is equal to the original
   * - assert cloned View with associated model NOT equal as Model association removed
   * - assert cloned View with model re associated is equal to the original 
   */
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
  }
}
