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
 * Copyright (c) 2019 (original work) Open Assessment Technologies SA ;
 */

namespace oat\taoTestCenter\test\unit\model;

use oat\generis\test\TestCase;
use oat\tao\model\import\service\MandatoryFieldException;
use oat\taoOffline\model\import\TestCenterMapper;
use oat\taoOffline\model\import\UniqueFieldException;
use oat\taoOffline\model\service\TaoOfflineTestCenterFormService;
use oat\generis\test\MockObject;

class TestCenterMapperTest extends TestCase
{
    /**
     * @var TaoOfflineTestCenterFormService|MockObject
     */
    private $serviceMock;

    /**
     * @var TestCenterMapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = $this->getMockBuilder(TaoOfflineTestCenterFormService::class)
            ->setMethods(['validateOrganisationIdValue'])
            ->disableOriginalConstructor()
            ->getMock();

        $mapper = new TestCenterMapper(
            ['schema' =>
                [
                    'mandatory' => [
                        'label' => 'http://www.w3.org/2000/01/rdf-schema#label',
                        'organisation id' => 'http://www.taotesting.com/ontologies/synchro.rdf#organisationId'
                    ]
                ]
            ]
        );

        $mapper->setServiceLocator(
            $this->getServiceLocatorMock([TaoOfflineTestCenterFormService::SERVICE_ID => $this->serviceMock])
        );

        $this->mapper = $mapper;
    }

    public function testMap()
    {
        $this->serviceMock
            ->expects($this->once())
            ->method('validateOrganisationIdValue')
            ->with('organisation id')
            ->willReturn(true);

        $this->assertSame(
            $this->mapper,
            $this->mapper->map(['label' => 'label', 'organisation id' => 'organisation id'])
        );
    }

    public function testMapWithInvalidOrganisationId()
    {
        $this->serviceMock
            ->expects($this->once())
            ->method('validateOrganisationIdValue')
            ->with('organisation id')
            ->willReturn(false);

        $this->expectException(UniqueFieldException::class);

        $this->mapper->map(['label' => 'label', 'organisation id' => 'organisation id']);
    }

    public function testMapWithInvalidParams()
    {
        $this->serviceMock
            ->expects($this->never())
            ->method('validateOrganisationIdValue');

        $this->expectException(MandatoryFieldException::class);

        $this->mapper->map(['label' => 'label']);
    }
}
