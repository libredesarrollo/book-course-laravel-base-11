<?php

use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    User::factory(1)->create();
    $this->user = User::first();

    $role = Role::firstOrCreate(['name' => 'Admin']);
    $this->user->assignRole($role);

    $this->actingAs($this->user);
});

test('test index', function () {
    // Permission::factory(20)->create();
    $this->seed(PermissionSeeder::class);

    $response = $this->get(route('permission.index'))
        ->assertOk()
        ->assertViewIs('dashboard.permission.index')
        ->assertSee('Dashboard')
        ->assertSee('Create')
        ->assertSee('Show')
        ->assertSee('Delete')
        ->assertSee('Edit')
        ->assertSee('Id')
        ->assertSee('Name')
        // ->assertViewHas('permissions', Permission::paginate(20))
    ;

    $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('permissions'));

});
test('test create get', function () {
    $this->get(route('permission.create'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Name')
        ->assertSee('Send')
        ->assertViewHas('permission', new Permission());
});
test('test create post', function () {
    $data = [
        'name' => 'Name'
    ];

    $this->post(route('permission.store', $data))
        ->assertRedirect(route('permission.index'));

    $this->assertDatabaseHas('permissions', $data);

});
test('test create post invalid', function () {

    $data = [
        'name' => '',
    ];

    $this->post(route('permission.store', $data))
        ->assertRedirect('/')
        ->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
});


test('test edit get', function () {
    // Permission::factory(1)->create();
    $this->seed(PermissionSeeder::class);
    $permission = Permission::first();

    $response = $this->get(route('permission.edit', $permission))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Name')
        ->assertSee('Send')
        ->assertSee($permission->name)
        ->assertViewHas('permission', $permission);
    $this->assertInstanceOf(Permission::class, $response->viewData('permission'));
});


test('test edit put', function () {
    // Permission::factory(1)->create();
    $this->seed(PermissionSeeder::class);
    $permission = Permission::first();

    $data = [
        'name' => 'Name'
    ];

    $this->put(route('permission.update', $permission), $data)
        ->assertRedirect(route('permission.index'));

    $this->assertDatabaseHas('permissions', $data);
    $this->assertDatabaseMissing('permissions', $permission->toArray());
});
test('test edit put invalid', function () {
    // Permission::factory(1)->create();
    $this->seed(PermissionSeeder::class);
    $permission = Permission::first();

    $this->get(route('permission.edit', $permission));

    $data = [
        'name' => 'a',
    ];

    $this->put(route('permission.update', $permission), $data)
        ->assertRedirect(route('permission.edit', $permission))
        ->assertSessionHasErrors([
            'name' => 'The name field must be at least 3 characters.'
        ]);
});

test('test destroy', function () {

    $this->seed(PermissionSeeder::class);
    $permission = Permission::first();

    $data = [
        'id' => $permission->id
    ];

    $this->delete(route('permission.destroy', $permission))
        ->assertRedirect(route('permission.index'));

    $this->assertDatabaseMissing('permissions', $data);
});