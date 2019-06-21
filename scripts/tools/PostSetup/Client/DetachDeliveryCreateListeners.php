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
 */
namespace oat\taoOffline\scripts\tools\PostSetup\Client;

use oat\oatbox\event\EventManager;
use oat\oatbox\extension\InstallAction;
use oat\taoDeliveryRdf\model\event\DeliveryCreatedEvent;
use oat\taoProctoring\model\delivery\DeliverySyncService;
use oat\taoDeliveryRdf\model\TestRunnerFeatures;

class DetachDeliveryCreateListeners extends InstallAction
{
    /**
     * @param $params
     * @return \common_report_Report
     * @throws \common_Exception
     */
    public function __invoke($params)
    {
        $eventManager = $this->getServiceManager()->get(EventManager::SERVICE_ID);
        $eventManager->detach(DeliveryCreatedEvent::class, [DeliverySyncService::SERVICE_ID, 'onDeliveryCreated']);
        $eventManager->detach(DeliveryCreatedEvent::class, [TestRunnerFeatures::class, 'enableDefaultFeatures']);
        $this->getServiceManager()->register(EventManager::SERVICE_ID, $eventManager);

        return \common_report_Report::createInfo('Default delivery values events detached.');
    }
}
