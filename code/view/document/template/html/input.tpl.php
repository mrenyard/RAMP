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
?>
          <div class="<?=$this->inputType; ?> <?=$this->class; ?><?=($this->isRequired) ? ' required' : ''; ?><?=($this->hasErrors) ? ' error' : ''; ?>"<?=$this->attribute('title'); ?>>
            <label for="<?=$this->id; ?>"><?=$this->label; ?></label>
            <input id="<?=$this->id; ?>" name="<?=$this->id; ?>" type="<?=$this->inputType; ?>" tabindex="<?=($this->hasErrors)? 1:0; ?>"<?=$this->attribute('placeholder') ?><?=$this->attribute('required'); ?><?=$this->attribute('pattern'); ?><?=$this->attribute('maxlength'); ?><?=$this->attribute('min'); ?><?=$this->attribute('max'); ?><?=$this->attribute('step'); ?><?=(!$this->isEditable) ? ' readonly' : ''; ?><?=$this->attribute('value'); ?>>
<?php if ($this->hasErrors) { ?>
            <span class="hint"><?=$this->hint; ?>.<?php if ($this->value != '') { ?> <em>Previous value was: <?=$this->value; ?>.</em><?php } ?></span>
<?php } ?>
          </div>
