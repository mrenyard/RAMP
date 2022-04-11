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
 * @version 0.0.9;
 */
namespace tests\ramp\view;

require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/view/View.class.php';
require_once '/usr/share/php/ramp/view/ChildView.class.php';
require_once '/usr/share/php/ramp/view/Dump.class.php';

require_once '/usr/share/php/tests/ramp/view/mocks/DumpTest/MockView.class.php';

use tests\ramp\view\mocks\DumpTest\MockView;

use ramp\view\View;
use ramp\view\Dump;

/**
 * Collection of tests for \ramp\view\Dump.
 */
class DumpTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Collection of assertions for \ramp\view\ChildView::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\view\View}
   * - assert is instance of {@link \ramp\view\RootView}
   * - assert output of children on provided parentView is as expected maintaining sequance and format
   * - assert output of render on provided parentView is as expected maintaining sequance and format
   * @link ramp.view.ChildView ramp\view\ChildView
   */
  public function test__construct()
  {
    $rootView = new MockView();
    $testObject = new Dump($rootView);
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\view\View', $testObject);
    $this->assertInstanceOf('\ramp\view\Dump', $testObject);

    $expectedRegEx = '#^<pre>[A-Za-z0-9 _/]*code/view/Dump.class.php:[0-9]*:'.PHP_EOL.
    'class ramp\\\view\\\Dump\#[0-9]* \([0-9]*\) {'.PHP_EOL.
    '  private \$viewCollection =>'.PHP_EOL.
    '  NULL'.PHP_EOL.
    '  private \$model =>'.PHP_EOL.
    '  NULL'.PHP_EOL.
    '}'.PHP_EOL.
    '</pre>$#i';

    ob_start();
    $rootView->children;
    $output = ob_get_clean();
    $this->assertRegExp($expectedRegEx, $output);

    ob_start();
    $rootView->render();
    $output = ob_get_clean();
    $this->assertRegExp($expectedRegEx, $output);
  }
}
