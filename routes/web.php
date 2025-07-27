<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeJoinedCourseController;
use App\Http\Controllers\CreatorDashboardController;
use App\Http\Controllers\UserCourseController;

// Halaman utama (Home)
Route::get('/', [CourseController::class, 'index'])->name('home');

// Guest routes (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Admin login khusus guest juga
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});

// Authenticated routes (sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin only routes - menggunakan Spatie middleware
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/courses/{course}/approve', [AdminController::class, 'approve'])->name('admin.courses.approve');
        Route::post('/courses/{course}/reject', [AdminController::class, 'reject'])->name('admin.courses.reject');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    });

    // Admin & Creator routes
    Route::middleware('role:admin|creator')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::post('/courses/store-with-paths', [CourseController::class, 'storeWithPaths'])->name('courses.store-with-paths');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    });

    // Dashboard Creator 
    Route::middleware('role:creator')->prefix('creator')->group(function () {
    Route::get('/dashboard', [CreatorDashboardController::class, 'index'])->name('creator.dashboard');
    Route::get('/courses/{course}/edit', [CreatorDashboardController::class, 'edit'])->name('creator.courses.edit');
    Route::put('/courses/{course}', [CreatorDashboardController::class, 'update'])->name('creator.courses.update');
    Route::delete('/courses/{course}', [CreatorDashboardController::class, 'destroy'])->name('creator.courses.destroy');
    });

    //akses user ke my course
    Route::middleware('role:user')->prefix('user')->group(function () {
    Route::get('/my-courses', [UserCourseController::class, 'index'])->name('user.my-courses');
    Route::post('/my-courses/{course}/unenroll', [UserCourseController::class, 'unenroll'])->name('user.courses.unenroll');
    });

    // User submissions
    Route::get('/my-submissions', [CourseController::class, 'mySubmissions'])->name('courses.submissions');

    // Course detail & actions
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
});
