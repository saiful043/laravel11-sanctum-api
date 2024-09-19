<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class);
// Route::apiResource('roles', RolesController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot_password', [AuthController::class, 'forgot_password']);
Route::get('/reset/{token}', [AuthController::class, 'getReset']);
Route::post('/reset_password/{token}', [AuthController::class, 'reset_password']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/roles', [RolesController::class, 'store']);
// Ensure that the Sanctum authentication middleware is applied
Route::middleware('auth:sanctum')->group(function () {
    // Route to get a list of roles
    Route::get('/roles', [RolesController::class, 'index']);

    // Route to create a new role
    

    // Route to get a specific role by ID
    Route::get('/roles/{id}', [RolesController::class, 'show']);

    // Route to update a specific role by ID
    Route::put('/roles/{id}', [RolesController::class, 'update']);

    // Route to delete a specific role by ID
    Route::delete('/roles/{id}', [RolesController::class, 'destroy']);
});


// Route::get('/', function() {
//     return 'API';
// });
