<?php

namespace App\View\Components\Dashboard\role\permission;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Manage extends Component
{
    // public $role;
    public function __construct(public Role $role)
    {
        // $this->role = $role;
    }


    public function render(): View|Closure|string
    {
        Gate::authorize('is-admin');
        return view('components.dashboard.role.permission.manage', ['permissionsRole' => $this->role->permissions, 'permissions' => Permission::get()]);
    }

    public function handle(Role $role)
    {
        Gate::authorize('is-admin');
        $permission = Permission::findOrFail(request('permission'));
        $role->givePermissionTo($permission);

        if (request()->ajax()) {
            //axios, jquery ajax fetch...
            return response()->json($permission);
        } else {
            //form
            return redirect()->back();
        }
    }

    function delete(Role $role)
    {
        Gate::authorize('is-admin');
        $permission = Permission::findOrFail(request('permission'));  
        $role->revokePermissionTo($permission);

        if (request()->ajax()) {
            //axios, jquery ajax fetch...
            return response()->json($permission);
        } else {
            //form
            return redirect()->back();
        }
    }
}
