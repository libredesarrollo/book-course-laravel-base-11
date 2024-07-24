<?php

use App\Http\Controllers\blog\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\LanguagePrefixMiddleware;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\UserAccessDashboardMiddleware;
use App\Jobs\ProcessImage;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\TestJob;
use App\Models\User;
use App\View\Components\AppLayout;
use Illuminate\Support\Facades\App as AppLavarel;

// AppLavarel::setLocale('es');
// echo AppLavarel::currentLocale();
// echo AppLavarel::isLocale('es');

// Route::get('set_locale/{locale}', function(string $locale){
//     if( !in_array($locale, ['en', 'es'])){
//         abort(400);
//     }

//     AppLavarel::setLocale($locale);
// });




// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// DB::listen(function($query){
//     echo $query->sql;
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', UserAccessDashboardMiddleware::class]], function () {
    Route::resources([
        'post' => App\Http\Controllers\Dashboard\PostController::class,
        'category' => App\Http\Controllers\Dashboard\CategoryController::class,
        'tag' => App\Http\Controllers\Dashboard\TagController::class,
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

function routeBlog()
{
    Route::get('', [BlogController::class, 'index'])->name('blog.index');
    Route::get('detail/{id}', [BlogController::class, 'show'])->name('blog.show');
}

Route::group(['prefix' => '{locale}/blog', 'middleware' => LanguagePrefixMiddleware::class], function () {
    // Route::group(['prefix' => 'blog','middleware' => LanguagePrefixMiddleware::class], function () {
    routeBlog();
    // Route::get('detail/{post}', [BlogController::class, 'show'])->name('blog.show');
});
Route::group(['prefix' => 'blog', 'middleware' => LanguagePrefixMiddleware::class], function () {
    // Route::group(['prefix' => 'blog','middleware' => LanguagePrefixMiddleware::class], function () {
    routeBlog();
    // Route::get('detail/{post}', [BlogController::class, 'show'])->name('blog.show');
});

Route::get('test-job', function () {
    TestJob::dispatch();
    return 'Super vista';
});
Route::get('test-welcome-user', function () {

    SendWelcomeEmail::dispatch(User::firs());
    return 'User welcome';
});

Route::get('/image', function () {
    ProcessImage::dispatch('uploads\posts\test.png');
    return 'image view';
});

require __DIR__ . '/auth.php';
























use App\Exports\PostsExport;

use App\Imports\PostsImport;

use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;







Route::get('/qr', function () {
    return QrCode::format('png')->generate('DesarrolloLibre');
});

Route::get('excel/export/post', function () {
    return Excel::download(new PostsExport, 'posts_lara.xlsx');
});
Route::get('excel/import/post', function () {
    Excel::import(new PostsImport, 'posts_lara.xlsx');
    return 'Import';
});
