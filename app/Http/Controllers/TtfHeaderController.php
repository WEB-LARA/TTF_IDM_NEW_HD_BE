<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TtfHeader;
use App\Models\SysFpFisik;
use App\Models\TtfLampiran;
use ZipArchive;
use Response;
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
        $getDataFpFisik = $sys_fp_fisik->getDataByTtfNumber('230022473841');
        $ttf_lampiran = new TtfLampiran();
        $getDataTtfLampiran = $ttf_lampiran->getDataTtfLampiranByTTfID(176);
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
    
    public function validateTtf(Request $request){
        $validate = TtfHeader::where('TTF_ID',$request->ttf_id)->update([
            'STATUS'=>'V'
        ]);
        if($validate){
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Berhasil di Validasi!'
                ]);
        }else{
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Gagal di Validasi!'
                ]);
        }
    }

    public function cancelTtf(Request $request){
        $validate = TtfHeader::where('TTF_ID',$request->ttf_id)->update([
            'STATUS'=>'C'
        ]);
        if($validate){
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Berhasil di Cancel!'
                ]);
        }else{
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data Ttf Gagal di Cancel!'
                ]);
        }
    }
}
