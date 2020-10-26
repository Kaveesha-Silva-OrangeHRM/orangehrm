<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
use Orangehrm\Rest\Api\User\AboutAPI;
use Orangehrm\Rest\Api\Exception\BadRequestException;
use Orangehrm\Rest\Http\Request;
use Orangehrm\Rest\Http\Response;

/**
 * @group API
 */
class ApiAboutAPITest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Request
     */
    private $request = null;

    protected function setUp()
    {
        $sfEvent = new sfEventDispatcher();
        $sfRequest = new sfWebRequest($sfEvent);
        $this->request = new Request($sfRequest);
    }

    public function testGetAboutRequest()
    {
        $aboutApi = new AboutAPI($this->request);
        $actualResponse = $aboutApi->getAboutInfo();

        $success = new Response(['success' => 'Successfully Saved']);
        $expected = new Response($this->getTestCaseForAboutApi());
        var_dump($actualResponse);
//        $this->assertEquals($actualResponse, $responseSaveLeaveRequest);
    }

    public function getTestCaseForAboutApi(){
        $result=[];
        $data=[];
        $data['OrganizationName'] = 'OrangeHRM';
        $data['OrganizationCountry'] = 'LK';
        $data['OrangeHRMVersion'] = '4.5';
        $data['DateFormat'] = 'Y-m-d';
        $data['Language'] = '';
        $rels=[];
        $result['data']=$data;
        $result['rels']=$rels;
        return $result;
    }
    public function testSaveLeaveRequestSaveFailed()
    {
        $applyLeaveRequestApi = $this->getMockBuilder('Orangehrm\Rest\Api\User\ApplyLeaveRequestAPI')
            ->setMethods(['filterParameters', 'validateLeaveType', 'isValidToDate'])
            ->setConstructorArgs([$this->request])
            ->getMock();
        $applyLeaveRequestApi->expects($this->once())
            ->method('filterParameters')
            ->will($this->returnValue([]));
        $applyLeaveRequestApi->expects($this->once())
            ->method('validateLeaveType')
            ->will($this->returnValue(false));
        $applyLeaveRequestApi->expects($this->once())
            ->method('isValidToDate')
            ->will($this->returnValue(true));

        $this->expectException(BadRequestException::class);
        $applyLeaveRequestApi->saveLeaveRequest();
    }

    public function testSaveLeaveRequestAllocationException()
    {
        $this->expectException(BadRequestException::class);
        $applyLeaveRequestApi = $this->getMockBuilder('Orangehrm\Rest\Api\User\ApplyLeaveRequestAPI')
            ->setMethods(['filterParameters', 'validateLeaveType', 'isValidToDate'])
            ->setConstructorArgs([$this->request])
            ->getMock();
        $applyLeaveRequestApi->expects($this->once())
            ->method('filterParameters')
            ->will($this->returnValue([]));
        $applyLeaveRequestApi->expects($this->once())
            ->method('validateLeaveType')
            ->will($this->returnValue(true));
        $applyLeaveRequestApi->expects($this->once())
            ->method('isValidToDate')
            ->will($this->returnValue(true));
        $apiLeaveApplicationService = $this->getMockBuilder(
            'Orangehrm\Rest\Api\User\Service\APILeaveApplicationService'
        )->getMock();
        $apiLeaveApplicationService->expects($this->once())
            ->method('applyLeave')
            ->will(
                $this->returnCallback(
                    function () {
                        throw new LeaveAllocationServiceException('Leave Balance Exceeded');
                    }
                )
            );

        $applyLeaveRequestApi->setApiLeaveApplicationService($apiLeaveApplicationService);

        $this->expectException(BadRequestException::class);
        $applyLeaveRequestApi->saveLeaveRequest();
    }
}
