<?php

namespace App\Http\Controllers\blog;

use App\Http\Controllers\Controller;

use App\Models\Post;

class BlogController extends Controller
{
    function index()
    {
        $posts = Post::paginate(2);
        return view('blog.index', compact('posts'));
    }

    function show(Post $post){
        
        return view('blog.show', ['post' => $post]);
    }
}
