<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AplicationFullRouteTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = DatabaseSeeder::class;

    private $headerWithToken;

    public function setUp():void{
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_login_with_invalid_credentials()
    {
        $response = $this->invalidUserLogin();

        $response->assertStatus(400)
            ->assertJson(fn (AssertableJson $json) =>
                $json->missing('token')
            );
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_login_with_valid_credentials()
    {
        $response = $this->validUserLogin();

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('token')
            );
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_refresh()
    {
        $response = $this->invalidUserLogin();

        $response = $this->postJson('/api/auth/refresh');

        $response->assertStatus(400);
        $this->assertEquals("[]",$response->getContent());
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_refresh()
    {
        $response = $this->validUserLogin();

        $response = $this->postJson('/api/auth/refresh',[],$this->headerWithToken);

        $response->assertStatus(200);
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_try_see_profile()
    {
        $response = $this->invalidUserLogin();

        $response = $this->getJson('/api/auth/user');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_try_see_profile()
    {
        $response = $this->validUserLogin();

        $response = $this->getJson('/api/auth/user',$this->headerWithToken);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', 1)
                    ->where('name', 'Adriano ADM')
                    ->where('email', fn ($email) => str($email)->is('adrianoadm@example.com'))
                    ->missing('password')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_logout()
    {
        $response = $this->invalidUserLogin();

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_logout()
    {
        $response = $this->validUserLogin();

        $response = $this->postJson('/api/auth/logout',[],$this->headerWithToken);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('message')
                    ->missing('token')
            );
        $this->assertEquals('logged out',$response['message']);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_register_with_already_existant_email()
    {
        $request = [
            'name' => 'Adriano Teste',
            'email' => 'adrianoadm@example.com',
            'password' => '123456'
        ];

        $response = $this->postJson('/api/auth/register',$request);

        $response->assertStatus(400)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('message')
                    ->missing('token')
            );
        $this->assertEquals('Falha ao criar usuário',$response['message']);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_update()
    {
        $response = $this->invalidUserLogin();

        $response = $this->putJson('/api/user/7');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_authenticated_user_update_empty_request()
    {
        $response = $this->validUserLogin();

        $response = $this->putJson('/api/user/1',[],$this->headerWithToken);

        $response->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['message','errors'])
                    ->missing('token')

            );
        $this->assertEquals('The name field is required. (and 2 more errors)',$response['message']);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_delete()
    {
        $response = $this->invalidUserLogin();

        $response = $this->deleteJson('/api/user/7');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_authenticated_user_delete_invalid_id()
    {
        $response = $this->validUserLogin();

        $response = $this->deleteJson('/api/user/0',[],$this->headerWithToken);

        $response->assertStatus(400)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('message')
                    ->missing('token')
            );
        $this->assertEquals('Falha ao deletar usuário',$response['message']);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_register_update_and_delete()
    {
        $createRequest = [
            'name' => 'Adriano Teste',
            'email' => 'adrianoteste@example.com',
            'password' => '123456'
        ];

        $response = $this->postJson('/api/auth/register',$createRequest);

        $response->assertStatus(200);

        $token = str_replace('{"token":"',"",$response->getContent());
        $token = str_replace('"}',"",$token);

        $response = $this->getJson('/api/auth/user',['Authorization' => 'Bearer '.$token]);
        $id = $response['id'];

        $updateRequest = [
            'name' => 'Adriano Teste 2',
            'email' => 'adrianoteste2@example.com',
            'password' => '123456'
        ];

        $response = $this->putJson('/api/user/'.$id,$updateRequest,['Authorization' => 'Bearer '.$token]);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['message','data'])
                    ->missing('token')
            );
        $this->assertEquals('Usuário atualizado com sucesso',$response['message']);
        $this->assertNotEmpty($response['data']);

        $response = $this->deleteJson('/api/user/'.$id,[],['Authorization' => 'Bearer '.$token]);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['message','data'])
                    ->missing('token')
            );
        $this->assertEquals('Usuário apagado com sucesso',$response['message']);
        $this->assertEquals(['deleted' => TRUE],$response['data']);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_update_permission()
    {
        $response = $this->invalidUserLogin();

        $response = $this->getJson('/api/user/permission');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_update_permission()
    {
        $response = $this->validUserLogin();

        $resourcePermissions = [
            [
                'resource_id' => 1,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE
            ],
            [
                'resource_id' => 2,
                'view' => TRUE,
                'create' => TRUE,
                'update' => TRUE,
                'delete' => TRUE
            ],

        ];
        $request = [
            'user_id' => 5,
            'resource_permissions' => $resourcePermissions
        ];

        $response = $this->postJson('/api/user/permission',$request,$this->headerWithToken);

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_list_resources()
    {
        $response = $this->invalidUserLogin();

        $response = $this->getJson('/api/resource');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_list_resources()
    {
        $response = $this->validUserLogin();

        $response = $this->getJson('/api/resource',$this->headerWithToken);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(3)
                    ->first(fn ($json) =>
                        $json->where('id', 1)
                            ->where('name', 'produtos')
                            ->where('slug', 'product')
                            ->etc()
                    )
            );
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_see_index()
    {
        $response = $this->invalidUserLogin();

        $response = $this->getJson('/api/user/');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_see_index()
    {
        $response = $this->validUserLogin();

        $response = $this->getJson('/api/user/',$this->headerWithToken);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(6)
                    ->first(fn ($json) =>
                        $json->where('id', 1)
                            ->where('name', 'Adriano ADM')
                            ->where('email', fn ($email) => str($email)->is('adrianoadm@example.com'))
                            ->missing('password')
                            ->etc()
                    )
            );
    }

    /**
     * @return void
     */
    public function test_application_returns_error_when_unauthenticated_user_see_info()
    {
        $response = $this->invalidUserLogin();

        $response = $this->getJson('/api/user/1');

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function test_application_returns_successful_response_when_authenticated_user_see_info()
    {
        $response = $this->validUserLogin();

        $response = $this->getJson('/api/user/1',$this->headerWithToken);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', 1)
                    ->where('name', 'Adriano ADM')
                    ->where('email', fn ($email) => str($email)->is('adrianoadm@example.com'))
                    ->missing('password')
                    ->etc()
            );
    }

    private function invalidUserLogin(){
        $credentials = [
            'email' => 'adriano@example.com',
            'password' => '123456'
        ];
        $response = $this->postJson('/api/auth/login',$credentials);
        return $response;
    }

    private function validUserLogin(){
        $credentials = [
            'email' => 'adrianoadm@example.com',
            'password' => '123456'
        ];
        $response = $this->postJson('/api/auth/login',$credentials);
        $this->headerWithToken = ['Authorization' => 'Bearer '.$response['token']];
        return $response;
    }
}
