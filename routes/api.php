<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function () {
    Route::post('v1/register', 'register');
    Route::post('v1/login', 'login');
});

Route::get('v1/me', 'App\Http\Controllers\API\RegisterController@me')->middleware('auth:sanctum');
Route::get('v1/users', 'App\Http\Controllers\API\RegisterController@getUsers')->middleware('auth:sanctum');
Route::post('v1/update-user/{userId}', 'App\Http\Controllers\API\RegisterController@updateUser')->middleware('auth:sanctum');
Route::post('v1/register', 'App\Http\Controllers\API\RegisterController@register');
Route::get('v1/me', 'App\Http\Controllers\API\RegisterController@me')->middleware('auth:sanctum');
Route::post('v1/logout', 'App\Http\Controllers\API\RegisterController@logout')->middleware('auth:sanctum');
// Route::delete('v1/user/delete/{id}', 'App\Http\Controllers\API\RegisterController@deleteUser')->middleware('auth:sanctum');
Route::post('v1/upload-image', 'App\Http\Controllers\API\FileController@storeImage');
Route::post('v1/upload-file', 'App\Http\Controllers\API\FileController@storeFile');

Route::group([
    'prefix' => 'v1/sport-categories'
], function () {
    Route::get('/', 'App\Http\Controllers\API\SportCategoryController@getCategories');
    Route::post('/create', 'App\Http\Controllers\API\SportCategoryController@createCategory')->middleware(['auth:sanctum', IsAdmin::class]);
    Route::post('/update/{categoryId}', 'App\Http\Controllers\API\SportCategoryController@updateCategory')->middleware(['auth:sanctum', IsAdmin::class]);
    Route::delete('/delete/{categoryId}', 'App\Http\Controllers\API\SportCategoryController@deleteCategory')->middleware(['auth:sanctum', IsAdmin::class]);
});

Route::group([
    'prefix' => 'v1/location'
], function () {
    Route::get('/provinces', 'App\Http\Controllers\API\LocationController@getProvinces');
    Route::get('/cities/{provinceId}', 'App\Http\Controllers\API\LocationController@getCitiesByProvinceId');
    Route::get('/cities', 'App\Http\Controllers\API\LocationController@getCities');
});

Route::group([
    'prefix' => 'v1/sport-activities'
], function () {
    Route::get('/', 'App\Http\Controllers\API\SportActivityController@getSportActivities');
    Route::get('/{sportActivityId}', 'App\Http\Controllers\API\SportActivityController@getSportActivityById');
    Route::post('/create', 'App\Http\Controllers\API\SportActivityController@createSportActivity')->middleware(['auth:sanctum', IsAdmin::class]);
    Route::post('/update/{sportActivityId}', 'App\Http\Controllers\API\SportActivityController@updateSportActivity')->middleware(['auth:sanctum', IsAdmin::class]);
    Route::delete('/delete/{sportActivityId}', 'App\Http\Controllers\API\SportActivityController@deleteSportActivity')->middleware(['auth:sanctum', IsAdmin::class]);
});

Route::group([
    'prefix' => 'v1/payment-methods'
], function () {
    Route::get('/', 'App\Http\Controllers\API\PaymentMethodController@getPaymentMethods');
});

Route::get('/v1/my-transaction', 'App\Http\Controllers\API\TransactionController@getMyTransaction')->middleware('auth:sanctum');
Route::get('/v1/all-transaction', 'App\Http\Controllers\API\TransactionController@getAllTransaction')->middleware('auth:sanctum');
Route::group([
    'prefix' => 'v1/transaction'
], function () {
    Route::get('/{transactionId}', 'App\Http\Controllers\API\TransactionController@getTransactionById')->middleware('auth:sanctum');
    Route::post('/create', 'App\Http\Controllers\API\TransactionController@createTransaction')->middleware('auth:sanctum');
    Route::post('/update-proof-payment/{transactionId}', 'App\Http\Controllers\API\TransactionController@updateTransactionProofPayment')->middleware('auth:sanctum');
    Route::post('/update-status/{transactionId}', 'App\Http\Controllers\API\TransactionController@updateTransactionStatus')->middleware('auth:sanctum');
    Route::post('/cancel/{transactionId}', 'App\Http\Controllers\API\TransactionController@cancelTransaction')->middleware('auth:sanctum');
});
