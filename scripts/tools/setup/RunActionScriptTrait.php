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

use oat\oatbox\action\Action;

trait RunActionScriptTrait
{
    abstract public function propagate($service);

    /**
     * Run an action script
     *
     * @param $action
     * @param array $params
     * @return \common_report_Report
     * @throws \common_exception_Error
     */
    protected function runScript($action, array $params = [])
    {
        if (!is_string($action) || !is_a($action, Action::class, true)) {
            return \common_report_Report::createFailure('Unable to run script for client server installation');
        }
        $report = \common_report_Report::createInfo(' * About running script ' . $action . '...');
        $action = $this->propagate(new $action());
        $report->add(call_user_func($action, $params));
        return $report;
    }
}