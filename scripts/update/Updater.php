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
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA;
 *
 */

namespace oat\taoOffline\scripts\update;

use oat\tao\model\import\service\ImportMapperInterface;
use oat\taoOffline\model\import\TestCenterMapper;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;
use oat\taoOffline\model\service\TestCenterService;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\model\import\TestCenterCsvImporterFactory;
use oat\taoTestCenter\model\TestCenterFormService;

class Updater extends \common_ext_ExtensionUpdater
{
    public function update($initialVersion)
    {
        $this->skip('0.1.0', '2.2.1');

        if ($this->isVersion('2.2.1')) {
            $options = [];

            if ($this->getServiceManager()->has(TestCenterService::SERVICE_ID)) {
                $options = $this->getServiceManager()->get(TestCenterService::SERVICE_ID)->getOptions();
            }

            $this->getServiceManager()->register(
                TestCenterService::SERVICE_ID,
                new TestCenterService($options)
            );

            $this->getServiceManager()->register(
                TestCenterFormService::SERVICE_ID,
                new TaoOfflineTestCenterFormService()
            );

            /** @var TestCenterCsvImporterFactory $importerFactory */
            $importerFactory = $this->getServiceManager()->get(TestCenterCsvImporterFactory::SERVICE_ID);
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

            $this->setVersion('2.3.0');
        }

        $this->skip('2.3.0', '2.4.2');
    }
}
