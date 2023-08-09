<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
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

Route::get('/', [LoginController::class, 'index']);
Route::get('/register', [LoginController::class, 'register']);
Route::get('/forget', [LoginController::class, 'forget']);

Route::get('/home', [HomeController::class, 'index']);

Route::post('/login', [LoginController::class, 'authenticate']);

Route::resource('/admin/profile', UserController::class)->middleware('auth');

Route::resource('/admin/order', OrderController::class)->middleware('auth');
