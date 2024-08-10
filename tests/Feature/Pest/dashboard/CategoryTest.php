<?php

use App\Models\User;
use App\Models\Category;

use Illuminate\Pagination\LengthAwarePaginator;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
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
});

test('test index', function () {
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
        ->assertSee('Title');

    $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('categories'));


});
test('test create get', function () {
    $this->get(route('category.create'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Title')
        ->assertSee('Slug')
        ->assertSee('Send')
        ->assertViewHas('category', new Category());
    ;

});
test('test create post', function () {

    $data = [
        'title' => 'Title',
        'slug' => 'title',
    ];

    $this->post(route('category.store', $data))
        ->assertRedirect(route('category.index'));

    $this->assertDatabaseHas('categories', $data);
});
test('test create post invalid', function () {

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
});


test('test edit get', function () {
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

});


test('test edit put', function () {

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
});
test('test edit put invalid', function () {


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
});

test('test destroy', function () {

    Category::factory(1)->create();
    $category = Category::first();

    $data = [
        'id' => $category->id
    ];

    $this->delete(route('category.destroy', $category))
        ->assertRedirect(route('category.index'));

    $this->assertDatabaseMissing('categories', $data);

});