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
namespace ramp\model\business\key;

use ramp\model\business\RecordComponent;

/**
 * Abstract Business Model Record Component Key.
 *
 * RESPONSIBILITIES
 * - Define generalized methods for iteration, validity checking & error reporting.
 *
 * @property-read \ramp\core\Str $id Returns unique identifier (ID) for *this* (URN).
 * @property-read \ramp\core\Str $type Returns type definition as a short list, much like we
 * might use in an HTML class tag (for CSS), we uses *this* and parent classnames to define the
 * resulting values.
 * @property-read bool $hasErrors Returns whether any errors have been recorded following validate().
 * @property-read StrCollection $errors Returns a StrCollection of recorded error messages.
 * @property-read int $count Returns the number of children currently parented by *this*.
 */
abstract class Key extends RecordComponent
{
}
