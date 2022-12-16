<?php

namespace Tests\Feature\Http\Controller;

use App\Http\Controllers\Api\AuthController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private $userRepository;
    private $authController;
    private $request;

    public function setUp():void{
        $this->request = $this->createMock(Request::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->authController = new AuthController(
            $this->userRepository
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function test_login_should_return_error_with_invalid_credentials(){
        $response = $this->invalidUserLoggedIn();

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_login_should_run_successfully_with_valid_credentials(){
        $response = $this->validUserLoggedIn();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_logout_should_run_successfully_with_no_user_authenticated(){
        $response = $this->authController->logout();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_logout_should_run_successfully_with_user_authenticated(){
        $response = $this->validUserLoggedIn();
        $response = $this->authController->logout();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_user_profile_should_not_run_with_no_user_authenticated(){
        $response = $this->authController->userProfile();

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_user_profile_should_run_successfully_with_user_authenticated(){
        $response = $this->validUserLoggedIn();
        $response = $this->authController->userProfile();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function test_refresh_should_not_run_with_no_user_authenticated(){
        $response = $this->authController->refresh();

        $this->assertEquals(400, $response->getStatusCode());
    }

    private function invalidUserLoggedIn(){
        $this->request->expects($this->once())
            ->method('only')
            ->willReturn(['email' => 'test@example.com', 'password' => '123456']);

        $response = $this->authController->login($this->request);

        return $response;
    }

    private function validUserLoggedIn(){
        $this->request->expects($this->once())
            ->method('only')
            ->willReturn(['email' => 'adrianoadm@example.com', 'password' => '123456']);

        $response = $this->authController->login($this->request);

        return $response;
    }
}
