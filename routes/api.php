<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller class
use App\Http\Controllers\AnimalController;

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

Route::middleware('api')->group(function () {
    Route::prefix('v1')->controller(AnimalController::class)->group(function () {
        Route::get('breeds/page/{offset}/limit/{limit}', 'getBreeds');
        // Route param pattern you may see on App\Providers\RouteServiceProvider
        Route::get('breeds/{base}/page/{offset}/limit/{limit}', 'getBreed');
        Route::get('images/{base}', 'getImage');
    });
});
