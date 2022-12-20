<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Entities\User as UserEntity;
use App\Http\Controllers\Api\UserController;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use Error;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $userRepository;
    private $userController;
    private $userEntity;

    public function setUp():void{
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userEntity = $this->createMock(UserEntity::class);

        $this->userController = new UserController(
            $this->userRepository
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_index_should_return_error_when_list_user_fails(){
        $this->userRepository->expects($this->once())
            ->method('list')
            ->will($this->throwException(new Error()));

        $response = $this->userController->index();

        $this->assertEquals('{"message":"Falha ao buscar a lista de usu\u00e1rios"}', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_index_should_return_list_user(){
        $this->userRepository->expects($this->once())
            ->method('list')
            ->willReturn('a user list');

        $response = $this->userController->index();

        $this->assertEquals('"a user list"', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_info_should_return_error_when_get_entity_by_id_fails(){
        $this->userRepository->expects($this->once())
            ->method('getEntityById')
            ->will($this->throwException(new Error()));

        $response = $this->userController->info(1);

        $this->assertEquals('{"message":"Falha ao buscar as informa\u00e7\u00f5es do usu\u00e1rio"}', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_info_should_return_user_data(){
        $this->userRepository->expects($this->once())
            ->method('getEntityById')
            ->willReturn($this->userEntity);

        $response = $this->userController->info(1);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_create_should_return_error_when_create_fails(){
        $this->userRepository->expects($this->once())
            ->method('create')
            ->will($this->throwException(new Error()));

        $createRequest = $this->createMock(UserCreateRequest::class);
        $response = $this->userController->create($createRequest);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"message":"Falha ao criar usu\u00e1rio"}', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_create_should_run_successfully(){
        $this->userRepository->expects($this->once())
            ->method('create')
            ->willReturn($this->userEntity);

        $createRequest = $this->createMock(UserCreateRequest::class);
        $this->userController->create($createRequest);
    }

    /**
     * @return void
     */
    public function test_update_should_return_error_when_update_fails(){
        $this->userRepository->expects($this->once())
            ->method('update')
            ->will($this->throwException(new Error()));

        $updateRequest = $this->createMock(UserUpdateRequest::class);
        $response = $this->userController->update(1,$updateRequest);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"message":"Falha ao atualizar usu\u00e1rio"}', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_update_should_run_successfully(){
        $this->userRepository->expects($this->once())
            ->method('update')
            ->willReturn($this->userEntity);

        $updateRequest = $this->createMock(UserUpdateRequest::class);
        $response = $this->userController->update(1,$updateRequest);

        $this->assertEquals('{"message":"Usu\u00e1rio atualizado com sucesso","data":[]}', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_delete_should_return_error_when_delete_fails(){
        $this->userRepository->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new Error()));

        $response = $this->userController->delete(1);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"message":"Falha ao deletar usu\u00e1rio"}', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_delete_should_run_successfully(){
        $this->userRepository->expects($this->once())
            ->method('delete')
            ->willReturn(TRUE);

        $response = $this->userController->delete(1);

        $this->assertEquals('{"message":"Usu\u00e1rio apagado com sucesso","data":{"deleted":true}}', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
