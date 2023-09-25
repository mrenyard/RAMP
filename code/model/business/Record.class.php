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
// use ramp\core\iOption;
// use ramp\core\Collection;
use ramp\core\StrCollection;
// use ramp\condition\PostData;
// use ramp\core\PropertyNotSetException;
use ramp\model\business\Relatable;

/**
 * A single Record (entry).
 *
 * RESPONSIBILITIES
 * - Provide generalised methods for property access (inherited from {@link \ramp\core\RAMPObject}).
 * - Define generalized methods for iteration, validity checking & error reporting.
 * - Define and restrict relational association to objects of this type ({@link \ramp\model\business\Relatable}). 
 * - Define API to maintain and/or assert valid state of dataObject and expose methods to BusinessModelManager.
 * - Implement methods for field/property managment and access.
 *
 * COLLABORATORS
 * - {@link \ramp\model\business\RecordComponent}s
 *
 * @property-read \ramp\core\Str $primaryKey Returns value of primary key.
 * @property-read bool $isModified Returns whether data has been modified since last update.
 * @property-read bool $isValid Returns whether data is in a valid/complete state from data store or as new.
 * @property-read bool $isNew Returns whether this is yet to be updated to data storage.
 */
abstract class Record extends Relatable
{
  private $dataObject;
  private $validFromSource;
  private $modified;
  private $primaryKeyField;

  /**
   * Returns property name/s of concrete classes primary key.
   * @return \ramp\core\StrCollection Primary Key Name/s
   */
  abstract public function primaryKeyNames() : StrCollection;

  /**
   * Creates record, new or with encapsulated source data contained.
   * @param \stdClass $dataObject Simple data container
   */
  public function __construct(\stdClass $dataObject = null)
  {
    parent::__construct();
    $this->dataObject = (isset($dataObject))? $dataObject : new \stdClass();
    $this->updated();
    $className = get_class($this);
    foreach (get_class_methods($className) as $methodName)
    {
      if (strpos($methodName, 'get_') === 0)
      {
        foreach (get_class_methods(__NAMESPACE__ . '\Record') as $parentMethod)
        {
          if ($methodName == $parentMethod) { continue 2; }
        }
        $propertyName = str_replace('get_', '', $methodName);
        $this->$propertyName;
      }
    }
  }

  /**
   * Get ID (URN).
   * **DO NOT CALL DIRECTLY, USE this->id;**
   * @return \ramp\core\Str Unique identifier for *this*
   */
  final protected function get_id() : Str
  {
    $primaryKeyValue = ($this->isNew || !isset($this->primaryKey->value)) ?
      Str::NEW() : Str::set($this->primaryKey->value); 
    //s->implode(Str::BAR())->replace(Str::SPACE(),Str::PLUS()));
    return Str::COLON()->prepend(
      $this->processType((string)$this, TRUE)
    )->append($primaryKeyValue);
  }

  /**
   * Returns value of primary key.
   * **DO NOT CALL DIRECTLY, USE this->key;**
   * @return \ramp\core\Str Value of primary key
   */
  final protected function get_primaryKey() : key\Primary
  {
    if (!isset($this->primaryKeyField)) {
      $this->primaryKeyField = new key\Primary($this);
    }
    return $this->primaryKeyField;
  }

  /**
   * ArrayAccess method offsetSet, USE DISCOURAGED.
   * @param mixed $offset Index to place provided object.
   * @param mixed $object RAMPObject to be placed at provided index.
   * @throws \BadMethosCallException Adding properties through offsetSet STRONGLY DISCOURAGED!
   */
  public function offsetSet($offset, $object)
  {
    if (!($object instanceof \ramp\model\business\RecordComponent)) {
      throw new \BadMethodCallException(
        'Adding properties through offsetSet STRONGLY DISCOURAGED, refer to manual!'
      );
    }
    $propertyName = $object->propertyName;
    // if ($object instanceof \ramp\model\business\field\Relation) {
    //   $propertyName = $propertyName->prepend(Str::FK());
    // }
    if (!isset($this->dataObject->$propertyName)) {
      $this->dataObject->$propertyName = NULL;
    }
    parent::offsetSet($offset, $object);
  }

