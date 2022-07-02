<?php
/**
 * RAMP - Rapid web application development using best practice.
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

/**
 * Collection of GB_Addresses.
 */
class GB_AddressesCollection extends RecordCollection { }

/**
 * Concrete Record for GB_Addresses.
 */
class GB_Addresses extends Record{
  /**
   * Returns property name of concrete classes primary key.
   * @return \ramp\core\Str Name of property that is concrete classes primary key
   */
  public function primaryKeyNames() : StrCollection
  {
    return StrCollection::set();
  }

  protected function get_postalCode() : field\Input
  {
    if (!isset($this['postalCode']))
    {
      $this['postalCode'] = new field\Input(
        Str::set('postalCode'),
        $this,
        new validation\dbtype\VarChar(
          8,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['postalCode'];
  }

  protected function get_PostTown() : field\Input
  {
    if (!isset($this['PostTown']))
    {
      $this['PostTown'] = new field\Input(
        Str::set('PostTown'),
        $this,
        new validation\dbtype\VarChar(
          30,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['PostTown'];
  }

  protected function get_dependentLocality() : field\Input
  {
    if (!isset($this['dependentLocality']))
    {
      $this['dependentLocality'] = new field\Input(
        Str::set('dependentLocality'),
        $this,
        new validation\dbtype\VarChar(
          35,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['dependentLocality'];
  }

  protected function get_doubleDependentLocality() : field\Input
  {
    if (!isset($this['doubleDependentLocality']))
    {
      $this['doubleDependentLocality'] = new field\Input(
        Str::set('doubleDependentLocality'),
        $this,
        new validation\dbtype\VarChar(
          35,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['doubleDependentLocality'];
  }

  protected function get_thoroughfare() : field\Input
  {
    if (!isset($this['thoroughfare']))
    {
      $this['thoroughfare'] = new field\Input(
        Str::set('thoroughfare'),
        $this,
        new validation\dbtype\VarChar(
          80,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['thoroughfare'];
  }

  protected function get_dependentThoroughfare() : field\Input
  {
    if (!isset($this['dependentThoroughfare']))
    {
      $this['dependentThoroughfare'] = new field\Input(
        Str::set('dependentThoroughfare'),
        $this,
        new validation\dbtype\VarChar(
          80,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['dependentThoroughfare'];
  }

  protected function get_buildingNumber() : field\Input
  {
    if (!isset($this['buildingNumber']))
    {
      $this['buildingNumber'] = new field\Input(
        Str::set('buildingNumber'),
        $this,
        new validation\dbtype\TinyInt(
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['buildingNumber'];
  }

  protected function get_buildingName() : field\Input
  {
    if (!isset($this['buildingName']))
    {
      $this['buildingName'] = new field\Input(
        Str::set('buildingName'),
        $this,
        new validation\dbtype\VarChar(
          50,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['buildingName'];
  }

  protected function get_subBuildingName() : field\Input
  {
    if (!isset($this['subBuildingName']))
    {
      $this['subBuildingName'] = new field\Input(
        Str::set('subBuildingName'),
        $this,
        new validation\dbtype\VarChar(
          30,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['subBuildingName'];
  }

  protected function get_PoBoxNumber() : field\Input
  {
    if (!isset($this['PoBoxNumber']))
    {
      $this['PoBoxNumber'] = new field\Input(
        Str::set('PoBoxNumber'),
        $this,
        new validation\dbtype\TinyInt(
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['PoBoxNumber'];
  }

  protected function get_departmentName() : field\Input
  {
    if (!isset($this['departmentName']))
    {
      $this['departmentName'] = new field\Input(
        Str::set('departmentName'),
        $this,
        new validation\dbtype\VarChar(
          60,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['departmentName'];
  }

  protected function get_organisationName() : field\Input
  {
    if (!isset($this['organisationName']))
    {
      $this['organisationName'] = new field\Input(
        Str::set('organisationName'),
        $this,
        new validation\dbtype\VarChar(
          60,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['organisationName'];
  }

  protected function get_SUOrgFLAG() : field\Input
  {
    if (!isset($this['SUOrgFLAG']))
    {
      $this['SUOrgFLAG'] = new field\Input(
        Str::set('SUOrgFLAG'),
        $this,
        new validation\dbtype\Flag(
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['SUOrgFLAG'];
  }

  protected function get_deliveryPointSuffix() : field\Input
  {
    if (!isset($this['deliveryPointSuffix']))
    {
      $this['deliveryPointSuffix'] = new field\Input(
        Str::set('deliveryPointSuffix'),
        $this,
        new validation\dbtype\VarChar(
          2,
          new validation\Alphanumeric(),
          Str::set('My error message HERE!')
        )
      );
    }
    return $this['deliveryPointSuffix'];
  }

  /**
   * Check requeried properties have value or not.
   * @param DataObject to be checked for requiered property values
   * @return bool Check all requiered properties are set.
   */
  protected static function checkRequired($dataObject) : bool
  {
    return (
      isset($dataObject->postalCode) &&
      isset($dataObject->PostTown) &&
      isset($dataObject->SUOrgFLAG) &&
      isset($dataObject->deliveryPointSuffix) 
    );
  }
}
