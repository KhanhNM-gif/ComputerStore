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

Route::post('/auth/register',[AuthController::class,'register']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/tab',[TabController::class,'store']);
});