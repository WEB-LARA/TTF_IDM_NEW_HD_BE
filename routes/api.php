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
use App\Http\Controllers\testController3;
use App\Http\Controllers\TempUploadDjpCsvController;
use App\Http\Controllers\TtfUploadTmpController;
use App\Http\Controllers\TtfParamTableController;
use App\Http\Controllers\SysFpFisikTempController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SysMasterBpbController;
use App\Http\Controllers\SysMasterNrbController;

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
    Route::post('getDataForInquiryUser', 'getDataForInquiryUser');
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
    Route::post('searchDataTtf', 'searchDataTtf');
    Route::post('reportTtfs', 'reportTtfs');
});
Route::controller(testController2::class)->group(function () {
    Route::post('searchDataInquiryLampiran', 'searchDataInquiryLampiran');
    Route::get('downloadInquiryLampiran', 'downloadInquiryLampiran');
    Route::get('getDataUsername', 'getDataUsername');
});
Route::controller(testController3::class)->group(function () {
    Route::get('getDataBranch', 'getDataBranch');
    Route::post('getDataSupplierbyBranch', 'getDataSupplierbyBranch');
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
    Route::post('saveTTfUpload','saveTTfUpload');
    Route::post('saveLampiranTerpisah','saveLampiranTerpisah');
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
    Route::post('submitTtf','submitTtf');
    Route::post('cancelTtf','cancelTtf');
    Route::post('getDetailTtfByTtfId','getDetailTtfByTtfId');
    Route::post('deleteTtf','deleteTtf');
    Route::post('checkUploadDataBlob','checkUploadDataBlob');
    Route::post('getCountTtfAndMaxDate','getCountTtfAndMaxDate');
    Route::post('getCountTtfUnvalidatedAndValidated','getCountTtfUnvalidatedAndValidated');
});
Route::controller(SysAnnouncementController::class)->group(function () {
    Route::post('createAnnouncement', 'createAnnouncement');
    Route::post('updateAnnouncement', 'updateAnnouncement');
    Route::post('deletePengumuman', 'deletePengumuman');
    Route::get('getDataAnnouncement', 'getDataAnnouncement');
    Route::post('getDataAnnouncementInquiry','getDataAnnouncementInquiry');
    Route::post('getDownload','getDownload');
});
Route::controller(TempUploadDjpCsvController::class)->group(function () {
    Route::post('insertFileDjp', 'insertFileDjp');
    Route::post('getFileDjpBySessionId','getFileDjpBySessionId');
    Route::post('deleteFileDjp', 'deleteFileDjp');
    Route::post('deleteTableTempDjpCsv','deleteTableTempDjpCsv');
});
Route::controller(TtfUploadTmpController::class)->group(function () {
    Route::post('getDataForInquiryUpload', 'getDataForInquiryUpload');
});
Route::controller(TtfParamTableController::class)->group(function () {
    Route::get('getMaxBpbAndPpn', 'getMaxBpbAndPpn');
});
Route::controller(SysFpFisikTempController::class)->group(function () {
    Route::post('deleteSysFpFisikTempBySessId', 'deleteSysFpFisikTempBySessId');
});
Route::controller(DashboardController::class)->group(function () {
    Route::post('getDataForInquiryTtfDashboard', 'getDataForInquiryTtfDashboard');
    Route::post('getDataForInquiryTtfDashboardUser','getDataForInquiryTtfDashboardUser');
    Route::post('getAllDataUser','getAllDataUser');
    Route::post('getAllbranch','getAllbranch');
    Route::post('getSuppSiteCode','getSuppSiteCode');
});
Route::controller(SysMasterBpbController::class)->group(function () {
    Route::post('getDataInquiryDownloadBpb', 'getDataInquiryDownloadBpb');
    Route::post('downloadBpb','downloadBpb');
});
Route::controller(SysMasterNrbController::class)->group(function () {
    Route::post('getDataInquiryDownloadNrb', 'getDataInquiryDownloadNrb');
    Route::post('downloadNrb','downloadNrb');
});


