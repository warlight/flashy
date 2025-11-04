<?php

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('r/{slug}', [LinkController::class, 'redirect'])->name('link.redirect');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);


Route::middleware('auth')->group(function () {
    Route::get('admin/links', [AdminPanelController::class, 'dashboard'])->name('dashboard');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
