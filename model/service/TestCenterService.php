<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2019 (original work) Open Assessment Technologies SA;
 *
 *
 */
namespace oat\taoOffline\model\service;

use common_Exception;
use common_exception_Error;
use core_kernel_classes_Property;
use core_kernel_classes_Resource;
use League\Flysystem\FileExistsException;
use oat\tao\model\GenerisServiceTrait;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\model\TestCenterService as OriginTestCenterService;

class TestCenterService extends OriginTestCenterService
{
    use GenerisServiceTrait {
        cloneInstanceProperty as protected cloneInstancePropertyTrait;
    }

    /**
     *
     * @param core_kernel_classes_Resource $source
     * @param core_kernel_classes_Resource $destination
     * @param core_kernel_classes_Property $property
     * @throws FileExistsException
     * @throws common_Exception
     * @throws common_exception_Error
     */
    protected function cloneInstanceProperty(
        core_kernel_classes_Resource $source,
        core_kernel_classes_Resource $destination,
        core_kernel_classes_Property $property
    ) {
        if ($property->getUri() !== TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY) {
            $this->cloneInstancePropertyTrait($source, $destination, $property);
        }
    }
}
