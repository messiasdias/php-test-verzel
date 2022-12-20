<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Models\UserPermission;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $user, $token, $userPermission;

    public function setUser() : self
    {
        $cred = $this->getUserCredentials();
        $this->user = new User([
            'name' => "Test ",
            'email' => $cred["email"]
        ]);

        $this->user->setPassword($cred["password"]);
        $this->user->save();
        return $this;
    }

    public function getUserCredentials() : array
    {
        return [
            "email" => "user@test.com",
            "password" => "user_pswd_123",
        ];
    }

    public function userLogin($showToken = false) : self
    {
        if(!$this->user) $this->setUser();
        $response = $this->post('/api/auth/login', $this->getUserCredentials());
        $this->token = $response->json('access_token');
        if($showToken) echo $this->token;
        return $this;
    }

    public function getRequestHeaders() : array
    {
        return ["Authorization" => "Bearer {$this->token}"];
    }
}
