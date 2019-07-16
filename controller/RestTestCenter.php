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
namespace oat\taoOffline\controller;

use common_exception_Error;
use common_exception_ResourceNotFound;
use common_exception_RestApi;
use core_kernel_classes_Property;
use core_kernel_classes_Resource;
use Exception;
use oat\generis\model\OntologyRdfs;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\model\TestCenterService;
use tao_actions_RestController;

class RestTestCenter extends tao_actions_RestController
{
    const PARAMETER_TEST_CENTER_LABEL = 'label';
    const PARAMETER_TEST_CENTER_ORGANISATION_ID = 'organisationId';
    const PARAMETER_TEST_CENTER_ID = 'testCenter';

    /**
     * @var array
     */
    private $parametersMap = [
        self::PARAMETER_TEST_CENTER_LABEL => OntologyRdfs::RDFS_LABEL,
        self::PARAMETER_TEST_CENTER_ORGANISATION_ID => TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY
    ];

    /**
     * @OA\Put(
     *     path="/taoOffline/api/testCenter",
     *     tags={"testCenter"},
     *     summary="Update test center",
     *     description="Update center organisation id or label",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="testCenter",
     *                     type="string",
     *                     description="Test center uri",
     *                 ),
     *                 @OA\Property(
     *                     property="label",
     *                     type="string",
     *                     description="Test center label",
     *                 ),
     *                 @OA\Property(
     *                     property="organisationId",
     *                     type="string",
     *                     description="Test center organisation id",
     *                 ),
     *                 required={"testCenter"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Test center URI",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     description="`false` on failure, `true` on success",
     *                 ),
     *                 @OA\Property(
     *                     property="uri",
     *                     type="string",
     *                     description="Updated test center URI",
     *                 ),
     *                 example={
     *                     "success": true,
     *                     "uri": "http://sample/first.rdf#i1536680377163171"
     *                 }
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid class uri",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 404,
     *                     "errorMsg": "Test Center `http://sample/first.rdf#i1536680377163171` does not exist.",
     *                     "version": "3.3.0-sprint106"
     *                 }
     *             )
     *         ),
     *     )
     * )
     */
    public function put()
    {
        try {
            $testCenter = $this->getTestCenterFromRequest();

            $data = $this->prepareRequestData(
                array_merge($this->getParametersFromRequest(), ['uri' => $testCenter->getUri()])
            );

            foreach ($data as $propertyUri => $value) {
                $testCenter->editPropertyValues(new core_kernel_classes_Property($propertyUri), $value);
            }

            $this->returnJson([
                'success' => true,
                'uri' => $testCenter->getUri()
            ]);
        } catch (Exception $e) {
             $this->returnFailure($e);
        }

    }

    /**
     * @OA\Post(
     *     path="/taoOffline/api/testCenter",
     *     tags={"testCenter"},
     *     summary="Create new test center",
     *     description="Create new test center with organisation id",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="class-uri",
     *                     type="string",
     *                     description="Class uri to import item. If not specified root class will be used.",
     *                 ),
     *                 @OA\Property(
     *                     property="class-label",
     *                     type="string",
     *                     description="Label of class to import item. If not specified root class will be used.
     *                     If label is not unique first match will be used.",
     *                 ),
     *                 @OA\Property(
     *                     property="label",
     *                     type="string",
     *                     description="Test center label",
     *                 ),
     *                 @OA\Property(
     *                     property="organisationId",
     *                     type="string",
     *                     description="Test center organisation id",
     *                 ),
     *                 required={"label", "organisationId"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Created test center URI",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     description="`false` on failure, `true` on success",
     *                 ),
     *                 @OA\Property(
     *                     property="uri",
     *                     type="string",
     *                     description="Created test center URI",
     *                 ),
     *                 example={
     *                     "success": true,
     *                     "uri": "http://sample/first.rdf#i1536680377163171"
     *                 }
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid class uri",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 400,
     *                     "errorMsg": "Class does not exist. Please use valid class-uri or class-label",
     *                     "version": "3.3.0-sprint106"
     *                 }
     *             )
     *         ),
     *     )
     * )
     */
    public function post()
    {
        try {
            $resource = $this->getClassFromRequest(
                $this->getTestCenterService()->getRootClass()
            )->createInstanceWithProperties(
                $this->prepareRequestData(
                    $this->getParametersFromRequest(true)
                )
            );

            $this->returnJson([
                'success' => true,
                'uri' => $resource->getUri(),
            ]);
        } catch (Exception $e) {
             $this->returnFailure($e);
        }
    }

