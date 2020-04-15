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
 * @author Andy Robinson (andy@andyr.co.uk)
 * @package svelte
 * @version 0.0.9;
 */
namespace svelte\core;

/**
 * Strongly typed Object used universally within Svelte.
 *
 * RESPONSIBILITIES
 *
 * - Act as superclass for all <i>Object</i>s (everything can cast to <i>SvelteObject</i>).
 * - Define C# type <i>get</i> access to properties by passing
 * <samp>$object->aProperty;</samp> to <samp>$object->get_aProperty();</samp>
 * - Define C# type <i>set</i> access to properties by passing
 * <samp>$object->aProperty = $value;</samp> to <samp>$object->set_aProperty($value);</samp>
 * - Define default <samp>_toString()</samp> method.
 */
abstract class SvelteObject {

  /**
   * Allows C# type access to properties.
   * <b>DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!</b>
   *
   * <pre><b>Passes:</b> <code>$object->aProperty;</code>
   * <b>to:</b> <code>$object->get_aProperty();</code></pre>
   *
   * Implementation in concrete Object
   * <pre>private aProperty;
   *
   * protected function get_aProperty()
   * {
   *   return $this->aProperty;
   * }</pre>
   *
   * Called externally (C# style)
   * <pre>$object->aProperty; //Get value 'aProperty'</pre>
   *
   * @param string $propertyName Name of property (handled internally)
   * @return mixed|void The value of requested property
   * @throws \svelte\core\BadPropertyCallException Undefined or inaccessible property called
   */
  public function __get($propertyName)
  {
    if (!method_exists($this, ($method = 'get_'.$propertyName))) {
      throw new BadPropertyCallException(
        'Unable to locate '.$propertyName.' of \''.get_class($this).'\''
      );
    }
    return $this->$method();
  }

  /**
   * Allows C# type access to properties.
   * <b>DO NOT CALL THIS METHOD DIRECTLY, TO BE HANDLED INTERNALLY!</b>
   *
   * <pre><b>Passes:</b> <code>$object->aProperty = $value;</code>
   * <b>to:</b> <code>$object->set_aProperty($value);</code></pre>
   *
   * Implementation in concrete Object
   * <pre>private aProperty;
   *
   * protected function set_aProperty($value)
   * {
   *   $this->aProperty = $value;
   * }</pre>
   *
   * Called externally (C# style)
   * <pre>$object->aProperty = $value; //Set value of 'aProperty'</pre>
   *
   * @param string $propertyName Name of property (handled internally)
   * @param mixed $propertyValue The value to set on requested property (handled internally)
   * @throws \svelte\core\PropertyNotSetException Unable to set property when undefined or inaccessible
   */
  public function __set($propertyName, $propertyValue)
  {
    if (!method_exists($this, ($method = 'set_'.$propertyName))) {
      throw new PropertyNotSetException(
        get_class($this).'->'.$propertyName.' is NOT settable'
      );
    }
    $this->$method($propertyValue);
  }

  /**
   * Returns this 'class name' for screen output.
   * @return string name of class.
   */
  public function __toString()
  {
    return get_class($this);
  }
}
