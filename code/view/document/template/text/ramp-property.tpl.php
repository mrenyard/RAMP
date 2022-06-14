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
 * @package RAMP
 * @version 0.0.9;
 */
if ($this->key == 'PRI') {
?>

  private $<?=$this->name; ?>;
  protected function get_<?=$this->name; ?>() : field\Input
  {
    if (!isset($this-><?=$this->name; ?>))
    {
      $this-><?=$this->name; ?> = new field\Input(
        Str::set('<?=$this->name; ?>'),
        $this,
        new validation\dbtype\<?=$this->dataType; ?>

      );
      if ($this->isNew) { $this['<?=$this->name; ?>'] = $this-><?=$this->name; ?>; }
    }
    return $this-><?=$this->name; ?>;
  }
<?php } else { ?>

  protected function get_<?=$this->name; ?>() : field\Input
  {
    if (!isset($this['<?=$this->name; ?>']))
    {
      $this['<?=$this->name; ?>'] = new field\Input(
        Str::set('<?=$this->name; ?>'),
        $this,
        new validation\dbtype\<?=$this->dataType; ?>

      );
    }
    return $this['<?=$this->name; ?>'];
  }
<?php } ?>
