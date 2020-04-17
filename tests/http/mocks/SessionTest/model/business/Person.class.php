<?php
/**
 * Svelte - Rapid web application development using best practice.
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
namespace svelte\model\business;

use svelte\SETTING;
use svelte\core\Str;
use svelte\model\business\RecordCollection;
use svelte\model\business\BusinessModel;
use svelte\model\business\LoginAccountType;

/**
 * Mock Collection of Person for testing \svelte\http\Session
 * .
 */
class PersonCollection extends RecordCollection
{
}

/**
 * Mock LoginAccount for testing \svelte\http\Session
 * .
 */
class Person extends BusinessModel
{
  private $dataObject;

  /**
   * Mock LoginAccount, new or with encapsulated source data contained.
   * @param \stdClass $dataObject Simple data container
   */
  final public function __construct(\stdClass $dataObject = null)
  {
    if (!isset($dataObject)) {
      $dataObject = new \stdClass();
      $dataObject->id = 'person:new';
      $dataObject->email = null;
    }
    $this->dataObject =  $dataObject;
    parent::__construct(null);
  }

  /**
   * Get ID (URN)
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Unique identifier for *this*
   */
  public function get_id() : Str
  {
    return Str::set($dataObject->id);
  }

  /**
   * Get email
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Email address associated with *this*.
   */
  protected function get_email()
  {
    return new MockField(); //Str::set('email'), $this);
  }

  /**
   * Get email
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Email address associated with *this*.
   */
  protected function get_familyName()
  {
    return new MockField(); //Str::set('email'), $this);
  }

  /**
   * Get email
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \svelte\core\Str Email address associated with *this*.
   */
  protected function get_givenName()
  {
    return new MockField(); //Str::set('email'), $this);
  }

  /**
   * Mocked isValid method returns TRUE
   */
  public function isValid() : bool
  {
    return TRUE; //(isset($this->dataObject->email));
  }
}
