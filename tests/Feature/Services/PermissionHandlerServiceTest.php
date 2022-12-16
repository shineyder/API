<?php

namespace Tests\Feature\Services;

use App\Entities\ResourcePermission;
use App\Entities\User as UserEntity;
use App\Exceptions\PermissionHandlingException;
use App\Models\User as UserModel;
use App\Repositories\ResourcePermissionRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\UserRepository;
use App\Services\PermissionHandlerService;
use Tests\TestCase;

class PermissionHandlerServiceTest extends TestCase
{
    private $resourcePermissionRepository;
    private $userRepository;
    private $resourceRepository;
    private $userEntity;
    private $resourcePermissionEntity;
    private $data;
    private $permissionHandlerService;

    public function setUp():void{
        $this->resourcePermissionRepository = $this->createMock(ResourcePermissionRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->resourceRepository = $this->createMock(ResourceRepository::class);
        $this->userEntity = $this->createMock(UserEntity::class);
        $this->resourcePermissionEntity = $this->createMock(ResourcePermission::class);

        $this->data = [
            'resource_id' => 1,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];

        $this->permissionHandlerService = new PermissionHandlerService(
            $this->resourcePermissionRepository,
            $this->userRepository,
            $this->resourceRepository
        );
        
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_user_is_invalid(){
        $this->withInvalidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja um usuário válido');

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_is_empty(){
        $this->withValidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que os dados sejam válidos');

        $this->permissionHandlerService->handle($this->userEntity,[]);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_not_have_resource_id(){
        $this->withValidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado o recurso');

        unset($this->data['resource_id']);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_not_have_view_permission(){
        $this->withValidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado a permissão de visualização');

        unset($this->data['view']);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_not_have_create_permission(){
        $this->withValidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado a permissão de criação');

        unset($this->data['create']);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_not_have_update_permission(){
        $this->withValidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado a permissão de atualização');

        unset($this->data['update']);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_data_not_have_delete_permission(){
        $this->withValidUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário que seja informado a permissão de remoção');

        unset($this->data['delete']);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_not_handle_when_user_is_not_admin(){
        $this->withValidUser()
            ->withAuthenticatedNotAdminUser();

        $this->expectException(PermissionHandlingException::class);
        $this->expectExceptionMessage('É necessário permissão de Administrador para realizar essa ação');

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_handle_update_permission_when_user_already_have_given_resource(){
        $this->withValidUser()
            ->withAuthenticatedAdminUser()
            ->withUserAlreadyHaveGivenResource();

        $this->resourcePermissionRepository
            ->expects($this->once())
            ->method('update')
            ->willReturn($this->resourcePermissionEntity);

        $this->userEntity
            ->expects($this->once())
            ->method('refreshPermission')
            ->willReturn($this->userEntity);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    /**
     * @return void
     */
    public function test_should_handle_create_permission_when_user_not_have_given_resource(){
        $this->withValidUser()
            ->withAuthenticatedAdminUser()
            ->withUserNotHaveGivenResource();

        $this->resourcePermissionRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->resourcePermissionEntity);

        $this->userEntity
            ->expects($this->once())
            ->method('addResourcePermission')
            ->willReturn($this->userEntity);

        $this->permissionHandlerService->handle($this->userEntity,$this->data);
    }

    private function withInvalidUser(){
        $this->userEntity->method('getId')
            ->willReturn(NULL);

        return $this;
    }

    private function withValidUser(){
        $this->userEntity->method('getId')
            ->willReturn(1);

        return $this;
    }

    private function withAuthenticatedAdminUser(){
        $userModel = UserModel::find(1);
        $this->actingAs($userModel,'api');

        return $this;
    }

    private function withAuthenticatedNotAdminUser(){
        $userModel = UserModel::find(2);
        $this->actingAs($userModel,'api');

        return $this;
    }

    private function withUserAlreadyHaveGivenResource(){
        $this->userEntity->method('hasResourceById')
            ->willReturn(TRUE);

        return $this;
    }

    private function withUserNotHaveGivenResource(){
        $this->userEntity->method('hasResourceById')
            ->willReturn(FALSE);

        return $this;
    }
}
