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
namespace ramp\view;

use ramp\model\Model;

/**
 * Belonging to one parent - definition of presentation format and
 * type to be associated with a given Model.
 * 
 * {@inheritdoc}
 */
abstract class ChildView extends View
{
  private $parent;

  /**
   * Base constructor for all Views that require a parent.
   * @param View $parent Parent of this child
   */
  public function __construct(View $parent)
  {
    $this->parent = $parent;
    $parent->add($this);
  }

  /**
   * Set associated Model.
   * Model can be a complex hierarchical ordered tree or a simple one level object,
   * either way it will be interlaced appropriately with *this* View up to the same
   * depth of structure unless specified cascade FALSE as well as upward to containing
   * {@link \ramp\model\business\Record} from {@link \ramp\model\business\field\Field}.
   * @param \ramp\model\Model $model Model containing data used in View
   * @param bool $cascade Set model for child views.
   * @throws \BadMethodCallException Model already set violation.
   *
  public function setModel(Model $model, bool $cascade = TRUE)
  {
    parent::setModel($model, $cascade);
    if ((!$this->parent->hasModel) && ($model instanceof \ramp\model\business\field\Field)) {
      $this->parent->setModel($model->get_containingRecord(), FALSE);
    }
  }*/
}