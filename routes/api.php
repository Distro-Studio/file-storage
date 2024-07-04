<?php

use App\Http\Controllers\BerkasController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/upload', [BerkasController::class, 'upload']);
    Route::post('/get-file', [BerkasController::class, 'getfile']);
});
