<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @package RAMP Testing
 * @version 0.0.9;
 */
namespace tests\ramp\model\business\mocks\RecordTest;

use ramp\core\Str;
use ramp\core\iOption;
use ramp\core\OptionList;
use ramp\core\Collection;
use ramp\model\business\field\Option;

/**
 * Pre defined enum/list for testing.
 * .
 */
class ConcreteOptionList extends OptionList
{
  /**
   * Constructor for new instance of ConcreteOptionList.
   */
  public function __construct()
  {
    parent::__construct(null, Str::set('\ramp\model\business\field\Option'));
    $this->add(new Option(0, Str::set('Please choose:')));
    $this->add(new Option(1, Str::set('Option One')));
    $this->add(new Option(2, Str::set('Option Two')));
    $this->add(new Option(3, Str::set('Option Three')));
    $this->add(new Option(4, Str::set('Option Four')));
    $this->add(new Option(5, Str::set('Option Five')));
    $this->add(new Option(6, Str::set('Option Six')));
  }
}
