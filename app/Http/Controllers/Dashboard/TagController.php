<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\PutRequest;
use App\Http\Requests\Tag\StoreRequest;

class TagController extends Controller
{

    public function index()
    {

        if (!auth()->user()->hasPermissionTo('editor.tag.index')) {
            return abort(403);
        }

        $tags = Tag::paginate(2);
        return view('dashboard/tag/index', compact('tags'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('editor.tag.create')) {
            return abort(403);
        }
        $tag = new Tag();
        return view('dashboard.tag.create', compact('tag'));
    }

    public function store(StoreRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('editor.tag.create')) {
            return abort(403);
        }
        Tag::create($request->validated());
        return to_route('tag.index')->with('status', 'Tag created');
    }

    public function show(Tag $tag)
    {
        if (!auth()->user()->hasPermissionTo('editor.tag.index')) {
            return abort(403);
        }
        return view('dashboard/tag/show', ['tag' => $tag]);
    }

    public function edit(Tag $tag)
    {
        if (!auth()->user()->hasPermissionTo('editor.tag.update')) {
            return abort(403);
        }
        return view('dashboard.tag.edit', compact('tag'));
    }

    public function update(PutRequest $request, Tag $tag)
    {
        if (!auth()->user()->hasPermissionTo('editor.tag.update')) {
            return abort(403);
        }
        $tag->update($request->validated());
        return to_route('tag.index')->with('status', 'Tag updated');
    }

    public function destroy(Tag $tag)
    {
        if (!auth()->user()->hasPermissionTo('editor.tag.destroy')) {
            return abort(403);
        }
        $tag->delete();
        return to_route('tag.index')->with('status', 'Tag delete');
    }
}
