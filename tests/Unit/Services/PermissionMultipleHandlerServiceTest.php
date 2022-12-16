<?php

namespace Tests\Unit\Services;

use App\Repositories\UserRepository;
use App\Entities\User as UserEntity;
use App\Exceptions\PermissionHandlingException;
use App\Services\PermissionHandlerService;
use App\Services\PermissionMultipleHandlerService;
use PHPUnit\Framework\TestCase;

class PermissionMultipleHandlerServiceTest extends TestCase
{
    private $userRepository;
    private $handlerService;
    private $permissionMultipleHandlerService;
    private $userEntity;
    private $data;

    public function setUp():void{
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->handlerService = $this->createMock(PermissionHandlerService::class);
        $this->userEntity = $this->createMock(UserEntity::class);

        $resourcePermissionsData = [
            [
                'resource_id' => 1,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE
            ],
            [
                'resource_id' => 2,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE
            ]
        ];
        $this->data = [
            'user_id' => 1,
            'resource_permissions' => $resourcePermissionsData,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];

        $this->permissionMultipleHandlerService = new PermissionMultipleHandlerService(
            $this->userRepository,
            $this->handlerService
        );
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_is_empty(){
        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que os dados sejam válidos');

        $this->permissionMultipleHandlerService->handle([]);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_user_id_is_missing(){
        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado o usuário');

        unset($this->data['user_id']);

        $this->permissionMultipleHandlerService->handle($this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_not_have_resource_permissions(){
        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado as permissões de recurso');

        unset($this->data['resource_permissions']);

        $this->permissionMultipleHandlerService->handle($this->data);
    }

    /**
     * @return void
     */
    public function test_should_successfully_handle(){
        $this->userRepository
            ->expects($this->once())
            ->method('getEntityById')
            ->willReturn($this->userEntity);

        $this->handlerService
            ->expects($this->exactly(2))
            ->method('handle');

        $this->permissionMultipleHandlerService->handle($this->data);
    }
}
