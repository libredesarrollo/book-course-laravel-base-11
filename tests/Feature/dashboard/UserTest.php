<?php
namespace Tests\Feature\dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public User $user;
    protected function setUp(): void
    {
        parent::setUp();

        User::factory(1)->create();
        $this->user = User::first();

        $role = Role::firstOrCreate(['name' => 'Admin']);

        Permission::firstOrCreate(['name' => 'editor.user.index']);
        Permission::firstOrCreate(['name' => 'editor.user.create']);
        Permission::firstOrCreate(['name' => 'editor.user.update']);
        Permission::firstOrCreate(['name' => 'editor.user.destroy']);

        $role->syncPermissions([1, 2, 3, 4]);

        $this->user->assignRole($role);

        $this->actingAs($this->user);
    }
    function test_index()
    {
        User::factory(20)->create();

        $response = $this->get(route('user.index'))
            ->assertOk()
            ->assertViewIs('dashboard.user.index')
            ->assertSee('Dashboard')
            ->assertSee('Create')
            ->assertSee('Show')
            ->assertSee('Delete')
            ->assertSee('Edit')
            ->assertSee('Id')
            ->assertSee('Name')
            // ->assertViewHas('users', User::paginate(20))
        ;

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('users'));
    }

    function test_create_get()
    {

        $this->get(route('user.create'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Password Confirmation')
            ->assertSee('Send')
            ->assertViewHas('user', new User());
        ;
    }

    function test_create_post()
    {
        $data = [
            'name' => 'andres',
            'email' => 'userregular@gmail.com',
            'password' => '&*FSDsdGDF1',
            'password_confirmation' => '&*FSDsdGDF1'
        ];

        $this->post(route('user.store', $data))
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'andres',
            'email' => 'userregular@gmail.com',
        ]);
    }
    function test_create_post_invalid()
    {
        $data = [
            'name' => ''
        ];

        $this->post(route('user.store', $data))
            ->assertRedirect('/')
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $data = [
            'name' => 'a',
            'email' => 'andres',
            'password' => '123',
            'password_confirmation' => '1234',
        ];

        $this->post(route('user.store', $data))
            ->assertRedirect('/')
            ->assertSessionHasErrors([
                'name' => 'The name field must be at least 5 characters.',
                'email' => 'The email field must be a valid email address.',
                'password' => 'The password field confirmation does not match.',
                // 'password' => 'The password field must be at least 8 characters.',
                // 'password' => 'The password field must contain at least one uppercase and one lowercase letter.',
                // 'password' => 'The password field must contain at least one symbol.',
                // 'password' => 'The password field must contain at least one number.',
            ]);
    }
    function test_edit_get()
    {
        User::factory(1)->create();
        $user = User::first();

        $response = $this->get(route('user.edit', $user))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Send')
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertViewHas('user', $user);
        $this->assertInstanceOf(User::class, $response->viewData('user'));
    }

    function test_edit_put()
    {
        User::factory(1)->create();
        $user = User::first();

        $data = [
            'name' => 'New Name',
            'email' => 'userregularnew@gmail.com',
            'password' => 'new&*FSDsdGDF1',
            'password_confirmation' => 'new&*FSDsdGDF1'
        ];

        $this->put(route('user.update', $user), $data)
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'userregularnew@gmail.com',
        ]);
        $this->assertDatabaseMissing('users', $user->toArray());
    }

    function test_edit_put_invalid()
    {
        User::factory(1)->create();
        $user = User::first();

        $this->get(route('user.edit', $user));

        $data = [
            'name' => 'a',
            'email' => ''
        ];

        $this->put(route('user.update', $user), $data)
            ->assertRedirect(route('user.edit', $user))
            ->assertSessionHasErrors([
                'name' => 'The name field must be at least 5 characters.',
                'email' => 'The email field is required.'
            ]);

        $data = [
            'name' => 'a',
            'email' => 'andres',
            'password' => '123',
            'password_confirmation' => '1234',
        ];

        $this->put(route('user.update', $user), $data)
            ->assertSessionHasErrors([
                'name' => 'The name field must be at least 5 characters.',
                'email' => 'The email field must be a valid email address.',
                'password' => 'The password field confirmation does not match.',
                // 'password' => 'The password field must be at least 8 characters.',
                // 'password' => 'The password field must contain at least one uppercase and one lowercase letter.',
                // 'password' => 'The password field must contain at least one symbol.',
                // 'password' => 'The password field must contain at least one number.',
            ]);
    }

    function test_edit_destroy()
    {
        User::factory(1)->create();
        $user = User::first();

        $data = [
            'id' => $user->id
        ];

        $this->delete(route('user.destroy', $user))
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseMissing('users', $data);
    }

}