<?php

namespace Tests\Feature\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\UserRepository;
use App\Entities\User as UserEntity;
use App\Models\User as UserModel;
use Database\Seeders\UserSeeder;
use InvalidArgumentException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = UserSeeder::class;
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
    public function test_create(){
        $this->userRepository->create($this->userEntity);

        $this->assertDatabaseHas('users', [
            'email' => 'adrianoshineyder@hotmail.com',
        ]);
    }

    /**
     * @return void
     */
    public function test_update(){
        $this->userEntity->setId(1);
        $this->userEntity->setEmail('adrianoshineyder2@hotmail.com');

        $this->userRepository->update($this->userEntity);

        $this->assertDatabaseHas('users', [
            'email' => 'adrianoshineyder2@hotmail.com',
        ]);
    }

    /**
     * @return void
     */
    public function test_delete(){
        $deleteResult = $this->userRepository->delete(3);
        $this->assertTrue($deleteResult);

        $this->assertDatabaseMissing('users', [
            'email' => 'adrianocategory@example.com'
        ]);
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
