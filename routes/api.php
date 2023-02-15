<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SysSupplierController;
use App\Http\Controllers\SysSuppSiteController;
use App\Http\Controllers\SysRefBranchController;
use App\Http\Controllers\InputTTfController;
use App\Http\Controllers\ConvertImageController;
use App\Http\Controllers\SysMapSupplierController;
use App\Http\Controllers\PrepopulatedFpController;
use App\Http\Controllers\TtfDataBpbController;
use App\Http\Controllers\TtfTmpTableController;
use App\Http\Controllers\TtfHeaderController;
use App\Http\Controllers\SysAnnouncementController;
use App\Http\Controllers\testController;
use App\Http\Controllers\testController2;
use App\Http\Controllers\TempUploadDjpCsvController;

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
    Route::post('updateEmailAndPasswordperUser','updateEmailAndPasswordperUser');
    Route::get('getDataForInquiryUser', 'getDataForInquiryUser');
});
Route::controller(SysSupplierController::class)->group(function () {
    Route::get('getAllSupplier', 'getAllSupplier');
});
Route::controller(SysSuppSiteController::class)->group(function () {
    Route::post('getAllSupplierSite', 'getAllSupplierSite');
});
Route::controller(SysMapSupplierController::class)->group(function () {
    Route::post('getSupplierByUserId', 'getSupplierByUserId');
});
Route::controller(SysRefBranchController::class)->group(function () {
    Route::get('getAllBranch', 'getAllBranch');
});
Route::controller(testController::class)->group(function () {
    Route::get('get', 'get');
    Route::get('getdata1', 'getdata');
    Route::get('selectdata1', 'selectdata');
    Route::get('joindata1', 'joindata');
    // Route::get('getDataInquiryTtf', 'getDataInquiryTtf');
    Route::get('searchDataTtf', 'searchDataTtf');
});
Route::controller(testController2::class)->group(function () {
    // Route::get('getDataInquiryLampiran', 'getDataInquiryLampiran');
    Route::get('searchDataInquiryLampiran', 'searchDataInquiryLampiran');
    Route::get('downloadInquiryLampiran', 'downloadInquiryLampiran');
});
Route::controller(InputTTfController::class)->group(function () {
    Route::post('saveToTmpTtf', 'saveToTmpTtf');
    Route::post('editTmpTTF', 'editTmpTTF');
    Route::post('getDataTtfTmpBYSessionId', 'getDataTtfTmpBYSessionId');
    Route::post('saveTTf', 'saveTTf');
    Route::get('getTtfNumber','getTtfNumber');
    Route::post('saveLampiran','saveLampiran');
    Route::post('uploadTTF','uploadTTF');
    Route::post('cekUploadLampiran','cekUploadLampiran');
    Route::get('downloadTemplateCsv','downloadTemplateCsv');
    Route::post('verifikasiDJP','verifikasiDJP');
});
Route::controller(ConvertImageController::class)->group(function () {
    Route::get('convert', 'index');
    Route::post('fileUploadPost','fileUploadPost');
    Route::get('createDirectory','createDirectory');
    Route::get('readQr','readQr');
});
Route::controller(PrepopulatedFpController::class)->group(function () {
    Route::post('getPrepopulatedFpByNpwp', 'getPrepopulatedFpByNpwp');
});
Route::controller(TtfDataBpbController::class)->group(function () {
    Route::post('getDataBPBPerSupplier', 'getDataBPBPerSupplier');
});
Route::controller(TtfTmpTableController::class)->group(function () {
    Route::post('getDataTmpTtfBySessId', 'getDataTmpTtfBySessId');
    Route::post('getDataDetailBPBperFP', 'getDataDetailBPBperFP');
    Route::post('deleteTmpTableBySiteCodeAndBranch','deleteTmpTableBySiteCodeAndBranch');
    Route::post('deleteTmpTableByNoFpAndSessId','deleteTmpTableByNoFpAndSessId');
});
Route::controller(TtfHeaderController::class)->group(function () {
    Route::post('getDataInquiryTTF', 'getDataInquiryTTF');
    Route::post('getDataInquiryDetailTTF', 'getDataInquiryDetailTTF');
    Route::post('downloadLampiran','downloadLampiran');
});
Route::controller(SysAnnouncementController::class)->group(function () {
    Route::post('createAnnouncement', 'createAnnouncement');
    Route::post('updateAnnouncement', 'updateAnnouncement');
    Route::post('deletePengumuman', 'deletePengumuman');
    Route::get('getDataAnnouncement', 'getDataAnnouncement');
    Route::post('getDownload','getDownload');
});
Route::controller(TempUploadDjpCsvController::class)->group(function () {
    Route::post('insertFileDjp', 'insertFileDjp');
    Route::post('getFileDjpBySessionId','getFileDjpBySessionId');
    Route::post('deleteFileDjp', 'deleteFileDjp');
    Route::post('deleteTableTempDjpCsv','deleteTableTempDjpCsv');
});