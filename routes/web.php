<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\commentcontroller;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Homepage → blog listing
Route::get('/', [BlogController::class, 'index'])->name('home');

// Login / Register pages
Route::get('/login', fn() => view('login'))->name('login');


// Auth actions

Route::post('/login', [UserController::class, 'login']);

// Public blog view
Route::get('/blog/{blog}', [BlogController::class, 'show'])
    ->name('blog.show');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Admin dashboard (admin_page.blade.php)
    Route::get('/admin', [BlogController::class, 'admin'])
        ->name('admin.dashboard');

    //profile update(admin area)
    Route::patch('/admin/profile/{user}', [UserController::class, 'update'])
        ->name('admin.profile.update');
        
        // Create blog form
    Route::get('/admin/blog/create', [BlogController::class, 'create'])
        ->name('blog.create');

    // Store blog
    Route::post('/admin/blog', [BlogController::class, 'store'])
        ->name('blog.store');

    // Update blog (publish / edit)
    Route::patch('/admin/blog/{blog}', [BlogController::class, 'update'])
        ->name('blog.update');

    // Unpublish blog
    Route::delete('/admin/blog/{blog}', [BlogController::class, 'destroy'])
        ->name('blog.destroy');
    
    Route::get('/admin/blog/{blog}/edit', [BlogController::class, 'edit'])
    ->name('blog.edit');

});


//blog comments
Route::post('/blog/{blog}/comments', [CommentController::class, 'store'])
    ->name('comments.store');


Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('user.show');