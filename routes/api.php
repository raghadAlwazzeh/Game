<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'logIn']);
Route::get('usersinfo/{token}', [UserController::class, 'getUserInfo']);
Route::post('addtopoints', [UserController::class, 'addToPoints']);
Route::post('addtopointsads', [UserController::class, 'addToPointsAds']);
Route::post('subplan', [UserController::class, 'subPlan']);
Route::post('orderprize', [UserController::class, 'orderPrize']);
Route::post('confirmprize', [UserController::class, 'confirmPrize']);
Route::get('getallusers', [UserController::class, 'getAllUsers']);
Route::get('getnotif/{token}', [UserController::class, 'getnotif']);