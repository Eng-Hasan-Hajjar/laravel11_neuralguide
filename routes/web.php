<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ArchitectureAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\ArchitectureController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResearchNoteController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\Training\DatasetController;
use App\Http\Controllers\Training\TrainingExperimentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// ─── Language Switch ──────────────────────────────────────────
Route::get('/locale/{locale}', function (Request $request, string $locale) {
    abort_unless(in_array($locale, ['ar', 'en']), 404);
    Session::put('locale', $locale);
    return redirect()->back()
        ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
})->name('locale.switch');

// ─── Public Routes ────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/architectures', [ArchitectureController::class, 'index'])->name('architectures.index');
Route::get('/architectures/{architecture:slug}', [ArchitectureController::class, 'show'])->name('architectures.show');

Route::get('/suggestions/{suggestion}', [SuggestionController::class, 'show'])->name('suggestions.show');

// ─── Auth ─────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'loginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.store');
Route::post('/logout',  [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Authenticated Routes ─────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Suggestions (needs auth to save)
    Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

    // Favorites
    Route::post('/favorites/{architecture}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Research Notes
    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/',           [ResearchNoteController::class, 'index'])->name('index');
        Route::get('/create',     [ResearchNoteController::class, 'create'])->name('create');
        Route::post('/',          [ResearchNoteController::class, 'store'])->name('store');
        Route::get('/{note}',     [ResearchNoteController::class, 'show'])->name('show');
        Route::get('/{note}/edit',[ResearchNoteController::class, 'edit'])->name('edit');
        Route::put('/{note}',     [ResearchNoteController::class, 'update'])->name('update');
        Route::delete('/{note}',  [ResearchNoteController::class, 'destroy'])->name('destroy');
    });

    // ── Training Module ────────────────────────────────────────
    Route::prefix('training')->name('training.')->group(function () {

        // ⚠️ IMPORTANT: static/nested routes MUST come BEFORE the
        // wildcard '/{experiment}' routes below, otherwise Laravel
        // matches "/training/datasets" as {experiment} = "datasets"
        // and returns 404 (route model binding failure).

        // Datasets — defined FIRST
        Route::prefix('datasets')->name('datasets.')->group(function () {
            Route::get('/',            [DatasetController::class, 'index'])->name('index');
            Route::get('/create',      [DatasetController::class, 'create'])->name('create');
            Route::post('/',           [DatasetController::class, 'store'])->name('store');
            Route::get('/{dataset}',   [DatasetController::class, 'show'])->name('show');
            Route::delete('/{dataset}',[DatasetController::class, 'destroy'])->name('destroy');
        });

        // Experiments — static routes first
        Route::get('/',       [TrainingExperimentController::class, 'index'])->name('index');
        Route::get('/create', [TrainingExperimentController::class, 'create'])->name('create');
        Route::post('/',      [TrainingExperimentController::class, 'store'])->name('store');

        // Experiments — wildcard routes LAST
        Route::get('/{experiment}',          [TrainingExperimentController::class, 'show'])->name('show');
        Route::get('/{experiment}/edit',     [TrainingExperimentController::class, 'edit'])->name('edit');
        Route::put('/{experiment}',          [TrainingExperimentController::class, 'update'])->name('update');
        Route::delete('/{experiment}',       [TrainingExperimentController::class, 'destroy'])->name('destroy');
        Route::get('/{experiment}/download', [TrainingExperimentController::class, 'downloadCode'])->name('download');
        Route::post('/{experiment}/code',    [TrainingExperimentController::class, 'updateCode'])->name('update-code');
        Route::post('/{experiment}/run',     [TrainingExperimentController::class, 'run'])->name('run');
    });
});

// ─── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Architectures CRUD
    Route::resource('architectures', ArchitectureAdminController::class)->except(['show']);

    // Categories CRUD
    Route::resource('categories', CategoryAdminController::class)->except(['show']);

    // Users management
    Route::resource('users', UserAdminController::class)->only(['index','show','edit','update','destroy']);

    // Comments moderation
    Route::post('/comments/{comment}/approve', function (\App\Models\Comment $comment) {
        $comment->update(['is_approved' => !$comment->is_approved]);
        return back()->with('status', 'تم تحديث حالة التعليق');
    })->name('comments.approve');
});
