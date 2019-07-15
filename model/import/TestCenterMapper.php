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

namespace oat\taoOffline\model\import;

use common_Exception;
use oat\tao\model\import\service\ImportMapperInterface;
use oat\tao\model\import\service\MandatoryFieldException;
use oat\tao\model\import\service\OntologyMapper;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;

class TestCenterMapper extends OntologyMapper
{
    /**
     * @param array $data
     * @return ImportMapperInterface
     * @throws MandatoryFieldException|UniqueFieldException|common_Exception
     */
    public function map(array $data = [])
    {
        $obj = parent::map($data);
        $this->validateUniqueProperties($data);

        return $obj;
    }

    /**
     * @param array $data
     * @throws UniqueFieldException|common_Exception
     */
    private function validateUniqueProperties(array $data)
    {
       if (!$this->getValidator()->validateOrganisationIdValue($data['organisation id'])) {
           throw new UniqueFieldException(
               sprintf(__('Organisation id %s already exists.', $data['organisation id']))
           );
       }
    }

    /**
     * @return TaoOfflineTestCenterFormService
     */
    private function getValidator()
    {
        return $this->getServiceLocator()->get(TaoOfflineTestCenterFormService::SERVICE_ID);
    }
}
