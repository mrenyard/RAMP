<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
namespace tests\svelte\http;

//require_once '/usr/share/php/svelte/SETTING.class.php';
require_once '/usr/share/php/svelte/core/SvelteObject.class.php';
require_once '/usr/share/php/svelte/core/Str.class.php';
require_once '/usr/share/php/svelte/core/iCollection.class.php';
require_once '/usr/share/php/svelte/core/Collection.class.php';
require_once '/usr/share/php/svelte/condition/Operator.class.php';
require_once '/usr/share/php/svelte/condition/Condition.class.php';
require_once '/usr/share/php/svelte/condition/BusinessCondition.class.php';
require_once '/usr/share/php/svelte/condition/PostData.class.php';
require_once '/usr/share/php/svelte/condition/InputDataCondition.class.php';
require_once '/usr/share/php/svelte/condition/Filter.class.php';
require_once '/usr/share/php/svelte/condition/FilterCondition.class.php';
require_once '/usr/share/php/svelte/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/svelte/http/Request.class.php';
require_once '/usr/share/php/svelte/http/Method.class.php';

require_once '/usr/share/php/tests/svelte/http/mocks/RequestTest/Field.class.php';
require_once '/usr/share/php/tests/svelte/http/mocks/RequestTest/MockRecord.class.php';

use svelte\core\Str;
use svelte\http\Request;
use svelte\http\Method;
use svelte\condition\Filter;
use svelte\condition\FilterCondition;
use svelte\condition\PostData;
use svelte\condition\InputDataCondition;
use svelte\model\business\iBusinessModelDefinition;

/**
 * Collection of tests for \svelte\http\Request.
 */
class RequestTest extends \PHPUnit\Framework\TestCase {

  private $record;
  private $key;

  /**
   * Setup - PHP varable that fake a basic HTTP Response.
   */
  public function setUp()
  {
    \svelte\SETTING::$SVELTE_BUSINESS_MODEL_NAMESPACE='tests\svelte\http\mocks\RequestTest';

    $_SERVER = array(); $_GET = array(); $_POST = array();
    $_SERVER['SCRIPT_NAME'] = '/404.php';

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['QUERY_STRING'] = null;
    $this->record = Str::set('MockRecord');
    $this->key = Str::set('key');
  }

  /**
   * Collection of assertions for svelte\http\Request::__construct().
   * - assert is instance of {@link \svelte\core\Object}
   * - assert is instance of {@link \svelte\model\iModelDefinition}
   * - assert is instance of {@link \svelte\http\Request}
   * @link svelte.http.Request svelte\request\http\Request
   */
  public function test__construct()
  {
    $testObject = new Request();
    $this->assertInstanceOf('svelte\core\SvelteObject', $testObject);
    $this->assertInstanceOf('svelte\model\business\iBusinessModelDefinition', $testObject);
    $this->assertInstanceOf('svelte\http\Request', $testObject);
  }

