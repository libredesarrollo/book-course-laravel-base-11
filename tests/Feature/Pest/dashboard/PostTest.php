<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Category;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    User::factory(1)->create();
    $this->user = User::first();

    $role = Role::firstOrCreate(['name' => 'Admin']);

    Permission::firstOrCreate(['name' => 'editor.post.index']);
    Permission::firstOrCreate(['name' => 'editor.post.create']);
    Permission::firstOrCreate(['name' => 'editor.post.update']);
    Permission::firstOrCreate(['name' => 'editor.post.destroy']);

    $role->syncPermissions([1, 2, 3, 4]);

    $this->user->assignRole($role);

    $this->actingAs($this->user);
});

test('test index', function () {
    Category::factory(3)->create();
    User::factory(3)->create();
    Post::factory(20)->create();

    $response = $this->get(route('post.index'))
        ->assertOk()
        ->assertViewIs('dashboard.post.index')
        ->assertSee('Dashboard')
        ->assertSee('Create')
        ->assertSee('Show')
        ->assertSee('Delete')
        ->assertSee('Edit')
        ->assertSee('Id')
        ->assertSee('Title')
        // ->assertViewHas('posts', Post::paginate(10))
    ;

    $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('posts'));


});
test('test create get', function () {
    Category::factory(10)->create();

    $response = $this->get(route('post.create'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Title')
        ->assertSee('Slug')
        ->assertSee('Content')
        ->assertSee('Category')
        ->assertSee('Description')
        ->assertSee('Posted')
        ->assertSee('Send')
        ->assertViewHas('categories', Category::pluck('id', 'title'))
        ->assertViewHas('post', new Post());
    $this->assertInstanceOf(Post::class, $response->viewData('post'));
    $this->assertInstanceOf(Collection::class, $response->viewData('categories'));
});
test('test create post', function () {

    Category::factory(1)->create();

    $data = [
        'title' => 'Title',
        'slug' => 'title',
        'content' => 'Content',
        'description' => 'Content',
        'category_id' => 1,
        'posted' => 'yes',
        'user_id' => $this->user->id
    ];

    $this->post(route('post.store', $data))
        ->assertRedirect(route('post.index'));

    $this->assertDatabaseHas('posts', $data);

});
test('test create post invalid', function () {

    Category::factory(1)->create();

    $data = [
        'title' => '',
        'slug' => '',
        'content' => '',
        'description' => '',
        // 'category_id' => 1,
        'posted' => '',
    ];

    $this->post(route('post.store', $data))
        ->assertRedirect('/')
        ->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'slug' => 'The slug field is required.',
            'content' => 'The content field is required.',
            'description' => 'The description field is required.',
            'posted' => 'The posted field is required.',
            'category_id' => 'The category id field is required.',
        ]);
});


test('test edit get', function () {
    User::factory(3)->create();
    Category::factory(10)->create();
    Post::factory(1)->create();
    $post = Post::first();

    $response = $this->get(route('post.edit', $post))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Title')
        ->assertSee('Slug')
        ->assertSee('Content')
        ->assertSee('Category')
        ->assertSee('Description')
        ->assertSee('Posted')
        ->assertSee('Send')
        ->assertSee($post->title)
        ->assertSee($post->content)
        ->assertSee($post->description)
        ->assertSee($post->slug)
        ->assertViewHas('categories', Category::pluck('id', 'title'))
        ->assertViewHas('post', $post);
    $this->assertInstanceOf(Post::class, $response->viewData('post'));
    $this->assertInstanceOf(Collection::class, $response->viewData('categories'));
});


test('test edit put', function () {

    User::factory(3)->create();
    Category::factory(10)->create();
    Post::factory(1)->create();
    $post = Post::first();

    $data = [
        'title' => 'Title',
        'slug' => 'title',
        'content' => 'Content',
        'description' => 'Content',
        'category_id' => 1,
        'posted' => 'yes'
    ];

    $this->put(route('post.update', $post), $data)
        ->assertRedirect(route('post.index'));

    $this->assertDatabaseHas('posts', $data);
    $this->assertDatabaseMissing('posts', $post->toArray());
});
test('test edit put invalid', function () {

    User::factory(3)->create();
    Category::factory(10)->create();
    Post::factory(1)->create();
    $post = Post::first();

    $this->get(route('post.edit', $post));

    $data = [
        'title' => 'a',
        'slug' => '',
        'content' => '',
        'description' => '',
        // 'category_id' => 1,
        'posted' => '',
    ];

    $this->put(route('post.update', $post), $data)
        ->assertRedirect(route('post.edit', $post))
        ->assertSessionHasErrors([
            'title' => 'The title field must be at least 5 characters.',
            'slug' => 'The slug field is required.',
            'content' => 'The content field is required.',
            'description' => 'The description field is required.',
            'posted' => 'The posted field is required.',
            'category_id' => 'The category id field is required.',
        ])
    ;
});

test('test destroy', function () {
    
    User::factory(3)->create();
    Category::factory(10)->create();
    Post::factory(1)->create();
    $post = Post::first();

    $data = [
        'id' => $post->id
    ];

    $this->delete(route('post.destroy', $post))
        ->assertRedirect(route('post.index'));

    $this->assertDatabaseMissing('posts', $data);

});