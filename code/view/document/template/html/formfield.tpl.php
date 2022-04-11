<?php
/**
 * Svelte - Rapid web application development enviroment for building
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
 * @package svelte
 * @version 0.0.9;
 * 
 */
$tabindex = (isset($this->hasErrors) && $this->hasErrors)? 1:0;
switch ($this->type) {
  case ' select-one field': ?>
          <label class="<?=$this->type; ?>" for="<?=$this->id; ?>" title="<?=$this->title; ?>" >
            <select id="<?=$this->id; ?>" name="<?=$this->id; ?>" required="required">
<?=$this->children; ?>
            </select>
            <span class="name" data-error-message="Please select from the list below"><?=$this->label; ?></span>
          </label>
<?php break;
  default: ?>
          <label class="<?=$this->type; ?>" for="<?=$this->id; ?>" title="<?=$this->title; ?>" >
            <span class="name" data-error-message="Error message"><?=$this->label; ?>:</span>
            <input id="<?=$this->id; ?>" name="<?=$this->id; ?>" type="text" placeholder="<?=$this->placeholder; ?>" value="<?=$this->value; ?>" tabindex="<?=$tabindex; ?>" />
          </label>
<?php break;
} ?>