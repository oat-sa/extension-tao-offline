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

use common_exception_Error;
use core_kernel_classes_Class;
use core_kernel_classes_Resource;
use oat\oatbox\service\ConfigurableService;
use oat\taoOffline\model\form\TestCenterForm;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use tao_helpers_form_FormContainer as FormContainer;

class TestCenterFormService extends ConfigurableService
{
    const SERVICE_ID = 'taoTestCenter/TestCenterFormService';

    /**
     * @param core_kernel_classes_Class $clazz
     * @param core_kernel_classes_Resource|null $testCenter
     * @return TestCenterForm
     */
    public function getTestCenterFormContainer(
        core_kernel_classes_Class $clazz,
        core_kernel_classes_Resource $testCenter
    ) {
        return new TestCenterForm($this, $clazz, $testCenter, [FormContainer::CSRF_PROTECTION_OPTION => true]);
    }

    /**
     * @param string $value
     * @param string|null $uri
     * @return bool
     * @throws common_exception_Error
     */
    public function validateOrganisationIdValue($value, $uri = null)
    {
        $resources = $this->getClass()->searchInstances(
            [TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY => $value],
            ['recursive' => true, 'like' => false]
        );

        if ($uri) {
            $resources = array_filter(
                $resources,
                function (core_kernel_classes_Resource $resource) use ($uri) {
                    return $resource->getUri() !== $uri;
                }
            );
        }
        if (count($resources) > 0) {
            return false;
        }
        return true;
    }

    /**
     * @return core_kernel_classes_Class
     * @throws common_exception_Error
     */
    protected function getClass()
    {
        return new core_kernel_classes_Class(TestCenterService::CLASS_URI);
    }
}
