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
 */

namespace oat\taoOffline\scripts\tools\byOrganisationId;

use oat\oatbox\extension\InstallAction;
use oat\taoOffline\model\service\TestCenterService;

/**
 * Class RegisterSyncService
 *
 * sudo -u www-data php index.php '\oat\taoOffline\scripts\tools\byOrganisationId\RewriteTestCenterManagerService'
 *
 * @package oat\taoOffline\scripts\tools\byOrganisationId
 */
class RewriteTestCenterManagerService extends InstallAction
{
    public function __invoke($params)
    {
        $options = [];
        if ($this->getServiceManager()->has(TestCenterService::SERVICE_ID)) {
            $options = $this->getServiceLocator()->get(TestCenterService::SERVICE_ID)->getOptions();
            $this->getServiceManager()->unregister(TestCenterService::SERVICE_ID);
        }

        $this->getServiceManager()->register(TestCenterService::SERVICE_ID, new TestCenterService($options));
        return \common_report_Report::createSuccess(__('TestCenterManagerService has been successfully updated.'));
    }
}
