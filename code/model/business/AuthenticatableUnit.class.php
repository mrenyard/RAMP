<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp\model\business;

use ramp\core\Str;
use ramp\condition\PostData;

/**
 * Abstract representing something that can be authenticated.
 *
 * RESPONSIBILITIES
 * - Describe base api for an authenticatible unit.
 *
 * COLLABORATORS
 * - extends {@see ramp\model\business\Record}
 * - used by {@see ramp\model\business\LoginAccount}
 *
 * @property-read \ramp\model\business\field\Field $email Returns an email address field.
 * @property-read bool $isValid Returns whether data is in a valid/complete state from data store or as new.
 */
abstract class AuthenticatableUnit extends Record
{
  /**
   * @ignore
   */
  protected function get_email() : ?RecordComponent
  {
    if ($this->register('email', RecordComponentType::PROPERTY)) {
      $this->initiate(new field\Input($this->registeredName, $this,
        new validation\dbtype\VarChar(
          Str::set('string with a maximun character length of '),
          150,  new validation\EmailAddress(
            Str::set('validly formatted email address')
          )
        )
      ));
    }
    return $this->registered; 
  }

  /**
   * @ignore
   */
  final protected function get_isValid() : bool
  {
    return ($this->email->value !== NULL && parent::get_isValid());
  }
}
