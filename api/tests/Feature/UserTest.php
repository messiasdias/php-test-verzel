<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $newUser;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->newUser = [
            'name' => "Messias Dias",
            'email' => "messiasdias.ti@gmail.com",
            'password' => "my_password_test"
        ];
    }

    /**
     * Store a user
     * @test
     * @return void
     */
    public function check_store_user()
    {
        $this->withExceptionHandling();

        //Error dont logged
        $response = $this->post('/api/users', $this->newUser);
        $response->assertStatus(403);

        $this->userLogin();

        //Error on add
        $response = $this->post('/api/users', array_merge($this->newUser, ['email' => null]), $this->getRequestHeaders());
        $response->assertStatus(302);
        $response = $this->post('/api/users', array_merge($this->newUser, ['password' => null]), $this->getRequestHeaders());
        $response->assertStatus(302);
        $response = $this->post('/api/users', array_merge($this->newUser, ['name' => null]), $this->getRequestHeaders());
        $response->assertStatus(302);

        //Sucess on add
        $response = $this->post('/api/users', $this->newUser, $this->getRequestHeaders());
        $response->assertStatus(200);
        $newUserId = $response->decodeResponseJson()['user']['id'];
        $this->assertEquals($this->newUser['name'], $response->decodeResponseJson()['user']['name']);

        //Error on add, alread exists
        $response = $this->post('/api/users', array_merge(["id" => 1], $this->newUser), $this->getRequestHeaders());
        $response->assertStatus(302);

        //Sucess update if alread exists
        $newUserName = "Messias W. Dias";
        $response = $this->post('/api/users', array_merge($this->newUser, ["id" => $newUserId, "name" => $newUserName]), $this->getRequestHeaders());
        $response->assertStatus(200);
        $this->assertEquals($newUserName, $response->decodeResponseJson()['user']['name']);
    }



    /**
     * Delete a user
     * @test
     * @return void
     */
    public function check_delete_user()
    {
        //Error on delete
        $response = $this->delete('/api/users', ["id" => 5]  , $this->getRequestHeaders());
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //Sucess add
        $response = $this->post('/api/users', $this->newUser, $this->getRequestHeaders());
        $response->assertStatus(200);
        $newUserId = $response->decodeResponseJson()['user']['id'];

        //Sucess on delete
        $response = $this->delete('/api/users',  ["id" => $newUserId] , $this->getRequestHeaders());
        $response->assertStatus(200);

        //Error on delete
        $response = $this->delete('/api/users',  ["id" => $newUserId] , $this->getRequestHeaders());
        $response->assertStatus(404);
    }


    /**
     * Get one user
     * @test
     * @return void
     */
    public function check_get_user()
    {
        //Error on get
        $response = $this->get('/api/users/5');
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //Sucess add
        $response = $this->post('/api/users', $this->newUser, $this->getRequestHeaders());
        $response->assertStatus(200);
        $newUserId = $response->decodeResponseJson()['user']['id'];

        //Sucess on get
        $response = $this->get("/api/users/{$newUserId}", $this->getRequestHeaders());
        $response->assertStatus(200);
        $this->assertEquals($newUserId, $response->decodeResponseJson()['user']['id']);
        $this->assertEquals($this->newUser['name'], $response->decodeResponseJson()['user']['name']);

        //Error on get
        $response = $this->get('/api/users/5', $this->getRequestHeaders());
        $response->assertStatus(404);
    }


    /**
     * Get all users
     * @test
     * @return void
     */
    public function check_get_users()
    {
        //Error
        $response = $this->get('/api/users');
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //get users
        $response = $this->get('/api/users', $this->getRequestHeaders());
        $this->assertEquals(1, count($response->decodeResponseJson()['data']));

        //Sucess
        $this->post('/api/users', [
            'name' => "Ana Maria",
            'email' => "ana@exemplomail.home",
            'password' => "my_password_test"
        ], $this->getRequestHeaders());

        $this->post('/api/users', [
            'name' => "Antonio José",
            'email' => "antonio@exemplomail.home",
            'password' => "my_password_test"
        ], $this->getRequestHeaders());

        $this->post('/api/users', [
            'name' => "Rafael Andrade",
            'email' => "rafael@exemplomail.home",
            'password' => "my_password_test"
        ], $this->getRequestHeaders());

        //get users
        $response = $this->get('/api/users?page=1', $this->getRequestHeaders());
        $this->assertEquals(1, $response->decodeResponseJson()['current_page']);
        $this->assertEquals(4, count($response->decodeResponseJson()['data']));

        $response = $this->get('/api/users?page=2', $this->getRequestHeaders());
        $this->assertEquals(2, $response->decodeResponseJson()['current_page']);
        $this->assertEquals(0, count($response->decodeResponseJson()['data']));
        $this->assertEquals(4, $response->decodeResponseJson()['total']);
    }

     /**
     * Get user permissions list
     * @test
     * @return void
     */
    public function check_get_permissions_list()
    {
        //Error
        $response = $this->get('/api/users/permissions');
        $response->assertStatus(403);

        //login
        $this->userLogin();

        $response = $this->get('/api/users/permissions',  $this->getRequestHeaders());
        $response->assertStatus(200);

        $permissionsList = $response->decodeResponseJson();
        $this->assertEquals("Usuários", $permissionsList['users']);
        $this->assertEquals("Veículos", $permissionsList['vehicles']);
    }

    /**
     * Set user permissions
     * @test
     * @return void
     */
    public function check_set_permissions()
    {
        //Error
        $response = $this->post('/api/users/permissions');
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //Sucess
        $response = $this->post('/api/users', [
            'name' => "Ana Maria",
            'email' => "ana@exemplomail.home",
            'password' => "my_password_test"
        ], $this->getRequestHeaders());

        $user = $response->decodeResponseJson()['user'];
        $this->assertEquals("Ana Maria", $user['name']);

        //Sucess
        $response = $this->post('/api/users/permissions', [
            'users_id' => $user['id'],
           'permissions' => [
            'users' => true,
            'vehicles' => true,
           ]
        ], $this->getRequestHeaders());

        $permissions = $response->decodeResponseJson()['user']['permissions'];
        $permissions = json_decode($permissions);

        $this->assertTrue($permissions->users);
        $this->assertTrue($permissions->vehicles);
    }


    /**
     * Send confirmation mail
     * @test
     * @return void
     */
    public function check_send_confirm_mail()
    {
        //Error
        $response = $this->get('/api/users/send-confirm-mail/100');
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //Sucess
        $response = $this->post('/api/users', [
            'name' => "Ana Maria",
            'email' => "ana@exemplomail.home",
            'password' => "my_password_test"
        ], $this->getRequestHeaders());

        $lastUserId = $response->decodeResponseJson()['user']['id'];

        $response = $this->get("/api/users/send-confirm-mail/{$lastUserId}", $this->getRequestHeaders());
        $response->assertStatus(200);
        $this->assertTrue($response->decodeResponseJson()['success']);
    }


     /**
     * Confirm mail url
     * @test
     * @return void
     */
    public function check_confirm_mail()
    {
        $hash = "akshkhdkhdkhsdhahsdhakjshdkashdkjdhasdhasjdhaskjhdkh";
        //Error
        $response = $this->get("/api/users/confirm-mail/100/{$hash}");
        $response->assertStatus(302);

        //login
        $this->userLogin();

        //Sucess
        $response = $this->post('/api/users', [
            'name' => "Ana Maria",
            'email' => "ana@exemplomail.home",
            'password' => "my_password_test"
        ], $this->getRequestHeaders());

        $lastUserId = $response->decodeResponseJson()['user']['id'];
        $user = User::find($lastUserId);
        $hash = $user->getConfirmationCode();

        $response = $this->get("/api/users/confirm-mail/{$lastUserId}/{$hash}", $this->getRequestHeaders());
        $response->assertStatus(302);
        $response->assertSessionHas('status', "Email confirmado com sucesso!");
    }
}
