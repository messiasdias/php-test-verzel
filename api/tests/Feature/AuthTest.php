<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Login test
     *
     * @test
     * @return void
     */
    public function login()
    {
        $this->setUser();
        $cred = $this->getUserCredentials();

        //Login error pass
        $response = $this->post('/api/auth/login', [
            'email' => $cred["email"],
            'password' => "user_pswd"
        ]);
        $response->assertStatus(401);

        //Login error email
        $response = $this->post('/api/auth/login', [
            'email' => "outher@test.com",
            'password' => $cred["password"]
        ]);
        $response->assertStatus(401);

        //Login success
        $response = $this->post('/api/auth/login', [
            'email' => $cred["email"],
            'password' => $cred["password"]
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test logout
     * 
     * @test
     * @return void
     */
    public function logout(){
        //Dont logged
        $response = $this->post('/api/auth/logout');
        $response->assertStatus(403);

        $this->userLogin();
        $response = $this->post('/api/auth/logout', [], $this->getRequestHeaders());
        $response->assertStatus(200)->assertJson(['message' => true ]);
    }

    /**
     * Test refresh json web token
     * 
     * @test
     * @return void
     */
    public function refresh(){
        //Logout before login
        $response = $this->post('/api/auth/refresh');
        $response->assertStatus(403);

        //Logout after success Login
        $this->userLogin();
        $response = $this->post('/api/auth/refresh', ['access_token' => $this->token], $this->getRequestHeaders());
        $response->assertStatus(200)->assertJson(['access_token' => true]);
    }
}
