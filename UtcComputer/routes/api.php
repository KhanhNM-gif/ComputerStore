<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TabController;
use App\Models\CartManager;
use App\Models\Item;
use App\Models\Region;
use Illuminate\Support\Facades\Route;
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

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/verify_account', [AuthController::class, 'verify_account']);
Route::post('/auth/send_OTP', [AuthController::class, 'send_OTP']);
Route::post('/auth/forget_password', [AuthController::class, 'forget_password']);
Route::post('/auth/verify_handle', [AuthController::class, 'verify_handle']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/region/get_list', [RegionController::class, 'GetListBy'])->middleware('check.key:parentID');

Route::get('/asset/get_list', [AssetController::class, 'GetList']);

Route::get('/item/get_list_search', [ItemController::class, 'GetListSearch']);
Route::get('/item/get_one', [ItemController::class, 'GetOne']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/cart/get_cart', [CartController::class, 'GetCart']);
    Route::post('/cart/add_to_cart', [CartController::class, 'AddToCart']);
    Route::post('/cart/delete_item_cart', [CartController::class, 'DeleteItemCart']);
});
