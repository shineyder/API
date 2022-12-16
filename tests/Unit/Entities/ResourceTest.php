<?php

namespace Tests\Unit\Entities;

use App\Entities\Commons\Timestamp;
use App\Entities\Resource;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class ResourceTest extends TestCase
{
    private $resource;

    public function setUp(): void {
        $data = [
            'id' => 10,
            'name' => 'test',
            'slug' => 'test'
        ];
        $this->resource = new Resource($data);
    }

    /**
     * @return void
     */
    public function test_getters(){
        $id = $this->resource->getId();
        $name = $this->resource->getName();
        $slug = $this->resource->getSlug('slug');

        $this->assertEquals(10, $id, 'Id getter error');
        $this->assertEquals('test', $name, 'Name getter error');
        $this->assertEquals('test', $slug, 'Slug getter error');
    }

    /**
     * @return void
     */
    public function test_fill_with_timestamp_in_data_array(){
        $timestamp = new Timestamp(['deleted_at' => '2040-01-01']);
        $data = [
            'id' => 11,
            'name' => 'test',
            'slug' => 'test',
            'timestamp' => $timestamp
        ];
        $resource = new Resource($data);

        $this->assertObjectHasAttribute('timestamp', $resource);
        $this->assertObjectHasAttribute('deletedAt', $resource->getTimestamp());
        $this->assertEquals(Carbon::parse('2040-01-01'), $resource->getTimestamp()->getDeletedAt());
        $this->assertEquals(['deletedAt' => Carbon::parse('2040-01-01')],$resource->getTimestamp()->jsonSerialize());
    }

    /**
     * @return void
     */
    public function test_get_model_data(){
        $resourceModelData = $this->resource->getModelData();
        $expectedData = [
            'id' => 10,
            'name' => 'test',
            'slug' => 'test'
        ];

        $this->assertEquals($expectedData,$resourceModelData);
    }

    /**
     * @return void
     */
    public function test_json_serialize(){
        $resourceJson = $this->resource->jsonSerialize();
        $expectedData = [
            'id' => 10,
            'name' => 'test',
            'slug' => 'test',
            'timestamp' => new Timestamp()
        ];

        $this->assertEquals($expectedData,$resourceJson);
    }
}
