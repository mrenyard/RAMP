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
 * @author mrenyard (renyard.m@gmail.com)
 * @version 0.0.9;
 * @package RAMP.test
 */
namespace tests\ramp\condition;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';

require_once '/usr/share/php/tests/ramp/condition/mocks/PostDataTest/Field.class.php';
require_once '/usr/share/php/tests/ramp/condition/mocks/PostDataTest/Record.class.php';

use \ramp\core\Str;
use \ramp\condition\PostData;

/**
 * Collection of tests for \ramp\condition\PostData.
 *
 * COLLABORATORS
 * - {@link \tests\ramp\condition\mocks\PostDataTest\Property}
 * - {@link \tests\ramp\condition\mocks\PostDataTest\Record}
 */
class PostDataTest extends \PHPUnit\Framework\TestCase
{
  /**
   * Setup - add variables
   */
  public function setUp() : void
  {
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE='tests\ramp\condition\mocks\PostDataTest';
  }

  /**
   * Collection of assertions for \ramp\condition\PostData::__construct().
   * - assert is instance of {@link \ramp\core\RAMPObject}
   * - assert is instance of {@link \ramp\core\Collection}
   * - assert is instance of {@link \ramp\condition\PostData}
   * - assert is composed of {@link \ramp\condition\InputDataCondition}s
   * @link ramp.condition.PostData#method___construct ramp\condition\PostData
   */
  public function test__Construct()
  {
    $testObject = new PostData();
    $this->assertInstanceOf('\ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('\ramp\core\Collection', $testObject);
    $this->assertInstanceOf('\ramp\condition\PostData', $testObject);

    $this->assertTrue($testObject->isCompositeType(
      Str::set('ramp\condition\InputDataCondition')
    ));
  }

  /**
   * Collection of assertions for \ramp\condition\PostData::build().
   * - assert throws \DomainException when any $postdata NAME is NOT in correct form
   *   - with message: <em>'Invalid format for name in $postdata, SHOULD be URN in the form "record:key:property"'</em>
   * - assert throws \DomainException when any $postdata NAME does NOT match business model
   *   - with message: <em>'Invalid name in $postdata does NOT match business model'</em>
   * - assert where valid produces like for like representation of provied array as PostData object
   * @link ramp.condition.PostData#method___build ramp\condition\PostData::build()
   */
  public function testBuild()
  {
    $alphabet = array( 'A','B','C' );
    $badlyFormedNameArray = array();
    $badBusinessModelArray = array();
    $goodArray = array();

    for ($i=0, $j=3; $i < $j; $i++) {
      $badlyFormedNameArray['name' . $i] = 'value' . $i;
      $badBusinessModelArray['record:' . $i . ':not-property'] = 'value' . $i;
      $goodArray['record:' . $i . ':property-' . $alphabet[$i]] = 'value' . $alphabet[$i];
    }

    try {
      PostData::build($badlyFormedNameArray);
    } catch(\DomainException $expected) {
      $this->assertSame(
        'Invalid format for name in $postdata, SHOULD be URN in the form "record:key:property"',
        $expected->getMessage()
      );

      try {
        PostData::build($badBusinessModelArray);
      } catch (\DomainException $expected) {
        $this->assertSame(
          'Invalid: Record->notProperty, does NOT match business model',
          $expected->getMessage()
        );

        $testObject = PostData::build($goodArray);
        for ($i=0,$j=count($goodArray); $i<$j; $i++) {
          $this->assertSame($goodArray['record:' . $i . ':property-' . $alphabet[$i]], $testObject[$i]->value);
          $this->assertSame('value' . $alphabet[$i], $testObject[$i]->value);
          $this->assertEquals(Str::set($i), $testObject[$i]->primaryKeyValue);
          $this->assertSame('Record', (string)$testObject[$i]->record);
        }
        return;
      }
    }
    $this->fail('An expected \DomainException has NOT been raised');
  }
}
