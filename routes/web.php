<?php
use App\Http\Controllers\Admin\ArchitectureAdminController;
use App\Http\Controllers\ArchitectureController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;



Route::get('/locale/{locale}', function (Request $request, string $locale) {
    abort_unless(in_array($locale, ['ar', 'en']), 404);

    Session::put('locale', $locale);

    return redirect()->back()
        ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
})->name('locale.switch');


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
