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
?>
      <section id="<?=$this->id; ?>"<?=$this->attribute('class'); ?>><form method="post">
        <header>
          <h2><a href="#<?=$this->id; ?>"<?=$this->attribute('title'); ?>>#</a><?=$this->heading; ?></h2>
          <p class="note"><?=$this->summary; ?></p>
<?php if (isset($this->hasErrors) && $this->hasErors) { ?>
          <ol class="error">
<?php foreach ($this->errors as $error) { ?>            
            <li><?=$error; ?></p>
<?php } ?>
          </ol>
<?php } ?>
        </header>
<?=$this->children; ?>
        <footer>
          <p><?=$this->summary; ?></p>
        </footer>
        <div class="controls"><input type="submit" value="Update" /></div>
      </form></section><!-- #<?=$this->id; ?> -->
