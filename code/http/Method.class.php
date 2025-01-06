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

/**
 * Request Method (Verb) (based on HTTP/1.1 specification).
 * INVARIANT
 * - State of *this* is always unchanged 
 * (all operations return one of several Method types)
 * @see https://tools.ietf.org/html/rfc2616#section-9 Method Definitions (RFC2616 Section 9)
 * @see http://tools.ietf.org/html/rfc2518  HTTP Extensions for Distributed Authoring -- WEBDAV
 */
class Method extends RAMPObject
{
  private static Method $GET;
  private static Method $POST;
  private static Method $LOCK;
  private static Method $UNLOCK;
  private static Method $PUT;
  private static Method $MOVE;
  private static Method $DELETE;

  private int $index;
  private Str $verb;

  /**
   * Constructor for new instance of Method.
   * @param int $index Number to be assigned to this Verb
   * @param \ramp\core\Str $verb Name of Method (verb/action) that this object is to represent
   */
  protected function __construct(int $index, Str $verb) {
    // if (!is_int($index)) {
    // throw new \InvalidArgumentException(
    //   get_class($this) . '::constructor expects first argument of type int.'
    //   );
    // }
    $this->index = $index;
    $this->verb = $verb;
  }

  /**
   * Returns the GET variant of Method.
   * Represents HTTP request for a specified resource.
   * @return \ramp\http\Method Method evaluating to 'GET' at index 1
   */
  public static function GET() : Method
  {
    if (!isset(SELF::$GET)) { SELF::$GET = new Method(1, Str::set('GET'));  }
    return SELF::$GET;
  }

  /**
   * Returns the POST variant of Method.
   * Represent HTTP request for a server to accept its enclosed entity as a new subordinate of specified resource.
   * @return \ramp\http\Method Method evaluating to 'POST' at index 2
   */
  public static function POST() : Method
  {
    if (!isset(SELF::$POST)) { SELF::$POST = new Method(2, Str::set('POST')); }
    return SELF::$POST;
  }

  /**
   * Returns the LOCK variant of Method.
   * Represents HTTP request to lock the specified resource.
   * @return \ramp\http\Method Method evaluating to 'LOCK' at index 3
   * @see http://tools.ietf.org/html/rfc2518#section-8.10 Lock Method (RFC2518 Section 8.10)
   */
  public static function LOCK() : Method
  {
    if (!isset(SELF::$LOCK)) { SELF::$LOCK = new Method(3, Str::set('LOCK')); }
    return SELF::$LOCK;
  }

  /**
   * Returns the UNLOCK variant of Method.
   * Represents HTTP request to unlock the specified resource.
   * @return \ramp\http\Method Method evaluating to 'UNLOCK' at index 4
   * @see http://tools.ietf.org/html/rfc2518#section-8.11 Unlock Method (RFC2518 Section 8.11)
   */
  public static function UNLOCK() : Method
  {
    if (!isset(SELF::$UNLOCK)) { SELF::$UNLOCK = new Method(4, Str::set('UNLOCK')); }
    return SELF::$UNLOCK;
  }

  /**
   * Returns the PUT variant of Method.
   * Represents HTTP request requesting enclosed entity be stored under supplied URI.
   * @return \ramp\http\Method Method evaluating to 'PUT' at index 5
   */
  public static function PUT() : Method
  {
    if (!isset(SELF::$PUT)) { SELF::$PUT = new Method(5, Str::set('PUT')); }
    return SELF::$PUT;
  }

  /**
   * Returns the MOVE variant of Method.
   * Represents HTTP request requesting specified resource be moved according to destination header.
   * @return \ramp\http\Method Method evaluating to 'MOVE' at index 6
   * @see http://tools.ietf.org/html/rfc2518#section-8.9 Move Method (RFC2518 Section 8.9)
   */
  public static function MOVE() : Method
  {
    if (!isset(SELF::$MOVE)) { SELF::$MOVE = new Method(6, Str::set('MOVE')); }
    return SELF::$MOVE;
  }

  /**
   * Returns the DELETE variant of Method.
   * Represents HTTP request to delete specified resource.
   * @return \ramp\http\Method Method evaluating to 'DELETE' at index 7
   */
  public static function DELETE() : Method
  {
    if (!isset(SELF::$DELETE)) { SELF::$DELETE = new Method(7, Str::set('DELETE')); }
    return SELF::$DELETE;
  }

  /**
   * Returns string equivalent of this verb.
   * @return string Verb (method name)
   */
  #[\Override]
  public function __toString() : string
  {
    return (string)$this->verb;
  }
}
