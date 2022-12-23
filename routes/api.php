<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SysSupplierController;
use App\Http\Controllers\SysSuppSiteController;
use App\Http\Controllers\SysRefBranchController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('createUser', 'createUser');
    Route::post('updateUser', 'updateUser');
    Route::get('getDataForInquiryUser', 'getDataForInquiryUser');
});

Route::controller(SysSupplierController::class)->group(function () {
    Route::get('getAllSupplier', 'getAllSupplier');
});

Route::controller(SysSuppSiteController::class)->group(function () {
    Route::post('getAllSupplierSite', 'getAllSupplierSite');
});

Route::controller(SysRefBranchController::class)->group(function () {
    Route::get('getAllBranch', 'getAllBranch');
});


