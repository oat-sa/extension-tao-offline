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
 * Copyright (c) 2018 Open Assessment Technologies SA
 */

namespace oat\taoOffline\scripts\tools\byOrganisationId;

use oat\oatbox\extension\InstallAction;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoSync\model\ui\FormFieldsService;

/**
 * Class SetupDataSynchronization
 *
 * @package oat\taoOffline\scripts\tools\byOrganisationId
 * @author Dieter Raber <dieter@taotesting.com>
 */
class SetupSyncFormByOrgId extends InstallAction
{
    /***
     * Register new org id field for synchronisation form
     *
     * @param array $params
     * @return \common_report_Report
     * @throws \common_Exception
     */
    public function __invoke($params=array())
    {
        /** @var FormFieldsService $formFieldsService */
        $formFieldsService = $this->getServiceLocator()->get(FormFieldsService::SERVICE_ID);
        $fields = (array) $formFieldsService->getOption(FormFieldsService::OPTION_INPUT);

        $orgIdField = [
            TestCenterByOrganisationId::OPTION_ORGANISATION_ID => [
                'element' => 'input',
                'attributes' => [
                    'required' => true,
                    'minlength' => 2
                ],
                'label' => __('Organisation identifier')
            ]
        ];

        $formFieldsService->setOption(FormFieldsService::OPTION_INPUT, array_merge($fields, $orgIdField));
        $this->registerService(FormFieldsService::SERVICE_ID, $formFieldsService);

        $this->logInfo('Configured new form fields for synchronization form.');
        return \common_report_Report::createSuccess('FormFieldsService successfully registered.');
    }

}

