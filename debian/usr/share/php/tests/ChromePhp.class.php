<?php
/**
 * Testing - Svelte - Rapid web application development enviroment for building
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
 * @version 0.0.9;
 */

/**
 *
 */
class ChromePhp
{
  private static $messages;

  public static function clear()
  {
    self::$messages = array();
  }

  public static function getMessages()
  {
    return self::$messages;
  }

  public static function log(string $label, $message = null)
  {
    $message = (isset($message)) ? (is_array($message)) ? ' ' . implode(', ', $message) : ' ' . $message : '';
    self::$messages[count(self::$messages)] = 'LOG:' . $label . $message;
  }

  public static function group(string $message)
  {
    self::$messages[count(self::$messages)] = 'GROUP:' . $message;
  }

  public static function groupEnd()
  {
    self::$messages[count(self::$messages)] = 'GROUPEND:';
  }
}
