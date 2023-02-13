<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfHeader;
use App\Models\SysFpFisik;
use App\Models\TtfLampiran;
use ZipArchive;
class TtfHeaderController extends Controller
{
    public function getDataInquiryTTF(Request $request){
        $ttf_header = new TtfHeader();
        $data = $ttf_header->getDataInquiryTTF($request->user_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    public function getDataInquiryDetailTTF(Request $request){
        $ttf_header = new TtfHeader();
        $data = $ttf_header->getDataInquiryDetailTTF($request->ttf_id);

        return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
    }

    public function downloadLampiran(Request $request){
        $sys_fp_fisik = new SysFpFisik();
        $getDataFpFisik = $sys_fp_fisik->getDataByTtfNumber($request->nomor_ttf);
        $ttf_lampiran = new TtfLampiran();
        $getDataTtfLampiran = $ttf_lampiran->getDataTtfLampiranByTTfID($request->id_ttf);
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

        header('Content-disposition: attachment; filename=download.zip');
        header('Content-type: application/zip');
        readfile(public_path('trigger_zip/test_new.zip'));
    }
}
