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
namespace ramp;

require_once('load.ini.php');
if (isset($_GET['scratch'])) { $GLOBALS["cssScratch"] = $_GET['scratch']; unset($_GET['scratch']); }

$document = view\WebRoot::getInstance();
// $document->type = view\PageType::INDEX;
new view\document\Templated($document, core\Str::set('empty'));

// $modal = $document->setModal('modal404');
// $modal->heading = core\Str::set('404 NOT FOUND!');
// new view\document\Templated($modal, core\Str::set('empty'));


$document->render();
