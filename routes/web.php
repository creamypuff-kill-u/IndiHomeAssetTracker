<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AreaController; // Pastikan ini ada

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Dashboard route (akan menampilkan peta)
Route::get('/', function () {
    return Inertia::render('Dashboard'); // Render Dashboard.vue
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk halaman input data Area
Route::get('/area', [AreaController::class, 'index']) // Akan merender AreaFormPage.vue
    ->middleware(['auth', 'verified'])
    ->name('area.index');

    // ✅ Tambahkan rute baru untuk mengelola statistik
Route::get('/stats/manage', function () {
    return Inertia::render('Stats/GlobalStatsForm');
})->name('stats.manage'); // Beri nama rute ini

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';