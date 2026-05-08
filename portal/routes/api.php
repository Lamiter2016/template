<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommicController;
use App\Http\Resources\UsersResource;
use App\Models\Users;
use App\Http\Resources\UsersCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//public route
Route::get('/test', function () {
    //return UsersResource::collection(Users::all()->keyBy->id);
    return 0;
});
Route:: post('users/login', [UsersController::class, 'processLoginJson'])->name('api.post.login');
Route::post('users/register', [UsersController::class, 'proccessRegister'])->name('api.post.register');
Route::get('users/logout', [UsersController::class, 'processLogout'])->name('api.post.logout');
//Route::resource('users', UsersController::class);
// COMMIC
Route::post('book/save', [CommicController::class, 'saveBook'])->name('api.post.savebook');
route::get('users/getUserAuth', function () {
    return Auth::guard('users')->user();
});
//protected route
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/check', [UsersController::class, 'check']);
    Route::get('/logout', [UsersController::class, 'logout']);
});

