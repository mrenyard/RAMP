<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\http;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\condition\PostData;
use ramp\condition\Filter;
use ramp\model\business\iBusinessModelDefinition;
use ramp\model\business\AccountType;
use ramp\http\Method;

/**
 * Request data interpreted based on HyperText Transfer Protocol (HTTP).
 *
 * RESPONSIBILITIES
 * - Interpret HTTP request
 * - Act as {@see \ramp\model\business\iBusinessModelDefinition}
 *
 * COLLABORATORS
 * - {@see ramp\model\business\iBusinessModelDefinition}
 * - {@see ramp\condition\PostData}
 * - {@see ramp\condition\Filter}
 * - {@see ramp\http\Method}
 * - $_SERVER
 * - $_POST
 * - $_GET
 *
 * EXAMPLE USE:
 * ```php
 * try {
 *   $request = new http\Request();
 * } catch (\DomainException $exception) {
 *   header('HTTP/1.1 404 Not Found');
 *   ...
  *  return;
 * }
 * ...
 * ```
 * @see https://tools.ietf.org/html/rfc2616 Hypertext Transfer Protocol - HTTP/1.1 (RFC2616)
 * @see https://tools.ietf.org/html/rfc2616#section-9 Method Definitions (RFC2616 Section 9)
 * @see https://www.ietf.org/rfc/rfc2141.txt URN defintion (RFC2141)
 * @see https://www.ietf.org/rfc/rfc2396.txt URI Specification
 *
 * @property-read bool $expectsFragment Returns whether this request is expecting a document fragment or a complete document.
 * @property-read \ramp\http\Method $method Returns request Method (Verb) (based on HTTP/1.1 specification).
 * @property-read ?\ramp\core\Str $modelURN Returns Uniform Resource Name (URN) of requested ramp\model\Model or NULL.
 * @property-read \ramp\core\Str $resourceIdentifier Returns Uniform Resource Identifier (URI) of requested resource.
 * @property-read int $fromIndex Returns any requested starting point within a collection.
 * @property-read ?\ramp\condition\Filter $filter Returns any filter to apply to a collection.
 * @property-read ?\ramp\condition\PostData $postData Returns any data for posting sent with request.
 */
class Request extends RAMPObject implements iBusinessModelDefinition
{
  private static $current;
  private $expectsFragment;
  private $method;
  private $modelURN;
  private $resourceURL;
  private $recordName;
  private $recordKey;
  private $propertyName;
  private $fromIndex;
  private $filter;
  private $postData;

  public static function reset() { self::$current = NULL; }

  /**
   * Current active HTTP Request based on CURRENT context.
   * PRECONDITIONS
   * - SETTING::$RAMP_BUSINESS_MODEL_MANAGER MUST be set.
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and
   * limits as defined by local business model (RAMP_BUESINESS_MODEL_NAMESPACE)
   */
  public static function current() : Request
  {
    if (!isset(self::$current)) { self::$current = new Request(); }
    return self::$current;
  }

  /**
   * Interprets HTTP and constructs new Request based on context.
   * PRECONDITIONS
   * - SETTING::$RAMP_BUSINESS_MODEL_MANAGER MUST be set.
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and
   * limits as defined by local business model (RAMP_BUESINESS_MODEL_NAMESPACE)
   */
  private function __construct()
  {
    $this->expectsFragment = \FALSE;
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $this->expectsFragment = \TRUE;
    }
    $method = $_SERVER['REQUEST_METHOD'];
    $this->method = ((count($_POST) <1) && ($method == 'POST'))?
      Method::GET() : Method::$method();
    $controller = ($_SERVER['SCRIPT_NAME'] == '/404.php')? '':
      explode('.', $_SERVER['SCRIPT_NAME'])[0]; //, '/');
    $filtersStr = $_SERVER['QUERY_STRING'];
    $viewURL = str_replace('?'.$filtersStr, '', $_SERVER['REQUEST_URI']);
    $modelURL = str_replace($controller, '', $viewURL);
    if (($modelURL != '') && ($modelURL != '/'))
    {
      $model = explode('/', trim($modelURL, '/'));
      $view = $model;
      $this->modelURN = Str::set(implode(':', $model));
      $this->recordName = Str::camelCase(Str::set($model[0]));
      $recordClassName = \ramp\SETTING::$RAMP_BUSINESS_MODEL_NAMESPACE . '\\' . $this->recordName;
      if (!class_exists($recordClassName)) {
        throw new \DomainException('Invalid: ' . $this->recordName . ', does NOT match business model');
      }
      $n = count($model);
      if ($n == 1)
      {
        $this->fromIndex = (isset($_GET['from']))? (int)(string)Str::set($_GET['from']) : 0;
        unset($_GET['from']);
        if (count($_GET) > 0 )
        {
          $this->filter = Filter::build($this->recordName, $_GET);
        }
      }
      if ($n > 1) {
        $this->recordKey = Str::set(str_replace(['+', '%20'], ' ', str_replace(['%7C', '%7c'], '|', $model[1])));
        $view[1] = '~';
      }
      if ($n > 2) {
        $this->propertyName = Str::camelCase(Str::set($model[2]), TRUE);
        if (!method_exists($recordClassName, 'get_' . $this->propertyName)) {
          throw new \DomainException('Invalid: ' . $this->recordName . '->' . $this->propertyName . ', does NOT match business model');
        }
      }
    } // END if (($modelURL != '') && ($modelURL != '/'))
    $strView = (isset($view))? implode('/', $view) : $controller;
    $this->resourceURL = Str::set('/' . $strView . '/');
    if ($this->method === Method::POST()) {
      $this->postData = PostData::build($_POST);
    }
  }

  /**
   * @ignore
   */
  protected function get_expectsFragment() : bool
  {
    return ($this->expectsFragment);
  }

  /**
   * @ignore
   */
  protected function get_method() : Method
  {
    return $this->method;
  }

  /**
   * @ignore
   */
  protected function get_modelURN() : ?Str
  {
    return $this->modelURN;
  }

  /**
   * Returns name of requested Record one or collection.
   * Can be called directly inline with iBusinessModelDefinition or by using this->recordName;
   * @return \ramp\core\Str Name of requested Record one or collection.
   */
  public function get_recordName() : Str
  {
    return $this->recordName;
  }

  /**
   * Returns primary key value of requested ramp\model\business\Record or NULL.
   * Can be called directly inline with iBusinessModelDefinition or by using this->recordKey;
   * @return \ramp\core\Str Primary key for requested Record if any.
   */
  public function get_recordKey() : ?Str
  {
    if ($this->recordKey == '~') {
      if (isset($_SESSION['loginAccount']) && $_SESSION['loginAccount']->isValid) {
        return Str::set($_SESSION['loginAccount']->auPK->value);
      }
      return Str::set('new');
    }
    return $this->recordKey;
  }

  /**
   * Returns name of requested Property of ramp\model\business\Record or NULL.
   * Can be called directly inline with iBusinessModelDefinition or by using this->propertyName;
   * @return \ramp\core\Str Name of requested Property if any.
   */
  public function get_propertyName() : ?Str
  {
    return $this->propertyName;
  }

  /**
   * @ignore
   */
  protected function get_resourceIdentifier() : Str
  {
    return $this->resourceURL;
  }

  /**
   * @ignore
   */
  protected function get_fromIndex() : int
  {
    return (int)$this->fromIndex;
  }

  /**
   * @ignore
   */
  protected function get_filter() : ?Filter
  {
    return $this->filter;
  }

  /**
   * @ignore
   */
  protected function get_postData() : ?PostData
  {
    return $this->postData;
  }
}
