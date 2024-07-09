<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaundrySepatuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderLaundryController;
use App\Http\Controllers\OrderUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\UserController;
use App\Models\LaundrySepatu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\WebhookController;

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


Route::get('/', function () {
    return view('nonlogin/landingpage');
});
Route::get('/login', [LoginController::class, 'index']);
Route::post('/github/webhook', [WebhookController::class, 'handleWebhook']);
Route::get('/forget', [LoginController::class, 'forget']);
// Route::get('/home', [HomeController::class, 'index']);
// // Route::get('/home/laundry', [MenuController::class, 'homeLaundry'])->middleware('auth');
// Route::get('laundry/{id}', [MenuController::class, 'homeLaundry'])->middleware('auth');
Route::resource('/register', RegisterController::class);
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/loginLocation', [LoginController::class, 'showLatLong']);
Route::get('/show-distance', 'MapsController@showDistance');
Route::get('/calculate-distance', [MapsController::class, 'calculateDistance']);
// Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/get-user-location', [MapsController::class, 'getUserLocation']);
Route::post('/get-user-location', 'MapsController@calculateDistance');
Route::post('/update-address', [MapsController::class, 'updateAddress'])->name('update-address');

// Route::resource('/profile', UserController::class)->middleware('auth');
// Route::resource('/order', OrderController::class)->middleware('auth');
// Route::resource('/laundrySepatu', LaundrySepatuController::class)->middleware('auth');
// Route::resource('/laundryorder', OrderLaundryController::class)->middleware('auth');
// Route::resource('/laundryservice', ServiceController::class)->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/home', [HomeController::class, 'index']);
    // Route::get('/editprofile', [UpdateController::class, 'index']);
    // Route::post('/profileEdit', [UpdateController::class, 'update']);
    Route::resource('/profile', UserController::class)->middleware('auth');

    Route::group(['middleware'  => 'buyer'], function () {
        // Route::get('/home/laundry', [MenuController::class, 'homeLaundry'])->middleware('auth');
        Route::get('laundry/{id}', [MenuController::class, 'homeLaundry'])->middleware('auth');
        Route::get('serviceDetail/{id}', [MenuController::class, 'serviceLaundryBuyer'])->middleware('auth');
        Route::resource('orderlist', OrderUserController::class)->middleware('auth');
        Route::resource('orderList', OrderUserController::class)->middleware('auth');
        Route::get('cart', [CartController::class, 'showCart'])->middleware('auth');
        Route::post('/addToCart', [CartController::class, 'addToCart']);
        Route::post('/updateCartDecreaseQuantity', [CartController::class, 'decreaseCart']);
        Route::post('/updateCartIncreaseQuantity', [CartController::class, 'increaseCart']);
        Route::get('/deleteCart/{id}/{idUser}', [CartController::class, 'deleteItem']);
        Route::post('/checkout', [CartController::class, 'checkoutPage']);
        Route::post('/generateToken', [CartController::class, 'generateToken']);
        Route::post('/makeOrder', [CartController::class, 'makeOrder']);
        Route::post('/buyNow', [CartController::class, 'buyNow']);
        // Route::post('/getLocation', [MapsController::class, 'calculateDistance']);
        Route::post('/mapsLocation', [MapsController::class, 'dataLatLong']);
        Route::get('/show-distance', 'MapsController@showDistance');
        Route::get('/calculate-distance', [MapsController::class, 'calculateDistance']);
        Route::post('/get-user-location', [MapsController::class, 'getUserLocation']);
        Route::post('/get-user-location', 'MapsController@calculateDistance');
        Route::post('/update-address', [MapsController::class, 'updateAddress'])->name('update-address');
    });

    Route::group(['middleware'  => 'laundrySepatu'], function () {
        Route::resource('/laundryorder', OrderLaundryController::class)->middleware('auth');
        Route::resource('/laundryservice', ServiceController::class)->middleware('auth');
        Route::get('service/{id}', [MenuController::class, 'serviceLaundry'])->middleware('auth');
        Route::resource('/laundrysepatu', LaundrySepatuController::class)->middleware('auth');
    });

    Route::group(['middleware'  => 'admin'], function () {
        Route::resource('/order', OrderController::class)->middleware('auth');
    });
});
