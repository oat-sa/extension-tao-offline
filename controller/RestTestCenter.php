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
use common_exception_RestApi;
use oat\generis\model\OntologyRdfs;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use oat\taoTestCenter\controller\RestTestCenter as ParentRestTestCenterController;

class RestTestCenter extends ParentRestTestCenterController
{
    const PARAMETER_TEST_CENTER_ORGANISATION_ID = 'organisationId';

    /**
     * @var array
     */
    protected $parametersMap = [
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
       parent::put();
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
       parent::post();
    }

    /**
     * @param array $values
     * @return array
     * @throws common_exception_RestApi|common_exception_Error
     */
    protected function validateRequestParameters(array $values)
    {
        $propertiesValues = parent::validateRequestParameters($values);

        if (array_key_exists(TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY, $propertiesValues)) {

            if (!$this->getValidatorsService()->validateOrganisationIdValue(
                $propertiesValues[TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY],
                array_key_exists('uri', $values) ? $values['uri'] : null
            )) {
                throw new common_exception_RestApi('OrganisationId should be unique', 400);
            }
        }
        return $propertiesValues;
    }

    /**
     * @return TaoOfflineTestCenterFormService
     */
    private function getValidatorsService()
    {
        return $this->getServiceLocator()->get(TaoOfflineTestCenterFormService::SERVICE_ID);
    }
}
