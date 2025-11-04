<?php

use App\Http\Controllers\LinkController;
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

Route::get('links/{slug}/stats', [LinkController::class, 'stats'])->middleware(['check_signed_link', 'debug'])->name('link.stats');
Route::post('links', [LinkController::class, 'store'])->middleware(['throttle:30', 'service_key_checker']);
Route::patch('links/{slug}/disable', [LinkController::class, 'disable']);
