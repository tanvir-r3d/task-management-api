<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Common\TaskStatusFetchController;
use App\Http\Controllers\Common\UserFetchController;
use App\Http\Controllers\TaskStatusController;
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

Route::post('/v1/auth/login', [AuthController::class, 'login']);

Route::group(['prefix' => 'v1', 'middleware' => ['api', 'jwt.verify']], function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::prefix('task-statuses')->group(function () {
        Route::get('/', [TaskStatusController::class, 'index']);
        Route::post('/', [TaskStatusController::class, 'store']);
        Route::get('/{id}', [TaskStatusController::class, 'show']);
        Route::put('/{id}', [TaskStatusController::class, 'update']);
        Route::delete('/{id}', [TaskStatusController::class, 'destroy']);
    });


    Route::prefix('commons')->group(function () {
        Route::get('users', UserFetchController::class);
        Route::get('task-statuses', TaskStatusFetchController::class);
    });
});
