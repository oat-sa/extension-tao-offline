<?php

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
        parent::map($data);
        $this->buildUniqueProperties($data);
        return $this;
    }

    /**
     * @param array $data
     * @throws UniqueFieldException|common_Exception
     */
    public function buildUniqueProperties(array $data)
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
