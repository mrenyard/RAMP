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
namespace tests\ramp\view;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/PropertyNotSetException.class.php';
require_once '/usr/share/php/ramp/core/BadPropertyCallException.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/RecordCollection.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/RootView.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';
require_once '/usr/share/php/ramp/view/ComplexView.class.php';

require_once '/usr/share/php/tests/ramp/view/mocks/ComplexViewTest/MockView.class.php';
require_once '/usr/share/php/tests/ramp/view/mocks/ComplexViewTest/MockModel.class.php';
require_once '/usr/share/php/tests/ramp/view/mocks/ComplexViewTest/MockNoCountModel.class.php';
require_once '/usr/share/php/tests/ramp/view/mocks/ComplexViewTest/MockModelCollection.class.php';

use tests\ramp\view\mocks\ComplexViewTest\MockModel;
use tests\ramp\view\mocks\ComplexViewTest\MockView;
use tests\ramp\view\mocks\ComplexViewTest\MockViewA;
use tests\ramp\view\mocks\ComplexViewTest\MockViewB;
use tests\ramp\view\mocks\ComplexViewTest\MockViewC;
use tests\ramp\view\mocks\ComplexViewTest\MockViewD;
use tests\ramp\view\mocks\ComplexViewTest\MockNoCountModel;
use tests\ramp\view\mocks\ComplexViewTest\MockModelCollection;

use ramp\view\View;
use ramp\view\RootView;
use ramp\view\ComplexView;
use ramp\core\PropertyNotSetException; 
use ramp\core\BadPropertyCallException;

/**
 * Collection of tests for \ramp\view\View.
 */
class ComplexViewTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for \ramp\view\ComplexView::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\ComplexView}
   * @see ramp.view.View ramp\view\View
   */
  public function test__construct()
  {
    $testObject = new MockView(RootView::getInstance());
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\view\View', $testObject);
    $this->assertInstanceOf('\ramp\view\ComplexView', $testObject);
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::__get() and \ramp\view\ComplexView::__set
   * - assert BadPropertyCallException thrown when trying to get value pre model set.
   * - assert PropertyNotSetException thrown when trying to set value of none existant property
   * - assert BadPropertyCallException thrown when trying to get value of none existant property
   * - assert that property calls are passes to its component (contained) \ramp\model\Model
   * @see ramp.view.ComplexView#method__get ramp\view\ComplexView::__get()
   * @see ramp.view.ComplexView#method__set ramp\view\ComplexView::__set()
   */
  public function test__get__set()
  {
    $testObject = new MockView(RootView::getInstance());
    $mockModel = new MockModel();
    try {
      $testObject->aProperty;
    } catch (BadPropertyCallException $expected) {
      $testObject->setModel($mockModel);
      try {
        $value = 'aValue';
        $testObject->noProperty = $value;
      } catch (PropertyNotSetException $expected) {
        try {
          $value = $testObject->noProperty;
        } catch (BadPropertyCallException $expected) {
          $mockModel->aProperty = $value;
          $this->assertSame($value, $testObject->aProperty);
          $this->assertSame($mockModel->aProperty, $testObject->aProperty);
          return;
        }
        $this->fail('An expected BadPropertyCallException has NOT been raised.');
      }
    }
    $this->fail('An expected PropertyNotSetException has NOT been raised.');
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::children and \ramp\view\ComplexView::add(Model $model).
   * - assert each child view added sequentially
   * - assert View->children output maintains sequance and format
   * @see ramp.view.ComplexView#method_add ramp\view\ComplexView::add()
   * @see ramp.view.ComplexView#method_children ramp\view\ComplexView::children
   */
  public function testAddGet_children()
  {
    $testObject = new MockView(RootView::getInstance());

    $viewA = new MockViewA($testObject);
    $viewA->setModel(new MockModel());
    ob_start();
    $testObject->children;
    $output = ob_get_clean();
    $this->assertEquals(
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES: ',
      $output
    );

    $viewB = new MockViewB($testObject);
    ob_start();
    $testObject->children;
    $output = ob_get_clean();
    $this->assertEquals(
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES: '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:NO ',
      $output
    );

    $viewC = new MockViewC($testObject);
    $viewC->setModel(new MockModel());
    ob_start();
    $testObject->children;
    $output = ob_get_clean();
    $this->assertEquals(
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES: '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:NO '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES: ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::setModel}.
   * - assert Exception thrown when model already set.
   * @see ramp.view.ComplexView#method_setModel ramp\view\ComplexView::setModel()
   */
  public function testSetModelAlreadySet()
  {
    $testObject = new MockView(RootView::getInstance());
    $testObject->setModel(new MockModel);
    $this->expectException('\Exception');
    $this->expectExceptionMessage('model already set violation');
    $testObject->setModel(new mockModel);
  }


  /**
   * Collection of assertions for \ramp\view\ComplexView::setModel() and \ramp\view\ComplexView::hasModel.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   * @see ramp.view.ViewComplex#method_setModel ramp\view\ComplexView::setModel()
   */
  public function testSetModelNoCascade()
  {
    RootView::reset();
    $testObject = new MockView(RootView::getInstance());
    $viewA = new MockViewA($testObject);
    $viewB = new MockViewB($testObject);
    $viewC = new MockViewC($testObject);

    $model = new MockModelCollection();
    $model->bProperty = 'this';
    $subModel1 = new MockModel();
    $subModel1->bProperty = 'one';
    $model->add($subModel1);
    $subModel2 = new MockModel();
    $subModel2->bProperty = 'two';
    $model->add($subModel2);
    $subModel3 = new MockModel();
    $subModel3->bProperty = 'three';
    $model->add($subModel3);

    $this->assertFalse($testObject->hasModel);
    $testObject->setModel($model, FALSE);
    $this->assertTrue($testObject->hasModel);

    ob_start();
    $testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\ramp\view\mocks\ComplexViewTest\MockView:YES:this '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:NO '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:NO '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:NO ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::setModel().
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * - assert each view added sequentially as expected
   * - assert output from View->render() maintains sequance
   * @see ramp.view.ComplexView#method_setModel ramp\view\ComplexView::setModel()
   */
  public function testSetModelSequentially1()
  {
    RootView::reset();
    $testObject = new MockView(RootView::getInstance());
    $viewA = new MockViewA($testObject);
    $viewB = new MockViewB($testObject);
    $viewC = new MockViewC($testObject);

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
    
    $testObject->setModel($model);

    ob_start();
    $testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\ramp\view\mocks\ComplexViewTest\MockView:YES:parent '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES:one '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:YES:two '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES:three ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::setModel() and \ramp\view\ComplexView::hasModel.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * - assert each view added sequentially as expected
   * - assert output from View->render() maintains sequance
   * @see ramp.view.ComplexView#method_setModel ramp\view\ComplexView::setModel()
   */
  public function testSetModelSequentially2()
  {
    RootView::reset();
    $testObject = new MockView(RootView::getInstance());
    $viewA = new MockViewA($testObject);
    $viewB = new MockViewB($testObject);
    $viewC = new MockViewC($testObject);

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

    $testObject->setModel($model);

    ob_start();
    $testObject->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\ramp\view\mocks\ComplexViewTest\MockView:YES:parent '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES:one '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:YES:two '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES:three '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES:four '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:YES:five '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES:six ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::setModel() and \ramp\view\ComplexView::hasModel.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   * @see ramp.view.ComplexView#method_setModel ramp\view\ComplexView::setModel()
   */
  public function testSetModelHierarchy1()
  {
    RootView::reset();
    $viewA = new MockViewA(RootView::getInstance());
    $viewB = new MockViewB($viewA);
    $viewC = new MockViewC($viewB);
    $viewD = new MockViewD($viewC);

    $modelCollection1 = new MockModelCollection();
    $modelCollection2 = new MockModelCollection();
    $modelCollection3 = new MockModelCollection();

    $model1 = new MockModel($modelCollection1);
    $model1->bProperty = 'one';
    $model2 = new MockModel($modelCollection2);
    $model2->bProperty = 'two';
    $modelCollection1->add($model2);
    $model3 = new MockModel($modelCollection3);
    $model3->bProperty = 'three';
    $modelCollection2->add($model3);
    $model4 = new MockModel();
    $model4->bProperty = 'four';
    $modelCollection3->add($model4);

    $viewA->setModel($model1);

    ob_start();
    RootView::getInstance()->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES:one '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:YES:two '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES:three '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewD:YES:four ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::setModel() and \ramp\view\ComplexView::hasModel.
   * - Prior to model set hasModel returns FALSE and post set TRUE
   * - assert each view added sequentially and hieratically as expected
   * - assert output from View->render() maintains sequance and hieratically format
   * @see ramp.view.ComplexView#method_setModel ramp\view\ComplexView::setModel()
   */
  public function testSetModelHierarchy2()
  {
    RootView::reset();
    $viewA = new MockViewA(RootView::getInstance());
    $viewB = new MockViewB($viewA);
    $viewC = new MockViewC($viewB);
    $viewD = new MockViewD($viewC);

    $modelCollectionA = new MockModelCollection();
    $modelCollectionB1 = new MockModelCollection();
    $modelCollectionB2 = new MockModelCollection();
    $modelCollectionC1 = new MockModelCollection();
    $modelCollectionC2 = new MockModelCollection();
    
    $model1 = new MockModel($modelCollectionA);
    $model1->bProperty = 'one';
      $model2 = new MockModel($modelCollectionB1);
      $model2->bProperty = 'two';
      $modelCollectionA->add($model2);
        $model3 = new MockModel($modelCollectionC1);
        $model3->bProperty = 'three';
        $modelCollectionB1->add($model3);
          $model4 = new MockModel();
          $model4->bProperty = 'four';
          $modelCollectionC1->add($model4);
    $model5 = new MockModel($modelCollectionB2);
      $model5->bProperty = 'five';
      $modelCollectionA->add($model5);
        $model6 = new MockModel($modelCollectionC2);
        $model6->bProperty = 'six';
        $modelCollectionB2->add($model6);
          $model7 = new MockModel();
          $model7->bProperty = 'seven';
          $modelCollectionC2->add($model7);
          $model8 = new MockModel();
          $model8->bProperty = 'eight';
          $modelCollectionC2->add($model8);

    $viewA->setModel($model1);

    ob_start();
    RootView::getInstance()->render();
    $output = ob_get_clean();

    $this->assertSame(
      'tests\ramp\view\mocks\ComplexViewTest\MockViewA:YES:one '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:YES:two '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES:three '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewD:YES:four '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewB:YES:five '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewC:YES:six '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewD:YES:seven '.
      'tests\ramp\view\mocks\ComplexViewTest\MockViewD:YES:eight ',
      $output
    );
  }

  /**
   * Collection of assertions for \ramp\view\ComplexView::__clone.
   * - assert cloned View without associated model is equal to the original
   * - assert cloned View with associated model NOT equal as Model association removed
   * - assert cloned View with model re associated is equal to the original 
   * @see ramp.view.ComplexView#method__clone ramp\view\ComplexView::__clone()
   */
  public function test__clone()
  {
    $testObject = new MockView(RootView::getInstance());
    $clone = clone $testObject;
    $this->assertNotSame($testObject, $clone);
    $this->assertEquals($testObject, $clone);
    unset($clone);

    $testObject->setModel(new MockModel());
    $clone = clone $testObject;
    $this->assertNotSame($testObject, $clone);
    $clone->setModel(new MockModel());
    $this->assertEquals($testObject, $clone);
    $this->assertNotSame($testObject, $clone);
    unset($clone);
  }
}
