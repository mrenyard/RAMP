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

use ramp\core\RAMPObject;
use ramp\condition\Filter;

/**
 * Defined abstract for business model managers that manage all models within systems business domain.
 *
 * RESPONSIBILITIES
 * - Create and manage business models
 * - Act as intermediary to any permanent data store
 * - Ensure only one version of any data row in system
 */
abstract class BusinessModelManager extends RAMPObject
{
  /**
   * Get instance - same instance on every request (singleton) within same http request.
   */
  abstract public static function getInstance() : BusinessModelManager;

  /**
   * Returns requested Model.
   * @param \ramp\model\business\iBusinessModelDefinition $definition Definition of requested Model
   * @param \ramp\condition\Filter $filter Optional Filter to be apply to BusinessModel
   * @param int $fromIndex Optional index of first entry in a collection
   * @return \ramp\model\business\BusinessModel Relevant requested BusinessModel
   * @throws \DomainException When {@see \ramp\model\business\BusinessModel}(s) NOT found
   * @throws \ramp\model\business\DataFetchException When unable to fetch from data store
   */
  abstract public function getBusinessModel(iBusinessModelDefinition $definition, Filter $filter = null, $fromIndex = null) : BusinessModel;

  /**
   * Update {@see BusinessModel} to any permanent data store
   * @param \ramp\model\business\BusinessModel $model BusinessModel object to be updated
   * @throws \InvalidArgumentException when {@see \ramp\model\business\BusinessModel}
   *  was not initially retrieved using *this* BusinessModelManager
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  abstract public function update(BusinessModel $model) : void;

  /**
   * Ensure update of anything out of sync within data store.
   * @throws \ramp\model\business\DataWriteException When unable to write to data store
   */
  abstract public function updateAny() : void;
}
