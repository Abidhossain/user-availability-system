<?php

use App\Http\Controllers\Availability\AvailabilityController;
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


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/availability', [AvailabilityController::class, 'setAvailability']);
    Route::get('/availability/{userId}/{timezone}', [AvailabilityController::class, 'getAvailability']);
});

