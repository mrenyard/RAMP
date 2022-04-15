<?php
/**
 * RAMP - Rapid web application development enviroment for building
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
 * @package ramp
 * @version 0.0.9;
 */
namespace ramp\view;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\http\Request;

/**
 */
class ViewManager extends RAMPObject 
{
  /**
   */
  public static function getView(Request $request)
  {
    //require_once(RAMP_LOCAL_DIR . RAMP_SITE_VIEWS . '.php');

    /*$viewFunc = str_replace('/', '_', trim((string)$request->resourceIdentifier, '/'));
    \FB::log($viewFunc, 'VIEW');
    if (isset($$viewFunc)) { return $$viewFunc(); }*/

    // DEFAULT VIEW IF NON DEFINED
    $body = new document\Templated(RootView::getInstance(), Str::set('body'));
    $page = new document\Templated($body, Str::set('page'));
    $section = new document\Templated($page, Str::set('section'));
    $form = new document\Templated($section, Str::set('form'));
    $formfield = new document\Templated($form, Str::set('formfield'));
    $option = new document\Templated($formfield, Str::set('option'));

    if ($request->get_propertyName()) {
      return $formfield;
    } elseif ($request->get_recordKey()) {
      return $form;
    } elseif ($request->get_recordName()) {
      return $section;
    }
  }
}
