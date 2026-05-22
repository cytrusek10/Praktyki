<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\AdminPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/post/{slug}', [PostController::class, 'show'])->name('post.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::post('/post/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::prefix('admin/posts')->middleware('auth')->group(function () {
    Route::get('/', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::get('/create', [AdminPostController::class, 'create'])->name('admin.posts.create');
    Route::post('/', [AdminPostController::class, 'store'])->name('admin.posts.store');
    Route::get('/{post}/edit', [AdminPostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
    Route::post('/preview', [AdminPostController::class, 'preview'])->name('admin.posts.preview');
});


use App\Http\Controllers\Admin\AiPostController;

Route::middleware('auth')->group(function () {
    Route::post('/admin/ai/generate', [AiPostController::class, 'generate'])->name('admin.ai.generate');
});
