<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PutRequest;
use App\Http\Requests\Post\StoreRequest;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Post::with('category')->paginate(10);

        return view('dashboard/post/index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (!auth()->user()->hasPermissionTo('editor.post.create')) {
            return abort(403);
        }

        $categories = Category::pluck('id', 'title');
        $tags = Tag::pluck('id', 'name');
        $post = new Post();

        return view('dashboard.post.create', compact('categories', 'post', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        if (!auth()->user()->hasPermissionTo('editor.post.create')) {
            return abort(403);
        }

        // Post::create($request->validated());
        $post = new Post($request->validated());
        $post->tags()->sync($request->tags_id);
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

        if (!auth()->user()->hasPermissionTo('editor.post.update')) {
            return abort(403);
        }

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

        // Gate::authorize('update', $post);

        $categories = Category::pluck('id', 'title');
        $tags = Tag::pluck('id', 'name');
        return view('dashboard.post.edit', compact('categories', 'post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutRequest $request, Post $post)
    {

        if (!auth()->user()->hasPermissionTo('editor.post.update')) {
            return abort(403);
        }

        // if (!Gate::allows('update', $post)) {
        //     return abort(403);
        // }

        $data = $request->validated();

        // image
        if (isset($data['image'])) {
            $data['image'] = $filename = time() . '.' . $data['image']->extension();
            $request->image->move(public_path('uploads/posts'), $filename);
        }
        // image

        Cache::forget('post_show_' . $post->id);
        $post->update($data);
        $post->tags()->sync($request->tags_id);
        return to_route('post.index')->with('status', 'Post updated');
    }

    public function destroy(Post $post)
    {
        if (!auth()->user()->hasPermissionTo('editor.post.destroy')) {
            return abort(403);
        }

        // if (!Gate::allows('delete', $post)) {
        //     return abort(403);
        // }
        $post->delete();
        return to_route('post.index')->with('status', 'Post delete');
    }

    // upload

    public function uploadCKEditor(Request $request)
    {

        if (!auth()->user()->hasPermissionTo('editor.post.update')) {
            return response()->json(['error' => 'No tienes permiso, PAGAME'], 500);
        }

        $validator = validator()->make($request->all(), [
            'upload' => 'required|mimes:jpeg,jpg,png|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 500);
        }

        // image
        // $filename = $request->upload->getClientOriginalName();
        $filename = time() . '.' . $request->upload->extension();
        $request->upload->move(public_path('uploads/posts'), $filename);

        return response()->json(['url' => '/uploads/posts/' . $filename]);
        // return response()->json(['url' => 'uploads/posts' . $filename]);
        // image
    }
}
