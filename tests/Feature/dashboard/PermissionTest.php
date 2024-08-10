<?php
namespace Tests\Feature\dashboard;

use App\Models\User;

use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Pagination\LengthAwarePaginator;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    public User $user;
    protected function setUp(): void
    {
        parent::setUp();

        User::factory(1)->create();
        $this->user = User::first();

        $role = Role::firstOrCreate(['name' => 'Admin']);
        $this->user->assignRole($role);

        $this->actingAs($this->user);
    }
    function test_index()
    {
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
    }

    function test_create_get()
    {

        $this->get(route('permission.create'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Name')
            ->assertSee('Send')
            ->assertViewHas('permission', new Permission());
    }

    function test_create_post()
    {
        $data = [
            'name' => 'Name'
        ];

        $this->post(route('permission.store', $data))
            ->assertRedirect(route('permission.index'));

        $this->assertDatabaseHas('permissions', $data);
    }
    function test_create_post_invalid()
    {
        $data = [
            'name' => '',
        ];

        $this->post(route('permission.store', $data))
            ->assertRedirect('/')
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
    }
    function test_edit_get()
    {
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
    }

    function test_edit_put()
    {
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
    }

    function test_edit_put_invalid()
    {
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
    }

    function test_edit_destroy()
    {
        $this->seed(PermissionSeeder::class);
        $permission = Permission::first();

        $data = [
            'id' => $permission->id
        ];

        $this->delete(route('permission.destroy', $permission))
            ->assertRedirect(route('permission.index'));

        $this->assertDatabaseMissing('permissions', $data);
    }

}