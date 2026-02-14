<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\commentcontroller;
use App\Http\Controllers\UserController;


use App\Models\User;

use function Symfony\Component\Clock\now;

Route::apiResource('blog', BlogController::class);


// Route::get('users/{user}/blogs', function ($userId) {
//     return \App\Models\Blog::where('user_id', $userId)->get();
// });


Route::get('users/{user}/blogs', function (User $user) {
    return $user->blogs()
        ->whereNotNull('published_at')
        ->where('published_at','<=', now())
        ->get();
});


Route::get('blog/{blog}/comments', [CommentController::class, 'index']);
Route::post('blog/{blog}/comments', [CommentController::class, 'store']);
Route::put('comments/{comment}', [CommentController::class, 'update']);
Route::delete('comments/{comment}', [CommentController::class, 'destroy']);


Route::post('users', [UserController::class, 'store']);