  /**
   * Collection of assertions based on (GET Record collection filtered):.
   * - $_SERVER['HTTP_X_REQUESTED_WITH'] equals NULL
   *  - assert expectsFragment returns {@link \svelte\core\Boolean::FALSE()}
   * - $_SERVER['REQUEST_METHOD'] equals 'GET'
   *  - assert method returns {@link \svelte\Method::GET()}
   * - $_SERVER['REQUEST_URI'] equals '/mock-record/?property-a=valueA&property-b=valueB&property-c=valueC&from=100'
   * - $_SERVER['QUERY_STRING'] equals 'property-a=valueA&property-b=valueB&property-c=valueC&from=100'
   *  - assert modelURN mock-record
   *  - assert recordName MockRecord
   *  - assert recordKey NULL
   *  - assert propertyName NULL
   * - $_SERVER
   *  - assert resourceIdentifier /mock-record/
   * - $_GET['from'] value of 100 (URL: /?from=100)
   *  - assert fromIndex return (int)100
   * - $_GET['property-a'] equals 'valueA' (URL: /?property-a=valueA)
   * - $_GET['property-b'] equals 'valueB' (URL: &property-b=valueB)
   * - $_GET['property-c'] equals 'valueC' (URL: &property-c=valueC)
   *  - assert filter returns comparable Filter object
   * - $_POST[] (postData)
   *  - assert postData returns NULL
   * @link svelte.http.Request#method_get_expectsFragment svelte\request\http\Request::expectsFragment
   * @link svelte.http.Request#method_get_method svelte\request\http\Request::method
   * @link svelte.http.Request#method_get_resourceIdentifier svelte\request\http\Request::resourceIdentifier
   * @link svelte.http.Request#method_get_modelURN svelte\request\http\Request::modelURN
   * @link svelte.http.Request#method_get_recordName svelte\request\http\Request::recordName
   * @link svelte.http.Request#method_get_recordKey svelte\request\http\Request::recordKey
   * @link svelte.http.Request#method_get_propertyName svelte\request\http\Request::propertyName
   * @link svelte.http.Request#method_get_fromIndex svelte\request\http\Request::fromIndex
   * @link svelte.http.Request#method_get_filter svelte\request\http\Request::filter
   * @link svelte.http.Request#method_get_postData svelte\request\http\Request::postData
   */
  public function testGETRecordCollection()
  {
    $_SERVER['REQUEST_URI'] = '/mock-record/?property-a=valueA&property-b=valueB&property-c=valueC&from=100';
    $_SERVER['QUERY_STRING'] = 'property-a=valueA&property-b=valueB&property-c=valueC&from=100';
    $_GET['property-a'] = 'valueA';
    $_GET['property-b'] = 'valueB';
    $_GET['property-c'] = 'valueC';
    $_GET['from'] = '100';
    $testObject = new Request();

    $this->assertFalse($testObject->expectsFragment);
    $this->assertSame($testObject->method, Method::GET());
    $this->assertInstanceOf('svelte\core\Str', $testObject->modelURN);
    $this->assertEquals('mock-record', (string)$testObject->modelURN);
    $this->assertInstanceOf('svelte\core\Str', $testObject->recordName);
    $this->assertEquals('MockRecord', (string)$testObject->recordName);
    $this->assertNull($testObject->recordKey);
    $this->assertNull($testObject->propertyName);
    $this->assertInstanceOf('svelte\core\Str', $testObject->resourceIdentifier);
    $this->assertEquals('/mock-record/', (string)$testObject->resourceIdentifier);
    $this->assertInternalType('int', $testObject->fromIndex);
    $this->assertEquals(100, $testObject->fromIndex);
    $this->assertInstanceOf('svelte\condition\Filter', $testObject->filter);
    $expectedFilter = new Filter();
    $expectedFilter->add(new FilterCondition($this->record, Str::set('propertyA'), 'valueA'));
    $expectedFilter->add(new FilterCondition($this->record, Str::set('propertyB'), 'valueB'));
    $expectedFilter->add(new FilterCondition($this->record, Str::set('propertyC'), 'valueC'));
    $this->assertEquals($expectedFilter, $testObject->filter);
    $this->assertNull($testObject->postData);
  }

  /**
   * Collection of assertions based on (POST individual Record):.
   * - $_SERVER['HTTP_X_REQUESTED_WITH'] equals 'NotXmlHTTPRequest'
   *  - assert expectsFragment returns {@link \svelte\core\Boolean::FALSE()}
   * - $_SERVER['REQUEST_METHOD'] equals 'POST'
   *  - assert method returns {@link \svelte\Method::POST()}
   * - $_SERVER['REQUEST_URI'] equals '/mock-record/key/'
   *  - assert modelURN mock-record:property-a
   *  - assert recordName MockRecord
   *  - assert recordKey key
   *  - assert propertyName NULL
   *  - assert resourceIdentifier /mock-record/~/
   * - $_GET['from'] (URL: /?from=n) defaults to 0
   *  - assert fromIndex return (int)0
   * - $_GET[] filter params
   *  - assert filter returns NULL
   * - $_POST['mock-record:key:property-a'] equals 'valueA'
   * - $_POST['mock-record:key:property-b'] equals 'valueB'
   * - $_POST['mock-record:key:property-c'] equals 'valueC'
   *   - assert postData returns comparable PostData object
   * @link svelte.http.Request#method_get_expectsFragment svelte\request\http\Request::expectsFragment
   * @link svelte.http.Request#method_get_method svelte\request\http\Request::method
   * @link svelte.http.Request#method_get_resourceIdentifier svelte\request\http\Request::resourceIdentifier
   * @link svelte.http.Request#method_get_modelURN svelte\request\http\Request::modelURN
   * @link svelte.http.Request#method_get_recordName svelte\request\http\Request::recordName
   * @link svelte.http.Request#method_get_recordKey svelte\request\http\Request::recordKey
   * @link svelte.http.Request#method_get_propertyName svelte\request\http\Request::propertyName
   * @link svelte.http.Request#method_get_fromIndex svelte\request\http\Request::fromIndex
   * @link svelte.http.Request#method_get_filter svelte\request\http\Request::filter
   * @link svelte.http.Request#method_get_postData svelte\request\http\Request::postData
   */
  public function testPOSTRecord()
  {
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'NotXmlHTTPRequest';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/mock-record/key/';
    $_POST['mock-record:key:property-a'] = 'valueA';
    $_POST['mock-record:key:property-b'] = 'valueB';
    $_POST['mock-record:key:property-c'] = 'valueC';
    $testObject = new Request();

    $this->assertFalse($testObject->expectsFragment);
    $this->assertSame($testObject->method, Method::POST());
    $this->assertInstanceOf('svelte\core\Str', $testObject->modelURN);
    $this->assertEquals('mock-record:key', (string)$testObject->modelURN);
    $this->assertInstanceOf('svelte\core\Str', $testObject->recordName);
    $this->assertEquals('MockRecord', (string)$testObject->recordName);
    $this->assertInstanceOf('svelte\core\Str', $testObject->recordKey);
    $this->assertEquals('key', (string)$testObject->recordKey);
    $this->assertNull($testObject->propertyName);
    $this->assertInstanceOf('svelte\core\Str', $testObject->resourceIdentifier);
    $this->assertEquals('/mock-record/~/', (string)$testObject->resourceIdentifier);
    $this->assertInternalType('int', $testObject->fromIndex);
    $this->assertEquals(0, $testObject->fromIndex);
    $this->assertNull($testObject->filter);
    $this->assertInstanceOf('svelte\condition\PostData', $testObject->postData);
    $expectedPostData = new PostData();
    $expectedPostData->add(
      new InputDataCondition($this->record, $this->key, Str::set('propertyA'), 'valueA')
    );
    $expectedPostData->add(
      new InputDataCondition($this->record, $this->key, Str::set('propertyB'), 'valueB')
    );
    $expectedPostData->add(
      new InputDataCondition($this->record, $this->key, Str::set('propertyC'), 'valueC')
    );
    $this->assertEquals($expectedPostData, $testObject->postData);
  }

