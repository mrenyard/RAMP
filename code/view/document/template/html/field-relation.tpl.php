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
 * 
 */
$tabindex = (isset($this->hasErrors) && $this->hasErrors)? 1:0;
switch ($this->type) {
  case 'relation field': ?>
          <fieldset id="<?=$this->id; ?>"<?=$this->attribute('title'); ?>>
            <legend><?=$this->heading; ?></legend>
            <p class="note"><?=$this->summary; ?></p>
<?php if ($this->hasErrors) { ?>
            <ol class="error">
<?php foreach ($this->errors as $error) { ?>            
              <li><?=$error; ?></p>
<?php } ?>
            </ol>
<?php } ?>
<?=$this->children; ?>
          </fieldset><!-- #<?=$this->id; ?> -->
<?php break; 
  case 'select-one field': ?>
          <label class="<?=$this->type; ?>" for="<?=$this->id; ?>"<?=$this->attribute('title'); ?>>
            <span class="name"><?=$this->label; ?></span>
            <select id="<?=$this->id; ?>" name="<?=$this->id; ?>" required="required" tabindex="<?=$tabindex; ?>">
<?=$this->children; ?>
            </select>
<?php if ($this->hasErrors) { foreach ($this->errors as $error) { ?>            
            <span class="error"><?=$error; ?></span>
<?php } } ?>
          </label>
<?php break;
  case 'input field': ?>
          <label class="<?=$this->type; ?>" for="<?=$this->id; ?>"<?=$this->attribute('title'); ?>>
            <span class="name"><?=$this->label; ?>:</span>
            <input id="<?=$this->id; ?>" name="<?=$this->id; ?>" type="text" placeholder="<?=$this->placeholder; ?>" value="<?=$this->value; ?>" tabindex="<?=$tabindex; ?>" />
<?php if ($this->hasErrors) { foreach ($this->errors as $error) { ?>            
            <span class="error"><?=$error; ?></span>
<?php } } ?>
          </label>
<?php break;
} ?>