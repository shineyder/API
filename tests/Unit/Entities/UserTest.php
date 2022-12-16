<?php

namespace Tests\Unit\Entities;

use App\Entities\Commons\Timestamp;
use App\Entities\Resource;
use App\Entities\ResourcePermission;
use App\Entities\User;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class UserTest extends TestCase
{
    private $resourcePermission;
    private $user;

    public function setUp(): void {
        $resource = [
            'id' => 10,
            'name' => 'test',
            'slug' => 'test'
        ];

        $resourcePermission = [
            'id' => 11,
            'resource' => $resource,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];
        $this->resourcePermission = new ResourcePermission($resourcePermission);

        $data = [
            'id' => 1,
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'is_admin' => TRUE
        ];
        $this->user = new User($data);
        $this->user->addResourcePermissionFromArray($resourcePermission);
    }

    /**
     * @return void
     */
    public function test_getters(){
        $id = $this->user->getId();
        $name = $this->user->getName();
        $email = $this->user->getEmail();
        $password = $this->user->getPassword();
        $isAdmin = $this->user->isAdmin();

        $this->assertEquals(1, $id, 'Id getter error');
        $this->assertEquals('Adriano', $name, 'Name getter error');
        $this->assertEquals('adrianoshineyder@hotmail.com', $email, 'Email getter error');
        $this->assertEquals('123456', $password, 'Password getter error');
        $this->assertEquals(TRUE, $isAdmin, 'Is Admin getter error');
    }

    /**
     * @return void
     */
    public function test_fill_with_resource_permission_in_data_array(){
        $resource = $this->createMock(Resource::class);
        $resourcePermission = [
            [
                'id' => 13,
                'resource' => $resource,
                'view' => FALSE,
                'create' => FALSE,
                'update' => FALSE,
                'delete' => FALSE
            ]
        ];
        $data = [
            'id' => 2,
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'is_admin' => TRUE,
            'resource_permissions' => $resourcePermission
        ];
        $user = new User($data);

        $this->assertObjectHasAttribute('resourcePermissions', $user);
    }

    /**
     * @return void
     */
    public function test_fill_with_timestamp_in_data_array(){
        $timestamp = new Timestamp(['created_at' => '2040-01-01']);
        $data = [
            'id' => 2,
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'is_admin' => TRUE,
            'timestamp' => $timestamp
        ];
        $user = new User($data);

        $this->assertObjectHasAttribute('timestamp', $user);
        $this->assertObjectHasAttribute('createdAt', $user->getTimestamp());
        $this->assertEquals(Carbon::parse('2040-01-01'), $user->getTimestamp()->getCreatedAt());
    }

    /**
     * @return void
     */
    public function test_fill_with_created_at_in_data_array(){
        $data = [
            'id' => 2,
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'is_admin' => TRUE,
            'created_at' => '2040-01-01'
        ];
        $user = new User($data);

        $this->assertObjectHasAttribute('timestamp', $user);
        $this->assertObjectHasAttribute('createdAt', $user->getTimestamp());
    }

    /**
     * @return void
     */
    public function test_get_permission_by_resource_id(){
        $getResourcePermission = $this->user->getPermissionByResourceId(10);
        $this->assertEquals($this->resourcePermission, $getResourcePermission);
    }

    /**
     * @return void
     */
    public function test_get_none_permission_by_resource_id(){
        $getResourcePermission = $this->user->getPermissionByResourceId(11);
        $this->assertNull($getResourcePermission);
    }

    /**
     * @return void
     */
    public function test_has_resource_by_id(){
        $hasTestResource = $this->user->hasResourceById(10);
        $this->assertTrue($hasTestResource);
    }

    /**
     * @return void
     */
    public function test_has_not_resource_by_id(){
        $hasTestResource = $this->user->hasResourceById(11);
        $this->assertFalse($hasTestResource);
    }

    /**
     * @return void
     */
    public function test_has_permission(){
        $hasViewTestPermission = $this->user->hasPermission('test','view');
        $this->assertTrue($hasViewTestPermission);
    }

    /**
     * @return void
     */
    public function test_has_not_permission(){
        $hasViewTestPermission = $this->user->hasPermission('another_test','view');
        $this->assertFalse($hasViewTestPermission);
    }

    /**
     * @return void
     */
    public function test_refresh_permission(){
        $this->resourcePermission->setPermission('view',FALSE);
        $this->user->refreshPermission($this->resourcePermission);
        $getResourcePermission = $this->user->getPermissionByResourceId(10);
        $this->assertEquals($this->resourcePermission,$getResourcePermission);
    }

    /**
     * @return void
     */
    public function test_refresh_none_permission_if_user_has_not_given_resource(){
        $resource = [
            'id' => 12,
            'name' => 'test2',
            'slug' => 'test2'
        ];
        $resourcePermission = [
            'id' => 13,
            'resource' => $resource,
            'view' => FALSE,
            'create' => FALSE,
            'update' => FALSE,
            'delete' => FALSE
        ];
        $resourcePermissionEntity = new ResourcePermission($resourcePermission);
        $this->user->refreshPermission($resourcePermissionEntity);

        $getNewResourcePermission = $this->user->getPermissionByResourceId(12);
        $this->assertNull($getNewResourcePermission,'Resource Permission incorrectly added');

        $getOldResourcePermission = $this->user->getPermissionByResourceId(10);
        $this->assertEquals($this->resourcePermission,$getOldResourcePermission,'Resource Permission incorrectly updated');
    }

    /**
     * @return void
     */
    public function test_add_resource_permission(){
        $resource = [
            'id' => 12,
            'name' => 'test2',
            'slug' => 'test2'
        ];
        $resourcePermission = [
            'id' => 13,
            'resource' => $resource,
            'view' => TRUE,
            'create' => TRUE,
            'update' => TRUE,
            'delete' => TRUE
        ];
        $resourcePermissionEntity = new ResourcePermission($resourcePermission);

        $this->user->addResourcePermission($resourcePermissionEntity);

        $getResourcePermission = $this->user->getPermissionByResourceId(12);
        $this->assertEquals($resourcePermissionEntity, $getResourcePermission);
    }

    /**
     * @return void
     */
    public function test_get_model_data(){
        $userModelData = $this->user->getModelData();
        $expectedData = [
            'id' => 1,
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'is_admin' => TRUE
        ];

        $this->assertEquals($expectedData,$userModelData);
    }

    /**
     * @return void
     */
    public function test_json_serialize(){
        $userJson = $this->user->jsonSerialize();
        $expectedData = [
            'id' => 1,
            'name' => 'Adriano',
            'email' => 'adrianoshineyder@hotmail.com',
            'password' => '123456',
            'isAdmin' => TRUE,
            'resourcePermissions' => [$this->resourcePermission],
            'timestamp' => new Timestamp()
        ];

        $this->assertEquals($expectedData,$userJson);
    }
}
