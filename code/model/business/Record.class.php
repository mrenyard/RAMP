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
namespace ramp\model\business;

use ramp\core\Str;
use ramp\core\StrCollection;
use ramp\core\BadPropertyCallException;
use ramp\condition\PostData;

/**
 * A single Record (entry).
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@see \ramp\core\RAMPObject}).
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Define API to maintain and/or assert valid state of dataObject and expose methods to BusinessModelManager.
 * - Define and restrict relational associations to one or many ({@see \ramp\model\business\Relatable}) other Records. 
 * - Managment composite property registration managment and access.
 * 
 * COLLABORATORS
 * - {@see \ramp\model\business\RecordComponent}
 * - {@see \ramp\model\business\RecordComponentType} (ENUM:KEY|PROPRTY|RELATION)
 * - {@see \ramp\model\business\BusinessModelManager}
 *
 * A typical Record (inherited) entery will consist of:
 * - One or more RecordComponent Registration Methods as exampled below:
 * ```php
 * protected function get_alpha() : ?RecordComponent
 * {
 *   if ($this->register('alpha', RecordComponentType::[KEY|PROPERTY|RELATION])) {
 *     $this->initiate(new [field\[Flag|Input|SelectOne|SelectMany]|RelationToOne|RelationToMany](
 *       $this->registeredName,
 *       $this,
 *       [...]
 *     ));
 *   }
 *   return $this->registered;
 * }
 * ```
 *
 * @property-read \ramp\core\Str $primaryKey Returns value of primary key.
 * @property-read bool $isModified Returns whether data has been modified since last update.
 * @property-read bool $isValid Returns whether data is in a valid/complete state from data store or as new.
 * @property-read bool $isNew Returns whether this is yet to be updated to data storage.
 * @property-read \ramp\core\Str $registeredName Returns expected 'name' of current acceptable registered RecordComponent
 * - can ONLY be called within `if ($this->register()) { .. }` statement.
 * @property-read ?\ramp\model\business\RecordComponent $registered Returns most recent registered RecordComponent.
 */
abstract class Record extends Relatable
{
  private ?string $awatingInitOn;
  private ?RecordComponent $active;
  private array $components;
  private PrimaryKey $primaryKey;
  private \stdClass $dataObject;
  private bool $modified;
  private bool $validAtSource;
  private array $required;

