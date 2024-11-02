<?php
/**
 * RAMP - Rapid web application development using best practice.
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
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\OptionList;
use ramp\model\business\field\Option;

/**
 * Pre defined enum/list for use as Login Account Types.
 * .
 */
class LoginAccountType extends OptionList
{
  public const REGISTERED = 1;
  public const USER = 2;
  public const AFFILIATE = 3;
  public const ADMINISTRATOR = 4;
  public const ADMINISTRATOR_MANAGER = 5;
  public const SYSTEM_ADMINISTRATOR = 6;
 
  /**
   * Creates LoginAccountType option list.
   */
  public function __construct()
  {
    parent::__construct(NULL, Str::set('\ramp\model\business\field\Option'));
    $this->add(new Option(0, Str::set('Please choose:')));
    $this->add(new Option(LoginAccountType::REGISTERED, Str::set('Registered')));
    $this->add(new Option(LoginAccountType::USER, Str::set('Customer')));
    $this->add(new Option(LoginAccountType::AFFILIATE, Str::set('Affiliate')));
    $this->add(new Option(LoginAccountType::ADMINISTRATOR, Str::set('Administrator')));
    $this->add(new Option(LoginAccountType::ADMINISTRATOR_MANAGER, Str::set('Administrator Manager')));
    $this->add(new Option(LoginAccountType::SYSTEM_ADMINISTRATOR, Str::set('System Administrator')));
  }
}
