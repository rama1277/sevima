<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', function () {
    //return view('home');
    return redirect('/home');
})->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
		->middleware('auth')
		->name('home');

Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])
		->middleware('auth')
        ->name('user');

Route::post('/user/save', [App\Http\Controllers\UserController::class, 'store'])
        ->middleware('auth')
        ->name('user.save');
                
Route::get('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])
        ->middleware('auth')
        ->name('user.edit');

Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'destroy'])
        ->middleware('auth')
        ->name('user.delete');

Route::get('/user/permission/{id}', [App\Http\Controllers\UserController::class, 'permission'])
        ->middleware('auth')
        ->name('user.permission');

Route::post('/user/permission', [App\Http\Controllers\UserController::class, 'storePermission'])
        ->middleware('auth')
        ->name('user.permission.save');

Route::post('/post/save', [App\Http\Controllers\PostController::class, 'store'])
        ->middleware('auth')
        ->name('post.save');

Route::get('/post/like/{id}', [App\Http\Controllers\PostController::class, 'like'])
        ->middleware('auth')
        ->name('post.like');

Route::post('/post/comment', [App\Http\Controllers\PostController::class, 'storeComment'])
        ->middleware('auth')
        ->name('post.comment.save');