  /**
   * Creates record, new or with encapsulated source data contained.
   * @param \stdClass $dataObject Simple data container
   */
  public function __construct(\stdClass $dataObject = NULL)
  {
    parent::__construct();
    $this->awatingInitOn = NULL;
    $this->active = NULL;
    $this->components = array(array(), array(), array());
    $this->primaryKey = new PrimaryKey($this);
    $this->dataObject = ($dataObject !== NULL)? $dataObject : new \stdClass();
    $this->modified = FALSE;
    $this->validAtSource = FALSE;
    $this->required = array();
    $className = get_class($this);
    foreach (get_class_methods($className) as $methodName) {
      if (strpos($methodName, 'get_') === 0) {
        foreach (get_class_methods('\ramp\model\business\Record') as $parentMethod) {
          if ($methodName == $parentMethod) { continue 2; }
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
      $this[$i] = $this->$name;
      $i++;
    }
    foreach ($this->components[RecordComponentType::RELATION] as $name => $o) {
      $this[$i] = $this->$name;
      if ($this[$i] instanceof RelationToOne) { $this[$i]->addForeignKey($this->dataObject); }
      $i++;
    }
  }

  /**
   * @ignore
   */
  #[\Override]
  final protected function get_id() : Str
  {
    $primaryKeyValue = ($this->primaryKey->values === NULL) ?
      Str::NEW() : $this->primaryKey->values->implode(Str::BAR())->replace(Str::SPACE(), Str::PLUS())->lowercase;
    return Str::COLON()->prepend(
      $this->processType((string)$this, TRUE)
    )->append($primaryKeyValue);
  }

  /**
   * @ignore
   */
  final protected function get_primaryKey() : PrimaryKey
  {
    return $this->primaryKey;
  }

  /**
   * Implementation of \IteratorAggregate method for use with foreach etc.
   * @return \Traversable Iterator to iterate over *this* traversable using foreach etc.
   */
  #[\Override]
  public function getIterator() : \Traversable
  {
    return ($this->primaryKey->value === NULL)? $this->primaryKey->getIterator() : parent::getIterator();
  }

  /**
   * @ignore
   */
  #[\Override]
  public function get_count() : int
  {
    return ($this->primaryKey->value === NULL)? count($this->primaryKey) : parent::get_count();
  }

  /**
   * ArrayAccess method offsetSet, USE DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \InvalidArgumentException Adding properties through offsetSet STRONGLY DISCOURAGED!
   */
  #[\Override]
  public function offsetSet($offset, $object) : void
  {
    if (!($object instanceof \ramp\model\business\RecordComponent)) {
      throw new \InvalidArgumentException(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    }
    parent::offsetSet($offset, $object);
  }

  /**
   * Register pre initiation and manage RecordComponent.
   * @param string $name Property name on Record for intended RecordComponent.
   * @param int $type Enum RecordComponentType one of:KEY|PROPERTY|RELATION.
   * @return bool Allow to continue to initiation.
   */
  protected function register(string $name, int $type, bool $required = FALSE) : bool
  {
    $this->active = NULL;
    if (!isset($this->components[$type][$name])) {
      $this->components[$type][$name] = '';
      if ($type === RecordComponentType::KEY || ($type === RecordComponentType::PROPERTY && $required)) { $this->required[$name] = ''; }
      return FALSE;
    }
    if ($this->components[$type][$name] === '') {
      $this->awatingInitOn = $name;
      return TRUE;
    }
      $this->active = $this->components[$type][$name];
      return FALSE;
  }

  /**
   * Pass provided RecordComponent as component of *this* Record.
   * @param RecordComponent $o Record component objet to associate.
   * @throws \BadMethodCallException When called without being proceded by register().
   * @throws \InvalidArgumentException When provided RecordComponent::$parent dose NOT match *this*.
   */
  protected function initiate(RecordComponent $o) : void
  {
    if ($this->awatingInitOn != $o->name) {
      throw new \BadMethodCallException(
        'Method call MUST be proceded by register() (x2) as documented!'
      );
    }
    if ($o->parent !== $this) {
      throw new \InvalidArgumentException(
        'Invalid RecordComponent argument (1), $parent does NOT match this Record.'
      );
    }
    $i = 0;
    if (isset($this->components[RecordComponentType::KEY][(string)$o->name])) {
      $this->components[RecordComponentType::KEY][(string)$o->name] = $o;
    } else if (isset($this->components[RecordComponentType::PROPERTY][(string)$o->name])) {
      $this->components[RecordComponentType::PROPERTY][(string)$o->name] = $o;
      if ($this->isRequiredField((string)$o->name)) { $this->required[(string)$o->name] = $o; }
    } else if (isset($this->components[RecordComponentType::RELATION][(string)$o->name])) {
      $this->components[RecordComponentType::RELATION][(string)$o->name] = $o;
    }
    $this->active = $o;
  }

  /**
   * @ignore
   */
  protected function get_registeredName() : Str
  {
    return Str::set($this->awatingInitOn);
  }

  /**
   * @ignore
   */
  protected function get_registered() : ?RecordComponent
  {
    return ($this->active !== NULL) ? $this->active : NULL;
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   * @todo:mrenyard: uncomment isEditable test.
   */
  #[\Override]
  public function validate(PostData $postdata, $update = TRUE) : void
  {
    // if (!$this->isEditable) {
    //   $this->errorCollection= new StrCollection($this->id . ' is NOT editable!');
    //   return;
    // }
    parent::validate($postdata);
    if ($this->isNew && !$this->hasErrors) { $this->PrimaryKey->validate($postdata); }
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_hasErrors() : bool
  {
    return ($this->primaryKey->hasErrors)? TRUE : parent::get_hasErrors();
  }

  /**
   * @ignore
   */
  #[\Override]
  protected function get_errors() : StrCollection
  {
    if ($this->primaryKey->hasErrors) {
      return $this->primaryKey->errors;
    }
    return parent::get_errors();
  }

  /**
   * Gets the value of a given property.
   * **DO NOT USE, METHOD TO ONLY BE CALLED FROM CHILD FIELD**
   * @param string $propertyName Name of property.
   * @return mixed The value of property assosiated with requested property.
   */
  public function getPropertyValue(string $propertyName) : string|int|float|bool|NULL
  {
    return (isset($this->dataObject->$propertyName)) ? $this->dataObject->$propertyName : NULL;
  }

  /**
   * Sets the value of a given property.
   * **DO NOT USE, METHOD TO ONLY BE CALLED FROM CHILD FIELD**
   * @param string $propertyName Name of property to be set.
   * @param mixed|NULL The value to be set on provided property or NULL to initiate. 
   */
  public function setPropertyValue(string $propertyName, $value = NULL) : void
  {
    $value = (\is_string($value) && $value === '')? NULL : $value;
    if ($this->getPropertyValue($propertyName) == $value) { return; }
    $this->dataObject->$propertyName = $value;
    $this->modified = TRUE;
  }

  /**
   * Check is property is a required field.
   * @param $propertyName Name of property to be assessed.
   * @return bool True if filed is a required field.
   */
  final public function isRequiredField(string $propertyName) : bool
  {
    return isset($this->required[$propertyName]);
  }

  /**
   * @ignore
   */
  final protected function get_isModified() : bool
  {
    return $this->modified;
  }

  /**
   * @ignore
   */
  protected function get_isValid() : bool
  {
    return ($this->validAtSource || (($this->primaryKey->value !== NULL) && $this->checkRequired()));
  }

  /**
   * @ignore
   */
  final protected function get_isNew() : bool
  {
    return (!$this->validAtSource);
  }

  /**
   * Set this as updated.
   * **METHOD TO ONLY BE CALLED FROM BUSINESSMODELMANGER**
   */
  public function updated() : void
  {
    $this->validAtSource = ($this->primaryKey->value === NULL) ? FALSE : ($this->checkRequired());
    $this->modified = FALSE;
  }

  private function checkRequired() : bool
  {
    foreach ($this->required as $name => $value) {
      if (!isset($this->dataObject->$name) || $this->dataObject->$name == NULL) { return FALSE; }
    }
    return TRUE;
  }
}
