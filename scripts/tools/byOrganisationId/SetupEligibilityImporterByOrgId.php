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
use oat\tao\model\import\service\ImportMapperInterface;
use oat\tao\model\import\service\RdsValidatorValueMapper;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\model\EligibilityService;
use oat\taoTestCenter\model\import\EligibilityCsvImporterFactory;

class SetupEligibilityImporterByOrgId extends InstallAction
{
    /**
     * Alter eligibility importer to associated test center to organisation id
     *
     * @param $params
     * @return \common_report_Report
     * @throws \common_Exception
     */
    public function __invoke($params)
    {
        /** @var EligibilityCsvImporterFactory $eligibilityImporter */
        $eligibilityImporter = $this->getServiceLocator()->get(EligibilityCsvImporterFactory::SERVICE_ID);
        $schema = $eligibilityImporter->getOption(EligibilityCsvImporterFactory::OPTION_DEFAULT_SCHEMA);

        /** @var RdsValidatorValueMapper $rdsMapper */
        $rdsMapper = $schema[ImportMapperInterface::OPTION_SCHEMA_MANDATORY]['test center'][EligibilityService::PROPERTY_TESTCENTER_URI];
        $rdsMapper->setOption(RdsValidatorValueMapper::OPTION_PROPERTY, TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY);
        $schema[ImportMapperInterface::OPTION_SCHEMA_MANDATORY]['test center'][EligibilityService::PROPERTY_TESTCENTER_URI] = $rdsMapper;

        $eligibilityImporter->setOption(EligibilityCsvImporterFactory::OPTION_DEFAULT_SCHEMA, $schema);
        $this->registerService(EligibilityCsvImporterFactory::SERVICE_ID, $eligibilityImporter);

        return \common_report_Report::createSuccess(__('EligibilityImporter has been successfully imported.'));
    }

}