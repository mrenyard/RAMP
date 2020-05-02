<?php
/**
 * Testing - Svelte - Rapid web application development using best practice.
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
 * @version 0.0.9;
 */
namespace tests\svelte\model\business\mocks\RecordTest;

use svelte\core\Str;
use svelte\core\iOption;
use svelte\core\Option;
use svelte\core\OptionList;
use svelte\core\Collection;

/**
 * A Single Concrete Option.
 */
class ConcreteOption extends Option
{
}

/**
 * Pre defined enum/list for testing.
 * .
 */
class ConcreteOptionList extends OptionList
{
  private static $instance;

  /**
   * Accessor to the full collection of enums
   * @return tests\svelte\model\business\mocks\RecordTest\ConcreteOptionList Full list of enum options
   */
  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      self::$instance = new ConcreteOptionList();
    }
    return self::$instance;
  }

  /**
   * Accessor to each of a collection of enums
   * @param int $index Index/ID of requiered LoginAccountTypeOption
   * @return \svelte\model\business\LoginAccountTypeOption LoginAccountType of provided index
   */
  public static function get(int $index) : iOption
  {
    return self::getInstance()[$index];
  }

  private function __construct()
  {
    $list = new Collection();
    $list[0] = new ConcreteOption(0, Str::set('Please choose:'));
    $list[1] = new ConcreteOption(1, Str::set('Option One'));
    $list[2] = new ConcreteOption(2, Str::set('Option Two'));
    $list[3] = new ConcreteOption(3, Str::set('Option Three'));
    $list[4] = new ConcreteOption(4, Str::set('Option Four'));
    $list[5] = new ConcreteOption(5, Str::set('Option Five'));
    $list[6] = new ConcreteOption(6, Str::set('Option Six'));
    parent::__construct($list);
  }
}
