<?php

use App\Http\Controllers\EntryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/f/{form:uuid}', [EntryController::class, 'store'])->name('forms.entries.store');
Route::get('/success', fn () => view('entries.success'))->name('entries.success');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
