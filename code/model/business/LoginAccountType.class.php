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
use svelte\core\iOption;
use svelte\core\Option;
use svelte\core\OptionList;
use svelte\core\Collection;

/**
 * A Single login account type.
 */
class LoginAccountTypeOption extends Option
{
}

/**
 * Pre defined enum/list of svelte\model\business\LoginAccountTypeOption.
 * .
 */
class LoginAccountType extends OptionList
{
  private static $instance;

  /**
   * Accessor to the full collection of enums
   * @return svelte\model\business\LoginAccountTypeOption Full list of enum options
   */
  public static function getInstance() : OptionList
  {
    if (!isset(self::$instance)) {
      self::$instance = new LoginAccountType();
    }
    return self::$instance;
  }

  /**
   * Accessor to each of a collection of enums
   * @param int $index Index/ID of requiered LoginAccountTypeOption
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType of provided index
   */
  public static function get(int $index) : LoginAccountTypeOption
  {
    return self::getInstance()[$index];
  }

  private function __construct()
  {
    $list = new Collection();
    $list[0] = new LoginAccountTypeOption(0, Str::set('Please choose:'));
    $list[1] = new LoginAccountTypeOption(1, Str::set('Registered'));
    $list[2] = new LoginAccountTypeOption(2, Str::set('User'));
    $list[3] = new LoginAccountTypeOption(3, Str::set('Affiliate'));
    $list[4] = new LoginAccountTypeOption(4, Str::set('Administrator'));
    $list[5] = new LoginAccountTypeOption(5, Str::set('Administrator Manager'));
    $list[6] = new LoginAccountTypeOption(6, Str::set('System Administrator'));
    parent::__construct($list);
  }

  /**
   * Returns the REGISTERED variant of LoginAccountType.
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType 'REGISTERED' at index 1
   */
  public static function REGISTERED() : LoginAccountTypeOption
  {
    return self::get(1);
  }

  /**
   * Returns the USER variant of LoginAccountType.
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType 'USER' at index 2
   */
  public static function USER() : LoginAccountTypeOption
  {
    return self::get(2);
  }

  /**
   * Returns the AFFILIATE variant of LoginAccountType.
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType 'AFFILIATE' at index 3
   */
  public static function AFFILIATE() : LoginAccountTypeOption
  {
    return self::get(3);
  }

  /**
   * Returns the ADMINISTRATOR variant of LoginAccountType.
   * @return \svelte\model\business\LoginAccountTypeOptionOption LoginAccountType 'ADMINISTRATOR' at index 4
   */
  public static function ADMINISTRATOR() : LoginAccountTypeOption
  {
    return self::get(4);
  }

  /**
   * Returns the ADMINISTRATOR_MANAGER variant of LoginAccountType.
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType 'ADMINISTRATOR_MANAGER' at index 5
   */
  public static function ADMINISTRATOR_MANAGER() : LoginAccountTypeOption
  {
    return self::get(5);
  }

  /**
   * Returns the SYSTEM_ADMINISTRATOR variant of LoginAccountType.
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType 'SYSTEM_ADMINISTRATOR' at index 6
   */
  public static function SYSTEM_ADMINISTRATOR() : LoginAccountTypeOption
  {
    return self::get(6);
  }
}
