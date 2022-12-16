<?php

namespace Tests\Feature\Repositories;

use App\Repositories\ResourceRepository;
use App\Entities\Resource as ResourceEntity;
use App\Models\Resource as ResourceModel;
use InvalidArgumentException;
use Tests\TestCase;

class ResourceRepositoryTest extends TestCase
{
    private $resourceModel;
    private $resourceRepository;
    private $resourceEntity;

    public function setUp():void{
        $this->resourceModel = new ResourceModel();
        $this->resourceRepository = new ResourceRepository($this->resourceModel);

        $data = [
            'name' => 'test',
            'slug' => 'test'
        ];
        $this->resourceEntity = new ResourceEntity($data);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_list(){
        $result = $this->resourceRepository->list();
        $this->assertNotEmpty($result);
    }

    /**
     * @return void
     */
    public function test_get_by_nonexistent_id(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível encontrar o Resource com este id');

        $this->resourceRepository->getById(0);
    }

    /**
     * @return void
     */
    public function test_get_by_existent_id(){
        $result = $this->resourceRepository->getById(1);

        $this->assertNotEmpty($result);
        $this->assertEquals(1, $result->id);
    }

    /**
     * @return void
     */
    public function test_get_by_nonexistent_slug(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível encontrar o Resource com este slug');

        $this->resourceRepository->getBySlug('teste');
    }

    /**
     * @return void
     */
    public function test_get_by_existent_slug(){
        $result = $this->resourceRepository->getBySlug('product');

        $this->assertNotEmpty($result);
        $this->assertEquals('product', $result->slug);
    }

    /**
     * @return void
     */
    public function test_create_update_delete_complete_cycle(){
        $createResult = $this->resourceRepository->create($this->resourceEntity);
        $id = $createResult->getId();
        $findResult = $this->resourceRepository->getBySlug('test');

        $this->assertNotEmpty($createResult);
        $this->assertEquals($this->resourceEntity, $createResult);
        $this->assertEquals('test', $findResult->slug);

        $this->resourceEntity->setSlug('test2');

        $updateResult = $this->resourceRepository->update($this->resourceEntity);
        $findResult = $this->resourceRepository->getBySlug('test2');

        $this->assertNotEmpty($updateResult);
        $this->assertEquals($this->resourceEntity, $updateResult);
        $this->assertEquals('test2', $findResult->slug);

        $deleteResult = $this->resourceRepository->delete($id);
        $this->assertTrue($deleteResult);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível encontrar o Resource com este slug');
        $this->resourceRepository->getBySlug('test2');
    }

    /**
     * @return void
     */
    public function test_update_resource_id_miss(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('É necessário um id para atualizar o Resource');
        $this->resourceRepository->update($this->resourceEntity);
    }

    /**
     * @return void
     */
    public function test_delete_invalid_id(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('É necessário um id válido para excluir o Resource');
        $this->resourceRepository->delete(0);
    }
}
