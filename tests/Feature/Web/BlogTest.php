<?php
namespace Tests\Feature\Web;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Pagination\LengthAwarePaginator;

use Tests\TestCase;

class BlogTest extends TestCase
{
    use DatabaseMigrations;


    function test_index()
    {
        // $response = $this->get('/blog')
        $response = $this
            ->get(route('blog.index'))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSee('Post List')
            ->assertViewHas('posts',
             Post::paginate(2));
            $this->assertInstanceOf(LengthAwarePaginator::class,$response->viewData('posts'));
    }
    // function test_show()
    // {
    //     // $response = $this->get('/blog')
    //     Category::factory(3)->create();
    //     User::factory(3)->create();
    //     Post::factory(1)->create();

    //     $post = Post::first();
    //     $response = $this
    //         ->get(route('blog.show', ['post'=> $post]))
    //         ->assertStatus(200)
    //         ->assertViewIs('blog.show')
    //         ->assertSee($post->title)
    //         ->assertSee($post->content)
    //         ->assertSee($post->category->title)
    //         ->assertViewHas('post', $post);

    //         $this->assertInstanceOf(Post::class,$response->viewData('post'));
    // }
    function test_show_return_html_cache()
    {
        // $response = $this->get('/blog')
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

    }
   

}