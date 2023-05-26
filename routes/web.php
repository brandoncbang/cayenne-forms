<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/f/{form:uuid}', [EntryController::class, 'store'])->name('forms.entries.store');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form:uuid}/edit', [FormController::class, 'edit'])->name('forms.edit');
    Route::patch('/forms/{form:uuid}/update', [FormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{form:uuid}', [FormController::class, 'destroy'])->name('forms.destroy');

    Route::get('/forms/{form:uuid}/entries', [EntryController::class, 'index'])->name('forms.entries.index');
//    Route::patch('/entries/{entry:uuid}', [EntryController::class, 'update'])->name('entries.update');
//    Route::delete('/entries/{entry:uuid}', [EntryController::class, 'destroy'])->name('entries.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/invites/{invite:code}', [InviteController::class, 'show'])->name('invites.show');

require __DIR__.'/auth.php';
