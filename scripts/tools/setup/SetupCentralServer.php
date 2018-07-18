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
 */

namespace oat\taoOffline\scripts\tools\setup;

use oat\oatbox\extension\script\ScriptAction;
use oat\taoEncryption\scripts\tools\SetupAsymmetricKeys;
use oat\taoEncryption\scripts\tools\SetupEncryptedSyncResult;
use oat\taoEncryption\scripts\tools\SetupRdfDeliveryEncrypted;
use oat\taoEncryption\scripts\tools\SetupSyncTestSessionService;
use oat\taoEncryption\scripts\tools\SetupUserApplicationKey;
use oat\taoEncryption\scripts\tools\SetupUserEventSubscription;
use oat\taoEncryption\scripts\tools\SetupUserSynchronizer;
use oat\taoOffline\scripts\tools\byOrganisationId\RegisterSyncServiceByOrgId;
use oat\taoOffline\scripts\tools\byOrganisationId\SetupSyncFormByOrgId;

class SetupCentralServer extends ScriptAction
{
    use RunActionScriptTrait;

    protected $report;

    /**
     * @return \common_report_Report
     * @throws \common_exception_Error
     */
    protected function run()
    {
        $report = \common_report_Report::createInfo('Setting up a central server.');

        $report->add($this->runScript(SetupAsymmetricKeys::class, ['generate']));
        $report->add($this->runScript(SetupEncryptedSyncResult::class));
        $report->add($this->runScript(SetupUserApplicationKey::class));
        $report->add($this->runScript(SetupRdfDeliveryEncrypted::class));
        $report->add($this->runScript(SetupUserEventSubscription::class));
        $report->add($this->runScript(SetupUserSynchronizer::class));
        $report->add($this->runScript(RegisterSyncServiceByOrgId::class));
        $report->add($this->runScript(SetupSyncFormByOrgId::class));
        $report->add($this->runScript(SetupSyncTestSessionService::class));

        return $report;
    }

    protected function provideOptions()
    {
        return [];
    }

    protected function provideDescription()
    {
        return 'Installation script for a central server. It sets up encryption and synchronisation against a central server';
    }
}