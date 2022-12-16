<?php

namespace Tests\Feature\Http\Controller;

use App\Http\Controllers\Api\UserPermissionController;
use App\Http\Requests\UserMultiplePermissionUpdateRequest;
use App\Http\Requests\UserUniquePermissionUpdateRequest;
use App\Services\PermissionMultipleHandlerService;
use App\Services\PermissionUniqueHandlerService;
use Error;
use Tests\TestCase;

class UserPermissionControllerTest extends TestCase
{
    private $uniqueService;
    private $multipleService;
    private $userPermissionController;

    public function setUp():void{
        $this->uniqueService = $this->createMock(PermissionUniqueHandlerService::class);
        $this->multipleService = $this->createMock(PermissionMultipleHandlerService::class);

        $this->userPermissionController = new UserPermissionController(
            $this->uniqueService,
            $this->multipleService
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_update_one_permission_should_return_error_when_updates_fails(){
        $updateUniqueRequest = $this->createMock(UserUniquePermissionUpdateRequest::class);
        $updateUniqueRequest->expects($this->once())
            ->method('validated')
            ->willReturn(['data']);

        $this->uniqueService->expects($this->once())
            ->method('handle')
            ->will($this->throwException(new Error()));

        $response = $this->userPermissionController->updateOnePermission($updateUniqueRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_update_one_permission_should_run_successfully(){
        $updateUniqueRequest = $this->createMock(UserUniquePermissionUpdateRequest::class);
        $updateUniqueRequest->expects($this->once())
            ->method('validated')
            ->willReturn(['data']);

        $this->uniqueService->expects($this->once())
            ->method('handle');

        $response = $this->userPermissionController->updateOnePermission($updateUniqueRequest);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_update_multiple_permission_should_return_error_when_updates_fails(){
        $updateMultipleRequest = $this->createMock(UserMultiplePermissionUpdateRequest::class);
        $updateMultipleRequest->expects($this->once())
            ->method('validated')
            ->willReturn(['data']);

        $this->multipleService->expects($this->once())
            ->method('handle')
            ->will($this->throwException(new Error()));

        $response = $this->userPermissionController->updateMultiplePermission($updateMultipleRequest);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_update_multiple_permission_should_run_successfully(){
        $updateMultipleRequest = $this->createMock(UserMultiplePermissionUpdateRequest::class);
        $updateMultipleRequest->expects($this->once())
            ->method('validated')
            ->willReturn(['data']);

        $this->multipleService->expects($this->once())
            ->method('handle');

        $response = $this->userPermissionController->updateMultiplePermission($updateMultipleRequest);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
