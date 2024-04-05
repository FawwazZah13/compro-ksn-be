<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PagesController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\ContentsController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\SectionsController;

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
// PAGES
Route::get('/pages/search', [PagesController::class, 'search']);
Route::get('/pages', [PagesController::class, 'index']);
Route::post('/pages', [PagesController::class, 'create']);
Route::post('/pages/{id}', [PagesController::class, 'update']);
Route::delete('/pages/{id}', [PagesController::class, 'destroy']);

// SECTIONS
Route::get('/sections/search', [SectionsController::class, 'search']);
Route::get('/sections', [SectionsController::class, 'index']);
Route::post('/sections', [SectionsController::class, 'create']);
Route::post('/sections/{id}', [SectionsController::class, 'update']);
Route::delete('/sections/{id}', [SectionsController::class, 'destroy']);

// CONTENTS
Route::get('/contents/search', [ContentsController::class, 'search']);
Route::get('/contents', [ContentsController::class, 'index']);
Route::post('/contents', [ContentsController::class, 'create']);
Route::post('/contents/{id}', [ContentsController::class, 'update']);
Route::delete('/contents/{id}', [ContentsController::class, 'destroy']);

// Admin
Route::post('/admin', [UsersController::class, 'create']);
Route::put('/admin/{id}', [UsersController::class, 'update']);
Route::delete('/admin/{id}', [UsersController::class, 'destroy']);

Route::post('/login', [UsersController::class, 'login']);
Route::post('/logout', [UsersController::class, 'logout']);

Route::post('/languages', [LanguageController::class, 'getContent']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin', [UsersController::class, 'index']);
    // Rute yang memerlukan autentikasi
});

// Route::middleware('auth:sanctum')->get('/admin', [UsersController::class, 'index']);


// Route::middleware('auth:sanctum')->get('/admin', function (Request $request) {
//     return $request->user();
// });