  /**
   * Validate postdata against this and update accordingly.
   * @param \ramp\condition\PostData $postdata Collection of InputDataCondition\s
   *  to be assessed for validity and imposed on *this* business model.
   *
  final public function validate(PostData $postdata)
  {
    parent::validate($postdata);
    if ($this->isNew && $this->isModified && $this->checkRequired($this->dataObject)) {
      $this->PrimaryKey->validate($postdata);
    }
  }*/

  /**
   * Checks if any errors have been recorded following validate().
   * **DO NOT CALL DIRECTLY, USE this->hasErrors;**
   * @return bool True if an error has been recorded
   *
  protected function get_hasErrors() : bool
  {
    return (parent::get_hasErrors())? TRUE : $this->primaryKey->hasErrors;
  }*/

  /**
   * Gets collection of recorded errors.
   * **DO NOT CALL DIRECTLY, USE this->errors;**
   * @return StrCollection List of recorded errors.
   *
  protected function get_errors() : StrCollection
  {
    $errors = StrCollection::set();
    if ($this->primaryKey->hasErrors) {
      $errors->add($this->primaryKey->errors[0]);
      return $errors;
    }
    return parent::get_errors();
  }*/

  /**
   * Gets the value of a given property.
   * **DO NOT USE, METHOD TO ONLY BE CALLED FROM CHILD FIELD**
   * @param string $propertyName Name of property.
   * @return mixed The value of property assosiated with requested property.
   */
  public function getPropertyValue(string $propertyName)
  {
    return (isset($this->dataObject->$propertyName)) ? $this->dataObject->$propertyName : NULL;
  }

  /**
   * Sets the value of a given property.
   * **DO NOT USE, METHOD TO ONLY BE CALLED FROM CHILD FIELD**
   * @param string $propertyName Name of property to be set.
   * @param mixed The value to be set on provided property.
   */
  public function setPropertyValue(string $propertyName, $value)
  {
    $value = (\is_string($value) && $value === '')? NULL: $value;
    if ($this->getPropertyValue($propertyName) === $value) { return; }
    $this->dataObject->$propertyName = $value;
    $this->modified = TRUE;
  }

  /**
   * Returns whether data has been modified since last update.
   * **DO NOT CALL DIRECTLY, USE this->isModified;**
   * @return bool Value of isModified
   */
  final protected function get_isModified() : bool
  {
    return $this->modified;
  }

  /**
   * Returns whether data is in a valid/complete state from data store or as new.
   * **DO NOT CALL DIRECTLY, USE this->isValid;**
   * @return bool Value of isValid
   */
  protected function get_isValid() : bool
  {
    return ($this->validFromSource || (isset($this->primaryKey->value) && $this->checkRequired($this->dataObject)));
  }

  /**
   * Returns whether this is yet to be updated to data storage.
   * **DO NOT CALL DIRECTLY, USE this->new;**
   * @return bool Value of isNew
   */
  final protected function get_isNew() : bool
  {
    return (!$this->validFromSource);
  }

  /**
   * Set this as updated.
   * **METHOD TO ONLY BE CALLED FROM BUSINESSMODELMANGER**
   */
  public function updated()
  {
    $this->modified = FALSE;
    $pkNames = $this->primaryKeyNames();
    foreach ($pkNames as $primaryKeyName) {
      $pkName = (string)$primaryKeyName;
      if (!isset($this->dataObject->$pkName)) {
        $this->validFromSource = FALSE;
        return;
      }
    }
    $this->validFromSource = ($this->checkRequired($this->dataObject));
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  abstract protected static function checkRequired($dataObject) : bool;
}
