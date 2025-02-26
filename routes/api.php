<?php

use App\Http\Controllers\ClothingController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\User\Usercontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('auth/register', [UserController::class, 'createUser']);
Route::post('auth/login', [UserController::class, 'login']);
//Route::get('test', [UserController::class, 'Notify']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user-auth', [UserController::class, 'auth']);
    Route::post('items', [ItemController::class, 'addItem']);
    Route::get('items', [ItemController::class, 'fetchItem']);
    Route::get('items/{id}', [ItemController::class, 'DeleteItem']);
});
