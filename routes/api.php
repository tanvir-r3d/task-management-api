<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Common\NumberOfTotalTaskController;
use App\Http\Controllers\Common\TaskStatusFetchController;
use App\Http\Controllers\Common\UserFetchController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\UserController;
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
Route::post('/v1/users', [UserController::class, 'store']);

Route::group(['prefix' => 'v1', 'middleware' => ['api', 'jwt.verify']], function () {

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('send-verification-mail', [AuthController::class, 'sendVerificationMail']);
        Route::post('check-verification-code', [AuthController::class, 'checkVerificationCode']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::prefix('task-statuses')->group(function () {
        Route::get('/', [TaskStatusController::class, 'index']);
        Route::post('/', [TaskStatusController::class, 'store']);
        Route::get('/{id}', [TaskStatusController::class, 'show']);
        Route::put('/{id}', [TaskStatusController::class, 'update']);
        Route::delete('/{id}', [TaskStatusController::class, 'destroy']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::get('/{id}', [TaskController::class, 'show']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'destroy']);

        Route::prefix('/{id}/comments')->group(function () {
            Route::get('/', [TaskCommentController::class, 'index']);
            Route::post('/', [TaskCommentController::class, 'store']);
        });
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('commons')->group(function () {
        Route::get('users', UserFetchController::class);
        Route::get('task-statuses', TaskStatusFetchController::class);
        Route::get('total-task', NumberOfTotalTaskController::class);
    });
});
