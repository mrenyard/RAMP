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

require_once '/usr/share/php/ramp/view/ComplexView.class.php';

require_once '/usr/share/php/tests/ramp/mocks/view/MockComplexView.class.php';
require_once '/usr/share/php/tests/ramp/mocks/model/MockBusinessModel.class.php';

use ramp\core\RAMPObject;
use ramp\view\ChildView;
use ramp\view\RootView;

use tests\ramp\mocks\view\MockView;
use tests\ramp\mocks\view\MockViewA;
use tests\ramp\mocks\view\MockViewB;
use tests\ramp\mocks\view\MockViewC;
use tests\ramp\mocks\view\MockComplexView;
use tests\ramp\mocks\view\MockComplexViewA;
use tests\ramp\mocks\view\MockComplexViewB;
use tests\ramp\mocks\view\MockComplexViewC;
use tests\ramp\mocks\view\MockComplexViewD;
use tests\ramp\mocks\model\MockBusinessModel;

/**
 * Collection of tests for \ramp\view\ComplexView.
 */
class ComplexViewTest extends \tests\ramp\view\ChildViewTest
{
  #region Setup
  protected function preSetup() : void { RootView::reset(); }
  protected function getTestObject() : RAMPObject { return new MockComplexView(RootView::getInstance()); }
  protected function postSetup() : void { }
  #endregion

  /**
   * Default base constructor assertions \ramp\view\View::__construct().
   * - assert is instance of {@see \ramp\core\RAMPObject}
   * - assert is instance of {@see \ramp\view\View}
   * - assert is instance of {@see \ramp\view\ChildView}
   * - assert is instance of {@see \ramp\view\ComplexView}
   * @see \ramp\model\Model
   */
  public function testConstruct() : void
  {
    parent::testConstruct();
    $this->assertInstanceOf('\ramp\view\ComplexView', $this->testObject);
  }

  /**
   * Addition of sub views. 
   * - assert each child view added sequentially.
   * - assert View->children output maintains sequance and format.
   * @see \ramp\view\View::add()
   * @see \ramp\view\View::children
   */
  public function testSubViewAddition(string $parentRender = 'tests\ramp\mocks\view\MockComplexView ') : void
  {
    parent::postSetup();
    $this->testObject->viewOnlyTesting = TRUE;
    parent::testSubViewAddition($parentRender);
  }

  /**
   * Cloning copies sub views.
   * - assert cloned View without associated model is equal to the original
   * - assert cloned View with associated model NOT equal as Model association removed
   * - assert cloned View with model re associated is equal to the original 
   * @see \ramp\view\View::__clone()
   */
  public function testClone() : void
  {
    $clone = clone $this->testObject; 
    $this->assertNotSame($this->testObject, $clone);
    $this->assertEquals($this->testObject, $clone);
    $this->testObject->setModel(new MockBusinessModel());
    $clone = clone $this->testObject; 
    $this->assertNotSame($this->testObject, $clone);
    $this->assertNotEquals($this->testObject, $clone);
    $this->assertTrue($this->testObject->hasModel);
    $this->assertFalse($clone->hasModel);
  }

  /**
   * Check BadMethodCallException thrown when model already set.
   * - assert throws BadMethodCallException when model already set.
   *   - with message: *'model already set violation'*
   * @see \ramp\view\ComplexView::setModel()
   */
  public function testModelAlreadySetException() : void
  {
    $this->testObject->setModel(new MockBusinessModel());
    $this->expectException(\BadMethodCallException::class);
    $this->expectExceptionMessage('model already set violation');
    $this->testObject->setModel(new MockBusinessModel());
  }

  /**
   * Check read access to associated Model's properties.
   * - assert that property calls are passes to its component (contained) {@see \ramp\model\Model}.
   */
  public function testPassthroughProperties() : void
  {
    $expectedValue = 'aValue';
    $model = new MockBusinessModel(FALSE, $expectedValue);
    $this->testObject->setModel($model);
    $this->assertSame($expectedValue, $this->testObject->aProperty);
  }

