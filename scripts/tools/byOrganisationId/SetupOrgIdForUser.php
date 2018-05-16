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

namespace oat\taoOffline\scripts\tools\byOrganisationId;

use core_kernel_users_Service;
use oat\generis\model\GenerisRdf;
use oat\oatbox\extension\script\ScriptAction;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use common_report_Report as Report;
use oat\taoSync\model\SyncService;

/**
 * Class SetupOrgIdForUser
 * @package oat\taoOffline\scripts\tools\byOrganisationId
 *
 * sudo -u www-data php index.php 'oat\taoOffline\scripts\tools\byOrganisationId\SetupOrgIdForUser' -u login -o 123
 */
class SetupOrgIdForUser extends ScriptAction
{
    protected function run()
    {
        $user = core_kernel_users_Service::singleton()->getOneUser($this->getOption('userLogin'));

        if ($user) {
            $user->editPropertyValues(
                new \core_kernel_classes_Property(GenerisRdf::PROPERTY_USER_ROLES),
                SyncService::TAO_SYNC_ROLE
            );

            $user->editPropertyValues(
                new \core_kernel_classes_Property(TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY),
                $this->getOption('organisationId')
            );

            return Report::createSuccess('User organization saved with success.');
        }
        return Report::createFailure('User not existing.');
    }


    protected function provideOptions()
    {
        return [
            'userLogin' => [
                'prefix' => 'u',
                'longPrefix' => 'user-login',
                'required' => true,
                'cast' => 'string',
                'description' => 'User Login',
            ],
            'organisationId' => [
                'prefix' => 'o',
                'longPrefix' => 'organisation-id',
                'required' => true,
                'cast' => 'string',
                'description' => 'Client id of http consumer',
            ],
        ];
    }

    protected function provideDescription()
    {
        return [

        ];
    }
}