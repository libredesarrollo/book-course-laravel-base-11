<?php

use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

use Database\Seeders\RoleSeeder;

beforeEach(function () {
    User::factory(1)->create();
    $this->user = User::first();

    $role = Role::firstOrCreate(['name' => 'Admin']);
    $this->user->assignRole($role);

    $this->actingAs($this->user);
});

test('test index', function () {
 // Role::factory(20)->create();
 $this->seed(RoleSeeder::class);

 $response = $this->get(route('role.index'))
     ->assertOk()
     ->assertViewIs('dashboard.role.index')
     ->assertSee('Dashboard')
     ->assertSee('Create')
     ->assertSee('Show')
     ->assertSee('Delete')
     ->assertSee('Edit')
     ->assertSee('Id')
     ->assertSee('Name')
     // ->assertViewHas('roles', Role::paginate(20))
 ;

 $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('roles'));

});
test('test create get', function () {
    $response = $this->get(route('role.create'))
    ->assertOk()
    ->assertSee('Dashboard')
    ->assertSee('Name')
    ->assertSee('Send')
    ->assertViewHas('role', new Role());
});
test('test create post', function () {
    $data = [
        'name' => 'Name'
    ];

    $this->post(route('role.store', $data))
        ->assertRedirect(route('role.index'));

    $this->assertDatabaseHas('roles', $data);

});
test('test create post invalid', function () {

    $data = [
        'name' => '',
    ];

    $this->post(route('role.store', $data))
        ->assertRedirect('/')
        ->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
});


test('test edit get', function () {
    // Role::factory(1)->create();
    $this->seed(RoleSeeder::class);
    $role = Role::first();

    $response = $this->get(route('role.edit', $role))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Name')
        ->assertSee('Send')
        ->assertSee($role->name)
        ->assertViewHas('role', $role);
    $this->assertInstanceOf(Role::class, $response->viewData('role'));
});


test('test edit put', function () {
     // Role::factory(1)->create();
     $this->seed(RoleSeeder::class);
     $role = Role::first();

     $data = [
         'name' => 'Name'
     ];

     $this->put(route('role.update', $role), $data)
         ->assertRedirect(route('role.index'));

     $this->assertDatabaseHas('roles', $data);
     $this->assertDatabaseMissing('roles', $role->toArray());
});
test('test edit put invalid', function () {
    // Role::factory(1)->create();
    $this->seed(RoleSeeder::class);
    $role = Role::first();

    $this->get(route('role.edit', $role));

    $data = [
        'name' => 'a',
    ];

    $this->put(route('role.update', $role), $data)
        ->assertRedirect(route('role.edit', $role))
        ->assertSessionHasErrors([
            'name' => 'The name field must be at least 3 characters.'
        ]);
});

test('test destroy', function () {
    $this->seed(RoleSeeder::class);
    $role = Role::first();

    $data = [
        'id' => $role->id
    ];

    $this->delete(route('role.destroy', $role))
        ->assertRedirect(route('role.index'));

    $this->assertDatabaseMissing('roles', $data);
});