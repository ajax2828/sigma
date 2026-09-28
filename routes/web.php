<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Publik: post published saja, 404 kalau draft/archived.
Route::get('/posts/{post}', [PostController::class, 'publicShow'])->name('posts.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // Pendaftaran mandiri. Akun baru sengaja BUKAN admin: middleware 'admin'
    // di bawah yang menjaga panel tetap tertutup untuk akun biasa.
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Panel admin beralamat /admin/dashboard. Tanpa redirect ini, mengetik
// "/admin" menghasilkan 404 dan bikin terlihat seperti panelnya rusak.
Route::get('/admin', fn () => redirect()->route('admin.dashboard'));
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'));

// Halaman ini sebelumnya bernama Hero dan sudah di-bookmark/dibuka banyak orang;
// arahkan ke bentuk barunya supaya tautan lama tidak mati.
Route::get('/admin/settings/hero', fn () => redirect()->route('admin.settings.header'));

// Keluar hanya butuh "sudah login". Kalau ikut di middleware 'admin', akun
// biasa yang tidak boleh masuk panel akan ikut terkunci logout.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Panel: tidak cukup sudah login, harus punya wewenang (middleware 'admin').
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts', [PostController::class, 'store'])->name('admin.posts.store');
    Route::get('/admin/posts/{post}', [PostController::class, 'show'])->name('admin.posts.show');
    Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/admin/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
    Route::get('/admin/settings/header', [SettingController::class, 'header'])->name('admin.settings.header');
    Route::post('/admin/settings/header', [SettingController::class, 'storeHeaderSlide'])->name('admin.settings.header.store');
    Route::put('/admin/settings/header/{headerSlide}', [SettingController::class, 'updateHeaderSlide'])->name('admin.settings.header.update');
    Route::delete('/admin/settings/header/{headerSlide}', [SettingController::class, 'destroyHeaderSlide'])->name('admin.settings.header.destroy');
    Route::post('/admin/settings/header/fallback', [SettingController::class, 'updateHeaderFallback'])->name('admin.settings.header.fallback');
    Route::get('/admin/settings/backgrounds', [SettingController::class, 'backgrounds'])->name('admin.settings.backgrounds');
    Route::post('/admin/settings/backgrounds', [SettingController::class, 'updateBackgrounds'])->name('admin.settings.backgrounds.update');
    Route::get('/admin/settings/landing', [SettingController::class, 'index'])->name('admin.settings.landing');
    Route::post('/admin/settings/landing', [SettingController::class, 'update'])->name('admin.settings.landing.update');
    Route::get('/admin/settings/members', [SettingController::class, 'members'])->name('admin.settings.members');
    Route::post('/admin/settings/members', [SettingController::class, 'storeMember'])->name('admin.settings.members.store');
    Route::put('/admin/settings/members/{member}', [SettingController::class, 'updateMember'])->name('admin.settings.members.update');
    Route::delete('/admin/settings/members/{member}', [SettingController::class, 'destroyMember'])->name('admin.settings.members.destroy');
    Route::post('/admin/settings/members/histories/{history}/restore', [SettingController::class, 'restoreMember'])->name('admin.settings.members.restore');
    Route::post('/admin/settings/members/section', [SettingController::class, 'updateMemberSection'])->name('admin.settings.members.section');
    Route::get('/admin/settings/members-print', [SettingController::class, 'membersPrint'])->name('admin.settings.members.print');
    Route::get('/admin/settings/achievements', [SettingController::class, 'achievements'])->name('admin.settings.achievements');
    Route::post('/admin/settings/achievements', [SettingController::class, 'storeAchievement'])->name('admin.settings.achievements.store');
    Route::put('/admin/settings/achievements/{achievement}', [SettingController::class, 'updateAchievement'])->name('admin.settings.achievements.update');
    Route::delete('/admin/settings/achievements/{achievement}', [SettingController::class, 'destroyAchievement'])->name('admin.settings.achievements.destroy');
    Route::post('/admin/settings/achievements/section', [SettingController::class, 'updateAchievementSection'])->name('admin.settings.achievements.section');
});
