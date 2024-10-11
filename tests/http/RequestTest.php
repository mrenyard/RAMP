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
namespace tests\ramp\http;

require_once '/usr/share/php/ramp/SETTING.class.php';
require_once '/usr/share/php/ramp/core/RAMPObject.class.php';
require_once '/usr/share/php/ramp/core/Str.class.php';
require_once '/usr/share/php/ramp/core/iList.class.php';
require_once '/usr/share/php/ramp/core/oList.class.php';
require_once '/usr/share/php/ramp/core/iCollection.class.php';
require_once '/usr/share/php/ramp/core/Collection.class.php';
require_once '/usr/share/php/ramp/core/StrCollection.class.php';
require_once '/usr/share/php/ramp/core/iOption.class.php';
require_once '/usr/share/php/ramp/core/OptionList.class.php';
require_once '/usr/share/php/ramp/condition/Operator.class.php';
require_once '/usr/share/php/ramp/condition/Condition.class.php';
require_once '/usr/share/php/ramp/condition/BusinessCondition.class.php';
require_once '/usr/share/php/ramp/condition/PostData.class.php';
require_once '/usr/share/php/ramp/condition/InputDataCondition.class.php';
require_once '/usr/share/php/ramp/condition/iEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Environment.class.php';
require_once '/usr/share/php/ramp/condition/PHPEnvironment.class.php';
require_once '/usr/share/php/ramp/condition/Filter.class.php';
require_once '/usr/share/php/ramp/condition/FilterCondition.class.php';
require_once '/usr/share/php/ramp/model/Model.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModelManager.class.php';
require_once '/usr/share/php/ramp/model/business/BusinessModel.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponent.class.php';
require_once '/usr/share/php/ramp/model/business/RecordComponentType.class.php';
require_once '/usr/share/php/ramp/model/business/Relatable.class.php';
require_once '/usr/share/php/ramp/model/business/PrimaryKey.class.php';
require_once '/usr/share/php/ramp/model/business/field/Field.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectFrom.class.php';
require_once '/usr/share/php/ramp/model/business/field/SelectOne.class.php';
require_once '/usr/share/php/ramp/model/business/field/Input.class.php';
require_once '/usr/share/php/ramp/model/business/field/Option.class.php';
require_once '/usr/share/php/ramp/model/business/iBusinessModelDefinition.class.php';
require_once '/usr/share/php/ramp/model/business/validation/ValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/RegexValidationRule.class.php';
require_once '/usr/share/php/ramp/model/business/validation/LowercaseAlphanumeric.class.php';
require_once '/usr/share/php/ramp/model/business/validation/EmailAddress.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/DbTypeValidation.class.php';
require_once '/usr/share/php/ramp/model/business/validation/dbtype/VarChar.class.php';
require_once '/usr/share/php/ramp/model/business/Record.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccountType.class.php';
require_once '/usr/share/php/ramp/model/business/LoginAccount.class.php';
require_once '/usr/share/php/ramp/http/Request.class.php';
require_once '/usr/share/php/ramp/http/Method.class.php';

require_once '/usr/share/php/tests/ramp/mocks/http/MockField.class.php';
require_once '/usr/share/php/tests/ramp/mocks/http/MockRecord.class.php';

use ramp\core\Str;
use ramp\http\Request;
use ramp\http\Method;
use ramp\condition\Filter;
use ramp\condition\FilterCondition;
use ramp\condition\PostData;
use ramp\condition\InputDataCondition;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\MockField;
use ramp\model\business\MockRecord;
use ramp\model\business\LoginAccount;
use ramp\model\business\LoginAccountType;
use ramp\model\business\HttpBusinessModelManager;

/**
 * Collection of tests for \ramp\http\Request.
 */
class RequestTest extends \PHPUnit\Framework\TestCase {

  private $record;
  private $key;

