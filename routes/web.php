<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('welcome');
});

Route::get('/gallery', [App\Http\Controllers\GalleryController::class, 'index'])->name('gallery');

// Маршруты для проектов
Route::get('/projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{id}', [App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');

// Отзыв пользователей
Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');

// Маршруты модерации отзывов (доступ только для admin, например через middleware 'admin')
Route::middleware('admin')->group(function(){
    Route::get('/admin/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/admin/feedback/{id}/approve', [App\Http\Controllers\FeedbackController::class, 'approve'])->name('feedback.approve');
    Route::post('/admin/feedback/{id}/disapprove', [App\Http\Controllers\FeedbackController::class, 'disapprove'])->name('feedback.disapprove');
    Route::delete('/admin/feedback/{id}', [App\Http\Controllers\FeedbackController::class, 'destroy'])->name('feedback.destroy');
});

// Маршруты для админки, защищенные middleware admin
Route::middleware('admin')->prefix('admin')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/users', function() { 
        return view('admin.users'); 
    })->name('admin.users');
    
    Route::get('/projects', [AdminController::class, 'projects'])->name('admin.projects');
    
    Route::get('/projects/create', [AdminController::class, 'createProject'])->name('admin.projects.create');
    Route::post('/projects', [AdminController::class, 'storeProject'])->name('admin.projects.store');
    Route::put('/projects/{id}', [AdminController::class, 'updateProject'])->name('admin.projects.update');
    Route::delete('/projects/{id}', [AdminController::class, 'destroyProject'])->name('admin.projects.destroy');
    
    Route::get('/messages', function() { 
        return view('admin.messages'); 
    })->name('admin.messages');
    
    Route::get('/projects/gallery/{id}', [App\Http\Controllers\ProjectController::class, 'gallery'])->name('admin.projects.gallery');
});

Route::post('/submitTelegram', [TelegramController::class, 'store'])->name('telegram.store');

Route::get('/thanks', [TelegramController::class, 'thanks'])->name('thanks');

Auth::routes();

Route::get('/welcome', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');
