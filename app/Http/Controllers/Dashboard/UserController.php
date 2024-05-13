<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PutRequest;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index()
    {
        // $users = User::paginate(10);
        if (!auth()->user()->hasPermissionTo('editor.user.index')) {
            return abort(403);
        }
        $users = User::when(!auth()->user()->hasRole('Admin'), function ($query, $isAdmin) {
            return $query->where('rol', 'regular'); // regular = editor
        })->paginate(10);

        return view('dashboard/user/index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('editor.user.create')) {
            return abort(403);
        }

        $user = new User();
        return view('dashboard.user.create', compact('user'));
    }

    public function store(StoreRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('editor.user.create')) {
            return abort(403);
        }
        $data = $request->validated();
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'rol' => 'admin',
        ]);
        return to_route('user.index')->with('status', 'User created');
    }

    public function show(User $user)
    {
        return view('dashboard/user/show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        Gate::authorize('update-view-user-admin', [$user, 'editor.user.update']);
        return view('dashboard.user.edit', compact('user'));
    }

    public function update(PutRequest $request, User $user)
    {
        Gate::authorize('update-view-user-admin', [$user, 'editor.user.update']);
        $user->update($request->validated());
        return to_route('user.index')->with('status', 'User updated');
    }

    public function destroy(User $user)
    {
        Gate::authorize('update-view-user-admin', [$user, 'editor.user.destroy']);
        $user->delete();
        return to_route('user.index')->with('status', 'User delete');
    }
}
