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

switch ((string)$this->type) {
  case 'relation record-component': ?>
          <fieldset id="<?=$this->id; ?>"<?=$this->attribute('title'); ?>>
            <legend><?=$this->heading; ?></legend>
            <p class="note"><?=$this->summary; ?></p>
<?php if ($this->hasErrors) { ?>
            <ol class="error"><?php foreach ($this->errors as $error) { ?>
              <li><?=$error; ?></p><?php } ?>
            </ol>
<?php } ?>
            <input type="checkbox" id="<?=$this->id; ?>[unset]" name="<?=$this->id; ?>[unset]" />
<?=$this->children; ?>
          </fieldset><!-- #<?=$this->id; ?> -->
<?php break; 
  case 'select-one field':
  case 'select-many field':
    include('select-from.tpl.php');
    break;
  case 'input field':
    include('input.tpl.php');
    break;
  case 'flag field':
    include('checkbox-fieldset.tpl.php');
    break;
  default: ?>
          <p><?=$this->id; ?></p>
<?php }
