<?php
/**
 * Testing - RAMP - Rapid web application development enviroment for building
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
 * @package RAMP.test
 * @version 0.0.9;
 */
namespace tests\ramp\mocks\model;

use ramp\core\RAMPObject;
use ramp\core\Str;
use ramp\core\Collection;
use ramp\model\business\BusinessModel;
use ramp\model\business\RecordComponentType;
use ramp\model\business\RecordComponent;
use ramp\model\business\Record;
use ramp\model\business\Key;
use ramp\model\business\Field;
use ramp\model\business\RelationToOne;
use ramp\model\business\RelationToMany;

use tests\ramp\mocks\model\MockField;

/**
 * Mock Concreate implementation of \ramp\model\business\Relatable for testing against.
 */
class TestRecord extends Record
{
  private $awatingInitOn; // string
  private $active; // Record
  private $components; // array
  public $primaryKey; // Key
  private $dataObject; // stdClass
  private $modified; // bool
  private $validAtSource; // bool

  public function __construct(\stdClass $dataObject = null)
  {
    $this->children = new Collection(Str::set('\ramp\model\business\BusinessModel'));
    $this->awatingInitOn = NULL;
    $this->active = NULL;
    $this->components = array(array(), array(), array());
    $this->primaryKey = new Key(Str::set('primaryKey'), $this);
    $this->dataObject = (isset($dataObject))? $dataObject : new \stdClass();
    $this->modified = FALSE;
    $this->validAtSource = FALSE;
    $className = get_class($this);
    foreach (get_class_methods($className) as $methodName) {
      if (strpos($methodName, 'get_') === 0) {
        foreach (get_class_methods('\ramp\model\business\Record') as $parentMethod) {
          if ($methodName == $parentMethod || $methodName == 'get_registeredName' || $methodName == 'get_return') { continue 2; }
        }
        $propertyName = str_replace('get_', '', $methodName);
        $this->$propertyName;
      }
    }
    foreach ($this->components[RecordComponentType::KEY] as $name => $o) {
      $this->primaryKey[$this->primaryKey->count] = $this->$name;
      if (!isset($this->dataObject->$name)) { $this->dataObject->$name = NULL; }
    }
    if ($this->primaryKey->value !== NULL) { $this->validAtSource = TRUE; }
    $i = 0;
    foreach ($this->components[RecordComponentType::PROPERTY] as $name => $o) {
      $this[$i++] = $this->$name;
      if (!isset($this->dataObject->$name)) { $this->dataObject->$name = NULL; }
    }
    foreach ($this->components[RecordComponentType::RELATION] as $name => $o) {
      $this[$i++] = $this->$name;
      if (!isset($this->dataObject->$name)) { $this->dataObject->$name = NULL; }
    }
  }

  protected function register(string $name, int $type) : bool
  {
    $this->active = NULL;
    if (!isset($this->components[$type][$name])) {
      $this->components[$type][$name] = '';
      return FALSE;
    }
    if ($this->components[$type][$name] === '') {
      $this->awatingInitOn = $name;
      return TRUE;
    }
      $this->active = $this->components[$type][$name];
      return FALSE;
  }

  protected function initiate(RecordComponent $o)
  {
    if ($this->awatingInitOn != $o->name || (!$o->parent == $this)) {
      throw new \Exception();
    }
    $i = 0;
    if (isset($this->components[RecordComponentType::KEY][(string)$o->name]))
    {
      $this->components[RecordComponentType::KEY][(string)$o->name] = $o;
    }
    else if (isset($this->components[RecordComponentType::PROPERTY][(string)$o->name]))
    {
      $this->components[RecordComponentType::PROPERTY][(string)$o->name] = $o;
    }
    else if (isset($this->components[RecordComponentType::RELATION][(string)$o->name]))
    {
      $this->components[RecordComponentType::RELATION][(string)$o->name] = $o;
    }
    $this->active = $o;
  }

  protected function get_registeredName() : Str
  {
    return Str::set($this->awatingInitOn);
  }

  protected function get_registered() : ?RecordComponent
  {
    return ($this->active !== NULL) ? $this->active : NULL;
  }

  protected function get_alpha() : ?RecordComponent
  {
    if ($this->register('alpha', RecordComponentType::KEY)) {
      $this->initiate(new MockField(
        $this->registeredName,
        $this
      ));
    }
    return $this->registered;
  }

  protected function get_beta() : ?RecordComponent
  {
    if ($this->register('beta', RecordComponentType::PROPERTY)) {
      $this->initiate(new MockField(
        $this->registeredName,
        $this
      ));
    }
    return $this->registered;
  }

  protected function get_delta() : ?RecordComponent
  {
    if ($this->register('delta', RecordComponentType::RELATION)) {
      $this->initiate(new MockField(
        $this->registeredName,
        $this
      ));
    }
    return $this->registered;
  }

  protected static function checkRequired($dataObject) : bool
  {
    return TRUE;
  }
}
