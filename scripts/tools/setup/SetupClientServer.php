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
use oat\taoEncryption\scripts\tools\SetupDeliveryEncrypted;
use oat\taoEncryption\scripts\tools\SetupEncryptedDeliveryLogService;
use oat\taoEncryption\scripts\tools\SetupEncryptedFileSystem;
use oat\taoEncryption\scripts\tools\SetupEncryptedMonitoringService;
use oat\taoEncryption\scripts\tools\SetupEncryptedResultStorage;
use oat\taoEncryption\scripts\tools\SetupEncryptedStateStorage;
use oat\taoEncryption\scripts\tools\SetupEncryptedSyncResult;
use oat\taoEncryption\scripts\tools\SetupEncryptedUser;
use oat\taoEncryption\scripts\tools\SetupUserSynchronizer;
use oat\taoOffline\scripts\tools\byOrganisationId\SetupSyncFormByOrgId;
use oat\taoOffline\scripts\tools\PostSetup\Client\SwitchLockoutOff;
use oat\taoSync\scripts\install\AttachReactivateDeliveryExecutionEvent;
use oat\taoSync\scripts\tool\RegisterHandShakeAuthAdapter;
use oat\taoOffline\scripts\tools\byOrganisationId\RegisterSyncServiceByOrgId;
use oat\taoSync\scripts\tool\synchronisationHistory\SetupClientSynchronisationHistory;
use oat\taoSync\scripts\tool\synchronizationLog\RegisterRdsSyncLogStorage;
use oat\taoSync\scripts\tool\synchronizationLog\RegisterClientSyncLogListener;
use oat\taoSync\scripts\tool\synchronizationLog\RegisterSyncLogService;

class SetupClientServer extends ScriptAction
{
    use RunActionScriptTrait;

    protected $report;

    /**
     * @return \common_report_Report
     * @throws \common_exception_Error
     */
    protected function run()
    {
        $report = \common_report_Report::createInfo('Setting up a client server.');

        if ($this->getServiceLocator()->get(\common_ext_ExtensionsManager::SERVICE_ID)->isInstalled('taoEncryption')) {
            $report->add($this->runScript(SetupEncryptedResultStorage::class));
            $report->add($this->runScript(SetupEncryptedSyncResult::class));
            $report->add($this->runScript(SetupEncryptedStateStorage::class));
            $report->add($this->runScript(SetupEncryptedDeliveryLogService::class));
            $report->add($this->runScript(SetupEncryptedMonitoringService::class));
            $report->add($this->runScript(SetupEncryptedUser::class));
            $report->add($this->runScript(SetupEncryptedFileSystem::class, [
                '-f', 'private',
                '-e', 'taoEncryption/symmetricEncryptionService',
                '-k', 'taoEncryption/symmetricFileKeyProvider',
            ]));
            $report->add($this->runScript(SetupDeliveryEncrypted::class));
            $report->add($this->runScript(SetupUserSynchronizer::class));
            $this->logNotice('Extension "taoEncryption" is installed, synchronisation data will be encrypted');
        } else {
            $this->logWarning('Extension "taoEncryption" is not installed and the synchronisation data is not encrypted');
        }

        $report->add($this->runScript(RegisterSyncServiceByOrgId::class));
        $report->add($this->runScript(RegisterHandShakeAuthAdapter::class));
        $report->add($this->runScript(SetupSyncFormByOrgId::class));
        $report->add($this->runScript(SwitchLockoutOff::class));
        $report->add($this->runScript(AttachReactivateDeliveryExecutionEvent::class));
        $report->add($this->runScript(SetupClientSynchronisationHistory::class));
        $report->add($this->runScript(RegisterClientSyncLogListener::class));
        $report->add($this->runScript(RegisterRdsSyncLogStorage::class));
        $report->add($this->runScript(RegisterSyncLogService::class));

        return $report;
    }

    protected function provideOptions()
    {
        return [];
    }

    protected function provideDescription()
    {
        return 'Installation script for a client server. It set up encryption and synchronisation against a central server';
    }
}