  /**
   * Setup - PHP varable that fake a basic HTTP Response.
   */
  public function setUp() : void
  {
    Request::reset();
    $_GET = array();
    $_POST = array();
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['QUERY_STRING'] = null;
    \ramp\SETTING::$RAMP_LOCAL_DIR = '/home/mrenyard/Projects/RAMP/tests/mocks/http';
    set_include_path( "'" . \ramp\SETTING::$RAMP_LOCAL_DIR . "'" . PATH_SEPARATOR . get_include_path() );
    \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE = 'ramp\model\business';
    $this->record = Str::set('MockRecord');
    $this->key = Str::set('key');
  }

  /**
   * Collection of assertions for ramp\http\Request::__construct().
   * - assert is instance of {@see \ramp\core\Object}
   * - assert is instance of {@see \ramp\model\iModelDefinition}
   * - assert is instance of {@see \ramp\http\Request}
   * @see \ramp\request\http\Request
   */
  public function test__construct()
  {
    $_SERVER['REQUEST_URI'] = '/';
    $testObject = Request::current();
    $this->assertInstanceOf('ramp\core\RAMPObject', $testObject);
    $this->assertInstanceOf('ramp\model\business\iBusinessModelDefinition', $testObject);
    $this->assertInstanceOf('ramp\http\Request', $testObject);
  }


