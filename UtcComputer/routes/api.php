<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TabController;
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
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
});
Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/verify_account',[AuthController::class,'verify_account']);
Route::post('/auth/send_OTP',[AuthController::class,'send_OTP']);
Route::post('/auth/forget_password',[AuthController::class,'forget_password']);
Route::post('/auth/verify_handle',[AuthController::class,'verify_handle']);
Route::post('/auth/login',[AuthController::class,'login'])->middleware('verified');


Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/tab/store',[TabController::class,'store']);
});