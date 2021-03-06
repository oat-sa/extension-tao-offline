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
 * Copyright (c) 2019 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 */
namespace oat\taoOffline\model\form;

use oat\oatbox\service\ServiceManager;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use tao_actions_form_Instance;
use tao_helpers_form_FormFactory;
use tao_helpers_Uri;

class TestCenterForm extends tao_actions_form_Instance
{
    /**
     * @return void
     */
    protected function initElements()
    {
        parent::initElements();

        $element = $this->form->getElement(
            tao_helpers_Uri::encode(TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY)
        );

        if ($element) {
            $element->addValidator(
                tao_helpers_form_FormFactory::getValidator(
                    'Callback',
                    [
                        'message' => __('Organisation Id must be unique'),
                        'object' => $this->getTestCenterFormService(),
                        'method' => 'validateOrganisationIdValue',
                        'param' => $this->instance ? $this->instance->getUri() : null
                    ]
                )
            );
        }
    }

    /**
     * @return TaoOfflineTestCenterFormService
     */
    private function getTestCenterFormService()
    {
        return ServiceManager::getServiceManager()->get(TaoOfflineTestCenterFormService::SERVICE_ID);
    }
}
