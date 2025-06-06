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
?>
          <fieldset class="<?=$this->class; ?><?=($this->isRequired) ? ' required' : ''; ?><?=($this->hasErrors) ? ' error' : ''; ?>"<?=$this->attribute('title'); ?>>
            <legend><?=$this->label; ?></legend>
            <input id="<?=$this->id; ?>" name="<?=$this->id; ?>:true" type="checkbox" tabindex="<?=($this->hasErrors)? 1:0; ?>"<?=$this->attribute('required'); ?><?=(!$this->isEditable) ? ' readonly="readonly"' : ''; ?><?=(!$this->hasErrors && $this->value === TRUE) ? ' checked="checked"' : ''; ?> />
            <label for="<?=$this->id; ?>"><?=$this->summary; ?></label>
          </fieldset>
