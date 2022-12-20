<?php

namespace Tests\Feature\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Entities\Resource;
use App\Repositories\ResourcePermissionRepository;
use App\Entities\ResourcePermission as ResourcePermissionEntity;
use App\Models\UserResourcePermission as ResourcePermissionModel;
use Database\Seeders\DatabaseSeeder;
use InvalidArgumentException;
use Tests\TestCase;

class ResourcePermissionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = DatabaseSeeder::class;
    private $resourcePermissionModel;
    private $resourcePermissionRepository;
    private $resourcePermissionEntity;

    public function setUp():void{
        $this->resourcePermissionModel = new ResourcePermissionModel();
        $this->resourcePermissionRepository = new ResourcePermissionRepository($this->resourcePermissionModel);

        $resource = [
            'id' => 1,
            'name' => 'produtos',
            'slug' => 'product'
        ];
        $data = [
            'resource' => $resource,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];
        $this->resourcePermissionEntity = new ResourcePermissionEntity($data);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_get_by_invalid_combination_of_user_and_resource_ids(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível encontrar a Resource Permission com este id de usuário e id de recurso');

        $this->resourcePermissionRepository->getByUserIdAndResourceId(0,0);
    }

    /**
     * @return void
     */
    public function test_get_by_valid_combination_of_user_and_resource_ids(){
        $result = $this->resourcePermissionRepository->getByUserIdAndResourceId(2,1);

        $this->assertNotEmpty($result);
        $this->assertEquals(1, $result->id);
    }

    /**
     * @return void
     */
    public function test_create(){
        $createResult = $this->resourcePermissionRepository->create(1,$this->resourcePermissionEntity);
        $findResult = $this->resourcePermissionRepository->getByUserIdAndResourceId(1,1);

        $this->assertNotEmpty($createResult);
        $this->assertEquals($this->resourcePermissionEntity, $createResult);
        $this->assertEquals($this->resourcePermissionEntity->getId(), $findResult->id);
        $this->assertTrue($this->resourcePermissionEntity->hasPermission('view'));
    }

    /**
     * @return void
     */
    public function test_update(){
        $this->resourcePermissionEntity->setPermission('view',FALSE);

        $updateResult = $this->resourcePermissionRepository->update(2,$this->resourcePermissionEntity);
        $findResult = $this->resourcePermissionRepository->getByUserIdAndResourceId(2,1);

        $this->assertNotEmpty($updateResult);
        $this->assertEquals($this->resourcePermissionEntity, $updateResult);
        $this->assertEquals($this->resourcePermissionEntity->getId(), $findResult->id);
        $this->assertFalse($this->resourcePermissionEntity->hasPermission('view'));
    }

    /**
     * @return void
     */
    public function test_update_resource_permission_id_miss(){
        $resource = [
            'name' => 'produtos',
            'slug' => 'product'
        ];
        $this->resourcePermissionEntity->setResource(new Resource($resource));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('É necessário um id para atualizar a Resource Permission');
        $this->resourcePermissionRepository->update(1,$this->resourcePermissionEntity);
    }
}
