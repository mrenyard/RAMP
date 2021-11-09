<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\http;

use svelte\core\SvelteObject;
use svelte\core\Str;
use svelte\core\Collection;
use svelte\condition\PostData;
use svelte\condition\Filter;
use svelte\model\business\iBusinessModelDefinition;
use svelte\model\business\AccountType;
use svelte\http\Method;

/**
 * Request data interpreted based on HyperText Transfer Protocol (HTTP).
 *
 * RESPONSIBILITIES
 * - Interpret HTTP request
 * - Act as {@link \svelte\model\business\iBusinessModelDefinition}
 *
 * COLLABORATORS
 * - {@link svelte\model\business\iBusinessModelDefinition}
 * - {@link svelte\condition\PostData}
 * - {@link svelte\condition\Filter}
 * - {@link svelte\http\Method}
 * - $_SERVER
 * - $_POST
 * - $_GET
 *
 * EXAMPLE USE:
 * ```php
 * try {
 *   $request = new http\Request();
 * } catch (\DomainException $e) {
 *   header('HTTP/1.1 404 Not Found');
 *   ...
  *  return;
 * }
 * ...
 * ```
 * @link https://tools.ietf.org/html/rfc2616 Hypertext Transfer Protocol - HTTP/1.1 (RFC2616)
 * @link https://tools.ietf.org/html/rfc2616#section-9 Method Definitions (RFC2616 Section 9)
 * @link https://www.ietf.org/rfc/rfc2141.txt URN defintion (RFC2141)
 * @link https://www.ietf.org/rfc/rfc2396.txt URI Specification
 *
 * @property-read bool $expectsFragment Returns whether this request is expecting a document fragment or a complete document.
 * @property-read \svelte\http\Method $method Returns request Method (Verb) (based on HTTP/1.1 specification).
 * @property-read ?\svelte\core\Str $modelURN Returns Uniform Resource Name (URN) of requested svelte\model\Model or NULL.
 * @property-read \svelte\core\Str $resourceIdentifier Returns Uniform Resource Identifier (URI) of requested resource.
 * @property-read int $fromIndex Returns any requested starting point within a collection.
 * @property-read ?\svelte\condition\Filter $filter Returns any filter to apply to a collection.
 * @property-read ?\svelte\condition\PostData $postData Returns any data for posting sent with request.
 */
class Request extends SvelteObject implements iBusinessModelDefinition
{
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

  /**
   * Interprets HTTP and constructs new Request based on current context.
   * PRECONDITIONS
   * - SETTING::$SVELTE_BUSINESS_MODEL_MANAGER MUST be set.
   * @throws \DomainException When supplied arguments do NOT meet the restrictions and
   * limits as defined by local business model (SVELTE_BUESINESS_MODEL_NAMESPACE)
 */
  public function __construct()
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
      if ($n > 1) { $this->recordKey = Str::set($model[1]); $view[1] = '~'; }
      if ($n > 2) { $this->propertyName = Str::camelCase(Str::set($model[2]), TRUE); }
      array_unshift($view, $controller);
    } // END if (($modelURL != '') && ($modelURL != '/'))
    $strView = (isset($view))? implode('/', $view) : $controller;
    $this->resourceURL = Str::set($strView . '/');
    if ($this->method === Method::POST())
    {
      $this->postData = PostData::build($_POST);
    }
  }

  /**
   * Is this request expecting a document fragment or a complete document.
   * **DO NOT CALL DIRECTLY, USE this->expectsFragment;**
   * @return bool Expects document fragment
   */
  protected function get_expectsFragment() : bool
  {
    return ($this->expectsFragment);
  }

  /**
   * Returns request Method (Verb) (based on HTTP/1.1 specification).
   * **DO NOT CALL DIRECTLY, USE this->method;**
   * @link https://tools.ietf.org/html/rfc2616#section-9 Method Definitions (RFC2616 Section 9)
   * @return \svelte\Method Requested Method
   */
  protected function get_method() : Method
  {
    return $this->method;
  }

  /**
   * Returns Uniform Resource Name (URN) of requested svelte\model\Model or NULL.
   * **DO NOT CALL DIRECTLY, USE this->modelURN;**
   * @link https://www.ietf.org/rfc/rfc2141.txt URN defintion (RFC2141)
   * @return \svelte\core\Str URN of requested svelte\model\Model or NULL.
   */
  protected function get_modelURN() : ?Str
  {
    return $this->modelURN;
  }

  /**
   * Returns name of requested Record one or collection.
   * **DO NOT CALL DIRECTLY, USE this->recordName;**
   * @return \svelte\core\Str Name of requested Record one or collection.
   */
  public function get_recordName() : Str
  {
    return $this->recordName;
  }

  /**
   * Returns primary key value of requested svelte\model\business\Record or NULL.
   * **DO NOT CALL DIRECTLY, USE this->recordKey;**
   * @return \svelte\core\Str Primary key for requested Record if any.
   */
  public function get_recordKey() : ?Str
  {
    /*if (($this->recordKey == '~') && Session::user()->isValid()->get()) {
      return Str::set(Session::user()->auPK);
    }*/
    return $this->recordKey;
  }

  /**
   * Returns name of requested Property of svelte\model\business\Record or NULL.
   * **DO NOT CALL DIRECTLY, USE this->propertyName;**
   * @return \svelte\core\Str Name of requested Property if any.
   */
  public function get_propertyName() : ?Str
  {
    return $this->propertyName;
  }

  /**
   * Returns Uniform Resource Identifier (URI) of requested resource.
   * **DO NOT CALL DIRECTLY, USE this->resourceURL;**
   * @link https://www.ietf.org/rfc/rfc2396.txt URI Specification
   * @return \svelte\core\Str URI of requested resource
   */
  protected function get_resourceIdentifier() : Str
  {
    return $this->resourceURL;
  }

  /**
   * Returns any requested starting point within a collection.
   * **DO NOT CALL DIRECTLY, USE this->fromIndex;**
   * @return int Index to start index from
   */
  protected function get_fromIndex() : int
  {
    return (int)$this->fromIndex;
  }

  /**
   * Returns any filter to apply to a collection.
   * **DO NOT CALL DIRECTLY, USE this->filter;**
   * @return \svelte\condition\Filter Filter to be applied to collection
   */
  protected function get_filter() : ?Filter
  {
    return $this->filter;
  }

  /**
   * Returns any data for posting sent with request.
   * **DO NOT CALL DIRECTLY, USE this->postData;**
   * @return \svelte\condition\PostData Data collection to be posted
   */
  protected function get_postData() : ?PostData
  {
    return $this->postData;
  }
}
