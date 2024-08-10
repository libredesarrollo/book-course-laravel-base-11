<?php
namespace Tests\Feature\dashboard;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public User $user;
    protected function setUp(): void
    {
        parent::setUp();

        User::factory(1)->create();
        $this->user = User::first();

        $role = Role::firstOrCreate(['name' => 'Admin']);

        Permission::firstOrCreate(['name' => 'editor.category.index']);
        Permission::firstOrCreate(['name' => 'editor.category.create']);
        Permission::firstOrCreate(['name' => 'editor.category.update']);
        Permission::firstOrCreate(['name' => 'editor.category.destroy']);

        $role->syncPermissions([1, 2, 3, 4]);

        $this->user->assignRole($role);

        $this->actingAs($this->user);
    }
    function test_index()
    {
        Category::factory(20)->create();

        $response = $this->get(route('category.index'))
            ->assertOk()
            ->assertViewIs('dashboard.category.index')
            ->assertSee('Dashboard')
            ->assertSee('Create')
            ->assertSee('Show')
            ->assertSee('Delete')
            ->assertSee('Edit')
            ->assertSee('Id')
            ->assertSee('Title')
            // ->assertViewHas('categories', Category::paginate(20))
        ;

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('categories'));
    }

    function test_create_get()
    {

        $response = $this->get(route('category.create'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Title')
            ->assertSee('Slug')
            ->assertSee('Send')
            ->assertViewHas('category', new Category());;
    }

    function test_create_post()
    {
        $data = [
            'title' => 'Title',
            'slug' => 'title',
        ];

        $this->post(route('category.store', $data))
            ->assertRedirect(route('category.index'));

        $this->assertDatabaseHas('categories', $data);
    }
    function test_create_post_invalid()
    {
        $data = [
            'title' => '',
            'slug' => ''
        ];

        $this->post(route('category.store', $data))
            ->assertRedirect('/')
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
                'slug' => 'The slug field is required.'
            ]);
    }
    function test_edit_get()
    {
        Category::factory(1)->create();
        $category = Category::first();

        $response = $this->get(route('category.edit', $category))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Title')
            ->assertSee('Slug')
            ->assertSee('Send')
            ->assertSee($category->title)
            ->assertSee($category->slug)
            ->assertViewHas('category', $category);
        $this->assertInstanceOf(Category::class, $response->viewData('category'));
    }

    function test_edit_put()
    {
        Category::factory(1)->create();
        $category = Category::first();

        $data = [
            'title' => 'Title',
            'slug' => 'title'
        ];

        $this->put(route('category.update', $category), $data)
            ->assertRedirect(route('category.index'));

        $this->assertDatabaseHas('categories', $data);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    function test_edit_put_invalid()
    {
        Category::factory(1)->create();
        $category = Category::first();

        $this->get(route('category.edit', $category));

        $data = [
            'title' => 'a',
            'slug' => ''
        ];

        $this->put(route('category.update', $category), $data)
            ->assertRedirect(route('category.edit', $category))
            ->assertSessionHasErrors([
                'title' => 'The title field must be at least 5 characters.',
                'slug' => 'The slug field is required.'
            ]);
    }

    function test_edit_destroy()
    {
        Category::factory(1)->create();
        $category = Category::first();

        $data = [
            'id' => $category->id
        ];

        $this->delete(route('category.destroy', $category))
            ->assertRedirect(route('category.index'));

        $this->assertDatabaseMissing('categories', $data);
    }

}