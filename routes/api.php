<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\NewsArticleController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Startup routes
Route::apiResource('startups', StartupController::class);

// Investor routes
Route::apiResource('investors', InvestorController::class);

// News Article routes
Route::apiResource('news-articles', NewsArticleController::class);
Route::post('news-articles/fetch', [NewsArticleController::class, 'fetchNews']); 