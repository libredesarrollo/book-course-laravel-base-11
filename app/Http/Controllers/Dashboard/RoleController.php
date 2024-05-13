<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\PutRequest;
use App\Http\Requests\Role\StoreRequest;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        Gate::authorize('is-admin');
        $roles = Role::paginate(10);
        return view('dashboard/role/index', compact('roles'));
    }

    public function create()
    {
        Gate::authorize('is-admin');
        $role = new Role();
        return view('dashboard.role.create', compact('role'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('is-admin');
        Role::create($request->validated());
        return to_route('role.index')->with('status', 'Role created');
    }

    public function show(Role $role)
    {
        Gate::authorize('is-admin');
        return view('dashboard/role/show',['role'=> $role]);
    }

    public function edit(Role $role)
    {
        Gate::authorize('is-admin');
        return view('dashboard.role.edit', compact('role'));
    }

    public function update(PutRequest $request, Role $role)
    {
        Gate::authorize('is-admin');
        $role->update($request->validated());
        return to_route('role.index')->with('status', 'Role updated');
    }

    public function destroy(Role $role)
    {
        Gate::authorize('is-admin');
        $role->delete();
        return to_route('role.index')->with('status', 'Role delete');
    }
}
