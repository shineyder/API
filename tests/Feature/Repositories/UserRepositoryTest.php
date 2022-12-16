<?php

namespace Tests\Feature\Repositories;

use App\Repositories\UserRepository;
use App\Entities\User as UserEntity;
use App\Models\User as UserModel;
use InvalidArgumentException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private $userModel;
    private $userRepository;
    private $userEntity;

    public function setUp():void{
        $this->userModel = new UserModel();
        $this->userRepository = new UserRepository($this->userModel);

        $data = [
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'is_admin' => TRUE
        ];
        $this->userEntity = new UserEntity($data);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_list(){
        $result = $this->userRepository->list();
        $this->assertNotEmpty($result);
    }

    /**
     * @return void
     */
    public function test_get_entity_by_nonexistent_id(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível criar a entidade do usuário com este id');

        $this->userRepository->getEntityById(0);
    }

    /**
     * @return void
     */
    public function test_get_entity_by_existent_id(){
        $result = $this->userRepository->getEntityById(1);

        $this->assertNotEmpty($result);
        $this->assertEquals(1, $result->getId());
    }

    /**
     * @return void
     */
    public function test_get_model_by_nonexistent_id(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível encontrar o usuário com este id');

        $this->userRepository->getModelById(0);
    }

    /**
     * @return void
     */
    public function test_get_model_by_existent_id(){
        $result = $this->userRepository->getModelById(1);

        $this->assertNotEmpty($result);
        $this->assertEquals(1, $result->id);
    }

    /**
     * @return void
     */
    public function test_create_update_delete_complete_cycle(){
        $createResult = $this->userRepository->create($this->userEntity);
        $id = $createResult->getId();
        $findResult = $this->userRepository->getModelById($id);

        $this->assertNotEmpty($createResult);
        $this->assertEquals($this->userEntity, $createResult);
        $this->assertEquals('Adriano', $findResult->name);

        $this->userEntity->setName('Adriano2');

        $updateResult = $this->userRepository->update($this->userEntity);
        $findResult = $this->userRepository->getModelById($id);

        $this->assertNotEmpty($updateResult);
        $this->assertEquals($this->userEntity, $updateResult);
        $this->assertEquals('Adriano2', $findResult->name);

        $deleteResult = $this->userRepository->delete($id);
        $this->assertTrue($deleteResult);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível encontrar o usuário com este id');
        $this->userRepository->getModelById($id);
    }

    /**
     * @return void
     */
    public function test_update_resource_id_miss(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('É necessário um id para atualizar o usuário');
        $this->userRepository->update($this->userEntity);
    }

    /**
     * @return void
     */
    public function test_delete_invalid_id(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('É necessário um id válido para excluir o usuário');
        $this->userRepository->delete(0);
    }
}
