<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;

// Trang chủ hiển thị bài viết, tìm kiếm, lọc
Route::get('/', [PublicController::class, 'index'])->name('home');

// Trang chi tiết bài viết truy cập bằng slug
Route::get('/posts/{slug}', [PublicController::class, 'show'])->name('posts.show');

// Đăng ký
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Nhóm yêu cầu đăng nhập chung (Member + Admin)
Route::middleware(['auth'])->group(function () {
    // Quản lý bài viết cá nhân
    Route::get('/my-posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/my-posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/my-posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/my-posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/my-posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/my-posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Thùng rác
    Route::get('/my-posts-trash', [PostController::class, 'trash'])->name('posts.trash');
    Route::post('/my-posts-trash/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::delete('/my-posts-trash/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.force-delete');

    // Gửi bình luận cho bài viết
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Nhóm yêu cầu quyền quản trị Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Quản lý bài viết hệ thống
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
});