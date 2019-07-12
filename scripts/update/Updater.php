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

use oat\taoOffline\scripts\tools\byOrganisationId\RegisterTestCenterFormService;
use oat\taoOffline\scripts\tools\byOrganisationId\RewriteTestCenterManagerService;

class Updater extends \common_ext_ExtensionUpdater
{
    public function update($initialVersion)
    {
        $this->skip('0.1.0', '2.2.1');

        if ($this->isVersion('2.2.1')) {
            $this->runExtensionScript(RewriteTestCenterManagerService::class);
            $this->runExtensionScript(RegisterTestCenterFormService::class);
            $this->setVersion('2.3.0');
        }
    }
}