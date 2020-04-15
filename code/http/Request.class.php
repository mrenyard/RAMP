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
 * @link https://tools.ietf.org/html/rfc2616 Hypertext Transfer Protocol -- HTTP/1.1 (RFC2616)
 */
class Request extends SvelteObject implements iBusinessModelDefinition {

  private $expectsFragment;
  private $method;
  private $resourceURL;
  private $modelURN;
  private $recordName;
  private $recordKey;
  private $propertyName;
  private $fromIndex;
  private $filter;
  private $postData;

  /**
   * Interprets HTTP and constructs new Request based on current context.
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

    if (($modelURL != '') && ($modelURL != '/')) {

      $model = explode('/', trim($modelURL, '/'));
      $view = $model;

      $this->modelURN = Str::set(implode(':', $model));
      $this->recordName = Str::camelCase(Str::set($model[0]));

      $n = count($model);
      if ($n == 1) {

        $this->fromIndex = (isset($_GET['from']))? (int)(string)Str::set($_GET['from']) : 0;
        unset($_GET['from']);
        if (count($_GET) > 0 ) {
          $this->filter = Filter::build($this->recordName, $_GET);
        }
      }
      if ($n > 1) { $this->recordKey = Str::set($model[1]); $view[1] = '~'; }
      if ($n > 2) { $this->propertyName = Str::camelCase(Str::set($model[2]), TRUE); }

      array_unshift($view, $controller);

    } // END if (($modelURL != '') && ($modelURL != '/'))

    $strView = (isset($view))? implode('/', $view) : $controller;

    $this->resourceURL = Str::set($strView . '/');

    if ($this->method === Method::POST()) {
      $this->postData = PostData::build($_POST);
    }
  }

  /**
   * Is this request expecting a document fragment or a complete document.
   * <b>DO NOT CALL DIRECTLY, USE this->expectsFragment;</b>
   * @return bool Expects document fragment
   */
  protected function get_expectsFragment() : bool
  {
    return ($this->expectsFragment);
  }

  /**
   * Returns request Method (Verb) (based on HTTP/1.1 specification).
   * <b>DO NOT CALL DIRECTLY, USE this->method;</b>
   * @link https://tools.ietf.org/html/rfc2616#section-9 Method Definitions (RFC2616 Section 9)
   * @return \svelte\Method Requested Method
   */
  protected function get_method() : Method
  {
    return $this->method;
  }

  /**
   * Returns Uniform Resource Name (URN) of requested svelte\model\Model or NULL.
   * <b>DO NOT CALL DIRECTLY, USE this->modelURN;</b>
   * @link https://www.ietf.org/rfc/rfc2141.txt URN defintion (RFC2141)
   * @return \svelte\core\Str URN of requested svelte\model\Model or NULL.
   */
  protected function get_modelURN() : ?Str
  {
    return $this->modelURN;
  }

  /**
   * {@inheritdoc}
   */
  public function getRecordName() : Str
  {
    return $this->recordName;
  }

  /**
   * {@inheritdoc}
   */
  public function getRecordKey() : ?Str
  {
    /*if (($this->recordKey == '~') && Session::user()->isValid()->get()) {
      return Str::set(Session::user()->auPK);
    }*/
    return $this->recordKey;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyName() : ?Str
  {
    return $this->propertyName;
  }

  /**
   * Returns Uniform Resource Identifier (URI) of requested resource.
   * <b>DO NOT CALL DIRECTLY, USE this->resourceURL;</b>
   * @link https://www.ietf.org/rfc/rfc2396.txt URI Specification
   * @return \svelte\core\Str URI of requested resource
   */
  protected function get_resourceIdentifier() : Str
  {
    return $this->resourceURL;
  }

  /**
   * Returns any requested starting point within a collection.
   * <b>DO NOT CALL DIRECTLY, USE this->fromIndex;</b>
   * @return int Index to start index from
   */
  protected function get_fromIndex() : int
  {
    return (int)$this->fromIndex;
  }

  /**
   * Returns any filter to apply to a collection.
   * <b>DO NOT CALL DIRECTLY, USE this->filter;</b>
   * @return \svelte\condition\Filter Filter to be applied to collection
   */
  protected function get_filter() : ?Filter
  {
    return $this->filter;
  }

  /**
   * Returns any data for posting sent with request.
   * <b>DO NOT CALL DIRECTLY, USE this->postData;</b>
   * @return \svelte\condition\PostData Data collection to be posted
   */
  protected function get_postData() : ?PostData
  {
    return $this->postData;
  }
}