    /**
     * @return core_kernel_classes_Resource
     * @throws common_exception_ResourceNotFound|common_exception_RestApi
     */
    private function getTestCenterFromRequest()
    {
        $data = $this->getRequestData();

        if (!array_key_exists(self::PARAMETER_TEST_CENTER_ID, $data)) {
            throw new common_exception_RestApi(
                __('Missed required parameter: `%s`', self::PARAMETER_TEST_CENTER_ID),
                400
            );
        }
        $resource = $this->getResource($data[self::PARAMETER_TEST_CENTER_ID]);

        if (!$resource->exists() || (!$resource->isInstanceOf($this->getClass(TestCenterService::CLASS_URI)))) {
            throw new common_exception_ResourceNotFound(
                __('Test Center `%s` does not exist.', $data[self::PARAMETER_TEST_CENTER_ID]),
                404
            );
        }
        return $resource;
    }

    /**
     * @param array $values
     * @return array
     * @throws common_exception_RestApi|common_exception_Error
     */
    private function prepareRequestData(array $values)
    {
        $propertiesValues = [];
        $uri = array_key_exists('uri', $values) ? $values['uri'] : null;

        foreach ($values as $name => $value) {
            if (array_key_exists($name, $this->parametersMap)) {
                $this->validateParameter($name, $value, $uri);
                $propertiesValues[$this->parametersMap[$name]] = $value;
            }
        }
        return $propertiesValues;
    }

    /**
     * @param string $name
     * @param string $propertyValue
     * @param string|null $uri
     * @throws common_exception_RestApi|common_exception_Error
     */
    private function validateParameter($name, $propertyValue, $uri = null)
    {
        if (empty($propertyValue)) {
            throw new common_exception_RestApi(sprintf(__('%s is required'), $name), 400);
        }

        if (
            $name === self::PARAMETER_TEST_CENTER_ORGANISATION_ID
            && !$this->getValidatorsService()->validateOrganisationIdValue($propertyValue, $uri)
        ) {
            throw new common_exception_RestApi(sprintf(__('%s must be unique'), $name), 400);
        }
    }

    /**
     * @param bool $isRequired
     * @return array
     * @throws common_exception_RestApi
     */
    private function getParametersFromRequest($isRequired = false)
    {
        $values = [];
        $data = $this->getRequestData();
        foreach (array_keys($this->parametersMap) as $name) {
            if (array_key_exists($name, $data)) {
                $values[$name] = $data[$name];
            } else if ($isRequired) {
                throw new common_exception_RestApi(__('Missed required parameter: `%s`', $name), 400);
            }
        }
        return $values;
    }

    /**
     * @return array
     */
    private function getRequestData()
    {
        if ($this->getPsrRequest()->getMethod() === 'POST') {
            return $this->getPsrRequest()->getParsedBody();
        }
        parse_str($this->getPsrRequest()->getBody(), $params);
        return $params;
    }

    /**
     * @return TaoOfflineTestCenterFormService
     */
    private function getValidatorsService()
    {
        return $this->getServiceLocator()->get(TaoOfflineTestCenterFormService::SERVICE_ID);
    }

    /**
     * @return TestCenterService
     */
    private function getTestCenterService()
    {
        return $this->getServiceLocator()->get(TestCenterService::SERVICE_ID);
    }
}
