<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
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
namespace ramp\view;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\http\Request;
use ramp\view\View;

/**
 */
class ViewManager extends RAMPObject 
{
  /**
   * /person/~
   * $page = new document\templated(RootView::getInstance(), Str::set('page'))
   * $page->title = Str::set('');
   * $page->heading = Str::set('');
   * $page->summary = Str::set('');
   * $record = new document\templated($sectionForm, Str::set(record));
   * /person/~/given-name
   * $givenName = new document\Templated($record, Str::set('input));
   * $givenName->title = Str::set('The name by which you are refered by, in western culture usually your first name, a single word consisting only upper and lower case letters');
   * $givenName->label = Str::set('First Name');
   * $givenName->placeholder = Str::set('e.g. John');
   * /person/~/family-name
   * $familyName = new document\Templated($record, Str::set('input));
   * $familyName->title = Str::set('The mostly hereditary portion of one's personal name that indicates one's family, a single word consisting only upper and lower case letters');
   * $familyName->label = Str::set('Surname');
   * $familyName->placeholder = Str::set('e.g. Smith');
   */
  public static function getView(Request $request) : View
  {
    //require_once(RAMP_LOCAL_DIR . RAMP_SITE_VIEWS . '.php');

    /*$viewFunc = str_replace('/', '_', trim((string)$request->resourceIdentifier, '/'));
    \FB::log($viewFunc, 'VIEW');
    if (isset($$viewFunc)) { return $$viewFunc(); }*/

    // DEFAULT VIEW IF NON DEFINED
    $body = new document\Templated(RootView::getInstance(), Str::set('body'));
    $page = new document\Templated($body, Str::set('page'));
    $sectionForm = new document\Templated($page, Str::set('section-form'));
    $record = new document\Templated($sectionForm, Str::set('record'));
    $fieldRelation = new document\Templated($record, Str::set('field-relation'));
    $relationfieldOption = new document\Templated($fieldRelation, Str::set('relationfield-option'));
    $option = new document\Templated($relationfieldOption, Str::set('option'));

    if ($request->get_propertyName()) {
      return $fieldRelation;
    } elseif ($request->get_recordKey()) {
      return $record;
    } elseif ($request->get_recordName()) {
      return $sectionForm;
    }
  }
}
