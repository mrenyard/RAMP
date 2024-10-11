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
namespace ramp;

require_once '/usr/share/php/ramp/SETTING.class.php';

defined('DEV_MODE') || define('DEV_MODE', (explode('.', $_SERVER['HTTP_HOST'])[0] == 'dev'));
if (DEV_MODE && isset($_GET['scratch'])) {
  SETTING::$SCRATCH__CSS = explode('|', $_GET['scratch']);
  unset($_GET['scratch']);
}

$config = parse_ini_file(__DIR__.'/../ramp.ini', \TRUE);
foreach ($config as $sectionName => $section) {
  foreach ($section as $settingName => $settingValue) {
    $settingName = strtoupper($sectionName.'_'.$settingName);
    SETTING::$$settingName = $settingValue;
  }
}
set_include_path( "'" . SETTING::$RAMP_LOCAL_DIR . "'" . PATH_SEPARATOR . get_include_path() );

/**
 * register '__autoload()' function to handle namespaces for RAMP projects
 * [http://php.net/manual/en/language.oop5.autoload.php]
 */
spl_autoload_register(function ($class_name) {
  $class_name = str_replace('\\','/', $class_name);
  $local_path = SETTING::$RAMP_LOCAL_DIR . '/' . $class_name . '.class.php';
  $core_path = '/usr/share/php/'. $class_name . '.class.php';
  if (file_exists($local_path)) { require_once($local_path); }
  else if (file_exists($core_path)) { require_once($core_path); }  
}, TRUE, TRUE);
