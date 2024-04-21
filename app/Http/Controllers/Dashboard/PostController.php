<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PutRequest;
use App\Http\Requests\Post\StoreRequest;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);
        return view('dashboard/post/index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Category::pluck('id', 'title');
        $post = new Post();

        return view('dashboard.post.create', compact('categories', 'post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        

        // Post::create($request->validated());
        $post = new Post($request->validated());
        auth()->user()->posts()->save($post);
        return to_route('post.index')->with('status', 'Post created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard/post/show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {


        // dd(Gate::check('create', $post));
        // dd(Gate::any(['create','update'], $post));
        // dd(Gate::none(['create','update'], $post));
        // dd(auth()->user()->can('create', $post));
        // dd(auth()->user()->cannot('create', $post));
        

        // Gate::allowIf(fn(User $user) => $user->id < 0);

        // dd(Gate::inspect('update', $post)->message());
        // if (!Gate::allows('update', $post)) {
        // $res = Gate::inspect('update', $post);
        // if (!$res->allowed()) {
        //     return abort(403, $res->message());
        // }

        Gate::authorize('update', $post);

        $categories = Category::pluck('id', 'title');
        return view('dashboard.post.edit', compact('categories', 'post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutRequest $request, Post $post)
    {

        if (!Gate::allows('update', $post)) {
            return abort(403);
        }

        $data = $request->validated();

        // image
        if (isset($data['image'])) {
            $data['image'] = $filename = time() . '.' . $data['image']->extension();
            $request->image->move(public_path('uploads/posts'), $filename);
        }
        // image

        Cache::forget('post_show_' . $post->id);
        $post->update($data);
        return to_route('post.index')->with('status', 'Post updated');
    }

    public function destroy(Post $post)
    {
        if (!Gate::allows('delete', $post)) {
            return abort(403);
        }
        $post->delete();
        return to_route('post.index')->with('status', 'Post delete');
    }
}
