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
namespace oat\taoOffline\controller;

use common_exception_Error;
use common_exception_RestApi;
use oat\generis\model\OntologyRdfs;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\controller\RestTestCenter as ParentRestTestCenterController;

class RestTestCenter extends ParentRestTestCenterController
{
    const PARAMETER_TEST_CENTER_ORGANISATION_ID = 'organisationId';

    /**
     * @var array
     */
    protected $parametersMap = [
        self::PARAMETER_TEST_CENTER_LABEL => OntologyRdfs::RDFS_LABEL,
        self::PARAMETER_TEST_CENTER_ORGANISATION_ID => TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY
    ];

    /**
     * @param array $values
     * @return array
     * @throws common_exception_RestApi|common_exception_Error
     */
    protected function prepareRequestData(array $values)
    {
        $propertiesValues = parent::prepareRequestData($values);

        if (empty($values[self::PARAMETER_TEST_CENTER_ORGANISATION_ID])) {
            throw new common_exception_RestApi ('organisation id required');
        }

        $uri = array_key_exists('uri', $values) ? $values['uri'] : null;
        $this->getValidatorsService()->validateOrganisationIdValue(
            $values[self::PARAMETER_TEST_CENTER_ORGANISATION_ID],
            $uri
        );
        $propertiesValues[$values[self::PARAMETER_TEST_CENTER_ORGANISATION_ID]] = $uri;
        return $propertiesValues;
    }

    /**
     * @return TaoOfflineTestCenterFormService
     */
    private function getValidatorsService()
    {
        return $this->getServiceLocator()->get(TaoOfflineTestCenterFormService::SERVICE_ID);
    }
}
