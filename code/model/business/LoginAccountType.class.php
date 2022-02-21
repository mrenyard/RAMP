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

use svelte\core\Str;
//use svelte\core\iOption;
use svelte\core\OptionList;
//use svelte\core\Collection;
use svelte\model\business\field\Option;

/**
 * Pre defined enum/list for use as Login Account Types.
 * .
 */
class LoginAccountType extends OptionList
{
  public function __construct()
  {
    parent::__construct(null, Str::set('\svelte\model\business\field\Option'));
    $this->add(new Option(0, Str::set('Please choose:')));
    $this->add(new Option(LoginAccountType::REGISTERED(), Str::set('Registered')));
    $this->add(new Option(LoginAccountType::USER(), Str::set('User')));
    $this->add(new Option(LoginAccountType::AFFILIATE(), Str::set('Affiliate')));
    $this->add(new Option(LoginAccountType::ADMINISTRATOR(), Str::set('Administrator')));
    $this->add(new Option(LoginAccountType::ADMINISTRATOR_MANAGER(), Str::set('Administrator Manager')));
    $this->add(new Option(LoginAccountType::SYSTEM_ADMINISTRATOR(), Str::set('System Administrator')));
  }

  /**
   * Returns the REGISTERED enum of LoginAccountType.
   * @return int LoginAccountType 'REGISTERED' enum 1
   */
  public static function REGISTERED() : int { return 1; }

  /**
   * Returns the USER enum of LoginAccountType.
   * @return int LoginAccountType 'USER' enum 2
   */
  public static function USER() : int { return 2; }

  /**
   * Returns the AFFILIATE enum of LoginAccountType.
   * @return int LoginAccountType 'AFFILIATE' enum 3
   */
  public static function AFFILIATE() : int { return 3; }

  /**
   * Returns the ADMINISTRATOR enum of LoginAccountType.
   * @return int LoginAccountType 'ADMINISTRATOR' enum 4
   */
  public static function ADMINISTRATOR() : int { return 4; }

  /**
   * Returns the ADMINISTRATOR_MANAGER enum of LoginAccountType.
   * @return int LoginAccountType 'ADMINISTRATOR_MANAGER' enum 5
   */
  public static function ADMINISTRATOR_MANAGER() : int { return 5; }

  /**
   * Returns the SYSTEM_ADMINISTRATOR enum of LoginAccountType.
   * @return int LoginAccountType 'SYSTEM_ADMINISTRATOR' enum 6
   */
  public static function SYSTEM_ADMINISTRATOR() : int { return 6; }
}
