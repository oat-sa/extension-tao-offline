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
use oat\oatbox\service\exception\InvalidServiceManagerException;
use oat\tao\model\import\service\ImportMapperInterface;
use oat\taoOffline\model\import\TestCenterMapper;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\model\import\TestCenterCsvImporterFactory;

class SetupTestCenterImporterByOrgId extends InstallAction
{
    /**
     * Add organisation id property to test center importer
     *
     * @param array $params
     * @return \common_report_Report
     * @throws InvalidServiceManagerException
     * @throws \common_Exception
     */
    public function __invoke($params=array())
    {
        /** @var TestCenterCsvImporterFactory $importerFactory */
        $importerFactory = $this->getServiceLocator()->get(TestCenterCsvImporterFactory::SERVICE_ID);
        $schema = $importerFactory->getOption(TestCenterCsvImporterFactory::OPTION_DEFAULT_SCHEMA);
        $mappers = $importerFactory->getOption(TestCenterCsvImporterFactory::OPTION_MAPPERS);

        $schema[ImportMapperInterface::OPTION_SCHEMA_MANDATORY] = array_merge(
            $schema[ImportMapperInterface::OPTION_SCHEMA_MANDATORY],
            ['organisation id' => TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY]
        );

        $importerFactory->setOption(TestCenterCsvImporterFactory::OPTION_DEFAULT_SCHEMA, $schema);
        $mapper = $importerFactory->create('default')->getMapper();

        $mappers['default'][TestCenterCsvImporterFactory::OPTION_MAPPERS_MAPPER]
            = new TestCenterMapper($mapper->getOptions());

        $importerFactory->setOption(TestCenterCsvImporterFactory::OPTION_MAPPERS, $mappers);

        $this->getServiceManager()->register(TestCenterCsvImporterFactory::SERVICE_ID, $importerFactory);

        return \common_report_Report::createSuccess(__('TestCenterImporter has been successfully registered.'));
    }
}