<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testModel2;
use App\Models\SysFpFisik;
use App\Models\SysUser;
use App\Models\TtfLampiran;
use ZipArchive;
use Response;

class testController2 extends Controller
{
    public function getDataUsername(){
        $SysUser= new SysUser();
        $getData = $SysUser->getDataUsername();

        return response()->json([
            'status' => 'success',
            'data' => $getData
        ]);
    }

    public function searchDataInquiryLampiran(Request $request){
        $test_model2 = new testModel2();

        $getData = $test_model2->searchDataInquiryLampiran($request->branch,$request->nottf,$request->kodesupp,$request->username,$request->tglttf_from,$request->tglttf_to,$request->status,$request->offset,$request->limit);

        return response()->json([
                'status' => 'success',
                'count' => $getData['count'],
                'data' => $getData['data']
        ]);
    }

    public function downloadInquiryLampiran(Request $request){
        $sys_fp_fisik = new SysFpFisik();
        $getDataFpFisik = $sys_fp_fisik->getDataByTtfNumber($request->ttf_num);
        $ttf_lampiran = new TtfLampiran();
        $getDataTtfLampiran = $ttf_lampiran->getDataTtfLampiranByTTfID($request->ttf_id);
        // $zip = new ZipArchive();
        $zip = new \ZipArchive();
        if ($zip->open(public_path('trigger_zip/test_new.zip'), \ZipArchive::CREATE) === TRUE)
        {
            foreach($getDataFpFisik as $a){
                $zip->addFile($a->PATH_FILE,$a->REAL_NAME);
            }
            foreach($getDataTtfLampiran as $b){
                $zip->addFile($b->PATH_FILE,$b->REAL_NAME);
            }
        }

        $zip->close();
        $file= public_path('trigger_zip/test_new.zip');
        $headers = array(
            "Content-type"        => "application/zip",
            "Content-Disposition" => "attachment; filename=test_zip.zip",
            "Content-Transfer-Encoding" => "Binary",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return Response::download($file,'test_zip.zip' ,$headers);
        // header('Content-disposition: attachment; filename=download.zip');
        // header('Content-type: application/zip');
        // readfile(public_path('trigger_zip/test_new.zip'));
    }
}
