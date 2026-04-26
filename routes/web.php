<?php
use App\Http\Controllers\Admin\ArchitectureAdminController;
use App\Http\Controllers\ArchitectureController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/architectures', [ArchitectureController::class, 'index'])->name('architectures.index');
Route::get('/architectures/{architecture:slug}', [ArchitectureController::class, 'show'])->name('architectures.show');
Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');
Route::get('/suggestions/{suggestion}', [SuggestionController::class, 'show'])->name('suggestions.show');

Route::get('/login', [AuthController::class,'loginForm'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.store');
Route::get('/register', [AuthController::class,'registerForm'])->name('register');
Route::post('/register', [AuthController::class,'register'])->name('register.store');
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('architectures', ArchitectureAdminController::class)->except(['show']);
});
