<?php

namespace Tests\Unit\Entities;

use App\Entities\Commons\Timestamp;
use App\Entities\Resource;
use App\Entities\ResourcePermission;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use Carbon\Carbon;

class ResourcePermissionTest extends TestCase
{
    private $resource;
    private $resourcePermission;

    public function setUp(): void {
        $resource = [
            'id' => 10,
            'name' => 'test',
            'slug' => 'test'
        ];
        $this->resource = new Resource($resource);

        $data = [
            'id' => 11,
            'resource' => $resource,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];
        $this->resourcePermission = new ResourcePermission($data);
    }

    /**
     * @return void
     */
    public function test_getters(){
        $id = $this->resourcePermission->getId();
        $resource = $this->resourcePermission->getResource();
        $resourceSlug = $this->resourcePermission->getResourceField('slug');

        $this->assertEquals(11, $id, 'Id getter error');
        $this->assertEquals($this->resource, $resource, 'Resource getter error');
        $this->assertEquals($this->resource->getSlug(), $resourceSlug, 'ResourceField getter error');
    }

    /**
     * @return void
     */
    public function test_fill_with_timestamp_in_data_array(){
        $resource = $this->createMock(Resource::class);
        $timestamp = new Timestamp(['updated_at' => '2040-01-01']);
        $data = [
            'id' => 11,
            'resource' => $resource,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE,
            'timestamp' => $timestamp
        ];
        $resourcePermission = new ResourcePermission($data);

        $this->assertObjectHasAttribute('timestamp', $resourcePermission);
        $this->assertObjectHasAttribute('updatedAt', $resourcePermission->getTimestamp());
        $this->assertEquals(Carbon::parse('2040-01-01'), $resourcePermission->getTimestamp()->getUpdatedAt());
    }

    /**
     * @return void
     */
    public function test_has_invalid_property(){
        $test = $this->resourcePermission->hasProperty('test');
        $this->assertFalse($test);
    }

    /**
     * @return void
     */
    public function test_get_invalid_property(){
        $this->expectException(InvalidArgumentException::class);
        $this->resourcePermission->getProperty('test');
    }

    /**
     * @return void
     */
    public function test_has_resource(){
        $hasResource = $this->resourcePermission->hasResource();
        $this->assertTrue($hasResource);
    }

    /**
     * @return void
     */
    public function test_has_resource_by_id(){
        $hasResourceById = $this->resourcePermission->hasResourceById(10);
        $this->assertTrue($hasResourceById);
    }

    /**
     * @return void
     */
    public function test_has_not_resource_by_id(){
        $hasResourceById = $this->resourcePermission->hasResourceById(11);
        $this->assertFalse($hasResourceById);
    }

    /**
     * @return void
     */
    public function test_set_invalid_permission(){
        $this->expectException(InvalidArgumentException::class);
        $this->resourcePermission->setPermission('test', TRUE);
    }

    /**
     * @return void
     */
    public function test_has_permission(){
        $view = $this->resourcePermission->hasPermission('view');
        $create = $this->resourcePermission->hasPermission('create');
        $update = $this->resourcePermission->hasPermission('update');
        $delete = $this->resourcePermission->hasPermission('delete');

        $this->assertTrue($view);
        $this->assertTrue($create);
        $this->assertTrue($update);
        $this->assertTrue($delete);
    }

    /**
     * @return void
     */
    public function test_has_invalid_permission(){
        $this->expectException(InvalidArgumentException::class);
        $test = $this->resourcePermission->hasPermission('test');

        $this->assertNull($test);
    }

    /**
     * @return void
     */
    public function test_get_model_data(){
        $resourcePermissionModelData = $this->resourcePermission->getModelData();
        $expectedData = [
            'id' => 11,
            'resource_id' => 10,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];

        $this->assertEquals($expectedData,$resourcePermissionModelData);
    }

    /**
     * @return void
     */
    public function test_json_serialize(){
        $resourcePermissionJson = $this->resourcePermission->jsonSerialize();
        $expectedData = [
            'id' => 11,
            'resource' => $this->resourcePermission->getResource(),
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE,
            'timestamp' => new Timestamp()
        ];

        $this->assertEquals($expectedData,$resourcePermissionJson);
    }
}
