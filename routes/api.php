<?php

use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function () {
    Route::post('v1/register', 'register');
    Route::post('v1/login', 'login');
});

Route::get('v1/me', 'App\Http\Controllers\API\RegisterController@me')->middleware('auth:sanctum');
Route::get('v1/users', 'App\Http\Controllers\API\RegisterController@getUsers')->middleware('auth:sanctum');
Route::post('v1/update-user/{userId}', 'App\Http\Controllers\API\RegisterController@updateUser')->middleware('auth:sanctum');
Route::post('v1/register', 'App\Http\Controllers\API\RegisterController@register')->middleware('auth:sanctum');
Route::get('v1/me', 'App\Http\Controllers\API\RegisterController@me')->middleware('auth:sanctum');
Route::post('v1/logout', 'App\Http\Controllers\API\RegisterController@logout')->middleware('auth:sanctum');
Route::delete('v1/user/delete/{id}', 'App\Http\Controllers\API\RegisterController@deleteUser')->middleware('auth:sanctum');
Route::post('v1/upload-image', 'App\Http\Controllers\API\FileController@storeImage');
Route::post('v1/upload-file', 'App\Http\Controllers\API\FileController@storeFile');

Route::group([
    'prefix' => 'v1/sport-categories'
], function () {
    Route::get('/', 'App\Http\Controllers\API\SportCategoryController@getCategories')->middleware('auth:sanctum');
    Route::post('/create', 'App\Http\Controllers\API\SportCategoryController@createCategory')->middleware('auth:sanctum');
    Route::post('/update/{categoryId}', 'App\Http\Controllers\API\SportCategoryController@updateCategory')->middleware('auth:sanctum');
    Route::delete('/delete/{categoryId}', 'App\Http\Controllers\API\SportCategoryController@deleteCategory')->middleware('auth:sanctum');
});
