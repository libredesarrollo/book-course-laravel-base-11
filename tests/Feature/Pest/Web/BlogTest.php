<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;

test('test index', function () {
    $response = $this
    ->get(route('blog.index'))
    ->assertStatus(200)
    ->assertViewIs('blog.index')
    ->assertSee('Post List')
    ->assertViewHas('posts',
     Post::paginate(2));
    $this->assertInstanceOf(LengthAwarePaginator::class,$response->viewData('posts'));
 ;
});

test('test show return html cache', function () {
    Category::factory(3)->create();
    User::factory(3)->create();
    Post::factory(1)->create();

    $post = Post::first();
    $html = view('blog.show', ['post' => $post])->render();

    $response = $this
        ->get(route('blog.show', ['post'=> $post]))
        // ->assertStatus(200)
        // ->assertSee($html, escape:false)
        ->assertOk()
        ->assertSee($post->title)
        ->assertSee($post->content)
        ->assertSee($post->category->title);
    // dd($response->getContent());
    $this->assertEquals($html, $response->getContent());
});
