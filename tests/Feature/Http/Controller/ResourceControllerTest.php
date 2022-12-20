<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\ResourceController;
use App\Repositories\ResourceRepository;
use Database\Seeders\ResourceSeeder;
use Error;
use Tests\TestCase;

class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = ResourceSeeder::class;

    private $resouceRepository;
    private $resourceController;

    public function setUp():void{
        $this->resouceRepository = $this->createMock(ResourceRepository::class);

        $this->resourceController = new ResourceController(
            $this->resouceRepository
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_list_should_return_error_when_list_resource_fails(){
        $this->resouceRepository->expects($this->once())
            ->method('list')
            ->will($this->throwException(new Error()));

        $response = $this->resourceController->list();

        $this->assertEquals('{"message":"Falha ao buscar a lista de recursos"}', $response->getContent());
    }

    /**
     * @return void
     */
    public function test_list_should_return_list_user(){
        $this->resouceRepository->expects($this->once())
            ->method('list')
            ->willReturn('a resource list');

        $response = $this->resourceController->list();

        $this->assertEquals('"a resource list"', $response->getContent());
    }
}
