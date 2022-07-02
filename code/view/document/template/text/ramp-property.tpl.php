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
 * @package RAMP.make
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
<?php } elseif ($this->isForeignKey) {
  switch ($this->selectType) {
    case 0:
?>

  /*
  protected function get_<?=$this->name; ?>() : field\Field
  {
    if (!isset($this['<?=$this->name; ?>']))
    {
      $this['<?=$this->name; ?>'] = new field\Relation(
        Str::set('<?=$this->name; ?>'),
        $this
        // Get Business Model From Data Store.
      );
    }
    return $this['<?=$this->name; ?>'];
  }*/
<?php   break;
    case 1:
    case 2:
      $fieldSelect = ($this->selectType = 2)? 'SelectOne' : 'SelectMany';
?>

  protected function get_<?=$this->name; ?> () : field\Field
  {
    if (!isset($this['<?=$this->name; ?>']))
    {
      $this['<?=$this->name; ?>'] = new field\<?=$fieldSelect; ?>(
        Str::set('<?=$this->name; ?>'),
        $this,
        new <?=ucfirst($this->name); ?>()
      );
    }
    return $this['<?=$this->name; ?>'];
  }
<?php   break;
  }
} else {
?>

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