  /**
   * Collection of assertions based on (POST Property of Record return fragment):.
   * - $_SERVER['HTTP_X_REQUESTED_WITH'] equals 'Xmlhttp\Request'
   *  - assert expectsFragment returns {@link \svelte\core\Boolean::TRUE()}
   * - $_SERVER['REQUEST_METHOD'] equals 'POST'
   *  - assert method returns {@link \svelte\Method::POST()}
   * - $_SERVER['REQUEST_URI'] equals '/mock-record/key/property-a'
   *  - assert modelURN mock-record:key:property-a
   *  - assert recordName MockRecord
   *  - assert recordKey key
   *  - assert propertyName propertyA
   * - $_SERVER
   *  - assert resourceIdentifier /mock-record/~/property-a/
   * - $_GET['from'] (URL: /?from=n) defaults to 0
   *  - assert fromIndex return (int)0
   * - $_GET[] filter params
   *  - assert filter returns NULL
   * - $_POST['record:key:property-b'] equals 'valueB'
   *   - assert postData returns comparable PostData object
   * @link svelte.http.Request#method_get_expectsFragment svelte\request\http\Request::expectsFragment
   * @link svelte.http.Request#method_get_method svelte\request\http\Request::method
   * @link svelte.http.Request#method_get_resourceIdentifier svelte\request\http\Request::resourceIdentifier
   * @link svelte.http.Request#method_get_modelURN svelte\request\http\Request::modelURN
   * @link svelte.http.Request#method_get_recordName svelte\request\http\Request::recordName
   * @link svelte.http.Request#method_get_recordKey svelte\request\http\Request::recordKey
   * @link svelte.http.Request#method_get_propertyName svelte\request\http\Request::propertyName
   * @link svelte.http.Request#method_get_fromIndex svelte\request\http\Request::fromIndex
   * @link svelte.http.Request#method_get_filter svelte\request\http\Request::filter
   * @link svelte.http.Request#method_get_postData svelte\request\http\Request::postData
   */
  public function test_POSTProperty()
  {
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XmlHTTPRequest';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/mock-record/key/property-b/';
    $_POST['mock-record:key:property-b'] = 'valueB';
    $testObject = new Request();

    $this->assertTrue($testObject->expectsFragment);
    $this->assertSame($testObject->method, Method::POST());
    $this->assertInstanceOf('svelte\core\Str', $testObject->modelURN);
    $this->assertEquals('mock-record:key:property-b', (string)$testObject->modelURN);
    $this->assertInstanceOf('svelte\core\Str', $testObject->recordName);
    $this->assertEquals('MockRecord', (string)$testObject->recordName);
    $this->assertInstanceOf('svelte\core\Str', $testObject->recordKey);
    $this->assertEquals('key', (string)$testObject->recordKey);
    $this->assertInstanceOf('svelte\core\Str', $testObject->propertyName);
    $this->assertEquals('propertyB', (string)$testObject->propertyName);
    $this->assertInstanceOf('svelte\core\Str', $testObject->resourceIdentifier);
    $this->assertEquals('/mock-record/~/property-b/', (string)$testObject->resourceIdentifier);
    $this->assertInternalType('int', $testObject->fromIndex);
    $this->assertEquals(0, $testObject->fromIndex);
    $this->assertNull($testObject->filter);
    $this->assertInstanceOf('svelte\condition\PostData', $testObject->postData);
    $expectedPostData = new PostData();
    $expectedPostData->add(
      new InputDataCondition($this->record, $this->key, Str::set('propertyB'), 'valueB')
    );
    $this->assertEquals($expectedPostData, $testObject->postData);
  }
}
