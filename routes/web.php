<?php

use App\Http\Controllers\blog\BlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\UserAccessDashboardMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', UserAccessDashboardMiddleware::class]], function () {
    Route::resources([
        'post' => App\Http\Controllers\Dashboard\PostController::class,
        'category' => App\Http\Controllers\Dashboard\CategoryController::class,
        'role' => App\Http\Controllers\Dashboard\RoleController::class,
        'permission' => App\Http\Controllers\Dashboard\PermissionController::class,
        'user' => App\Http\Controllers\Dashboard\UserController::class,
    ]);

    // roles - permissions
    Route::post('role/assign/permission/{role}', [App\View\Components\Dashboard\role\permission\Manage::class, 'handle'])->name('role.assign.permission');
    Route::delete('role/delete/permission/{role}', [App\View\Components\Dashboard\role\permission\Manage::class, 'delete'])->name('role.delete.permission');
    Route::post('role/delete/permission/{role}', [App\View\Components\Dashboard\role\permission\Manage::class, 'delete'])->name('role.delete.permission');
    
    // user - roles - permissions
    Route::post('user/assign/role/{user}', [App\View\Components\Dashboard\user\role\permission\Manage::class, 'handleRole'])->name('user.assign.role');
    Route::delete('user/delete/role/{user}', [App\View\Components\Dashboard\user\role\permission\Manage::class, 'deleteRole'])->name('user.delete.role');
    Route::post('user/delete/role/{user}', [App\View\Components\Dashboard\user\role\permission\Manage::class, 'deleteRole'])->name('user.delete.role');
    //permissions
    Route::post('user/assign/permission/{user}', [App\View\Components\Dashboard\user\role\permission\Manage::class, 'handlePermission'])->name('user.assign.permission');
    Route::delete('user/delete/permission/{user}', [App\View\Components\Dashboard\user\role\permission\Manage::class, 'deletePermission'])->name('user.delete.permission');
    Route::post('user/delete/permission/{user}', [App\View\Components\Dashboard\user\role\permission\Manage::class, 'deletePermission'])->name('user.delete.permission');

    Route::get('', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');
});
Route::group(['prefix' => 'blog'], function () {
    Route::get('', [BlogController::class, 'index'])->name('blog.index');
    // Route::get('detail/{id}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('detail/{post}', [BlogController::class, 'show'])->name('blog.show');
});



require __DIR__ . '/auth.php';
