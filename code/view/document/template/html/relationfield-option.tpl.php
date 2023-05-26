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
$tabindex = (isset($this->hasErrors) && $this->hasErrors)? 1:0;
switch ($this->type) {
  case 'option business-model': ?>
              <option value="<?=$this->key; ?>"<?=($this->isSelected)? ' selected':''; ?>><?=$this->description; ?></option>
<?php break;
  case 'relation field': ?>
              <label class="<?=$this->type; ?>"<?=$this->attribute('title'); ?>>
                <a href="<?=$this->value; ?>"><?=$this->id; ?></a>
              </label>
<?=$this->children; ?>
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
  case 'foreign-key-part field': ?>
            <label class="<?=$this->type; ?>" for="<?=$this->id; ?>"<?=$this->attribute('title'); ?>>
              <span class="name"><?=$this->label; ?>:</span>
              <input id="<?=$this->id; ?>" name="<?=$this->id; ?>" type="text" placeholder="<?=$this->placeholder; ?>" value="<?=$this->value; ?>" tabindex="<?=$tabindex; ?>" />
<?php if ($this->hasErrors) { foreach ($this->errors as $error) { ?>            
                <span class="error"><?=$error; ?></span>
<?php } } ?>
            </label>
<?php break; } ?>
