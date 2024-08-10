<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;

test('test login', function () {
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

});

test('test login invalid', function () {
    User::factory(1)->create();
    $user = User::first();

    $credentials = [
        'email' => $user->email,
        'password' => 'invalid-password',
    ];

    $response = $this->post('/login', $credentials);
    $response->assertRedirect('/');

    $this->assertInvalidCredentials($credentials);
});
test('test register', function () {
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
});
test('test register invalid name', function () {
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
});
