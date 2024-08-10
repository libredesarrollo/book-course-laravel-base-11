<?php
namespace Tests\Feature\Web;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    // function test_login_get()
    // {
    //     $this->get('/login')
    //         ->assertStatus(200)
    //         ->assertSee('Forgot your password?')
    //         ->assertSee('Email')
    //         ->assertSee('Password')
    //     ;
    // }

    // function test_login_post()
    // {
    //     User::factory(1)->create();
    //     $user = User::first();

    //     $credentials = [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ];

    //     $response = $this->post('/login', $credentials);
    //     $response->assertRedirect('/dashboard');

    //     $this->assertCredentials($credentials);
    // }
    function test_login()
    {
        // get
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Forgot your password?')
            ->assertSee('Email')
            ->assertSee('Password');

        // post
        User::factory(1)->create();
        $user = User::first();

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];
        
        $response = $this->post('/login', $credentials);
        $response->assertRedirect('/dashboard');

        $this->assertCredentials($credentials);
    }
    function test_login_invalid()
    {
        User::factory(1)->create();
        $user = User::first();
        
        $credentials = [
            'email' => $user->email,
            'password' => 'invalid-password',
        ];
        
        $response = $this->post('/login', $credentials);
        $response->assertRedirect('/');
        
        $this->assertInvalidCredentials($credentials);
    }
    function test_register()
    {
        // get
        $this->get('/register')
            ->assertStatus(200)
            ->assertSee('Already registered?')
            ->assertSee('Email')
            ->assertSee('Password');
    
        // post    
        $data = [
            'name' => 'Andres',
            'email' => 'andres@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    
        $response = $this->post('/register', $data);
        $response->assertRedirect('/dashboard');

        $this->assertCredentials($data);
    }
    function test_register_invalid_name()
    {
        //  get para el redirect back al momento de los errores del form
        $this->get('/register');
    
        // post    
        $data = [
            'name' => '',
            'email' => 'andres@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    
        $response = $this->post('/register', $data);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    }
}