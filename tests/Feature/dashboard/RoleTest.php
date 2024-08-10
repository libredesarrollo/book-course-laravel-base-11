<?php
namespace Tests\Feature\dashboard;

use App\Models\User;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Pagination\LengthAwarePaginator;

use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
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
    }

    function test_create_get()
    {

        $response = $this->get(route('role.create'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Name')
            ->assertSee('Send')
            ->assertViewHas('role', new Role());
    }

    function test_create_post()
    {
        $data = [
            'name' => 'Name'
        ];

        $this->post(route('role.store', $data))
            ->assertRedirect(route('role.index'));

        $this->assertDatabaseHas('roles', $data);
    }
    function test_create_post_invalid()
    {
        $data = [
            'name' => '',
        ];

        $this->post(route('role.store', $data))
            ->assertRedirect('/')
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
    }
    function test_edit_get()
    {
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
    }

    function test_edit_put()
    {
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
    }

    function test_edit_put_invalid()
    {
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
    }

    function test_edit_destroy()
    {
        $this->seed(RoleSeeder::class);
        $role = Role::first();

        $data = [
            'id' => $role->id
        ];

        $this->delete(route('role.destroy', $role))
            ->assertRedirect(route('role.index'));

        $this->assertDatabaseMissing('roles', $data);
    }

}