  /**
   * Manage complex hierarchical and ordered model to view interlacing, as appropriate from *this* View definition.
   * - assert prior to model set hasModel returns FALSE and post set TRUE.
   * - assert when Sequentially model provided (x2 examples):
   *   - each view added sequentially as expected.
   *   - output from View->render() maintains ecpected sequance.
   * - assert when Hierarchy model provided (x2 examples):
   *   - each view added sequentially and hieratically as expected.
   *   - output from View->render() maintains sequance and hieratically format.
   */
  public function testComplexModelCascading() : void
  {
    new MockComplexViewA($this->testObject);
    new MockComplexViewB($this->testObject);
    new MockComplexViewC($this->testObject);
    $model1 = new MockBusinessModel(FALSE, 'Parent1');
    $sub1Model1 = new MockBusinessModel(FALSE, 'Value1A');
    $sub2Model1 = new MockBusinessModel(FALSE, 'Value1B');
    $sub3Model1 = new MockBusinessModel(FALSE, 'Value1C');
    $model1[$model1->count] = $sub1Model1;
    $model1[$model1->count] = $sub2Model1;
    $model1[$model1->count] = $sub3Model1;
    $this->assertFalse($this->testObject->hasModel);
    $this->testObject->setModel($model1);
    $this->assertTrue($this->testObject->hasModel);
    ob_start();
    RootView::getInstance()->render();
    $output = ob_get_clean();
    $this->assertSame(
      'tests\ramp\mocks\view\MockComplexView:Parent1 ' .
      'tests\ramp\mocks\view\MockComplexViewA:Value1A ' .
      'tests\ramp\mocks\view\MockComplexViewB:Value1B ' .
      'tests\ramp\mocks\view\MockComplexViewC:Value1C ',
      $output
    );

    RootView::reset();
    $testObject2 =  new MockComplexView(RootView::getInstance());
    new MockComplexViewA($testObject2);
    new MockComplexViewB($testObject2);
    new MockComplexViewC($testObject2);
    $model2 = new MockBusinessModel(FALSE, 'Parent2');
    $sub1Model2 = new MockBusinessModel(FALSE, 'ValueOne');
    $sub2Model2 = new MockBusinessModel(FALSE, 'ValueTwo');
    $sub3Model2 = new MockBusinessModel(FALSE, 'ValueThree');
    $sub4Model2 = new MockBusinessModel(FALSE, 'ValueFour');
    $sub5Model2 = new MockBusinessModel(FALSE, 'ValueFive');
    $sub6Model2 = new MockBusinessModel(FALSE, 'ValueSix');
    $model2[$model2->count] = $sub1Model2;
    $model2[$model2->count] = $sub2Model2;
    $model2[$model2->count] = $sub3Model2;
    $model2[$model2->count] = $sub4Model2;
    $model2[$model2->count] = $sub5Model2;
    $model2[$model2->count] = $sub6Model2;
    $this->assertFalse($testObject2->hasModel);
    $testObject2->setModel($model2);
    $this->assertTrue($testObject2->hasModel);
    ob_start();
    RootView::getInstance()->render();
    $output = ob_get_clean();
    $this->assertSame(
      'tests\ramp\mocks\view\MockComplexView:Parent2 ' .
      'tests\ramp\mocks\view\MockComplexViewA:ValueOne ' .
      'tests\ramp\mocks\view\MockComplexViewB:ValueTwo ' .
      'tests\ramp\mocks\view\MockComplexViewC:ValueThree ' .
      'tests\ramp\mocks\view\MockComplexViewA:ValueFour ' .
      'tests\ramp\mocks\view\MockComplexViewB:ValueFive ' .
      'tests\ramp\mocks\view\MockComplexViewC:ValueSix ',
      $output
    );

    RootView::reset();
    $testObject3 = new MockComplexView(RootView::getInstance());
    new MockComplexViewD(
      new MockComplexViewC(
        new MockComplexViewB(
          new MockComplexViewA(
            $testObject3
          )
        )
      )
    );
    $model3 = new MockBusinessModel(FALSE, 'Parent3');
    $sub1Model3 = new MockBusinessModel(FALSE, 'ValueOne');
    $sub2Model3 = new MockBusinessModel(FALSE, 'ValueTwo');
    $sub3Model3 = new MockBusinessModel(FALSE, 'ValueThree');
    $sub4Model3 = new MockBusinessModel(FALSE, 'ValueFour');
    $model3[$model3->count] = $sub1Model3;
    $sub1Model3[$sub1Model3->count] = $sub2Model3;
    $sub2Model3[$sub2Model3->count] = $sub3Model3;
    $sub3Model3[$sub3Model3->count] = $sub4Model3;
    $this->assertFalse($testObject3->hasModel);
    $testObject3->setModel($model3);
    $this->assertTrue($testObject3->hasModel);
    ob_start();
    RootView::getInstance()->render();
    $output = ob_get_clean();
    $this->assertSame(
      'tests\ramp\mocks\view\MockComplexView:Parent3 ' .
      'tests\ramp\mocks\view\MockComplexViewA:ValueOne ' .
      'tests\ramp\mocks\view\MockComplexViewB:ValueTwo ' .
      'tests\ramp\mocks\view\MockComplexViewC:ValueThree ' .
      'tests\ramp\mocks\view\MockComplexViewD:ValueFour ',
      $output
    );

    RootView::reset();
    $testObject4 = new MockComplexView(RootView::getInstance());
    new MockComplexViewD(
      new MockComplexViewC(
        new MockComplexViewB(
          new MockComplexViewA(
            $testObject4
          )
        )
      )
    );
    $model4 = new MockBusinessModel(FALSE, 'Parent4'); // ComplexView
    $sub1Model4 = new MockBusinessModel(FALSE, 'ValueOne');
    $sub2Model4 = new MockBusinessModel(FALSE, 'ValueTwo');
    $sub3Model4 = new MockBusinessModel(FALSE, 'ValueThree');
    $sub4Model4 = new MockBusinessModel(FALSE, 'ValueFour');
    $sub5Model4 = new MockBusinessModel(FALSE, 'ValueFive');
    $sub6Model4 = new MockBusinessModel(FALSE, 'ValueSix');
    $sub7Model4 = new MockBusinessModel(FALSE, 'ValueSeven');
    $sub8Model4 = new MockBusinessModel(FALSE, 'ValueEight');
    $model4[0] = $sub1Model4; // ViewA
      $sub1Model4[0] = $sub2Model4; // ViewB
        $sub2Model4[0] = $sub3Model4; // ViewC
          $sub3Model4[0] = $sub4Model4; // ViewD
    $model4[1] = $sub5Model4; // ViewA
      $sub5Model4[0] = $sub6Model4; // ViewB
        $sub6Model4[0] = $sub7Model4; // ViewC
        $sub6Model4[1] = $sub8Model4; // ViewC
    $this->assertFalse($testObject4->hasModel);
    $testObject4->setModel($model4);
    $this->assertTrue($testObject4->hasModel);
    ob_start();
    RootView::getInstance()->render();
    $output = ob_get_clean();
    $this->assertSame(
      'tests\ramp\mocks\view\MockComplexView:Parent4 ' .
      'tests\ramp\mocks\view\MockComplexViewA:ValueOne '.
      'tests\ramp\mocks\view\MockComplexViewB:ValueTwo '.
      'tests\ramp\mocks\view\MockComplexViewC:ValueThree '.
      'tests\ramp\mocks\view\MockComplexViewD:ValueFour '.
      'tests\ramp\mocks\view\MockComplexViewA:ValueFive '.
      'tests\ramp\mocks\view\MockComplexViewB:ValueSix '.
      'tests\ramp\mocks\view\MockComplexViewC:ValueSeven '.
      'tests\ramp\mocks\view\MockComplexViewC:ValueEight ',
      $output
    );
  }
}
