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

use common_Exception;
use core_kernel_classes_Class;
use core_kernel_classes_Resource;
use oat\generis\test\TestCase;
use oat\taoOffline\model\service\TestCenterFormService;
use PHPUnit_Framework_MockObject_MockObject;

class TestCenterFormServiceTest extends TestCase
{

    /**
     * @var TestCenterFormService|PHPUnit_Framework_MockObject_MockObject
     */
    private $serviceMock;

    /**
     * @var core_kernel_classes_Class|PHPUnit_Framework_MockObject_MockObject
     */
    private $classMock;

    protected function setUp()
    {
        parent::setUp();

        $this->classMock = $this->createMock(core_kernel_classes_Class::class);

        $this->serviceMock = $this->getMockBuilder(TestCenterFormService::class)
            ->setMethods(['getClass'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceMock->method('getClass')->willReturn($this->classMock);

    }

    /**
     * @param array $searchResult
     * @param string $url
     * @param bool $isFail
     * @dataProvider testValidateOrganisationIdValueDataProvider
     * @throws common_Exception
     */
    public function testValidateOrganisationIdValue(array $searchResult, $url, $isFail)
    {
        $this->classMock->expects($this->once())
            ->method('searchInstances')
            ->with(
                ['http://www.taotesting.com/ontologies/synchro.rdf#organisationId' => 'orgId'],
                ['recursive' => true, 'like' => false]
            )
            ->willReturn($searchResult);

        if ($isFail) {
            $this->assertFalse($this->serviceMock->validateOrganisationIdValue('orgId', $url));
        } else {
            $this->assertTrue($this->serviceMock->validateOrganisationIdValue('orgId', $url));
        }
    }

    /**
     * @return array
     */
    public function testValidateOrganisationIdValueDataProvider()
    {
        $resourceMock = $this->getMockBuilder(core_kernel_classes_Resource::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUri'])
            ->getMock();

        $resourceMock->method('getUri')->willReturn('uri');
        return [
            [[], null, false],
            [[0 => $resourceMock], null, true],
            [[0 => $resourceMock], 'uri', false],
            [[0 => $resourceMock], 'uri2', true],

        ];
    }
}