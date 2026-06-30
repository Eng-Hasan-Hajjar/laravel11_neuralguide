<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ArchitectureAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\ArchitectureController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\Training\DatasetController;
use App\Http\Controllers\Training\TrainingExperimentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// ─── اللغة ────────────────────────────────────────────────────────────────────
Route::get('/locale/{locale}', function (Request $request, string $locale) {
    abort_unless(in_array($locale, ['ar', 'en']), 404);
    Session::put('locale', $locale);
    return redirect()->back()
        ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
})->name('locale.switch');

// ─── الصفحات العامة ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/architectures', [ArchitectureController::class, 'index'])->name('architectures.index');
Route::get('/architectures/{architecture:slug}', [ArchitectureController::class, 'show'])->name('architectures.show');
Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');
Route::get('/suggestions/{suggestion}', [SuggestionController::class, 'show'])->name('suggestions.show');

// ─── المصادقة ─────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'loginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.store');
Route::post('/logout',  [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─── لوحة المستخدم ────────────────────────────────────────────────────────────
Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

// ─── وحدة التدريب (مستخدم مسجل) ──────────────────────────────────────────────
Route::middleware('auth')->prefix('training')->name('training.')->group(function () {
    // تجارب التدريب
    Route::get('/',                    [TrainingExperimentController::class, 'index'])->name('index');
    Route::get('/create',              [TrainingExperimentController::class, 'create'])->name('create');
    Route::post('/',                   [TrainingExperimentController::class, 'store'])->name('store');
    Route::get('/{experiment}',        [TrainingExperimentController::class, 'show'])->name('show');
    Route::get('/{experiment}/edit',   [TrainingExperimentController::class, 'edit'])->name('edit');
    Route::put('/{experiment}',        [TrainingExperimentController::class, 'update'])->name('update');
    Route::delete('/{experiment}',     [TrainingExperimentController::class, 'destroy'])->name('destroy');
    Route::get('/{experiment}/download',[TrainingExperimentController::class, 'downloadCode'])->name('download');
    Route::post('/{experiment}/code',  [TrainingExperimentController::class, 'updateCode'])->name('update-code');
    Route::post('/{experiment}/run',   [TrainingExperimentController::class, 'run'])->name('run');

    // مجموعات البيانات
    Route::prefix('datasets')->name('datasets.')->group(function () {
        Route::get('/',          [DatasetController::class, 'index'])->name('index');
        Route::get('/create',    [DatasetController::class, 'create'])->name('create');
        Route::post('/',         [DatasetController::class, 'store'])->name('store');
        Route::get('/{dataset}', [DatasetController::class, 'show'])->name('show');
        Route::delete('/{dataset}',[DatasetController::class, 'destroy'])->name('destroy');
    });
});

// ─── لوحة الإدارة ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // المعماريات
    Route::resource('architectures', ArchitectureAdminController::class)->except(['show']);

    // الفئات
    Route::resource('categories', CategoryAdminController::class)->except(['show']);

    // المستخدمون
    Route::resource('users', UserAdminController::class)->only(['index','show','edit','update','destroy']);
});