  /**
   * Collection of assertions based on Property NOT defined in business model.
   * - $_SERVER['REQUEST_URI'] equals '/non-record'
   * - assert throws \DomainException when supplied argument do NOT meet the restrictions and
   *   limits as defined by local business model (RAMP_BUESINESS_MODEL_NAMESPACE)
   */
  public function testDomainExceptionBadRecord()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Invalid: NonRecord, does NOT match business model');
    $_SERVER['REQUEST_URI'] = '/non-record';
    $testObject = Request::current();
  }

  /**
   * Collection of assertions based on Property NOT defined in business model.
   * - $_SERVER['REQUEST_URI'] equals '/mock-record/key/bad-property'
   * - assert throws \DomainException when supplied argument do NOT meet the restrictions and
   *   limits as defined by local business model (RAMP_BUESINESS_MODEL_NAMESPACE)
   */
  public function testDomainExceptionBadProperty()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Invalid: MockRecord->badProperty, does NOT match business model');
    $_SERVER['REQUEST_URI'] = '/mock-record/key/bad-property';
    $testObject = Request::current();
  }

  /**
   * Collection of assertions based on Property NOT defined in business model.
   * - $_SERVER['REQUEST_URI'] equals '/mock-record/?property-not=valueOK'
   * - $_SERVER['QUERY_STRING'] = 'property-not=valueOK';
   * - $_GET['property-not'] = 'valueOK';
   * - assert throws \DomainException when supplied argument do NOT meet the restrictions and
   *   limits as defined by local business model (RAMP_BUESINESS_MODEL_NAMESPACE)
   */
  public function testDomainExceptionBadFilter()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Invalid: MockRecord->propertyNot, does NOT match business model');
    $_SERVER['REQUEST_URI'] = '/mock-record/?property-not=valueOK';
    $_SERVER['QUERY_STRING'] = 'property-not=valueOK';
    $_GET['property-not'] = 'valueOK';
    $testObject = Request::current();
  }

  /**
   * Collection of assertions based on (GET Record collection filtered):.
   * - $_SERVER['HTTP_X_REQUESTED_WITH'] equals NULL
   *  - assert expectsFragment returns {@see \ramp\core\Boolean::FALSE()}
   * - $_SERVER['REQUEST_METHOD'] equals 'GET'
   *  - assert method returns {@see \ramp\Method::GET()}
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
   * @see \ramp\request\http\Request::expectsFragment
   * @see \ramp\request\http\Request::method
   * @see \ramp\request\http\Request::resourceIdentifier
   * @see \ramp\request\http\Request::modelURN
   * @see \ramp\request\http\Request::recordName
   * @see \ramp\request\http\Request::recordKey
   * @see \ramp\request\http\Request::propertyName
   * @see \ramp\request\http\Request::fromIndex
   * @see \ramp\request\http\Request::filter
   * @see \ramp\request\http\Request::postData
   */
  public function testGETRecordCollection()
  {
    $_SERVER['REQUEST_URI'] = '/mock-record/?property-a=valueA&property-b=valueB&property-c=valueC&from=100';
    $_SERVER['QUERY_STRING'] = 'property-a=valueA&property-b=valueB&property-c=valueC&from=100';
    $_GET['property-a'] = 'valueA';
    $_GET['property-b'] = 'valueB';
    $_GET['property-c'] = 'valueC';
    $_GET['from'] = '100';
    $testObject = Request::current();
    $this->assertFalse($testObject->expectsFragment);
    $this->assertSame($testObject->method, Method::GET());
    $this->assertInstanceOf('ramp\core\Str', $testObject->modelURN);
    $this->assertEquals('mock-record', (string)$testObject->modelURN);
    $this->assertInstanceOf('ramp\core\Str', $testObject->recordName);
    $this->assertInstanceOf('ramp\core\Str', $testObject->get_recordName());
    $this->assertEquals('MockRecord', (string)$testObject->recordName);
    $this->assertEquals('MockRecord', (string)$testObject->get_recordName());
    $this->assertNull($testObject->recordKey);
    $this->assertNull($testObject->get_recordKey());
    $this->assertNull($testObject->propertyName);
    $this->assertNull($testObject->get_propertyName());
    $this->assertInstanceOf('ramp\core\Str', $testObject->resourceIdentifier);
    $this->assertEquals('/mock-record/', (string)$testObject->resourceIdentifier);
    $this->assertIsInt($testObject->fromIndex);
    $this->assertEquals(100, $testObject->fromIndex);
    $this->assertInstanceOf('ramp\condition\Filter', $testObject->filter);
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
   *  - assert expectsFragment returns {@see \ramp\core\Boolean::FALSE()}
   * - $_SERVER['REQUEST_METHOD'] equals 'POST'
   *  - assert method returns {@see \ramp\Method::POST()}
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
   * @see \ramp\request\http\Request::expectsFragment
   * @see \ramp\request\http\Request::method
   * @see \ramp\request\http\Request::resourceIdentifier
   * @see \ramp\request\http\Request::modelURN
   * @see \ramp\request\http\Request::recordName
   * @see \ramp\request\http\Request::recordKey
   * @see \ramp\request\http\Request::propertyName
   * @see \ramp\request\http\Request::fromIndex
   * @see \ramp\request\http\Request::filter
   * @see \ramp\request\http\Request::postData
   */
  public function testPOSTRecord()
  {
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'NotXmlHTTPRequest';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/mock-record/key/';
    $_POST['mock-record:key:property-a'] = 'valueA';
    $_POST['mock-record:key:property-b'] = 'valueB';
    $_POST['mock-record:key:property-c'] = 'valueC';
    $testObject = Request::current();
    $this->assertFalse($testObject->expectsFragment);
    $this->assertSame($testObject->method, Method::POST());
    $this->assertInstanceOf('ramp\core\Str', $testObject->modelURN);
    $this->assertEquals('mock-record:key', (string)$testObject->modelURN);
    $this->assertInstanceOf('ramp\core\Str', $testObject->recordName);
    $this->assertInstanceOf('ramp\core\Str', $testObject->get_recordName());
    $this->assertEquals('MockRecord', (string)$testObject->recordName);
    $this->assertEquals('MockRecord', (string)$testObject->get_recordName());
    $this->assertInstanceOf('ramp\core\Str', $testObject->recordKey);
    $this->assertInstanceOf('ramp\core\Str', $testObject->get_recordKey());
    $this->assertEquals('key', (string)$testObject->recordKey);
    $this->assertEquals('key', (string)$testObject->get_recordKey());
    $this->assertNull($testObject->propertyName);
    $this->assertNull($testObject->get_propertyName());
    $this->assertInstanceOf('ramp\core\Str', $testObject->resourceIdentifier);
    $this->assertEquals('/mock-record/~/', (string)$testObject->resourceIdentifier);
    $this->assertIsInt($testObject->fromIndex);
    $this->assertEquals(0, $testObject->fromIndex);
    $this->assertNull($testObject->filter);
    $this->assertInstanceOf('ramp\condition\PostData', $testObject->postData);
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
   *  - assert expectsFragment returns {@see \ramp\core\Boolean::TRUE()}
   * - $_SERVER['REQUEST_METHOD'] equals 'POST'
   *  - assert method returns {@see \ramp\Method::POST()}
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
   * @see \ramp\request\http\Request::expectsFragment
   * @see \ramp\request\http\Request::method
   * @see \ramp\request\http\Request::resourceIdentifier
   * @see \ramp\request\http\Request::modelURN
   * @see \ramp\request\http\Request::recordName
   * @see \ramp\request\http\Request::recordKey
   * @see \ramp\request\http\Request::propertyName
   * @see \ramp\request\http\Request::fromIndex
   * @see \ramp\request\http\Request::filter
   * @see \ramp\request\http\Request::postData
   */
  public function test_POSTProperty()
  {
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XmlHTTPRequest';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/mock-record/key/property-b/';
    $_POST['mock-record:key:property-b'] = 'valueB';
    $testObject = Request::current();

    $this->assertTrue($testObject->expectsFragment);
    $this->assertSame($testObject->method, Method::POST());
    $this->assertInstanceOf('ramp\core\Str', $testObject->modelURN);
    $this->assertEquals('mock-record:key:property-b', (string)$testObject->modelURN);
    $this->assertInstanceOf('ramp\core\Str', $testObject->recordName);
    $this->assertInstanceOf('ramp\core\Str', $testObject->get_recordName());
    $this->assertEquals('MockRecord', (string)$testObject->recordName);
    $this->assertEquals('MockRecord', (string)$testObject->get_recordName());
    $this->assertInstanceOf('ramp\core\Str', $testObject->recordKey);
    $this->assertInstanceOf('ramp\core\Str', $testObject->get_recordKey());
    $this->assertEquals('key', (string)$testObject->recordKey);
    $this->assertEquals('key', (string)$testObject->get_recordKey());
    $this->assertInstanceOf('ramp\core\Str', $testObject->propertyName);
    $this->assertInstanceOf('ramp\core\Str', $testObject->get_propertyName());
    $this->assertEquals('propertyB', (string)$testObject->propertyName);
    $this->assertEquals('propertyB', (string)$testObject->get_propertyName());
    $this->assertInstanceOf('ramp\core\Str', $testObject->resourceIdentifier);
    $this->assertEquals('/mock-record/~/property-b/', (string)$testObject->resourceIdentifier);
    $this->assertIsInt($testObject->fromIndex);
    $this->assertEquals(0, $testObject->fromIndex);
    $this->assertNull($testObject->filter);
    $this->assertInstanceOf('ramp\condition\PostData', $testObject->postData);
    $expectedPostData = new PostData();
    $expectedPostData->add(
      new InputDataCondition($this->record, $this->key, Str::set('propertyB'), 'valueB')
    );
    $this->assertEquals($expectedPostData, $testObject->postData);
  }

  /**
   * Collection of assertions for \ramp\http\Request::recordKey based on (~) the current Session logged in account.
   * - assert returns key value 'new' when Session not set.
   * - assert returns key of relevant Session loginAccount when set.
   * @see \ramp\http\Request::recordKey
   */
  public function testRecordKeyLoggedinAccount()
  {
    $_SERVER['REQUEST_URI'] = '/mock-record/~/property-b/';    $dataObject = new \stdClass();
    $testObject = Request::current();
    $this->assertInstanceOf('ramp\core\Str', $testObject->recordKey);
    $this->assertSame((string)$testObject->recordKey, 'new');
    $dataObject->auPK = 'aperson';
    $dataObject->id = 'login-account:aperson';
    $dataObject->email = 'aperson@domain.com';
    $dataObject->encryptedPassword = crypt(
      'Pa55w0rd', \ramp\SETTING::$SECURITY_PASSWORD_SALT
    );
    $dataObject->loginAccountTypeID = LoginAccountType::ADMINISTRATOR;
    $_SESSION['loginAccount'] = new LoginAccount($dataObject);
    $this->assertTrue($_SESSION['loginAccount']->isValid);
    $this->assertSame((string)$testObject->recordKey, 'aperson');
  }
}
