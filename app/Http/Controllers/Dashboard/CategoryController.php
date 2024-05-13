<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\PutRequest;
use App\Http\Requests\Category\StoreRequest;

class CategoryController extends Controller
{

    public function index()
    {

        if (!auth()->user()->hasPermissionTo('editor.category.index')) {
            return abort(403);
        }

        $categories = Category::paginate(2);
        return view('dashboard/category/index', compact('categories'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('editor.category.create')) {
            return abort(403);
        }
        $category = new Category();
        return view('dashboard.category.create', compact('category'));
    }

    public function store(StoreRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('editor.category.create')) {
            return abort(403);
        }
        Category::create($request->validated());
        return to_route('category.index')->with('status', 'Category created');
    }

    public function show(Category $category)
    {
        if (!auth()->user()->hasPermissionTo('editor.category.index')) {
            return abort(403);
        }
        return view('dashboard/category/show', ['category' => $category]);
    }

    public function edit(Category $category)
    {
        if (!auth()->user()->hasPermissionTo('editor.category.update')) {
            return abort(403);
        }
        return view('dashboard.category.edit', compact('category'));
    }

    public function update(PutRequest $request, Category $category)
    {
        if (!auth()->user()->hasPermissionTo('editor.category.update')) {
            return abort(403);
        }
        $category->update($request->validated());
        return to_route('category.index')->with('status', 'Category updated');
    }

    public function destroy(Category $category)
    {
        if (!auth()->user()->hasPermissionTo('editor.category.destroy')) {
            return abort(403);
        }
        $category->delete();
        return to_route('category.index')->with('status', 'Category delete');
    }
}
