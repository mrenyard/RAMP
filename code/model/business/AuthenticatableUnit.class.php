<?php
/**
 * Svelte - Rapid web application development enviroment for building
 *  flexible, customisable web systems.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;,
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

use svelte\core\Str;
use svelte\condition\PostData;
use svelte\model\business\Record;

/**
 * Abstract representing something that can be authenticated.
 *
 * RESPONSIBILITIES
 * - Describe base api for an authenticatible unit.
 *
 * COLLABORATORS
 * - extends {@link svelte\model\business\Record}
 * - used by {@link svelte\model\business\LoginAccount}
 *
 * @property-read \svelte\model\business\field\Field $email Returns an email address field.
 */
abstract class AuthenticatableUnit extends Record
{
  private $email;

  /**
   * Get email
   * **DO NOT CALL DIRECTLY, USE this->email;**
   * @return field\Field Containing value for email.
   */
  protected function get_email() : field\Field
  {
    if (!isset($this->email))
    {
      $this->email = new field\Input(
        Str::set('email'),
        $this,
        new validation\dbtype\VarChar(
          150,
          new validation\RegexEmail(),
          Str::set('Please provide a correctly formated email address!')
        )
      );
      if ($this->isNew) { $this['email'] = $this->email; }
    }
    return $this->email;
  }

  /**
   * Set this as updated.
   * **METHOD TO ONLY BE CALLED FROM BUSINESSMODELMANGER**
   */
  final public function updated()
  {
    if (isset($this['email'])) { unset($this['email']); }
    parent::updated();
  }

  /**
   * Returns whether data is in a valid/complete state from data store or as new.
   * **DO NOT CALL DIRECTLY, USE this->isValid;**
   * @return bool Value of isValid
   */
  final protected function get_isValid() : bool
  {
    return (($this->email->value != NULL) && parent::get_isValid());
  }
}
